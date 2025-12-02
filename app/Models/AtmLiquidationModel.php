<?php

namespace App\Models;

use CodeIgniter\Model;

class AtmLiquidationModel extends Model
{
    protected $table = 'atm_liquidations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'batch_name', 'file_path', 'file_type', 'uploaded_by', 'total_records',
        'processed_records', 'semester', 'academic_year', 'status',
        'chairman_approval_date', 'accounting_received_date', 'remarks'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'batch_name' => 'required|max_length[100]',
        'file_path' => 'required|max_length[255]',
        'file_type' => 'required|in_list[csv,excel]',
        'uploaded_by' => 'required|numeric',
        'semester' => 'required|max_length[20]',
        'academic_year' => 'required|max_length[20]',
        'status' => 'required|in_list[uploaded,processing,processed,verified,sent_to_chairman,approved,sent_to_accounting,completed,rejected]'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getBatchesWithUploader($filters = [])
    {
        $builder = $this->db->table($this->table . ' al');
        $builder->select('al.*, 
            u.username as uploader_name, 
            u.email as uploader_email,
            COUNT(ald.id) as recipient_count,
            COALESCE(SUM(ald.amount), 0) as total_amount');
        $builder->join('users u', 'al.uploaded_by = u.id', 'left');
        $builder->join('atm_liquidation_details ald', 'al.id = ald.atm_liquidation_id', 'left');

        if (isset($filters['status']) && !empty($filters['status'])) {
            $builder->where('al.status', $filters['status']);
        }

        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $builder->where('al.semester', $filters['semester']);
        }

        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $builder->where('al.academic_year', $filters['academic_year']);
        }

        $builder->groupBy('al.id');
        $builder->orderBy('al.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getDashboardStatistics($filters = [])
    {
        $builder = $this->db->table($this->table);

        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $builder->where('semester', $filters['semester']);
        }

        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $builder->where('academic_year', $filters['academic_year']);
        }

        $stats = [];
        $stats['total_batches'] = $builder->countAllResults(false);
        $stats['uploaded'] = $builder->where('status', 'uploaded')->countAllResults(false);
        $stats['processing'] = $builder->where('status', 'processing')->countAllResults(false);
        $stats['processed'] = $builder->where('status', 'processed')->countAllResults(false);
        $stats['sent_to_chairman'] = $builder->where('status', 'sent_to_chairman')->countAllResults(false);
        $stats['approved'] = $builder->where('status', 'approved')->countAllResults(false);
        $stats['sent_to_accounting'] = $builder->where('status', 'sent_to_accounting')->countAllResults(false);
        $stats['completed'] = $builder->where('status', 'completed')->countAllResults(false);
        $stats['rejected'] = $builder->where('status', 'rejected')->countAllResults();

        // Get total records across all batches
        $recordsBuilder = $this->db->table($this->table);
        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $recordsBuilder->where('semester', $filters['semester']);
        }
        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $recordsBuilder->where('academic_year', $filters['academic_year']);
        }

        $result = $recordsBuilder->selectSum('total_records', 'total_records')
                               ->selectSum('processed_records', 'processed_records')
                               ->get()->getRowArray();
        
        $stats['total_records'] = $result['total_records'] ?: 0;
        $stats['processed_records'] = $result['processed_records'] ?: 0;

        return $stats;
    }

    public function updateStatus($id, $status, $remarks = null)
    {
        $data = ['status' => $status];
        
        if ($status === 'approved') {
            $data['chairman_approval_date'] = date('Y-m-d H:i:s');
        } elseif ($status === 'sent_to_accounting') {
            $data['accounting_received_date'] = date('Y-m-d H:i:s');
        }
        
        if ($remarks) {
            $data['remarks'] = $remarks;
        }

        return $this->update($id, $data);
    }
}
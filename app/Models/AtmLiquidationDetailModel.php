<?php

namespace App\Models;

use CodeIgniter\Model;

class AtmLiquidationDetailModel extends Model
{
    protected $table = 'atm_liquidation_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'atm_liquidation_id', 'recipient_id', 'transaction_date', 'amount',
        'reference_number', 'file_path', 'file_type', 'status', 'semester', 
        'academic_year', 'remarks', 'created_by', 'verified_by', 'verified_at',
        'approved_by', 'approved_at', 'accounting_received_date', 'completed_at'
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
        'recipient_id' => 'required|numeric',
        'transaction_date' => 'required|valid_date',
        'amount' => 'required|decimal|greater_than[0]',
        'semester' => 'required|max_length[20]',
        'academic_year' => 'required|max_length[20]',
        'status' => 'required|in_list[pending,verified,approved,rejected,sent_to_accounting,completed]',
        'created_by' => 'required|numeric'
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

    public function getDetailsWithRecipients($atmLiquidationId)
    {
        $builder = $this->db->table($this->table . ' ald');
        $builder->select('ald.*, sr.recipient_id as recipient_code, sr.first_name, sr.last_name, sr.middle_name, sr.campus, sr.bank_account, sr.bank_name');
        $builder->join('scholarship_recipients sr', 'ald.recipient_id = sr.id', 'left');
        $builder->where('ald.atm_liquidation_id', $atmLiquidationId);
        $builder->orderBy('ald.transaction_date', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getLiquidationsWithRecipients($filters = [])
    {
        $builder = $this->db->table($this->table . ' ald');
        $builder->select('ald.*, 
            sr.recipient_id as recipient_code, 
            CONCAT(sr.first_name, " ", sr.last_name) as recipient_name, 
            sr.first_name, 
            sr.last_name, 
            sr.middle_name, 
            sr.campus,
            al.batch_name,
            al.file_type,
            al.status as batch_status,
            u.username as uploader_name');
        $builder->join('scholarship_recipients sr', 'ald.recipient_id = sr.id', 'left');
        $builder->join('atm_liquidations al', 'ald.atm_liquidation_id = al.id', 'left');
        $builder->join('users u', 'al.uploaded_by = u.id', 'left');

        if (isset($filters['status']) && !empty($filters['status'])) {
            $builder->where('ald.status', $filters['status']);
            
            // For approved status, also filter out batches that have been processed
            if ($filters['status'] === 'approved') {
                $builder->groupStart();
                    $builder->where('ald.atm_liquidation_id IS NULL'); // Individual records
                    $builder->orWhere('al.status', 'approved'); // Or batch records where batch is also approved
                $builder->groupEnd();
            }
        }

        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $builder->where('ald.semester', $filters['semester']);
        }

        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $builder->where('ald.academic_year', $filters['academic_year']);
        }

        // Handle atm_liquidation_id filter (null for individual records)
        if (array_key_exists('atm_liquidation_id', $filters)) {
            if ($filters['atm_liquidation_id'] === null) {
                $builder->where('ald.atm_liquidation_id IS NULL');
            } else {
                $builder->where('ald.atm_liquidation_id', $filters['atm_liquidation_id']);
            }
        }

        $builder->orderBy('ald.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getDetailStatistics($atmLiquidationId)
    {
        $builder = $this->db->table($this->table);
        $builder->where('atm_liquidation_id', $atmLiquidationId);

        $stats = [];
        $stats['total'] = $builder->countAllResults(false);
        $stats['pending'] = $builder->where('status', 'pending')->countAllResults(false);
        $stats['verified'] = $builder->where('status', 'verified')->countAllResults(false);
        $stats['approved'] = $builder->where('status', 'approved')->countAllResults(false);
        $stats['rejected'] = $builder->where('status', 'rejected')->countAllResults();

        // Get total amount
        $amountBuilder = $this->db->table($this->table);
        $amountBuilder->where('atm_liquidation_id', $atmLiquidationId);
        $result = $amountBuilder->selectSum('amount', 'total_amount')->get()->getRowArray();
        $stats['total_amount'] = $result['total_amount'] ?: 0;

        return $stats;
    }

    public function bulkInsert($data)
    {
        return $this->insertBatch($data);
    }
}
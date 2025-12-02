<?php

namespace App\Models;

use CodeIgniter\Model;

class ManualLiquidationModel extends Model
{
    protected $table = 'manual_liquidations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'recipient_id', 'disbursing_officer_id', 'scholarship_coordinator_id',
        'voucher_number', 'amount', 'liquidation_date', 'semester', 'academic_year',
        'campus', 'description', 'status', 'remarks', 'type', 'source_type', 
        'disbursement_id', 'approved_date', 'approved_by'
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
        'disbursing_officer_id' => 'required|numeric',
        'voucher_number' => 'required|max_length[50]',
        'amount' => 'required|decimal|greater_than[0]',
        'liquidation_date' => 'required|valid_date',
        'semester' => 'required|max_length[20]',
        'academic_year' => 'required|max_length[20]',
        'campus' => 'required|max_length[100]',
        'status' => 'required|in_list[pending,verified,approved,rejected]'
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

    public function getLiquidationsWithRecipients($filters = [])
    {
        // Get manual liquidations
        $builder = $this->db->table($this->table . ' ml');
        $builder->select('ml.*, 
                         sr.recipient_id as recipient_code, 
                         sr.first_name, 
                         sr.last_name, 
                         sr.middle_name, 
                         sr.campus as recipient_campus,
                         ml.campus as campus,
                         u1.username as disbursing_officer, 
                         u2.username as scholarship_coordinator');
        $builder->join('scholarship_recipients sr', 'ml.recipient_id = sr.id', 'left');
        $builder->join('users u1', 'ml.disbursing_officer_id = u1.id', 'left');
        $builder->join('users u2', 'ml.scholarship_coordinator_id = u2.id', 'left');

        // Exclude hidden status records (these are for disbursements that haven't been approved yet)
        $builder->where('ml.status !=', 'hidden');

        if (isset($filters['campus']) && !empty($filters['campus'])) {
            $builder->where('ml.campus', $filters['campus']);
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $builder->where('ml.status', $filters['status']);
        }

        if (isset($filters['disbursing_officer']) && !empty($filters['disbursing_officer'])) {
            $builder->where('ml.disbursing_officer_id', $filters['disbursing_officer']);
        }

        if (isset($filters['scholarship_coordinator']) && !empty($filters['scholarship_coordinator'])) {
            $builder->where('ml.scholarship_coordinator_id', $filters['scholarship_coordinator']);
        }

        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $builder->where('ml.semester', $filters['semester']);
        }

        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $builder->where('ml.academic_year', $filters['academic_year']);
        }

        $manualLiquidations = $builder->get()->getResultArray();
        
        // Sort by created_at descending
        usort($manualLiquidations, function($a, $b) {
            $timeA = $a['created_at'] ? strtotime($a['created_at']) : 0;
            $timeB = $b['created_at'] ? strtotime($b['created_at']) : 0;
            return $timeB - $timeA;
        });
        
        return $manualLiquidations;
    }

    public function getDashboardStatistics($filters = [])
    {
        // Manual liquidations stats (exclude hidden records)
        $builder = $this->db->table($this->table);
        $builder->where('status !=', 'hidden');

        if (isset($filters['campus']) && !empty($filters['campus'])) {
            $builder->where('campus', $filters['campus']);
        }

        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $builder->where('semester', $filters['semester']);
        }

        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $builder->where('academic_year', $filters['academic_year']);
        }

        if (isset($filters['disbursing_officer']) && !empty($filters['disbursing_officer'])) {
            $builder->where('disbursing_officer_id', $filters['disbursing_officer']);
        }

        $manualStats = [];
        $manualStats['total'] = $builder->countAllResults(false);
        $manualStats['pending'] = $builder->where('status', 'pending')->countAllResults(false);
        $manualStats['verified'] = $builder->where('status', 'verified')->countAllResults(false);
        $manualStats['approved'] = $builder->where('status', 'approved')->countAllResults(false);
        $manualStats['rejected'] = $builder->where('status', 'rejected')->countAllResults();

        $amountBuilder = $this->db->table($this->table);
        $amountBuilder->where('status !=', 'hidden');
        if (isset($filters['campus']) && !empty($filters['campus'])) {
            $amountBuilder->where('campus', $filters['campus']);
        }
        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $amountBuilder->where('semester', $filters['semester']);
        }
        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $amountBuilder->where('academic_year', $filters['academic_year']);
        }
        if (isset($filters['disbursing_officer']) && !empty($filters['disbursing_officer'])) {
            $amountBuilder->where('disbursing_officer_id', $filters['disbursing_officer']);
        }

        $result = $amountBuilder->selectSum('amount', 'total_amount')->get()->getRowArray();
        $manualStats['total_amount'] = $result['total_amount'] ?: 0;

        // Disbursements stats
        $disbursementBuilder = $this->db->table('disbursements');
        if (isset($filters['campus']) && !empty($filters['campus'])) {
            $disbursementBuilder->where('campus', $filters['campus']);
        }
        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $disbursementBuilder->where('semester', $filters['semester']);
        }
        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $disbursementBuilder->where('academic_year', $filters['academic_year']);
        }
        if (isset($filters['disbursing_officer']) && !empty($filters['disbursing_officer'])) {
            $disbursementBuilder->where('disbursing_officer_id', $filters['disbursing_officer']);
        }

        $disbursementStats = [];
        $disbursementStats['total'] = $disbursementBuilder->countAllResults(false);
        $disbursementStats['pending'] = $disbursementBuilder->where('status', 'pending')->countAllResults(false);
        $disbursementStats['verified'] = $disbursementBuilder->where('status', 'verified')->countAllResults(false);
        $disbursementStats['approved'] = $disbursementBuilder->where('status', 'approved')->countAllResults(false);
        $disbursementStats['rejected'] = $disbursementBuilder->where('status', 'rejected')->countAllResults();

        $disbursementAmountBuilder = $this->db->table('disbursements');
        if (isset($filters['campus']) && !empty($filters['campus'])) {
            $disbursementAmountBuilder->where('campus', $filters['campus']);
        }
        if (isset($filters['semester']) && !empty($filters['semester'])) {
            $disbursementAmountBuilder->where('semester', $filters['semester']);
        }
        if (isset($filters['academic_year']) && !empty($filters['academic_year'])) {
            $disbursementAmountBuilder->where('academic_year', $filters['academic_year']);
        }
        if (isset($filters['disbursing_officer']) && !empty($filters['disbursing_officer'])) {
            $disbursementAmountBuilder->where('disbursing_officer_id', $filters['disbursing_officer']);
        }

        $disbursementResult = $disbursementAmountBuilder->selectSum('amount', 'total_amount')->get()->getRowArray();
        $disbursementStats['total_amount'] = $disbursementResult['total_amount'] ?: 0;

        // Combine stats
        $combinedStats = [
            'total' => $manualStats['total'] + $disbursementStats['total'],
            'pending' => $manualStats['pending'] + $disbursementStats['pending'],
            'verified' => $manualStats['verified'] + $disbursementStats['verified'],
            'approved' => $manualStats['approved'] + $disbursementStats['approved'],
            'rejected' => $manualStats['rejected'] + $disbursementStats['rejected'],
            'total_amount' => $manualStats['total_amount'] + $disbursementStats['total_amount']
        ];

        return $combinedStats;
    }

    public function getLiquidationWithDetails($id)
    {
        $builder = $this->db->table($this->table . ' ml');
        $builder->select('ml.*, sr.recipient_id as recipient_code, sr.first_name, sr.last_name, sr.middle_name, 
                         sr.campus as recipient_campus, sr.email as recipient_email, sr.course, sr.year_level,
                         u1.username as disbursing_officer_name, u1.email as officer_email,
                         u2.username as coordinator_name, u2.email as coordinator_email');
        $builder->join('scholarship_recipients sr', 'ml.recipient_id = sr.id', 'left');
        $builder->join('users u1', 'ml.disbursing_officer_id = u1.id', 'left');
        $builder->join('users u2', 'ml.scholarship_coordinator_id = u2.id', 'left');
        $builder->where('ml.id', $id);
        
        return $builder->get()->getRowArray();
    }

    public function getLiquidationsByRecipient($recipientId, $filters = [])
    {
        $builder = $this->db->table($this->table . ' ml');
        $builder->select('ml.*, u1.username as disbursing_officer, u2.username as scholarship_coordinator');
        $builder->join('users u1', 'ml.disbursing_officer_id = u1.id', 'left');
        $builder->join('users u2', 'ml.scholarship_coordinator_id = u2.id', 'left');
        $builder->where('ml.recipient_id', $recipientId);
        $builder->where('ml.status !=', 'hidden');

        if (isset($filters['status']) && !empty($filters['status'])) {
            $builder->where('ml.status', $filters['status']);
        }

        $builder->orderBy('ml.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getLiquidationsByCampus($campus, $filters = [])
    {
        $builder = $this->db->table($this->table . ' ml');
        $builder->select('ml.*, sr.recipient_id as recipient_code, sr.first_name, sr.last_name,
                         u1.username as disbursing_officer, u2.username as scholarship_coordinator');
        $builder->join('scholarship_recipients sr', 'ml.recipient_id = sr.id', 'left');
        $builder->join('users u1', 'ml.disbursing_officer_id = u1.id', 'left');
        $builder->join('users u2', 'ml.scholarship_coordinator_id = u2.id', 'left');
        $builder->where('ml.campus', $campus);
        $builder->where('ml.status !=', 'hidden');

        if (isset($filters['status']) && !empty($filters['status'])) {
            $builder->where('ml.status', $filters['status']);
        }

        $builder->orderBy('ml.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getLiquidationsByOfficer($officerId, $filters = [])
    {
        $builder = $this->db->table($this->table . ' ml');
        $builder->select('ml.*, sr.recipient_id as recipient_code, sr.first_name, sr.last_name,
                         u2.username as scholarship_coordinator');
        $builder->join('scholarship_recipients sr', 'ml.recipient_id = sr.id', 'left');
        $builder->join('users u2', 'ml.scholarship_coordinator_id = u2.id', 'left');
        $builder->where('ml.disbursing_officer_id', $officerId);
        $builder->where('ml.status !=', 'hidden');

        if (isset($filters['status']) && !empty($filters['status'])) {
            $builder->where('ml.status', $filters['status']);
        }

        $builder->orderBy('ml.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getLiquidationsByCoordinator($coordinatorId, $filters = [])
    {
        $builder = $this->db->table($this->table . ' ml');
        $builder->select('ml.*, sr.recipient_id as recipient_code, sr.first_name, sr.last_name,
                         u1.username as disbursing_officer');
        $builder->join('scholarship_recipients sr', 'ml.recipient_id = sr.id', 'left');
        $builder->join('users u1', 'ml.disbursing_officer_id = u1.id', 'left');
        $builder->where('ml.scholarship_coordinator_id', $coordinatorId);
        $builder->where('ml.status !=', 'hidden');

        if (isset($filters['status']) && !empty($filters['status'])) {
            $builder->where('ml.status', $filters['status']);
        }

        $builder->orderBy('ml.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
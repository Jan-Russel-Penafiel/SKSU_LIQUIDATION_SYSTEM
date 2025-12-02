<?php

namespace App\Models;

use CodeIgniter\Model;

class DisbursementModel extends Model
{
    protected $table = 'disbursements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'disbursement_date',
        'recipient_name',
        'recipient_id',
        'course_program',
        'year_level',
        'semester',
        'academic_year',
        'scholarship_type',
        'amount',
        'disbursement_method',
        'campus',
        'disbursing_officer_id',
        'status',
        'remarks',
        'verification_date',
        'verified_by',
        'approval_date',
        'approved_by',
        'disbursed_date'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'disbursement_date' => 'required|valid_date',
        'recipient_name' => 'required|min_length[2]|max_length[255]',
        'recipient_id' => 'required|min_length[1]|max_length[100]',
        'course_program' => 'required|min_length[2]|max_length[200]',
        'year_level' => 'required|max_length[50]',
        'semester' => 'required|in_list[1st Semester,2nd Semester,Summer]',
        'academic_year' => 'required|max_length[20]',
        'scholarship_type' => 'required|min_length[2]|max_length[150]',
        'amount' => 'required|decimal|greater_than[0]',
        'disbursement_method' => 'required|in_list[Cash,Check,Bank_Transfer,ATM]',
        'campus' => 'required|min_length[2]|max_length[100]',
        'disbursing_officer_id' => 'required|integer',
        'status' => 'permit_empty|in_list[pending,verified,approved,rejected,disbursed]'
    ];

    protected $validationMessages = [
        'disbursement_date' => [
            'required' => 'Disbursement date is required',
            'valid_date' => 'Please provide a valid disbursement date'
        ],
        'recipient_name' => [
            'required' => 'Recipient name is required',
            'min_length' => 'Recipient name must be at least 2 characters',
            'max_length' => 'Recipient name cannot exceed 255 characters'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid decimal number',
            'greater_than' => 'Amount must be greater than 0'
        ]
    ];

    /**
     * Get disbursements with officer information
     */
    public function getDisbursementsWithOfficers($filters = [])
    {
        $builder = $this->builder();
        $builder->select('disbursements.*, 
                         officers.username as officer_name, 
                         officers.campus as officer_campus,
                         verifiers.username as verified_by_name,
                         approvers.username as approved_by_name');
        $builder->join('users as officers', 'officers.id = disbursements.disbursing_officer_id', 'left');
        $builder->join('users as verifiers', 'verifiers.id = disbursements.verified_by', 'left');
        $builder->join('users as approvers', 'approvers.id = disbursements.approved_by', 'left');

        // Apply filters
        if (!empty($filters['campus'])) {
            $builder->where('disbursements.campus', $filters['campus']);
        }

        if (!empty($filters['semester'])) {
            $builder->where('disbursements.semester', $filters['semester']);
        }

        if (!empty($filters['academic_year'])) {
            $builder->where('disbursements.academic_year', $filters['academic_year']);
        }

        if (!empty($filters['status'])) {
            $builder->where('disbursements.status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('disbursements.disbursement_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('disbursements.disbursement_date <=', $filters['date_to']);
        }

        if (!empty($filters['officer_id'])) {
            $builder->where('disbursements.disbursing_officer_id', $filters['officer_id']);
        }

        $builder->orderBy('disbursements.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get disbursement statistics by officer
     */
    public function getDisbursementStatsByOfficer($filters = [])
    {
        $builder = $this->db->table('disbursements d');
        $builder->select('d.disbursing_officer_id,
                         u.username,
                         u.campus,
                         COUNT(*) as total_disbursements,
                         SUM(d.amount) as total_amount,
                         SUM(CASE WHEN d.status = "pending" THEN 1 ELSE 0 END) as pending_count,
                         SUM(CASE WHEN d.status = "verified" THEN 1 ELSE 0 END) as verified_count,
                         SUM(CASE WHEN d.status = "approved" THEN 1 ELSE 0 END) as approved_count,
                         SUM(CASE WHEN d.status = "rejected" THEN 1 ELSE 0 END) as rejected_count,
                         SUM(CASE WHEN d.status = "disbursed" THEN 1 ELSE 0 END) as disbursed_count');
        $builder->join('users u', 'u.id = d.disbursing_officer_id');

        // Apply filters
        if (!empty($filters['campus'])) {
            $builder->where('d.campus', $filters['campus']);
        }

        if (!empty($filters['semester'])) {
            $builder->where('d.semester', $filters['semester']);
        }

        if (!empty($filters['academic_year'])) {
            $builder->where('d.academic_year', $filters['academic_year']);
        }

        if (!empty($filters['status'])) {
            $builder->where('d.status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('d.disbursement_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('d.disbursement_date <=', $filters['date_to']);
        }

        $builder->groupBy('d.disbursing_officer_id, u.username, u.campus');
        $builder->orderBy('total_amount', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get disbursement summary statistics
     */
    public function getDisbursementSummary($filters = [])
    {
        $builder = $this->builder();

        // Apply filters
        if (!empty($filters['campus'])) {
            $builder->where('campus', $filters['campus']);
        }

        if (!empty($filters['semester'])) {
            $builder->where('semester', $filters['semester']);
        }

        if (!empty($filters['academic_year'])) {
            $builder->where('academic_year', $filters['academic_year']);
        }

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('disbursement_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('disbursement_date <=', $filters['date_to']);
        }

        if (!empty($filters['officer_id'])) {
            $builder->where('disbursing_officer_id', $filters['officer_id']);
        }

        $totalBuilder = clone $builder;
        $amountBuilder = clone $builder;
        
        // Create separate builders for each status to maintain filters
        $pendingBuilder = clone $builder;
        $verifiedBuilder = clone $builder;
        $approvedBuilder = clone $builder;
        $rejectedBuilder = clone $builder;
        $disbursedBuilder = clone $builder;

        return [
            'total' => $totalBuilder->countAllResults(),
            'total_amount' => $amountBuilder->selectSum('amount')->get()->getRow()->amount ?? 0,
            'pending' => $pendingBuilder->where('status', 'pending')->countAllResults(),
            'verified' => $verifiedBuilder->where('status', 'verified')->countAllResults(),
            'approved' => $approvedBuilder->where('status', 'approved')->countAllResults(),
            'rejected' => $rejectedBuilder->where('status', 'rejected')->countAllResults(),
            'disbursed' => $disbursedBuilder->where('status', 'disbursed')->countAllResults(),
        ];
    }
}
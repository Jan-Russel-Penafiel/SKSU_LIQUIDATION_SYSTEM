<?php

namespace App\Models;

use CodeIgniter\Model;

class ScholarshipRecipientModel extends Model
{
    protected $table = 'scholarship_recipients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'recipient_id', 'first_name', 'last_name', 'middle_name', 'email', 'phone',
        'campus', 'course', 'year_level', 'scholarship_type', 'bank_account', 'bank_name', 'is_active'
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
        'recipient_id' => 'required|is_unique[scholarship_recipients.recipient_id,id,{id}]',
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email',
        'campus' => 'required|max_length[100]',
        'course' => 'required|max_length[100]',
        'year_level' => 'required|max_length[20]',
        'scholarship_type' => 'required|max_length[100]'
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

    public function getRecipientsByCampus($campus)
    {
        // Exclude recipients with pending liquidations
        $subquery = $this->db->table('manual_liquidations')
                             ->select('recipient_id')
                             ->where('status', 'pending')
                             ->getCompiledSelect();
        
        return $this->where('campus', $campus)
                    ->where('is_active', 1)
                    ->where("id NOT IN ($subquery)", null, false)
                    ->findAll();
    }

    public function searchRecipients($keyword, $campus = null, $excludePending = false)
    {
        $builder = $this->groupStart()
                        ->like('recipient_id', $keyword)
                        ->orLike('first_name', $keyword)
                        ->orLike('last_name', $keyword)
                        ->orLike('email', $keyword)
                        ->groupEnd()
                        ->where('is_active', 1);
        
        // Optionally exclude recipients with pending liquidations (for manual liquidation only)
        if ($excludePending) {
            $subquery = $this->db->table('manual_liquidations')
                                 ->select('recipient_id')
                                 ->where('status', 'pending')
                                 ->getCompiledSelect();
            $builder->where("id NOT IN ($subquery)", null, false);
        }
        
        // Filter by campus if provided
        if (!empty($campus)) {
            $builder->where('campus', $campus);
        }
        
        return $builder->findAll();
    }

    public function getFullName($id)
    {
        $recipient = $this->find($id);
        if ($recipient) {
            return trim($recipient['first_name'] . ' ' . $recipient['middle_name'] . ' ' . $recipient['last_name']);
        }
        return '';
    }
}
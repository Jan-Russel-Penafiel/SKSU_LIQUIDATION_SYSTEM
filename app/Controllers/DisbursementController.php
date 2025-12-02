<?php

namespace App\Controllers;

use App\Models\ManualLiquidationModel;
use App\Models\AtmLiquidationModel;
use App\Models\DisbursementModel;
use App\Models\ScholarshipRecipientModel;
use App\Models\UserModel;

class DisbursementController extends BaseController
{
    protected $manualLiquidationModel;
    protected $atmLiquidationModel;
    protected $disbursementModel;
    protected $recipientModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->manualLiquidationModel = new ManualLiquidationModel();
        $this->atmLiquidationModel = new AtmLiquidationModel();
        $this->disbursementModel = new DisbursementModel();
        $this->recipientModel = new ScholarshipRecipientModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        // Get filters
        $filters = [
            'campus' => $this->request->getGet('campus'),
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'status' => $this->request->getGet('status'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to')
        ];

        // Get disbursement summary by officer
        $disbursementSummary = $this->getDisbursementSummary($filters);
        
        // Get individual disbursements
        $disbursements = $this->disbursementModel->getDisbursementsWithOfficers($filters);

        $data = [
            'title' => 'Disbursement List',
            'disbursement_summary' => $disbursementSummary,
            'disbursements' => $disbursements,
            'filters' => $filters,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('disbursement/index', $data);
    }

    public function byOfficer()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $filters = [
            'campus' => $this->request->getGet('campus'),
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'status' => $this->request->getGet('status')
        ];

        // Get all disbursing officers with their disbursement statistics
        $officers = $this->userModel->getUsersByRole('disbursing_officer');
        $officerStats = [];

        foreach ($officers as $officer) {
            $manualStats = $this->manualLiquidationModel->getDashboardStatistics(
                array_merge($filters, ['disbursing_officer' => $officer['id']])
            );
            
            $officerStats[] = [
                'officer' => $officer,
                'manual_stats' => $manualStats
            ];
        }

        $data = [
            'title' => 'Disbursements by Officer',
            'officer_stats' => $officerStats,
            'filters' => $filters,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('disbursement/by_officer', $data);
    }

    public function viewOfficerDisbursements($officerId)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $officer = $this->userModel->find($officerId);
        if (!$officer) {
            return redirect()->back()->with('error', 'Officer not found.');
        }

        $filters = [
            'campus' => $this->request->getGet('campus'),
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'status' => $this->request->getGet('status'),
            'officer_id' => $officerId
        ];

        // Get officer's disbursements
        $disbursements = $this->disbursementModel->getDisbursementsWithOfficers($filters);
        
        // Get statistics from disbursement model
        $stats = $this->disbursementModel->getDisbursementSummary(
            array_merge($filters, ['officer_id' => $officerId])
        );

        $data = [
            'title' => 'Disbursements - ' . $officer['username'],
            'officer' => $officer,
            'disbursements' => $disbursements,
            'stats' => $stats,
            'filters' => $filters,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('disbursement/officer_details', $data);
    }

    public function create()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        // Get all disbursing officers and scholarship coordinators
        $disbursing_officers = $this->userModel->getUsersByRole('disbursing_officer');
        $scholarship_coordinators = $this->userModel->getUsersByRole('scholarship_coordinator');
        
        $data = [
            'title' => 'Add Scholarship Disbursement',
            'user' => $user,
            'disbursing_officers' => $disbursing_officers,
            'scholarship_coordinators' => $scholarship_coordinators
        ];

        return view('disbursement/create', $data);
    }

    public function store()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));

        // Validate input
        if (!$this->validate([
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
            'scholarship_coordinator_id' => 'permit_empty|integer'
        ])) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // First, check if recipient exists or create a new one
        $nameArray = explode(' ', trim($this->request->getPost('recipient_name')));
        $firstName = $nameArray[0] ?? '';
        $lastName = count($nameArray) > 1 ? end($nameArray) : '';
        $middleName = count($nameArray) > 2 ? implode(' ', array_slice($nameArray, 1, -1)) : '';
        
        $recipientData = [
            'recipient_id' => $this->request->getPost('recipient_id'),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'course' => $this->request->getPost('course_program'),
            'year_level' => $this->request->getPost('year_level'),
            'campus' => $this->request->getPost('campus'),
            'scholarship_type' => $this->request->getPost('scholarship_type'),
            'email' => $this->request->getPost('recipient_id') . '@temp.edu', // Temporary email
            'is_active' => 1
        ];

        // Check if recipient exists
        $existingRecipient = $this->recipientModel->where('recipient_id', $recipientData['recipient_id'])->first();
        
        if (!$existingRecipient) {
            $recipient_id = $this->recipientModel->insert($recipientData);
        } else {
            $recipient_id = $existingRecipient['id'];
        }

        // Prepare disbursement data
        $disbursementData = [
            'disbursement_date' => $this->request->getPost('disbursement_date'),
            'recipient_name' => $this->request->getPost('recipient_name'),
            'recipient_id' => $this->request->getPost('recipient_id'),
            'course_program' => $this->request->getPost('course_program'),
            'year_level' => $this->request->getPost('year_level'),
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'scholarship_type' => $this->request->getPost('scholarship_type'),
            'amount' => $this->request->getPost('amount'),
            'disbursement_method' => $this->request->getPost('disbursement_method'),
            'campus' => $this->request->getPost('campus'),
            'disbursing_officer_id' => $this->request->getPost('disbursing_officer_id'),
            'status' => 'pending',
            'remarks' => $this->request->getPost('remarks') ?: null
        ];

        // Start transaction
        $this->disbursementModel->transStart();

        // Save disbursement
        $disbursementId = $this->disbursementModel->insert($disbursementData);

        $this->disbursementModel->transComplete();

        // Check transaction status
        if ($this->disbursementModel->transStatus() === FALSE) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to save disbursement. Please try again.');
        } else {
            return redirect()->to('/disbursement')
                           ->with('success', 'Scholarship disbursement has been successfully added. Please approve it to create a liquidation record.');
        }
    }

    public function show($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $disbursement = $this->disbursementModel->find($id);
        
        if (!$disbursement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get disbursing officer info
        $officer = $this->userModel->find($disbursement['disbursing_officer_id']);
        
        $data = [
            'title' => 'Disbursement Details',
            'disbursement' => $disbursement,
            'officer' => $officer,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('disbursement/show', $data);
    }

    private function getDisbursementSummary($filters = [])
    {
        $officers = $this->userModel->getUsersByRole('disbursing_officer');
        $summary = [];

        foreach ($officers as $officer) {
            $manualStats = $this->manualLiquidationModel->getDashboardStatistics(
                array_merge($filters, ['disbursing_officer' => $officer['id']])
            );
            
            if ($manualStats['total'] > 0) {
                $summary[] = [
                    'officer' => $officer,
                    'stats' => $manualStats
                ];
            }
        }

        return $summary;
    }

    public function officerDashboard()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        // Check if user is a disbursing officer
        if ($user['role'] !== 'disbursing_officer') {
            return redirect()->back()->with('error', 'Access denied. This page is for disbursing officers only.');
        }

        $filters = [
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'status' => $this->request->getGet('status'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to')
        ];

        // Get officer's disbursement statistics
        $officerStats = $this->manualLiquidationModel->getDashboardStatistics(
            array_merge($filters, ['disbursing_officer' => $user['id']])
        );
        
        // Get officer's recent liquidations
        $recentLiquidations = $this->manualLiquidationModel->getLiquidationsByOfficer(
            $user['id'], 
            array_merge($filters, ['limit' => 10])
        );
        
        // Get pending disbursements requiring attention
        $pendingDisbursements = $this->manualLiquidationModel->where('disbursing_officer_id', $user['id'])
                                                             ->where('status', 'pending')
                                                             ->orderBy('created_at', 'DESC')
                                                             ->findAll(20);
        
        $data = [
            'title' => 'Officer Dashboard - ' . $user['username'],
            'user' => $user,
            'officer_stats' => $officerStats,
            'recent_liquidations' => $recentLiquidations,
            'pending_disbursements' => $pendingDisbursements,
            'filters' => $filters
        ];

        return view('disbursement/officer_dashboard', $data);
    }

    public function approveDisbursement($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'disbursing_officer') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied'
            ]);
        }

        $liquidation = $this->manualLiquidationModel->find($id);
        
        if (!$liquidation || $liquidation['disbursing_officer_id'] != $user['id']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Liquidation not found or access denied'
            ]);
        }

        $success = $this->manualLiquidationModel->update($id, [
            'status' => 'approved',
            'approved_date' => date('Y-m-d H:i:s'),
            'remarks' => $this->request->getPost('remarks')
        ]);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Disbursement approved successfully' : 'Failed to approve disbursement'
        ]);
    }

    public function rejectDisbursement($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'disbursing_officer') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied'
            ]);
        }

        $liquidation = $this->manualLiquidationModel->find($id);
        
        if (!$liquidation || $liquidation['disbursing_officer_id'] != $user['id']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Liquidation not found or access denied'
            ]);
        }

        $remarks = $this->request->getPost('remarks');
        if (empty($remarks)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Remarks are required for rejection'
            ]);
        }

        $success = $this->manualLiquidationModel->update($id, [
            'status' => 'rejected',
            'rejected_date' => date('Y-m-d H:i:s'),
            'remarks' => $remarks
        ]);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Disbursement rejected successfully' : 'Failed to reject disbursement'
        ]);
    }

    public function verify($id)
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if (!in_array($user['role'], ['disbursing_officer', 'admin', 'administrator'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Only authorized personnel can verify disbursements.'
            ]);
        }

        $disbursement = $this->disbursementModel->find($id);
        
        if (!$disbursement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Disbursement not found'
            ]);
        }

        if ($disbursement['status'] !== 'pending') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This disbursement has already been processed'
            ]);
        }

        // Update disbursement status to verified
        $success = $this->disbursementModel->update($id, [
            'status' => 'verified',
            'verification_date' => date('Y-m-d H:i:s'),
            'verified_by' => $user['id']
        ]);

        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Disbursement verified successfully. It can now be approved.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to verify disbursement'
            ]);
        }
    }

    public function approve($id)
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ]);
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        // Allow both disbursing officers and administrators to approve
        if (!in_array($user['role'], ['disbursing_officer', 'administrator', 'admin', 'chairman'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Only authorized personnel can approve disbursements.'
            ]);
        }

        $disbursement = $this->disbursementModel->find($id);
        
        if (!$disbursement) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Disbursement not found'
            ]);
        }

        if (!in_array($disbursement['status'], ['verified', 'pending'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This disbursement cannot be approved in its current status'
            ]);
        }

        // Start transaction
        $this->disbursementModel->transStart();

        // Update disbursement status to approved
        $disbursementUpdate = $this->disbursementModel->update($id, [
            'status' => 'approved',
            'approval_date' => date('Y-m-d H:i:s'),
            'approved_by' => $user['id']
        ]);

        // Find or create corresponding manual liquidation entry
        $voucherNumber = 'DV-' . date('Y') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        $manualLiquidation = $this->manualLiquidationModel
            ->where('voucher_number', $voucherNumber)
            ->first();

        if ($manualLiquidation) {
            // Update existing manual liquidation entry to make it visible
            $liquidationUpdate = $this->manualLiquidationModel->update($manualLiquidation['id'], [
                'status' => 'pending',
                'type' => 'disbursement',
                'source_type' => 'disbursement',
                'disbursement_id' => $id,
                'approved_date' => null,
                'approved_by' => null,
                'remarks' => 'Transformed from approved disbursement - awaiting liquidation'
            ]);
        } else {
            // Create new manual liquidation entry if it doesn't exist
            $recipient = $this->recipientModel->where('recipient_id', $disbursement['recipient_id'])->first();
            if (!$recipient) {
                // Create recipient if not exists
                $nameArray = explode(' ', trim($disbursement['recipient_name']));
                $firstName = $nameArray[0] ?? '';
                $lastName = count($nameArray) > 1 ? end($nameArray) : '';
                $middleName = count($nameArray) > 2 ? implode(' ', array_slice($nameArray, 1, -1)) : '';
                
                $recipientData = [
                    'recipient_id' => $disbursement['recipient_id'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'middle_name' => $middleName,
                    'course' => $disbursement['course_program'],
                    'year_level' => $disbursement['year_level'],
                    'campus' => $disbursement['campus'],
                    'scholarship_type' => $disbursement['scholarship_type'],
                    'email' => $disbursement['recipient_id'] . '@temp.edu',
                    'is_active' => 1
                ];
                $recipient_id = $this->recipientModel->insert($recipientData);
            } else {
                $recipient_id = $recipient['id'];
            }

            $manualLiquidationData = [
                'disbursement_id' => $id,
                'recipient_id' => $recipient_id,
                'disbursing_officer_id' => $disbursement['disbursing_officer_id'],
                'voucher_number' => $voucherNumber,
                'amount' => $disbursement['amount'],
                'liquidation_date' => $disbursement['disbursement_date'],
                'semester' => $disbursement['semester'],
                'academic_year' => $disbursement['academic_year'],
                'campus' => $disbursement['campus'],
                'description' => $disbursement['scholarship_type'] . ' - ' . $disbursement['recipient_name'] . ' (From Disbursement)',
                'status' => 'pending',
                'type' => 'disbursement',
                'source_type' => 'disbursement',
                'remarks' => 'Transformed from approved disbursement - awaiting liquidation'
            ];
            
            $this->manualLiquidationModel->insert($manualLiquidationData);
        }

        $this->disbursementModel->transComplete();

        if ($this->disbursementModel->transStatus() === FALSE) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to approve disbursement'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Disbursement approved successfully and transformed to liquidation record!'
            ]);
        }
    }
}
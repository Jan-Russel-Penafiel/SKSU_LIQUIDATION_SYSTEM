<?php

namespace App\Controllers;

use App\Models\ManualLiquidationModel;
use App\Models\AtmLiquidationModel;
use App\Models\AtmLiquidationDetailModel;
use App\Models\ScholarshipRecipientModel;
use App\Models\DisbursementModel;
use App\Models\UserModel;

class ScholarshipCoordinatorController extends BaseController
{
    protected $manualLiquidationModel;
    protected $atmLiquidationModel;
    protected $atmLiquidationDetailModel;
    protected $recipientModel;
    protected $disbursementModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->manualLiquidationModel = new ManualLiquidationModel();
        $this->atmLiquidationModel = new AtmLiquidationModel();
        $this->atmLiquidationDetailModel = new AtmLiquidationDetailModel();
        $this->recipientModel = new ScholarshipRecipientModel();
        $this->disbursementModel = new DisbursementModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        // Check if user is a scholarship coordinator
        if ($user['role'] !== 'scholarship_coordinator') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Scholarship Coordinator privileges required.');
        }

        // Get coordinator's campus
        $coordinatorCampus = $user['campus'];
        $coordinatorId = $user['id'];

        $filters = [
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'status' => $this->request->getGet('status'),
            'campus' => $coordinatorCampus // Filter by coordinator's campus
        ];

        // Get statistics for coordinator's campus
        $manualStats = $this->manualLiquidationModel->getDashboardStatistics($filters);
        $atmStats = $this->atmLiquidationModel->getDashboardStatistics($filters);

        // Get liquidations assigned to this coordinator
        $coordinatorLiquidations = $this->manualLiquidationModel
            ->where('scholarship_coordinator_id', $coordinatorId)
            ->where('status !=', 'hidden')
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Get pending items that need coordinator attention
        $pendingLiquidations = $this->manualLiquidationModel->getLiquidationsWithRecipients([
            'status' => 'pending',
            'campus' => $coordinatorCampus
        ]);

        // Get recent disbursements for this campus
        $recentDisbursements = $this->disbursementModel
            ->where('campus', $coordinatorCampus)
            ->where('status !=', 'hidden')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Get campus recipients count
        $totalRecipients = $this->recipientModel
            ->where('campus', $coordinatorCampus)
            ->where('is_active', 1)
            ->countAllResults();

        $data = [
            'title' => 'Scholarship Coordinator Dashboard',
            'user' => $user,
            'coordinator_campus' => $coordinatorCampus,
            'manualStats' => $manualStats,
            'atmStats' => $atmStats,
            'coordinator_liquidations' => $coordinatorLiquidations,
            'pending_liquidations' => $pendingLiquidations,
            'recent_disbursements' => $recentDisbursements,
            'total_recipients' => $totalRecipients,
            'filters' => $filters
        ];

        return view('scholarship_coordinator/index', $data);
    }

    public function myLiquidations()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'scholarship_coordinator') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $filters = [
            'status' => $this->request->getGet('status'),
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'campus' => $user['campus'], // Filter by coordinator's campus
            'scholarship_coordinator' => $user['id'] // Also filter by assigned coordinator
        ];

        $liquidations = $this->manualLiquidationModel->getLiquidationsWithRecipients($filters);

        $data = [
            'title' => 'My Assigned Liquidations',
            'user' => $user,
            'liquidations' => $liquidations,
            'filters' => $filters
        ];

        return view('scholarship_coordinator/my_liquidations', $data);
    }

    public function campusOverview()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'scholarship_coordinator') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $coordinatorCampus = $user['campus'];

        $filters = [
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'campus' => $coordinatorCampus
        ];

        // Get all liquidations for this campus
        $allLiquidations = $this->manualLiquidationModel->getLiquidationsWithRecipients($filters);
        
        // Get campus recipients
        $campusRecipients = $this->recipientModel
            ->where('campus', $coordinatorCampus)
            ->where('is_active', 1)
            ->findAll();

        // Get campus disbursements
        $campusDisbursements = $this->disbursementModel
            ->where('campus', $coordinatorCampus)
            ->where('status !=', 'hidden')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Get campus statistics
        $campusStats = [
            'total_recipients' => count($campusRecipients),
            'total_liquidations' => count($allLiquidations),
            'pending_liquidations' => count(array_filter($allLiquidations, fn($l) => $l['status'] === 'pending')),
            'approved_liquidations' => count(array_filter($allLiquidations, fn($l) => $l['status'] === 'approved')),
            'total_disbursements' => count($campusDisbursements)
        ];

        $data = [
            'title' => 'Campus Overview - ' . $coordinatorCampus,
            'user' => $user,
            'coordinator_campus' => $coordinatorCampus,
            'campus_stats' => $campusStats,
            'campus_liquidations' => $allLiquidations,
            'campus_recipients' => $campusRecipients,
            'campus_disbursements' => $campusDisbursements,
            'filters' => $filters
        ];

        return view('scholarship_coordinator/campus_overview', $data);
    }

    public function manageLiquidations()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'scholarship_coordinator') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $filters = [
            'status' => $this->request->getGet('status'),
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'campus' => $user['campus']
        ];

        $liquidations = $this->manualLiquidationModel->getLiquidationsWithRecipients($filters);

        $data = [
            'title' => 'Manage Campus Liquidations',
            'user' => $user,
            'liquidations' => $liquidations,
            'filters' => $filters
        ];

        return view('scholarship_coordinator/manage_liquidations', $data);
    }

    public function reviewLiquidation($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'scholarship_coordinator') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $liquidation = $this->manualLiquidationModel->getLiquidationWithRecipient($id);
        
        if (!$liquidation) {
            return redirect()->back()->with('error', 'Liquidation not found.');
        }

        // Check if liquidation is from coordinator's campus
        if ($liquidation['campus'] !== $user['campus']) {
            return redirect()->back()->with('error', 'Access denied. This liquidation is not from your campus.');
        }

        $data = [
            'title' => 'Review Liquidation',
            'user' => $user,
            'liquidation' => $liquidation
        ];

        return view('scholarship_coordinator/review_liquidation', $data);
    }

    public function updateLiquidationStatus()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'scholarship_coordinator') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('remarks');

        // Debug logging
        log_message('debug', 'Update Status - ID: ' . $id . ', Status: ' . $status);

        // Validate input
        if (!$id || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID and status are required']);
        }

        if (!in_array($status, ['pending', 'verified', 'approved', 'rejected'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status value']);
        }

        // Handle multiple IDs (comma-separated)
        $ids = array_filter(array_map('trim', explode(',', $id)));
        
        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No valid IDs provided']);
        }

        $successCount = 0;
        $errors = [];

        foreach ($ids as $liquidationId) {
            // Get liquidation to verify campus
            $liquidation = $this->manualLiquidationModel->find($liquidationId);
            
            if (!$liquidation) {
                $errors[] = "Liquidation ID {$liquidationId}: not found";
                log_message('error', "Liquidation ID {$liquidationId} not found");
                continue;
            }

            if ($liquidation['campus'] !== $user['campus']) {
                $errors[] = "Liquidation ID {$liquidationId}: access denied (different campus)";
                log_message('error', "Access denied for liquidation ID {$liquidationId}");
                continue;
            }

            // Prepare update data with all required fields to satisfy validation
            $data = [
                'recipient_id' => $liquidation['recipient_id'],
                'disbursing_officer_id' => $liquidation['disbursing_officer_id'],
                'voucher_number' => $liquidation['voucher_number'],
                'amount' => $liquidation['amount'],
                'liquidation_date' => $liquidation['liquidation_date'],
                'semester' => $liquidation['semester'],
                'academic_year' => $liquidation['academic_year'],
                'campus' => $liquidation['campus'],
                'status' => $status,
                'scholarship_coordinator_id' => $user['id']
            ];

            if (!empty($remarks)) {
                $data['remarks'] = $remarks;
            }

            try {
                if ($this->manualLiquidationModel->update($liquidationId, $data)) {
                    $successCount++;
                    log_message('debug', "Successfully updated liquidation ID {$liquidationId}");
                } else {
                    $dbErrors = $this->manualLiquidationModel->errors();
                    $errorMsg = !empty($dbErrors) ? implode(', ', $dbErrors) : 'Database update failed';
                    $errors[] = "Liquidation ID {$liquidationId}: {$errorMsg}";
                    log_message('error', "Failed to update liquidation ID {$liquidationId}: " . print_r($dbErrors, true));
                }
            } catch (\Exception $e) {
                $errors[] = "Liquidation ID {$liquidationId}: " . $e->getMessage();
                log_message('error', "Exception updating liquidation ID {$liquidationId}: " . $e->getMessage());
            }
        }

        if ($successCount > 0) {
            $message = $successCount === count($ids) 
                ? 'Status updated successfully' 
                : "Updated {$successCount} of " . count($ids) . " liquidations";
            return $this->response->setJSON([
                'success' => true, 
                'message' => $message,
                'errors' => $errors,
                'updated_count' => $successCount
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => count($errors) > 0 ? implode('; ', $errors) : 'Failed to update status',
                'errors' => $errors
            ]);
        }
    }

    public function reports()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'scholarship_coordinator') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $coordinatorCampus = $user['campus'];

        // Get report data
        $filters = [
            'campus' => $coordinatorCampus,
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year')
        ];

        $reportData = [
            'manual_liquidations' => $this->manualLiquidationModel->getLiquidationsWithRecipients($filters),
            'campus_statistics' => $this->manualLiquidationModel->getDashboardStatistics($filters),
            'disbursements' => $this->disbursementModel->where('campus', $coordinatorCampus)->findAll()
        ];

        $data = [
            'title' => 'Campus Reports - ' . $coordinatorCampus,
            'user' => $user,
            'coordinator_campus' => $coordinatorCampus,
            'report_data' => $reportData,
            'filters' => $filters
        ];

        return view('scholarship_coordinator/reports', $data);
    }
}
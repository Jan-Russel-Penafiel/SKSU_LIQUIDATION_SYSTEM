<?php

namespace App\Controllers;

use App\Models\ManualLiquidationModel;
use App\Models\ScholarshipRecipientModel;
use App\Models\UserModel;

class ManualLiquidationController extends BaseController
{
    protected $manualLiquidationModel;
    protected $recipientModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->manualLiquidationModel = new ManualLiquidationModel();
        $this->recipientModel = new ScholarshipRecipientModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $filters = [
            'campus' => $this->request->getGet('campus'),
            'status' => $this->request->getGet('status'),
            'disbursing_officer' => $this->request->getGet('disbursing_officer'),
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year')
        ];

        $liquidations = $this->manualLiquidationModel->getLiquidationsWithRecipients($filters);
        $disbursing_officers = $this->userModel->getUsersByRole('disbursing_officer');

        $data = [
            'title' => 'Manual Liquidations',
            'liquidations' => $liquidations,
            'disbursing_officers' => $disbursing_officers,
            'filters' => $filters,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/index', $data);
    }

    public function create()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $recipients = $this->recipientModel->findAll();
        $disbursing_officers = $this->userModel->getUsersByRole('disbursing_officer');
        $scholarship_coordinators = $this->userModel->getUsersByRole('scholarship_coordinator');

        $data = [
            'title' => 'Create Manual Liquidation',
            'recipients' => $recipients,
            'disbursing_officers' => $disbursing_officers,
            'scholarship_coordinators' => $scholarship_coordinators,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/create', $data);
    }

    public function store()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        
        if (!$this->validate([
            'recipient_id' => 'required|numeric',
            'voucher_number' => 'required|max_length[50]',
            'amount' => 'required|decimal',
            'liquidation_date' => 'required|valid_date',
            'semester' => 'required|max_length[20]',
            'academic_year' => 'required|max_length[20]',
            'disbursing_officer_id' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Get recipient's campus from database to ensure accuracy
        $recipientId = $this->request->getPost('recipient_id');
        $recipient = $this->recipientModel->find($recipientId);
        
        if (!$recipient) {
            return redirect()->back()->withInput()->with('error', 'Recipient not found.');
        }

        $data = [
            'recipient_id' => $recipientId,
            'disbursing_officer_id' => $this->request->getPost('disbursing_officer_id'),
            'voucher_number' => $this->request->getPost('voucher_number'),
            'amount' => $this->request->getPost('amount'),
            'liquidation_date' => $this->request->getPost('liquidation_date'),
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'campus' => $recipient['campus'], // Use recipient's actual campus from database
            'description' => $this->request->getPost('description'),
            'status' => 'pending'
        ];

        if ($this->manualLiquidationModel->insert($data)) {
            return redirect()->to('/manual-liquidation')->with('success', 'Manual liquidation entry created successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to create liquidation entry.');
        }
    }

    public function show($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $liquidation = $this->manualLiquidationModel->getLiquidationWithDetails($id);
        
        if (!$liquidation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Liquidation Details',
            'liquidation' => $liquidation,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/show', $data);
    }

    public function edit($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $liquidation = $this->manualLiquidationModel->getLiquidationWithDetails($id);
        
        if (!$liquidation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $recipients = $this->recipientModel->findAll();
        $disbursing_officers = $this->userModel->getUsersByRole('disbursing_officer');
        $scholarship_coordinators = $this->userModel->getUsersByRole('scholarship_coordinator');

        $data = [
            'title' => 'Edit Manual Liquidation',
            'liquidation' => $liquidation,
            'recipients' => $recipients,
            'officers' => $disbursing_officers,
            'coordinators' => $scholarship_coordinators,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        
        if (!$this->validate([
            'recipient_id' => 'required|numeric',
            'voucher_number' => 'required|max_length[50]',
            'amount' => 'required|decimal',
            'liquidation_date' => 'required|valid_date',
            'semester' => 'required|max_length[20]',
            'academic_year' => 'required|max_length[20]',
            'campus' => 'required|max_length[100]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'recipient_id' => $this->request->getPost('recipient_id'),
            'voucher_number' => $this->request->getPost('voucher_number'),
            'amount' => $this->request->getPost('amount'),
            'liquidation_date' => $this->request->getPost('liquidation_date'),
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'campus' => $this->request->getPost('campus'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->manualLiquidationModel->update($id, $data)) {
            return redirect()->to('/manual-liquidation')->with('success', 'Liquidation entry updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update liquidation entry.');
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        if ($this->manualLiquidationModel->delete($id)) {
            return redirect()->to('/manual-liquidation')->with('success', 'Liquidation entry deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete liquidation entry.');
        }
    }

    public function entryByRecipient()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $recipients = $this->recipientModel->findAll();

        $data = [
            'title' => 'Entry by Recipient',
            'recipients' => $recipients,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_recipient', $data);
    }

    public function entryByCampus()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $campuses = ['Main Campus', 'Kalamansig Campus', 'Palimbang Campus', 'Isulan Campus', 'Bagumbayan Campus'];
        $disbursing_officers = $this->userModel->getUsersByRole('disbursing_officer');
        $scholarship_coordinators = $this->userModel->getUsersByRole('scholarship_coordinator');

        $data = [
            'title' => 'Entry by Campus',
            'campuses' => $campuses,
            'officers' => $disbursing_officers,
            'coordinators' => $scholarship_coordinators,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_campus', $data);
    }

    public function entryByOfficer()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $officers = $this->userModel->getUsersByRole('disbursing_officer');

        $data = [
            'title' => 'Entry by Officer',
            'officers' => $officers,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_officer', $data);
    }

    public function entryByCoordinator()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $coordinators = $this->userModel->getUsersByRole('scholarship_coordinator');

        $data = [
            'title' => 'Entry by Coordinator',
            'coordinators' => $coordinators,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_coordinator', $data);
    }

    public function searchRecipients()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $keyword = $this->request->getPost('keyword');
        $recipients = $this->recipientModel->searchRecipients($keyword);

        return $this->response->setJSON(['recipients' => $recipients]);
    }

    // API Methods for AJAX calls
    public function getLiquidationsByRecipientAPI()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $recipientId = $this->request->getGet('recipient_id');
        $liquidations = $this->manualLiquidationModel->getLiquidationsByRecipient($recipientId);
        $statistics = $this->manualLiquidationModel->getDashboardStatistics(['recipient_id' => $recipientId]);

        return $this->response->setJSON([
            'success' => true,
            'liquidations' => $liquidations,
            'statistics' => $statistics
        ]);
    }

    public function getLiquidationsByCampusAPI()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $campus = $this->request->getGet('campus');
        $liquidations = $this->manualLiquidationModel->getLiquidationsByCampus($campus);
        $statistics = $this->manualLiquidationModel->getDashboardStatistics(['campus' => $campus]);

        return $this->response->setJSON([
            'success' => true,
            'liquidations' => $liquidations,
            'statistics' => $statistics
        ]);
    }

    public function getLiquidationsByOfficerAPI()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $officerId = $this->request->getGet('officer_id');
        $liquidations = $this->manualLiquidationModel->getLiquidationsByOfficer($officerId);
        $statistics = $this->manualLiquidationModel->getDashboardStatistics(['disbursing_officer' => $officerId]);

        return $this->response->setJSON([
            'success' => true,
            'liquidations' => $liquidations,
            'statistics' => $statistics
        ]);
    }

    public function getLiquidationsByCoordinatorAPI()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $coordinatorId = $this->request->getGet('coordinator_id');
        $liquidations = $this->manualLiquidationModel->getLiquidationsByCoordinator($coordinatorId);
        $pending = array_filter($liquidations, function($l) { return $l['status'] == 'pending'; });
        $statistics = $this->manualLiquidationModel->getDashboardStatistics(['scholarship_coordinator' => $coordinatorId]);

        return $this->response->setJSON([
            'success' => true,
            'all' => $liquidations,
            'pending' => array_values($pending),
            'statistics' => $statistics
        ]);
    }

    public function updateLiquidationStatus()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('remarks');

        $data = ['status' => $status];
        if ($remarks) {
            $data['remarks'] = $remarks;
        }

        if ($this->manualLiquidationModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status']);
        }
    }

    public function bulkStore()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        try {
            // Get form data
            $recipientIds = json_decode($this->request->getPost('recipient_ids'), true);
            $voucherPrefix = $this->request->getPost('voucher_prefix') ?? '';
            $amount = $this->request->getPost('amount');
            $liquidationDate = $this->request->getPost('liquidation_date');
            $semester = $this->request->getPost('semester');
            $academicYear = $this->request->getPost('academic_year');
            $description = $this->request->getPost('description') ?? '';
            $campus = $this->request->getPost('campus');

            if (empty($recipientIds) || !is_array($recipientIds)) {
                session()->setFlashdata('error', 'No recipients selected.');
                return redirect()->back();
            }

            $successCount = 0;
            $errors = [];
            $voucherCounter = 1;

            foreach ($recipientIds as $recipientId) {
                // Get recipient's campus from database to ensure accuracy
                $recipient = $this->recipientModel->find($recipientId);
                if (!$recipient) {
                    $errors[] = "Recipient ID $recipientId not found";
                    continue;
                }
                
                // Generate voucher number
                $voucherNumber = $voucherPrefix . str_pad($voucherCounter, 4, '0', STR_PAD_LEFT);
                
                $liquidationData = [
                    'recipient_id' => $recipientId,
                    'voucher_number' => $voucherNumber,
                    'amount' => $amount,
                    'liquidation_date' => $liquidationDate,
                    'semester' => $semester,
                    'academic_year' => $academicYear,
                    'campus' => $recipient['campus'], // Use recipient's actual campus from database
                    'description' => $description,
                    'status' => 'pending',
                    'created_by' => $this->session->get('user_id')
                ];

                if ($this->manualLiquidationModel->insert($liquidationData)) {
                    $successCount++;
                } else {
                    $errors[] = "Failed to create liquidation for recipient ID: $recipientId";
                }
                
                $voucherCounter++;
            }

            if ($successCount > 0) {
                $message = "Successfully created $successCount liquidation entries.";
                if (!empty($errors)) {
                    $message .= " " . count($errors) . " entries failed.";
                }
                session()->setFlashdata('success', $message);
            } else {
                session()->setFlashdata('error', 'Failed to create any liquidation entries. ' . implode(' ', $errors));
            }

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'An error occurred: ' . $e->getMessage());
        }

        return redirect()->to('manual-liquidation/entry-by-campus');
    }

    public function bulkUpdateStatus()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        try {
            $liquidationIds = $this->request->getJSON(true)['liquidation_ids'] ?? [];
            $newStatus = $this->request->getJSON(true)['status'] ?? '';

            if (empty($liquidationIds) || !is_array($liquidationIds)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No liquidations selected'
                ]);
            }

            if (!in_array($newStatus, ['pending', 'verified', 'approved', 'rejected'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid status'
                ]);
            }

            $successCount = 0;
            foreach ($liquidationIds as $id) {
                $updateData = [
                    'status' => $newStatus,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if ($newStatus === 'approved') {
                    $updateData['approved_by'] = $this->session->get('user_id');
                    $updateData['approved_date'] = date('Y-m-d H:i:s');
                }

                if ($this->manualLiquidationModel->update($id, $updateData)) {
                    $successCount++;
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Successfully updated $successCount liquidation(s) to $newStatus status"
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
}
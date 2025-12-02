<?php

namespace App\Controllers;

use App\Models\AtmLiquidationModel;
use App\Models\ManualLiquidationModel;

class AccountingController extends BaseController
{
    protected $atmLiquidationModel;
    protected $manualLiquidationModel;

    public function __construct()
    {
        $this->atmLiquidationModel = new AtmLiquidationModel();
        $this->manualLiquidationModel = new ManualLiquidationModel();
    }

    public function index()
    {
        // Check if user is accounting officer
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        $data = [
            'title' => 'Accounting Dashboard',
            'user' => session()->get('user')
        ];

        return view('accounting/dashboard', $data);
    }

    public function approvedAtmLiquidations()
    {
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        // Load the detail model to get individual records
        $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
        
        // Get approved liquidations (both individual and batch records)
        $liquidations = $atmDetailModel->getLiquidationsWithRecipients(['status' => 'approved']);

        $data = [
            'title' => 'Approved ATM Liquidations',
            'user' => session()->get('user'),
            'liquidations' => $liquidations
        ];

        return view('accounting/atm_liquidations', $data);
    }

    public function receivedAtmLiquidations()
    {
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        // Load detail model
        $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
        
        // Get all liquidations (individual and batch) sent to accounting
        $liquidations = $atmDetailModel->getLiquidationsWithRecipients(['status' => 'sent_to_accounting']);

        $data = [
            'title' => 'Received ATM Liquidations',
            'user' => session()->get('user'),
            'liquidations' => $liquidations
        ];

        return view('accounting/received_atm_liquidations', $data);
    }

    public function approvedManualLiquidations()
    {
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        // Get approved manual liquidations
        $liquidations = $this->manualLiquidationModel->getLiquidationsWithRecipients(['status' => 'approved']);

        $data = [
            'title' => 'Approved Manual Liquidations',
            'user' => session()->get('user'),
            'liquidations' => $liquidations
        ];

        return view('accounting/manual_liquidations', $data);
    }

    public function viewAtmLiquidation($id)
    {
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        // Load detail model
        $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
        
        // Get individual liquidation with recipient details
        $builder = $atmDetailModel->db->table('atm_liquidation_details ald');
        $builder->select('ald.*, 
            sr.recipient_id as recipient_code, 
            CONCAT(sr.first_name, " ", sr.last_name) as recipient_name,
            sr.first_name, 
            sr.last_name, 
            sr.campus,
            u.username as created_by_name');
        $builder->join('scholarship_recipients sr', 'ald.recipient_id = sr.id', 'left');
        $builder->join('users u', 'ald.created_by = u.id', 'left');
        $builder->where('ald.id', $id);
        
        $liquidation = $builder->get()->getRowArray();
        
        if (!$liquidation) {
            return redirect()->back()->with('error', 'Liquidation not found.');
        }

        // Only show approved or received liquidations
        if (!in_array($liquidation['status'], ['approved', 'sent_to_accounting', 'completed'])) {
            return redirect()->back()->with('error', 'Access denied. Liquidation not approved.');
        }

        $data = [
            'title' => 'View ATM Liquidation',
            'user' => session()->get('user'),
            'liquidation' => $liquidation
        ];

        return view('accounting/view_atm_liquidation', $data);
    }

    public function viewAtmBatch($id)
    {
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        // Get batch information
        $batch = $this->atmLiquidationModel->find($id);
        
        if (!$batch) {
            return redirect()->back()->with('error', 'Batch not found.');
        }

        // Only show approved or received batches
        if (!in_array($batch['status'], ['approved', 'sent_to_accounting', 'completed'])) {
            return redirect()->back()->with('error', 'Access denied. Batch not approved.');
        }

        // Get all recipients in this batch
        $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
        $builder = $atmDetailModel->db->table('atm_liquidation_details ald');
        $builder->select('ald.*, 
            sr.recipient_id as recipient_code,
            CONCAT(sr.first_name, " ", sr.last_name) as recipient_name,
            sr.campus');
        $builder->join('scholarship_recipients sr', 'ald.recipient_id = sr.id', 'left');
        $builder->where('ald.atm_liquidation_id', $id);
        $builder->orderBy('sr.last_name', 'ASC');
        
        $details = $builder->get()->getResultArray();
        
        // Calculate total amount
        $totalAmount = array_sum(array_column($details, 'amount'));

        $data = [
            'title' => 'View Batch ATM Liquidation',
            'user' => session()->get('user'),
            'batch' => $batch,
            'details' => $details,
            'totalAmount' => $totalAmount
        ];

        return view('accounting/view_atm_batch', $data);
    }

    public function viewManualLiquidation($id)
    {
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        $liquidation = $this->manualLiquidationModel->getLiquidationWithDetails($id);
        
        if (!$liquidation) {
            return redirect()->back()->with('error', 'Liquidation not found.');
        }

        // Only show approved or processing liquidations
        $allowedStatuses = ['approved', 'sent_to_accounting', 'completed'];
        if (!in_array($liquidation['status'], $allowedStatuses)) {
            return redirect()->back()->with('error', 'Access denied. Liquidation not available for accounting.');
        }

        $data = [
            'title' => 'View Manual Liquidation',
            'user' => session()->get('user'),
            'liquidation' => $liquidation
        ];

        return view('accounting/view_manual_liquidation', $data);
    }

    public function receiveAtmLiquidation()
    {
        if (!$this->isAccountingOfficer()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        try {
            $id = $this->request->getPost('id');
            $remarks = $this->request->getPost('remarks');

            if (!$id) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation ID.']);
            }

            // Load detail model for individual records
            $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
            $liquidation = $atmDetailModel->find($id);
            
            if (!$liquidation || $liquidation['status'] !== 'approved') {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not approved.']);
            }

            // Update individual record status and received date
            $updateData = [
                'status' => 'sent_to_accounting',
                'accounting_received_date' => date('Y-m-d H:i:s')
            ];
            
            if (!empty($remarks)) {
                $updateData['remarks'] = $remarks;
            }

            // Skip validation on update to avoid required field issues
            if ($atmDetailModel->skipValidation(true)->update($id, $updateData)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Liquidation received successfully.']);
            } else {
                $errors = $atmDetailModel->errors();
                log_message('error', 'Failed to update liquidation: ' . json_encode($errors));
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to receive liquidation.']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in receiveAtmLiquidation: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function receiveAtmBatch()
    {
        if (!$this->isAccountingOfficer()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        try {
            $id = $this->request->getPost('id');
            $remarks = $this->request->getPost('remarks');

            if (!$id) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid batch ID.']);
            }

            $batch = $this->atmLiquidationModel->find($id);
            if (!$batch) {
                log_message('error', 'Batch not found: ' . $id);
                return $this->response->setJSON(['success' => false, 'message' => 'Batch not found.']);
            }
            
            if ($batch['status'] !== 'approved') {
                log_message('error', 'Batch status is not approved: ' . $batch['status'] . ' for batch ID: ' . $id);
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid batch or not approved. Current status: ' . $batch['status']]);
            }

            // Update the batch record
            $updateData = [
                'status' => 'sent_to_accounting',
                'accounting_received_date' => date('Y-m-d H:i:s')
            ];
            
            if (!empty($remarks)) {
                $updateData['remarks'] = $remarks;
            }

            // Update the batch header (skip validation)
            if (!$this->atmLiquidationModel->skipValidation(true)->update($id, $updateData)) {
                log_message('error', 'Failed to update batch: ' . json_encode($this->atmLiquidationModel->errors()));
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to receive batch.']);
            }

            // Update all detail records in this batch
            $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
            $detailData = [
                'status' => 'sent_to_accounting',
                'accounting_received_date' => date('Y-m-d H:i:s')
            ];
            
            if (!empty($remarks)) {
                $detailData['remarks'] = $remarks;
            }
            
            $atmDetailModel->where('atm_liquidation_id', $id)
                           ->where('status', 'approved')
                           ->set($detailData)
                           ->update();

            return $this->response->setJSON(['success' => true, 'message' => 'Batch received successfully.']);
        } catch (\Exception $e) {
            log_message('error', 'Exception in receiveAtmBatch: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function completeAtmLiquidation()
    {
        if (!$this->isAccountingOfficer()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation ID.']);
        }

        // Load detail model for individual records
        $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
        $liquidation = $atmDetailModel->find($id);
        
        if (!$liquidation || $liquidation['status'] !== 'sent_to_accounting') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not received.']);
        }

        $data = [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($remarks)) {
            $data['remarks'] = $remarks;
        }

        if ($atmDetailModel->skipValidation(true)->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Liquidation completed successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to complete liquidation.']);
        }
    }

    public function completeAtmBatch()
    {
        if (!$this->isAccountingOfficer()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $batchId = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$batchId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid batch ID.']);
        }

        // Check if batch exists and is in sent_to_accounting status
        $batch = $this->atmLiquidationModel->find($batchId);
        if (!$batch || $batch['status'] !== 'sent_to_accounting') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid batch or not received.']);
        }

        // Update all detail records in this batch
        $atmDetailModel = new \App\Models\AtmLiquidationDetailModel();
        $detailData = [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($remarks)) {
            $detailData['remarks'] = $remarks;
        }
        
        $atmDetailModel->where('atm_liquidation_id', $batchId)
                       ->where('status', 'sent_to_accounting')
                       ->set($detailData)
                       ->update();

        // Update batch status
        $batchData = [
            'status' => 'completed'
        ];
        
        if (!empty($remarks)) {
            $batchData['remarks'] = $remarks;
        }

        if ($this->atmLiquidationModel->skipValidation(true)->update($batchId, $batchData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Batch completed successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to complete batch.']);
        }
    }

    public function getDashboardData()
    {
        if (!$this->isAccountingOfficer()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        // Get ATM liquidations statistics
        $atmStats = [];
        $atmStats['approved'] = $this->atmLiquidationModel->where('status', 'approved')->countAllResults();
        $atmStats['received'] = $this->atmLiquidationModel->where('status', 'sent_to_accounting')->countAllResults();
        $atmStats['completed'] = $this->atmLiquidationModel->where('status', 'completed')->countAllResults();

        // Get Manual liquidations statistics  
        $manualStats = [];
        $manualStats['approved'] = $this->manualLiquidationModel->where('status', 'approved')->countAllResults();

        // Recent activity
        $recentApproved = $this->atmLiquidationModel->getBatchesWithUploader(['status' => 'approved']);
        $recentReceived = $this->atmLiquidationModel->getBatchesWithUploader(['status' => 'sent_to_accounting']);
        $recentManual = $this->manualLiquidationModel->getLiquidationsWithRecipients(['status' => 'approved']);

        $data = [
            'atmStats' => $atmStats,
            'manualStats' => $manualStats,
            'recentApproved' => array_slice($recentApproved, 0, 5),
            'recentReceived' => array_slice($recentReceived, 0, 5),
            'recentManual' => array_slice($recentManual, 0, 5)
        ];

        return $this->response->setJSON(['success' => true, 'data' => $data]);
    }

    private function isAccountingOfficer()
    {
        $user = session()->get('user');
        return $user && $user['role'] === 'accounting_officer';
    }
}
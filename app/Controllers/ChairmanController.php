<?php

namespace App\Controllers;

use App\Models\AtmLiquidationModel;
use App\Models\AtmLiquidationDetailModel;
use App\Models\ManualLiquidationModel;

class ChairmanController extends BaseController
{
    protected $atmLiquidationModel;
    protected $atmDetailModel;
    protected $manualLiquidationModel;

    public function __construct()
    {
        $this->atmLiquidationModel = new AtmLiquidationModel();
        $this->atmDetailModel = new AtmLiquidationDetailModel();
        $this->manualLiquidationModel = new ManualLiquidationModel();
    }

    public function index()
    {
        // Check if user is chairman
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        $data = [
            'title' => 'Chairman Dashboard',
            'user' => session()->get('user')
        ];

        return view('chairman/dashboard', $data);
    }

    public function pendingAtmLiquidations()
    {
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        // Get individual liquidations (verified status, no batch)
        $liquidations = $this->atmDetailModel->getLiquidationsWithRecipients([
            'status' => 'verified',
            'atm_liquidation_id' => null
        ]);

        // Get batch liquidations (verified status)
        $batches = $this->atmLiquidationModel->getBatchesWithUploader(['status' => 'verified']);

        $data = [
            'title' => 'Pending ATM Liquidations',
            'user' => session()->get('user'),
            'liquidations' => $liquidations,
            'batches' => $batches
        ];

        return view('chairman/atm_liquidations', $data);
    }

    public function pendingManualLiquidations()
    {
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        // Get manual liquidations pending chairman approval
        $liquidations = $this->manualLiquidationModel->getLiquidationsWithRecipients(['status' => 'verified']);

        $data = [
            'title' => 'Pending Manual Liquidations',
            'user' => session()->get('user'),
            'liquidations' => $liquidations
        ];

        return view('chairman/manual_liquidations', $data);
    }

    public function approvedAtmLiquidations()
    {
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        // Get approved per-recipient liquidations (no batch)
        $approvedRecipients = $this->atmDetailModel->getLiquidationsWithRecipients([
            'status' => 'approved',
            'atm_liquidation_id' => null
        ]);

        // Calculate total amount for recipients
        $totalApprovedRecipientAmount = 0;
        foreach ($approvedRecipients as $liquidation) {
            $totalApprovedRecipientAmount += $liquidation['amount'];
        }

        // Get approved batch liquidations
        $approvedBatches = $this->atmLiquidationModel->getBatchesWithUploader(['status' => 'approved']);

        // Calculate total amount for batches
        $totalApprovedBatchAmount = 0;
        foreach ($approvedBatches as $batch) {
            $totalApprovedBatchAmount += $batch['total_amount'];
        }

        $data = [
            'title' => 'Approved ATM Liquidations',
            'user' => session()->get('user'),
            'approvedRecipients' => $approvedRecipients,
            'approvedBatches' => $approvedBatches,
            'totalApprovedRecipientAmount' => $totalApprovedRecipientAmount,
            'totalApprovedBatchAmount' => $totalApprovedBatchAmount
        ];

        return view('chairman/approved_atm_liquidations', $data);
    }

    public function approvedManualLiquidations()
    {
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        // Get approved manual liquidations
        $approvedManual = $this->manualLiquidationModel->getLiquidationsWithRecipients(['status' => 'approved']);

        // Calculate total amount
        $totalApprovedAmount = 0;
        foreach ($approvedManual as $liquidation) {
            $totalApprovedAmount += $liquidation['amount'];
        }

        // Group by recipient for statistics
        $recipientStats = [];
        foreach ($approvedManual as $liquidation) {
            $recipientKey = $liquidation['recipient_id'];
            if (!isset($recipientStats[$recipientKey])) {
                $recipientStats[$recipientKey] = [
                    'recipient_id' => $liquidation['recipient_id'] ?? 'N/A',
                    'recipient_name' => $liquidation['first_name'] . ' ' . $liquidation['last_name'],
                    'campus' => $liquidation['campus'],
                    'total_count' => 0,
                    'total_amount' => 0,
                    'last_approved' => $liquidation['updated_at']
                ];
            }
            $recipientStats[$recipientKey]['total_count']++;
            $recipientStats[$recipientKey]['total_amount'] += $liquidation['amount'];
            
            // Update last approved date if newer
            if (strtotime($liquidation['updated_at']) > strtotime($recipientStats[$recipientKey]['last_approved'])) {
                $recipientStats[$recipientKey]['last_approved'] = $liquidation['updated_at'];
            }
        }

        // Sort by total amount descending
        usort($recipientStats, function($a, $b) {
            return $b['total_amount'] - $a['total_amount'];
        });

        $data = [
            'title' => 'Approved Manual Liquidations',
            'user' => session()->get('user'),
            'approvedManual' => $approvedManual,
            'totalApprovedAmount' => $totalApprovedAmount,
            'recipientStats' => $recipientStats
        ];

        return view('chairman/approved_manual_liquidations', $data);
    }

    public function viewAtmLiquidation($id)
    {
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        // Get individual liquidation with recipient details
        $builder = $this->atmDetailModel->db->table('atm_liquidation_details ald');
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

        $data = [
            'title' => 'Review ATM Liquidation',
            'user' => session()->get('user'),
            'liquidation' => $liquidation
        ];

        return view('chairman/view_atm_liquidation', $data);
    }

    public function viewAtmBatch($id)
    {
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        // Get batch information
        $batch = $this->atmLiquidationModel->find($id);
        
        if (!$batch) {
            return redirect()->back()->with('error', 'Batch not found.');
        }

        // Get all recipients in this batch
        $builder = $this->atmDetailModel->db->table('atm_liquidation_details ald');
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
            'title' => 'Review Batch ATM Liquidation',
            'user' => session()->get('user'),
            'batch' => $batch,
            'details' => $details,
            'totalAmount' => $totalAmount
        ];

        return view('chairman/view_atm_batch', $data);
    }

    public function viewManualLiquidation($id)
    {
        if (!$this->isChairman()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Chairman privileges required.');
        }

        $liquidation = $this->manualLiquidationModel->getLiquidationWithDetails($id);
        
        if (!$liquidation) {
            return redirect()->back()->with('error', 'Liquidation not found.');
        }

        $data = [
            'title' => 'Review Manual Liquidation',
            'user' => session()->get('user'),
            'liquidation' => $liquidation
        ];

        return view('chairman/view_manual_liquidation', $data);
    }

    public function approveAtmLiquidation()
    {
        if (!$this->isChairman()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation ID.']);
        }

        $liquidation = $this->atmDetailModel->find($id);
        if (!$liquidation || $liquidation['status'] !== 'verified') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not pending approval.']);
        }

        $updateData = [
            'status' => 'approved',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'remarks' => $remarks
        ];

        if ($this->atmDetailModel->update($id, $updateData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Liquidation approved successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve liquidation.']);
        }
    }

    public function rejectAtmLiquidation()
    {
        if (!$this->isChairman()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id || !$remarks) {
            return $this->response->setJSON(['success' => false, 'message' => 'Liquidation ID and rejection reason are required.']);
        }

        $liquidation = $this->atmDetailModel->find($id);
        if (!$liquidation || $liquidation['status'] !== 'verified') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not pending approval.']);
        }

        $updateData = [
            'status' => 'rejected',
            'remarks' => $remarks
        ];

        if ($this->atmDetailModel->update($id, $updateData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Liquidation rejected successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject liquidation.']);
        }
    }

    public function approveAtmBatch()
    {
        if (!$this->isChairman()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid batch ID.']);
        }

        $batch = $this->atmLiquidationModel->find($id);
        if (!$batch || $batch['status'] !== 'verified') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid batch or not pending approval.']);
        }

        // Update the batch record
        $updateData = [
            'status' => 'approved',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'remarks' => $remarks
        ];

        if ($this->atmLiquidationModel->update($id, $updateData)) {
            // Also update all detail records in this batch
            $this->atmDetailModel->where('atm_liquidation_id', $id)
                                 ->set([
                                     'status' => 'approved',
                                     'approved_by' => session()->get('user_id'),
                                     'approved_at' => date('Y-m-d H:i:s')
                                 ])
                                 ->update();
            
            return $this->response->setJSON(['success' => true, 'message' => 'Batch approved successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve batch.']);
        }
    }

    public function rejectAtmBatch()
    {
        if (!$this->isChairman()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id || !$remarks) {
            return $this->response->setJSON(['success' => false, 'message' => 'Batch ID and rejection reason are required.']);
        }

        $batch = $this->atmLiquidationModel->find($id);
        if (!$batch || $batch['status'] !== 'verified') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid batch or not pending approval.']);
        }

        // Update the batch record
        $updateData = [
            'status' => 'rejected',
            'remarks' => $remarks
        ];

        if ($this->atmLiquidationModel->update($id, $updateData)) {
            // Also update all detail records in this batch
            $this->atmDetailModel->where('atm_liquidation_id', $id)
                                 ->set(['status' => 'rejected'])
                                 ->update();
            
            return $this->response->setJSON(['success' => true, 'message' => 'Batch rejected successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject batch.']);
        }
    }

    public function approveManualLiquidation()
    {
        if (!$this->isChairman()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation ID.']);
        }

        $liquidation = $this->manualLiquidationModel->find($id);
        if (!$liquidation || $liquidation['status'] !== 'verified') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not pending approval.']);
        }

        $updateData = [
            'status' => 'approved',
            'remarks' => $remarks
        ];

        if ($this->manualLiquidationModel->update($id, $updateData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Liquidation approved successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve liquidation.']);
        }
    }

    public function rejectManualLiquidation()
    {
        if (!$this->isChairman()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id || !$remarks) {
            return $this->response->setJSON(['success' => false, 'message' => 'Liquidation ID and rejection reason are required.']);
        }

        $liquidation = $this->manualLiquidationModel->find($id);
        if (!$liquidation || $liquidation['status'] !== 'verified') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not pending approval.']);
        }

        $updateData = [
            'status' => 'rejected',
            'remarks' => $remarks
        ];

        if ($this->manualLiquidationModel->update($id, $updateData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Liquidation rejected successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject liquidation.']);
        }
    }

    public function getDashboardData()
    {
        if (!$this->isChairman()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        // Get ATM liquidations statistics from details table
        $atmStats = [];
        $atmStats['pending'] = $this->atmDetailModel->where('status', 'verified')->countAllResults();
        $atmStats['approved'] = $this->atmDetailModel->where('status', 'approved')->countAllResults();
        $atmStats['rejected'] = $this->atmDetailModel->where('status', 'rejected')->countAllResults();

        // Get Manual liquidations statistics  
        $manualStats = [];
        $manualStats['pending'] = $this->manualLiquidationModel->where('status', 'verified')->countAllResults();
        $manualStats['approved'] = $this->manualLiquidationModel->where('status', 'approved')->countAllResults();
        $manualStats['rejected'] = $this->manualLiquidationModel->where('status', 'rejected')->countAllResults();

        // Recent activity - Get both batch and per-recipient ATM liquidations
        $recentAtmBatch = $this->atmLiquidationModel->getBatchesWithUploader(['status' => 'verified']);
        $recentAtmPerRecipient = $this->atmDetailModel->getLiquidationsWithRecipients([
            'status' => 'verified',
            'atm_liquidation_id' => null
        ]);
        $recentManual = $this->manualLiquidationModel->getLiquidationsWithRecipients(['status' => 'verified']);

        $data = [
            'atmStats' => $atmStats,
            'manualStats' => $manualStats,
            'recentAtmBatch' => array_slice($recentAtmBatch, 0, 5),
            'recentAtmPerRecipient' => array_slice($recentAtmPerRecipient, 0, 5),
            'recentManual' => array_slice($recentManual, 0, 5)
        ];

        return $this->response->setJSON(['success' => true, 'data' => $data]);
    }

    private function isChairman()
    {
        $user = session()->get('user');
        return $user && $user['role'] === 'scholarship_chairman';
    }
}
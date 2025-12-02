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

        // Get approved ATM liquidations
        $liquidations = $this->atmLiquidationModel->getBatchesWithUploader(['status' => 'approved']);

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

        // Get liquidations sent to accounting
        $liquidations = $this->atmLiquidationModel->getBatchesWithUploader(['status' => 'sent_to_accounting']);

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

        $liquidation = $this->atmLiquidationModel->find($id);
        
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

    public function viewManualLiquidation($id)
    {
        if (!$this->isAccountingOfficer()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Accounting officer privileges required.');
        }

        $liquidation = $this->manualLiquidationModel->getLiquidationWithDetails($id);
        
        if (!$liquidation) {
            return redirect()->back()->with('error', 'Liquidation not found.');
        }

        // Only show approved liquidations
        if ($liquidation['status'] !== 'approved') {
            return redirect()->back()->with('error', 'Access denied. Liquidation not approved.');
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

        $id = $this->request->getPost('id');
        $remarks = $this->request->getPost('remarks');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation ID.']);
        }

        $liquidation = $this->atmLiquidationModel->find($id);
        if (!$liquidation || $liquidation['status'] !== 'approved') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not approved.']);
        }

        if ($this->atmLiquidationModel->updateStatus($id, 'sent_to_accounting', $remarks)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Liquidation received successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to receive liquidation.']);
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

        $liquidation = $this->atmLiquidationModel->find($id);
        if (!$liquidation || $liquidation['status'] !== 'sent_to_accounting') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid liquidation or not received.']);
        }

        if ($this->atmLiquidationModel->updateStatus($id, 'completed', $remarks)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Liquidation completed successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to complete liquidation.']);
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
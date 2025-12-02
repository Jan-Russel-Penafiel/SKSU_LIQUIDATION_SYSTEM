<?php

namespace App\Controllers;

use App\Models\ManualLiquidationModel;
use App\Models\ScholarshipRecipientModel;
use App\Models\UserModel;

class ManualLiquidation extends BaseController
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
            'disbursing_officer_id' => 'required|numeric',
            'voucher_number' => 'required|max_length[50]',
            'amount' => 'required|decimal|greater_than[0]',
            'liquidation_date' => 'required|valid_date',
            'semester' => 'required|max_length[20]',
            'academic_year' => 'required|max_length[20]',
            'campus' => 'required|max_length[100]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'recipient_id' => $this->request->getPost('recipient_id'),
            'disbursing_officer_id' => $this->request->getPost('disbursing_officer_id'),
            'scholarship_coordinator_id' => $this->request->getPost('scholarship_coordinator_id'),
            'voucher_number' => $this->request->getPost('voucher_number'),
            'amount' => $this->request->getPost('amount'),
            'liquidation_date' => $this->request->getPost('liquidation_date'),
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'campus' => $this->request->getPost('campus'),
            'description' => $this->request->getPost('description'),
            'status' => 'pending'
        ];

        if ($this->manualLiquidationModel->save($data)) {
            return redirect()->to('/manual-liquidation')->with('success', 'Manual liquidation created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create manual liquidation.');
        }
    }

    public function show($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $liquidation = $this->manualLiquidationModel->getLiquidationsWithRecipients(['id' => $id]);
        
        if (empty($liquidation)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Manual Liquidation Details',
            'liquidation' => $liquidation[0],
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/show', $data);
    }

    public function edit($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $liquidation = $this->manualLiquidationModel->find($id);
        
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
            'disbursing_officers' => $disbursing_officers,
            'scholarship_coordinators' => $scholarship_coordinators,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $liquidation = $this->manualLiquidationModel->find($id);
        
        if (!$liquidation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();
        
        if (!$this->validate([
            'recipient_id' => 'required|numeric',
            'disbursing_officer_id' => 'required|numeric',
            'voucher_number' => 'required|max_length[50]',
            'amount' => 'required|decimal|greater_than[0]',
            'liquidation_date' => 'required|valid_date',
            'semester' => 'required|max_length[20]',
            'academic_year' => 'required|max_length[20]',
            'campus' => 'required|max_length[100]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'recipient_id' => $this->request->getPost('recipient_id'),
            'disbursing_officer_id' => $this->request->getPost('disbursing_officer_id'),
            'scholarship_coordinator_id' => $this->request->getPost('scholarship_coordinator_id'),
            'voucher_number' => $this->request->getPost('voucher_number'),
            'amount' => $this->request->getPost('amount'),
            'liquidation_date' => $this->request->getPost('liquidation_date'),
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'campus' => $this->request->getPost('campus'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status'),
            'remarks' => $this->request->getPost('remarks')
        ];

        if ($this->manualLiquidationModel->update($id, $data)) {
            return redirect()->to('/manual-liquidation')->with('success', 'Manual liquidation updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update manual liquidation.');
        }
    }

    public function delete($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        if ($this->manualLiquidationModel->delete($id)) {
            return redirect()->to('/manual-liquidation')->with('success', 'Manual liquidation deleted successfully.');
        } else {
            return redirect()->to('/manual-liquidation')->with('error', 'Failed to delete manual liquidation.');
        }
    }

    // Entry by recipient ID
    public function entryByRecipient()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Entry by Recipient ID',
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_recipient', $data);
    }

    // Entry by campus
    public function entryByCampus()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $campuses = ['Main Campus', 'Kalamansig Campus', 'Palimbang Campus', 'Isulan Campus'];
        
        $data = [
            'title' => 'Entry by Campus',
            'campuses' => $campuses,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_campus', $data);
    }

    // Entry by disbursing officer
    public function entryByOfficer()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $officers = $this->userModel->getUsersByRole('disbursing_officer');
        
        $data = [
            'title' => 'Entry by Disbursing Officer',
            'officers' => $officers,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_officer', $data);
    }

    // Entry by scholarship coordinator
    public function entryByCoordinator()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $coordinators = $this->userModel->getUsersByRole('scholarship_coordinator');
        
        $data = [
            'title' => 'Entry by Scholarship Coordinator',
            'coordinators' => $coordinators,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('manual_liquidation/entry_by_coordinator', $data);
    }

    // Search recipients
    public function searchRecipients()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $keyword = $this->request->getPost('keyword');
        $recipients = $this->recipientModel->searchRecipients($keyword);

        return $this->response->setJSON(['recipients' => $recipients]);
    }
}
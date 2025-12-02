<?php

namespace App\Controllers;

use App\Models\UserModel;

class AccountManagementController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->session->get('user');
        
        if ($user['role'] !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        $filters = [
            'role' => $this->request->getGet('role'),
            'campus' => $this->request->getGet('campus'),
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search')
        ];

        $users = $this->getUsersWithFilters($filters);

        $data = [
            'title' => 'Account Management',
            'users' => $users,
            'filters' => $filters,
            'user' => $user
        ];

        return view('account_management/index', $data);
    }

    public function create()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->session->get('user');
        
        if ($user['role'] !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        $data = [
            'title' => 'Create New Account',
            'user' => $user
        ];

        return view('account_management/create', $data);
    }

    public function store()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->session->get('user');
        
        if ($user['role'] !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        $validation = \Config\Services::validation();
        
        if (!$this->validate([
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[admin,disbursing_officer,scholarship_coordinator,scholarship_chairman,accounting_officer]',
            'campus' => 'required|max_length[100]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'campus' => $this->request->getPost('campus'),
            'is_active' => 1
        ];

        if ($this->userModel->save($userData)) {
            return redirect()->to('/accounts')->with('success', 'Account created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create account.');
        }
    }

    public function toggleStatus($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $currentUser = $this->session->get('user');
        
        if ($currentUser['role'] !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        if ($id == $currentUser['id']) {
            return redirect()->back()->with('error', 'Cannot deactivate your own account.');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        
        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'Account activated successfully.' : 'Account deactivated successfully.';
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Failed to update account status.');
        }
    }

    public function edit($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $currentUser = $this->session->get('user');
        
        if ($currentUser['role'] !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit Account - ' . $user['username'],
            'user' => $currentUser,
            'edit_user' => $user
        ];

        return view('account_management/edit', $data);
    }

    public function update($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $currentUser = $this->session->get('user');
        
        if ($currentUser['role'] !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,$id]",
            'email' => "required|valid_email|is_unique[users.email,id,$id]",
            'role' => 'required|in_list[admin,disbursing_officer,scholarship_coordinator,scholarship_chairman,accounting_officer]',
            'campus' => 'required|max_length[100]'
        ];

        // Only validate password if it's provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $updateData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'campus' => $this->request->getPost('campus')
        ];

        // Only update password if provided
        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->update($id, $updateData)) {
            return redirect()->to('/accounts')->with('success', 'Account updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update account.');
        }
    }

    private function getUsersWithFilters($filters = [])
    {
        $builder = $this->userModel->builder();

        if (!empty($filters['role'])) {
            $builder->where('role', $filters['role']);
        }

        if (!empty($filters['campus'])) {
            $builder->where('campus', $filters['campus']);
        }

        if (!empty($filters['status'])) {
            $isActive = $filters['status'] === 'active' ? 1 : 0;
            $builder->where('is_active', $isActive);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('username', $filters['search'])
                   ->orLike('email', $filters['search'])
                   ->groupEnd();
        }

        $builder->orderBy('created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
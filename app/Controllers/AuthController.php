<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function login()
    {
        if ($this->session->get('user_id')) {
            $user = $this->session->get('user');
            // Redirect to appropriate dashboard based on role
            switch ($user['role']) {
                case 'scholarship_chairman':
                    return redirect()->to('/chairman');
                case 'accounting_officer':
                    return redirect()->to('/accounting');
                case 'scholarship_coordinator':
                    return redirect()->to('/scholarship-coordinator');
                default:
                    return redirect()->to('/dashboard');
            }
        }

        return view('auth/login');
    }

    public function authenticate()
    {
        $validation = \Config\Services::validation();
        
        if (!$this->validate([
            'username' => 'required',
            'password' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Account is inactive.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Invalid password.');
        }

        // Store complete user data in session
        $this->session->set([
            'user_id' => $user['id'],
            'user' => $user,
            'logged_in' => true
        ]);

        // Role-based dashboard redirection
        switch ($user['role']) {
            case 'scholarship_chairman':
                return redirect()->to('/chairman');
            case 'accounting_officer':
                return redirect()->to('/accounting');
            case 'scholarship_coordinator':
                return redirect()->to('/scholarship-coordinator');
            default:
                return redirect()->to('/dashboard');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function createAccount()
    {
        $validation = \Config\Services::validation();
        
        if (!$this->validate([
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[disbursing_officer,scholarship_coordinator,scholarship_chairman,accounting_officer]',
            'campus' => 'required|max_length[100]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'campus' => $this->request->getPost('campus')
        ];

        if ($this->userModel->save($userData)) {
            return redirect()->to('/login')->with('success', 'Account created successfully. Please login.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create account.');
        }
    }
}
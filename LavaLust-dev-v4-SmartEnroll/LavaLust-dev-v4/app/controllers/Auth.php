<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth extends Controller
{
    private function requireRole($allowed_roles)
    {
        $user = $this->session->userdata('user');
        $roles = is_array($allowed_roles) ? $allowed_roles : [$allowed_roles];

        if (empty($user) || !isset($user['role']) || !in_array($user['role'], $roles, true)) {
            $this->session->set_flashdata('error', 'Access denied. Please sign in with the correct account.');
            redirect('login');
            return false;
        }

        return true;
    }

    public function login_form()
    {
        if ($this->session->has_userdata('user_id')) {
            redirect('dashboard');
        }

        $this->call->view('auth/login', [
            'page_title' => 'SmartEnroll Login',
            'error' => $this->session->flashdata('error'),
            'success' => $this->session->flashdata('success')
        ]);
    }

    public function register_form()
    {
        if ($this->session->has_userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $this->call->view('auth/register', [
            'page_title' => 'Create a Student Account',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function register()
    {
        if ($this->session->has_userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $this->call->library('database');
        $user_model = $this->call->model('User_model');

        $full_name = trim((string) $this->request->post('full_name', ''));
        $username = strtolower(trim((string) $this->request->post('username', '')));
        $email = strtolower(trim((string) $this->request->post('email', '')));
        $password = (string) $this->request->post('password', '');
        $password_confirmation = (string) $this->request->post('password_confirmation', '');
        $errors = [];

        if (strlen($full_name) < 2 || strlen($full_name) > 150) {
            $errors[] = 'Full name must be between 2 and 150 characters.';
        }
        if (!preg_match('/^[a-zA-Z0-9._-]{3,50}$/D', $username)) {
            $errors[] = 'Username must be 3-50 characters and use only letters, numbers, dots, underscores, or hyphens.';
        }
        if (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if (strlen($password) < 10 || strlen($password) > 72) {
            $errors[] = 'Password must be between 10 and 72 characters.';
        }
        if (!hash_equals($password, $password_confirmation)) {
            $errors[] = 'Password confirmation does not match.';
        }

        if (!$errors && $user_model->usernameExists($username)) {
            $errors[] = 'That username is already in use.';
        }
        if (!$errors && $user_model->emailExists($email)) {
            $errors[] = 'That email address is already registered.';
        }

        if ($errors) {
            $this->call->view('auth/register', [
                'page_title' => 'Create a Student Account',
                'errors' => $errors,
                'old' => [
                    'full_name' => $full_name,
                    'username' => $username,
                    'email' => $email,
                ],
            ]);
            return;
        }

        try {
            $user_model->createStudentAccount([
                'full_name' => $full_name,
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ]);
        } catch (PDOException $exception) {
            $this->call->view('auth/register', [
                'page_title' => 'Create a Student Account',
                'errors' => ['Registration could not be completed. Check the username and email, then try again.'],
                'old' => [
                    'full_name' => $full_name,
                    'username' => $username,
                    'email' => $email,
                ],
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Your student account was created. You can sign in now.');
        redirect('login');
    }

    public function login()
    {
        $this->call->library('database');
        $user_model = $this->call->model('User_model');

        $identifier = trim((string) $this->request->post('identifier', ''));
        $password = (string) $this->request->post('password', '');

        if ($identifier === '' || $password === '') {
            $this->session->set_flashdata('error', 'Username/email and password are required.');
            redirect('login');
            return;
        }

        $user = $user_model->findByIdentifier($identifier);

        if (!$user || !isset($user->password_hash) || !password_verify($password, $user->password_hash)) {
            $this->session->set_flashdata('error', 'Invalid username/email or password.');
            redirect('login');
            return;
        }

        if ((int) $user->is_active !== 1 || !empty($user->deleted_at)) {
            $this->session->set_flashdata('error', 'This account is inactive or disabled.');
            redirect('login');
            return;
        }

        $this->session->set_userdata([
            'user_id' => (int) $user->id,
            'user_role' => $user->role,
            'user' => [
                'id' => (int) $user->id,
                'full_name' => $user->full_name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);

        $user_model->updateLastLogin((int) $user->id);
        $this->session->set_flashdata('success', 'Welcome back, ' . htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8') . '!');
        redirect('dashboard');
    }

    public function dashboard()
    {
        if (!$this->requireRole(['student', 'teacher', 'admin'])) {
            return;
        }

        $user = $this->session->userdata('user');

        $this->call->view('auth/dashboard', [
            'page_title' => 'Dashboard',
            'user' => $user,
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
        ]);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}

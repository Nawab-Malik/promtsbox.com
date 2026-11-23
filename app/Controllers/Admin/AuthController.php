<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;

final class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect(base_url('admin'));
        }
        $this->renderAdmin('auth/login', ['error' => Session::flash('login_error'), 'page_title' => 'Login'], 'auth');
    }

    public function login(): void
    {
        Csrf::abortIfInvalid();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!Auth::attempt($email, $password)) {
            Session::flash('login_error', 'Invalid email or password.');
            redirect(base_url('admin/login'));
        }
        ActivityLog::write('login');
        redirect(base_url('admin'));
    }

    public function logout(): void
    {
        Csrf::abortIfInvalid();
        ActivityLog::write('logout');
        Auth::logout();
        redirect(base_url('admin/login'));
    }
}
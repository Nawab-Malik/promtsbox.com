<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\ActivityLog;
use App\Models\User;

final class UserController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('superadmin');
        $this->renderAdmin('users/index', [
            'page_title' => 'Users',
            'users' => User::all(),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole('superadmin');
        $this->renderAdmin('users/form', ['page_title' => 'New User', 'user' => null]);
    }

    public function store(): void
    {
        Auth::requireRole('superadmin');
        Csrf::abortIfInvalid();
        $id = User::create($this->validated(true));
        ActivityLog::write('create', 'user', $id);
        redirect(base_url('admin/users'));
    }

    public function edit(string $id): void
    {
        Auth::requireRole('superadmin');
        $user = User::find((int) $id);
        if (!$user) {
            http_response_code(404);
            exit;
        }
        $this->renderAdmin('users/form', ['page_title' => 'Edit User', 'user' => $user]);
    }

    public function update(string $id): void
    {
        Auth::requireRole('superadmin');
        Csrf::abortIfInvalid();
        User::update((int) $id, $this->validated(false));
        ActivityLog::write('update', 'user', (int) $id);
        redirect(base_url('admin/users'));
    }

    private function validated(bool $requirePassword): array
    {
        $password = $_POST['password'] ?? '';
        if ($requirePassword && strlen($password) < 10) {
            redirect(base_url('admin/users/create'));
        }
        return [
            'email' => trim($_POST['email'] ?? ''),
            'display_name' => trim($_POST['display_name'] ?? ''),
            'role' => $_POST['role'] ?? 'editor',
            'status' => $_POST['status'] ?? 'active',
            'password' => $password,
        ];
    }
}
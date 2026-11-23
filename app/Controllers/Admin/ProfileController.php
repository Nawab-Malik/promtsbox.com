<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\User;

final class ProfileController extends Controller
{
    public function edit(): void
    {
        Auth::requireLogin();
        $this->renderAdmin('profile/edit', [
            'page_title' => 'My profile',
            'user' => Auth::user(),
        ]);
    }

    public function save(): void
    {
        Auth::requireLogin();
        Csrf::abortIfInvalid();
        $id = (int) Auth::id();
        $user = Auth::user();
        if ($user === null) {
            redirect(base_url('admin/login'));
        }
        User::update($id, [
            'display_name' => trim($_POST['display_name'] ?? $user['display_name']),
            'role' => $user['role'],
            'status' => $user['status'],
            'password' => $_POST['password'] ?? '',
        ]);
        ActivityLog::write('update', 'profile', $id);
        Session::flash('toast_success', 'Profile updated.');
        redirect(base_url('admin/profile'));
    }
}
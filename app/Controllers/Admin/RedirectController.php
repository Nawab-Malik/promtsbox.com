<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Redirect;

final class RedirectController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->renderAdmin('redirects/index', [
            'page_title' => 'Redirects (301)',
            'redirects' => Redirect::all(),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole('superadmin', 'admin');
        $this->renderAdmin('redirects/form', ['page_title' => 'New redirect', 'redirect' => null]);
    }

    public function store(): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $from = trim($_POST['from_path'] ?? '');
        $to = trim($_POST['to_path'] ?? '');
        $code = (int) ($_POST['status_code'] ?? 301);
        $id = Redirect::create($from, $to, $code);
        ActivityLog::write('create', 'redirect', $id);
        Session::flash('toast_success', 'Redirect created.');
        redirect(base_url('admin/redirects'));
    }

    public function edit(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        $redirect = Redirect::find((int) $id);
        if (!$redirect) {
            http_response_code(404);
            exit;
        }
        $this->renderAdmin('redirects/form', ['page_title' => 'Edit redirect', 'redirect' => $redirect]);
    }

    public function update(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        Redirect::update(
            (int) $id,
            trim($_POST['from_path'] ?? ''),
            trim($_POST['to_path'] ?? ''),
            (int) ($_POST['status_code'] ?? 301)
        );
        ActivityLog::write('update', 'redirect', (int) $id);
        Session::flash('toast_success', 'Redirect updated.');
        redirect(base_url('admin/redirects'));
    }

    public function destroy(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        Redirect::delete((int) $id);
        ActivityLog::write('delete', 'redirect', (int) $id);
        Session::flash('toast_success', 'Redirect deleted.');
        redirect(base_url('admin/redirects'));
    }
}
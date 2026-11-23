<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Page;
use App\Services\RedirectService;

final class PageController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->renderAdmin('pages/index', [
            'page_title' => 'Pages',
            'pages' => Page::allAdmin(),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole('superadmin', 'admin');
        $this->renderAdmin('pages/form', ['page_title' => 'New Page', 'page' => null]);
    }

    public function store(): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $id = Page::create($this->validated());
        ActivityLog::write('create', 'page', $id);
        redirect(base_url('admin/pages'));
    }

    public function edit(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        $page = Page::find((int) $id);
        if (!$page) {
            http_response_code(404);
            exit;
        }
        $this->renderAdmin('pages/form', ['page_title' => 'Edit Page', 'page' => $page]);
    }

    public function update(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $existing = Page::find((int) $id);
        $data = $this->validated();
        if ($existing && $existing['slug'] !== $data['slug']) {
            RedirectService::onSlugChange('/page', (string) $existing['slug'], (string) $data['slug']);
        }
        Page::update((int) $id, $data);
        ActivityLog::write('update', 'page', (int) $id);
        Session::flash('toast_success', 'Page saved.');
        redirect(base_url('admin/pages/edit/' . $id));
    }

    public function destroy(string $id): void
    {
        Auth::requireRole('superadmin');
        Csrf::abortIfInvalid();
        Page::delete((int) $id);
        ActivityLog::write('delete', 'page', (int) $id);
        redirect(base_url('admin/pages'));
    }

    private function validated(): array
    {
        $title = trim($_POST['title'] ?? '');
        return [
            'title' => $title,
            'slug' => trim($_POST['slug'] ?? '') ?: slugify($title),
            'content' => sanitize_html($_POST['content'] ?? ''),
            'body_font' => $_POST['body_font'] ?? 'poppins',
            'custom_font_url' => trim($_POST['custom_font_url'] ?? '') ?: null,
            'template' => $_POST['template'] ?? 'default',
            'status' => $_POST['status'] ?? 'draft',
            'meta_title' => trim($_POST['meta_title'] ?? '') ?: null,
            'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
            'meta_robots' => $_POST['meta_robots'] ?? 'index,follow',
            'show_in_footer' => isset($_POST['show_in_footer']),
            'show_in_header' => isset($_POST['show_in_header']),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];
    }
}
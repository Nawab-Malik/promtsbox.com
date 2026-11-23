<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Services\RedirectService;

final class CategoryController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->renderAdmin('categories/index', [
            'page_title' => 'Categories',
            'categories' => Category::allAdmin(),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole('superadmin', 'admin');
        $this->renderAdmin('categories/form', ['page_title' => 'New Category', 'category' => null]);
    }

    public function store(): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $data = $this->validated();
        $id = Category::create($data);
        ActivityLog::write('create', 'category', $id);
        redirect(base_url('admin/categories'));
    }

    public function edit(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        $cat = Category::find((int) $id);
        if (!$cat) {
            http_response_code(404);
            exit;
        }
        $this->renderAdmin('categories/form', ['page_title' => 'Edit Category', 'category' => $cat]);
    }

    public function update(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $existing = Category::find((int) $id);
        $data = $this->validated();
        if ($existing && $existing['slug'] !== $data['slug']) {
            RedirectService::onSlugChange('/category', (string) $existing['slug'], (string) $data['slug']);
        }
        Category::update((int) $id, $data);
        ActivityLog::write('update', 'category', (int) $id);
        Session::flash('toast_success', 'Category saved.');
        redirect(base_url('admin/categories'));
    }

    public function destroy(string $id): void
    {
        Auth::requireRole('superadmin');
        Csrf::abortIfInvalid();
        Category::delete((int) $id);
        ActivityLog::write('delete', 'category', (int) $id);
        redirect(base_url('admin/categories'));
    }

    private function validated(): array
    {
        $name = trim($_POST['name'] ?? '');
        return [
            'name' => $name,
            'slug' => trim($_POST['slug'] ?? '') ?: slugify($name),
            'description' => trim($_POST['description'] ?? ''),
            'meta_title' => trim($_POST['meta_title'] ?? '') ?: null,
            'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_featured' => isset($_POST['is_featured']),
            'status' => $_POST['status'] ?? 'published',
        ];
    }
}
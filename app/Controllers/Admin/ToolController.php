<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Tool;
use App\Services\RedirectService;

final class ToolController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->renderAdmin('tools/index', [
            'page_title' => 'Tools',
            'tools' => Tool::allAdmin(),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole('superadmin', 'admin');
        $this->renderAdmin('tools/form', [
            'page_title' => 'New Tool',
            'tool' => null,
            'categories' => Category::allAdmin(),
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $id = Tool::create($this->validated());
        ActivityLog::write('create', 'tool', $id);
        redirect(base_url('admin/tools'));
    }

    public function edit(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        $tool = Tool::find((int) $id);
        if (!$tool) {
            http_response_code(404);
            exit;
        }
        $this->renderAdmin('tools/form', [
            'page_title' => 'Edit Tool',
            'tool' => $tool,
            'categories' => Category::allAdmin(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $existing = Tool::find((int) $id);
        $data = $this->validated();
        if ($existing && $existing['slug'] !== $data['slug']) {
            RedirectService::onSlugChange('/tools', (string) $existing['slug'], (string) $data['slug']);
        }
        Tool::update((int) $id, $data);
        ActivityLog::write('update', 'tool', (int) $id);
        Session::flash('toast_success', 'Tool saved.');
        redirect(base_url('admin/tools/edit/' . $id));
    }

    public function destroy(string $id): void
    {
        Auth::requireRole('superadmin');
        Csrf::abortIfInvalid();
        Tool::delete((int) $id);
        ActivityLog::write('delete', 'tool', (int) $id);
        redirect(base_url('admin/tools'));
    }

    private function validated(): array
    {
        $name = trim($_POST['name'] ?? '');
        return [
            'name' => $name,
            'slug' => trim($_POST['slug'] ?? '') ?: slugify($name),
            'description' => trim($_POST['description'] ?? ''),
            'long_content' => sanitize_html($_POST['long_content'] ?? ''),
            'tool_type' => $_POST['tool_type'] ?? 'prompt_builder',
            'config_json' => $_POST['config_json'] ?? '{}',
            'category_id' => (int) ($_POST['category_id'] ?? 0) ?: null,
            'meta_title' => trim($_POST['meta_title'] ?? '') ?: null,
            'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
            'og_title' => trim($_POST['og_title'] ?? '') ?: null,
            'og_description' => trim($_POST['og_description'] ?? '') ?: null,
            'og_image' => trim($_POST['og_image'] ?? '') ?: null,
            'status' => $_POST['status'] ?? 'draft',
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];
    }
}
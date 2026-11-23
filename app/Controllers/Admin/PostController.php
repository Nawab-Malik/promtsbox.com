<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Post;
use App\Models\Media;
use App\Services\ImageUploadService;
use App\Services\RedirectService;

final class PostController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $this->renderAdmin('posts/index', [
            'page_title' => 'Posts',
            'posts' => Post::adminList($page, 20),
            'page' => $page,
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->renderAdmin('posts/form', [
            'page_title' => 'New Post',
            'post' => null,
            'categories' => Category::allAdmin(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        Csrf::abortIfInvalid();
        $user = Auth::user();
        $data = $this->validated();
        $data['author_id'] = (int) $user['id'];
        $id = Post::create($data);
        ActivityLog::write('create', 'post', $id);
        Session::flash('toast_success', 'Post created.');
        redirect(base_url('admin/posts/edit/' . $id));
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $post = Post::find((int) $id);
        if (!$post) {
            http_response_code(404);
            exit;
        }
        $this->renderAdmin('posts/form', [
            'page_title' => 'Edit Post',
            'post' => $post,
            'categories' => Category::allAdmin(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        Csrf::abortIfInvalid();
        $existing = Post::find((int) $id);
        $data = $this->validated($existing['featured_image'] ?? null);
        $data['author_id'] = (int) ($existing['author_id'] ?? Auth::id());
        if ($existing && $existing['slug'] !== $data['slug']) {
            RedirectService::onSlugChange('/blog', (string) $existing['slug'], (string) $data['slug']);
        }
        Post::update((int) $id, $data);
        ActivityLog::write('update', 'post', (int) $id);
        Session::flash('toast_success', 'Post saved.');
        redirect(base_url('admin/posts/edit/' . $id));
    }

    public function destroy(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        Post::delete((int) $id);
        ActivityLog::write('delete', 'post', (int) $id);
        Session::flash('toast_success', 'Post deleted.');
        redirect(base_url('admin/posts'));
    }

    public function duplicate(string $id): void
    {
        Auth::requireLogin();
        Csrf::abortIfInvalid();
        $post = Post::find((int) $id);
        if (!$post) {
            http_response_code(404);
            exit;
        }
        $data = $post;
        unset($data['id']);
        $data['title'] = $post['title'] . ' (Copy)';
        $data['slug'] = slugify($data['title']);
        $data['status'] = 'draft';
        $data['author_id'] = (int) Auth::id();
        $newId = Post::create($data);
        ActivityLog::write('duplicate', 'post', $newId);
        Session::flash('toast_success', 'Post duplicated as draft.');
        redirect(base_url('admin/posts/edit/' . $newId));
    }

    private function validated(?string $currentFeatured = null): array
    {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
        return [
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'title' => $title,
            'slug' => $slug,
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => sanitize_html($_POST['content'] ?? ''),
            'featured_image' => $this->resolveFeaturedImage($currentFeatured),
            'body_font' => $_POST['body_font'] ?? 'poppins',
            'custom_font_url' => trim($_POST['custom_font_url'] ?? '') ?: null,
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => trim($_POST['published_at'] ?? '') ?: null,
            'meta_title' => trim($_POST['meta_title'] ?? '') ?: null,
            'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
            'meta_robots' => $_POST['meta_robots'] ?? 'index,follow',
            'canonical_url' => trim($_POST['canonical_url'] ?? '') ?: null,
            'og_title' => trim($_POST['og_title'] ?? '') ?: null,
            'og_description' => trim($_POST['og_description'] ?? '') ?: null,
            'og_image' => trim($_POST['og_image'] ?? '') ?: null,
            'schema_type' => $_POST['schema_type'] ?? 'Article',
            'focus_keyword' => trim($_POST['focus_keyword'] ?? '') ?: null,
            'post_type' => 'article',
        ];
    }

    private function resolveFeaturedImage(?string $current = null): ?string
    {
        $file = $_FILES['featured_file'] ?? null;
        if (is_array($file) && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $stored = ImageUploadService::store($file, (int) Auth::id());
            if ($stored !== null) {
                Media::create([
                    'filename' => $stored['filename'],
                    'path' => $stored['path'],
                    'mime' => $stored['mime'],
                    'size' => $stored['size'],
                    'alt_text' => trim($_POST['featured_alt'] ?? $_POST['title'] ?? 'Featured image'),
                    'uploaded_by' => Auth::id(),
                ]);
                return $stored['path'];
            }
        }
        $url = trim($_POST['featured_image'] ?? '');
        if ($url !== '') {
            return $url;
        }
        return $current;
    }
}
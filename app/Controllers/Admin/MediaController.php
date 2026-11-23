<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\Media;
use App\Services\ImageUploadService;

final class MediaController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $this->renderAdmin('media/index', [
            'page_title' => 'Media library',
            'media' => Media::list($page),
            'page' => $page,
        ]);
    }

    public function upload(): void
    {
        Auth::requireLogin();
        Csrf::abortIfInvalid();
        $file = $_FILES['file'] ?? null;
        if (!is_array($file)) {
            Session::flash('toast_error', 'No file uploaded.');
            redirect(base_url('admin/media'));
        }
        $stored = ImageUploadService::store($file, (int) Auth::id());
        if ($stored === null) {
            Session::flash('toast_error', 'Upload failed. Check file type/size.');
            redirect(base_url('admin/media'));
        }
        $alt = trim($_POST['alt_text'] ?? '');
        Media::create([
            'filename' => $stored['filename'],
            'path' => $stored['path'],
            'mime' => $stored['mime'],
            'size' => $stored['size'],
            'alt_text' => $alt,
            'uploaded_by' => Auth::id(),
        ]);
        ActivityLog::write('upload', 'media');
        Session::flash('toast_success', 'File uploaded.');
        redirect(base_url('admin/media'));
    }

    public function destroy(string $id): void
    {
        Auth::requireRole('superadmin', 'admin');
        Csrf::abortIfInvalid();
        $row = Media::delete((int) $id);
        if ($row && isset($row['path'])) {
            $full = BASE_PATH . '/public/' . ltrim($row['path'], '/');
            if (is_file($full)) {
                @unlink($full);
            }
        }
        ActivityLog::write('delete', 'media', (int) $id);
        Session::flash('toast_success', 'Media deleted.');
        redirect(base_url('admin/media'));
    }

    public function updateAlt(string $id): void
    {
        Auth::requireLogin();
        Csrf::abortIfInvalid();
        Media::updateAlt((int) $id, trim($_POST['alt_text'] ?? ''));
        Session::flash('toast_success', 'Alt text saved.');
        redirect(base_url('admin/media'));
    }

    public function json(): void
    {
        Auth::requireLogin();
        header('Content-Type: application/json; charset=utf-8');
        $items = array_map(static function (array $m): array {
            return [
                'id' => (int) $m['id'],
                'url' => ImageUploadService::publicUrl($m['path']),
                'path' => $m['path'],
                'alt' => $m['alt_text'],
            ];
        }, Media::list(1, 48));
        echo json_encode(['items' => $items], JSON_THROW_ON_ERROR);
        exit;
    }
}
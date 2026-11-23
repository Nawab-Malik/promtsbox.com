<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Models\Media;
use App\Services\ImageUploadService;

final class EditorController
{
    public function uploadImage(): void
    {
        Auth::requireLogin();
        Csrf::abortIfInvalid();
        header('Content-Type: application/json; charset=utf-8');
        $file = $_FILES['file'] ?? $_FILES['image'] ?? null;
        if (!is_array($file)) {
            http_response_code(400);
            echo json_encode(['error' => 'No file']);
            exit;
        }
        $stored = ImageUploadService::store($file, (int) Auth::id());
        if ($stored === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Upload failed']);
            exit;
        }
        $alt = trim($_POST['alt_text'] ?? 'Article image');
        Media::create([
            'filename' => $stored['filename'],
            'path' => $stored['path'],
            'mime' => $stored['mime'],
            'size' => $stored['size'],
            'alt_text' => $alt,
            'uploaded_by' => Auth::id(),
        ]);
        $url = ImageUploadService::publicUrl($stored['path']);
        echo json_encode(['location' => $url]);
        exit;
    }
}
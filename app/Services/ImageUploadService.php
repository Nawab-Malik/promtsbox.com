<?php

declare(strict_types=1);

namespace App\Services;

final class ImageUploadService
{
    /** @return array{filename:string,path:string,mime:string,size:int}|null */
    public static function store(array $file, int $userId): ?array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }
        $maxBytes = (int) app_config('upload_max_mb', 2) * 1024 * 1024;
        if (($file['size'] ?? 0) > $maxBytes) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        $allowed = app_config('allowed_image_types', []);
        if (!in_array($mime, $allowed, true)) {
            return null;
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'bin',
        };

        $subdir = date('Y/m');
        $dir = BASE_PATH . '/public/uploads/' . $subdir;
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return null;
        }

        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        $fullPath = $dir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            return null;
        }

        $publicPath = 'uploads/' . $subdir . '/' . $filename;

        return [
            'filename' => $filename,
            'path' => $publicPath,
            'mime' => $mime,
            'size' => (int) $file['size'],
        ];
    }

    public static function publicUrl(string $path): string
    {
        return base_url(ltrim($path, '/'));
    }
}
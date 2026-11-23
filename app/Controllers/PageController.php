<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Models\Page;
use App\Services\FontService;

final class PageController extends Controller
{
    public function show(string $slug): void
    {
        $page = Page::findPublishedBySlug($slug);
        if ($page === null) {
            http_response_code(404);
            $this->render('errors/404', ['meta_robots' => 'noindex, nofollow']);
            return;
        }

        $this->render('page/show', [
            'title' => $page['meta_title'] ?: $page['title'],
            'meta_title' => $page['meta_title'] ?: $page['title'],
            'meta_description' => $page['meta_description'],
            'meta_robots' => $page['meta_robots'],
            'page' => $page,
            'sent' => $_GET['sent'] ?? null,
            'content_font' => FontService::resolve(
                (string) ($page['body_font'] ?? 'poppins'),
                $page['custom_font_url'] ?? null
            ),
        ]);
    }

    public function contactSubmit(): void
    {
        Csrf::abortIfInvalid();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if ($name === '' || $email === '' || $message === '') {
            redirect(base_url('page/contact?error=1'));
        }
        $stmt = Database::connection()->prepare(
            'INSERT INTO contact_messages (name, email, subject, message, ip) VALUES (:n,:e,:s,:m,:ip)'
        );
        $stmt->execute([
            'n' => $name,
            'e' => $email,
            's' => $subject,
            'm' => $message,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
        redirect(base_url('page/contact?sent=1'));
    }
}
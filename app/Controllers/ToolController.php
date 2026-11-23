<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Tool;
use App\Services\SeoService;
use App\Services\ToolEngine;

final class ToolController extends Controller
{
    public function index(): void
    {
        $this->render('tool/index', [
            'title' => 'Free Creator Tools',
            'meta_title' => 'Free AI & Social Media Tools',
            'meta_description' => 'Instagram bio generator, AI prompt builder, captions, and more free tools on PromptsBox.',
            'tools' => Tool::published(),
        ]);
    }

    public function show(string $slug): void
    {
        $tool = Tool::findPublishedBySlug($slug);
        if ($tool === null) {
            http_response_code(404);
            $this->render('errors/404', ['meta_robots' => 'noindex, nofollow']);
            return;
        }

        $breadcrumbs = [
            ['name' => 'Home', 'url' => base_url()],
            ['name' => 'Tools', 'url' => base_url('tools')],
            ['name' => $tool['name']],
        ];
        $this->render('tool/show', [
            'title' => $tool['meta_title'] ?: $tool['name'],
            'meta_title' => $tool['meta_title'] ?: $tool['name'],
            'meta_description' => $tool['meta_description'] ?: truncate($tool['description'] ?? '', 155),
            'og_title' => $tool['og_title'],
            'og_description' => $tool['og_description'],
            'og_image' => $tool['og_image'],
            'tool' => $tool,
            'output' => $_SESSION['_tool_output'][$slug] ?? null,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumb_schema' => SeoService::breadcrumbJsonLd($breadcrumbs),
        ]);
        unset($_SESSION['_tool_output'][$slug]);
    }

    public function generate(string $slug): void
    {
        Csrf::abortIfInvalid();
        $tool = Tool::findPublishedBySlug($slug);
        if ($tool === null) {
            http_response_code(404);
            exit;
        }
        $output = ToolEngine::generate($tool['tool_type'], $_POST);
        $_SESSION['_tool_output'][$slug] = $output;
        redirect(base_url('tools/' . $slug . '#result'));
    }
}
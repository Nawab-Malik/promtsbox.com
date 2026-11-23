<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

final class SeoService
{
    /** @param array<string, mixed> $data */
    public static function forPage(array $data): array
    {
        $siteName = Setting::get('site_name', (string) app_config('name'));
        $defaultTitle = Setting::get('default_meta_title', $siteName);
        $defaultDesc = Setting::get('default_meta_description', (string) app_config('tagline'));

        $title = $data['meta_title'] ?? $data['title'] ?? $defaultTitle;
        $description = $data['meta_description'] ?? $data['excerpt'] ?? $defaultDesc;
        if (isset($data['content']) && empty($data['meta_description'])) {
            $description = truncate((string) $data['content'], 155);
        }

        $robots = $data['meta_robots'] ?? 'index,follow';
        $canonical = $data['canonical_url'] ?? ($data['canonical'] ?? null);
        if ($canonical === null || $canonical === '') {
            $canonical = request_canonical();
        }
        $ogTitle = $data['og_title'] ?? $title;
        $ogDesc = $data['og_description'] ?? $description;
        $ogImage = $data['og_image'] ?? $data['featured_image'] ?? Setting::get('organization_logo');

        $fullTitle = $title;
        if (!str_contains($title, $siteName) && ($data['append_site_name'] ?? true)) {
            $fullTitle = $title . app_config('title_separator', ' | ') . $siteName;
        }

        return [
            'site_name' => $siteName,
            'title' => $fullTitle,
            'meta_title' => $title,
            'description' => truncate(strip_tags((string) $description), 160),
            'robots' => $robots,
            'canonical' => $canonical,
            'og_title' => $ogTitle,
            'og_description' => truncate(strip_tags((string) $ogDesc), 200),
            'og_image' => $ogImage ? (str_starts_with((string) $ogImage, 'http') ? $ogImage : base_url(ltrim((string) $ogImage, '/'))) : null,
            'schema_type' => $data['schema_type'] ?? 'WebPage',
            'verification' => Setting::get('google_site_verification'),
            'analytics_id' => Setting::get('analytics_id'),
            'adsense_client' => Setting::get('adsense_client_id'),
            'adsense_enabled' => Setting::get('adsense_enabled') === '1',
        ];
    }

    public static function articleJsonLd(array $post, string $url): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => $post['schema_type'] ?? 'Article',
            'headline' => $post['title'],
            'description' => truncate(strip_tags($post['excerpt'] ?? $post['content'] ?? ''), 200),
            'datePublished' => $post['published_at'] ?? $post['created_at'],
            'dateModified' => $post['updated_at'] ?? $post['published_at'],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
            'author' => ['@type' => 'Person', 'name' => $post['author_name'] ?? 'PromptsBox'],
            'publisher' => [
                '@type' => 'Organization',
                'name' => Setting::get('site_name', 'PromptsBox'),
            ],
        ];
        if (!empty($post['featured_image'])) {
            $img = $post['featured_image'];
            if (!str_starts_with($img, 'http')) {
                $img = base_url(ltrim($img, '/'));
            }
            $data['image'] = [$img];
        }
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
    }

    /** @param list<array{name:string,url?:string}> $items */
    public static function breadcrumbJsonLd(array $items): string
    {
        $list = [];
        $pos = 1;
        foreach ($items as $item) {
            $entry = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => $item['name'],
            ];
            if (!empty($item['url'])) {
                $entry['item'] = $item['url'];
            }
            $list[] = $entry;
        }
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
    }

    public static function organizationJsonLd(): string
    {
        $name = Setting::get('site_name', 'PromptsBox');
        $url = rtrim((string) app_config('url'), '/');
        $logo = Setting::get('organization_logo');
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $url,
        ];
        if ($logo !== '') {
            $data['logo'] = str_starts_with($logo, 'http') ? $logo : base_url(ltrim($logo, '/'));
        }
        $sameAs = array_filter([
            Setting::get('social_facebook'),
            Setting::get('social_twitter'),
            Setting::get('social_instagram'),
        ]);
        if ($sameAs !== []) {
            $data['sameAs'] = array_values($sameAs);
        }
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
    }
}
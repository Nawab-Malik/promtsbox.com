<?php

declare(strict_types=1);

namespace App\Services;

final class FontService
{
    /** @return array<string, array{label: string, css: string, family: string, google: string|null}> */
    public static function presets(): array
    {
        return [
            'poppins' => [
                'label' => 'Poppins (default)',
                'css' => 'Poppins',
                'family' => "'Poppins', sans-serif",
                'google' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap',
            ],
            'plus_jakarta' => [
                'label' => 'Plus Jakarta Sans',
                'css' => 'Plus Jakarta Sans',
                'family' => "'Plus Jakarta Sans', sans-serif",
                'google' => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap',
            ],
            'inter' => [
                'label' => 'Inter',
                'css' => 'Inter',
                'family' => "'Inter', sans-serif",
                'google' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
            ],
            'roboto' => [
                'label' => 'Roboto',
                'css' => 'Roboto',
                'family' => "'Roboto', sans-serif",
                'google' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
            ],
            'lora' => [
                'label' => 'Lora (serif)',
                'css' => 'Lora',
                'family' => "'Lora', serif",
                'google' => 'https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap',
            ],
            'custom' => [
                'label' => 'Custom Google Font URL',
                'css' => '',
                'family' => 'inherit',
                'google' => null,
            ],
        ];
    }

  /** @return array{href: ?string, family: string, class: string} */
    public static function resolve(string $key, ?string $customUrl = null): array
    {
        $presets = self::presets();
        $key = $key !== '' ? $key : 'poppins';
        if ($key === 'custom' && $customUrl) {
            return [
                'href' => $customUrl,
                'family' => "'Poppins', sans-serif",
                'class' => 'font-custom',
            ];
        }
        $p = $presets[$key] ?? $presets['poppins'];
        return [
            'href' => $p['google'],
            'family' => $p['family'],
            'class' => 'font-' . $key,
        ];
    }
}
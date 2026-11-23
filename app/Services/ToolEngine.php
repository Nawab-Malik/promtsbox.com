<?php

declare(strict_types=1);

namespace App\Services;

final class ToolEngine
{
    /** @param array<string, string> $input */
    public static function generate(string $type, array $input): string
    {
        return match ($type) {
            'bio_generator' => self::bioGenerator($input),
            'prompt_builder' => self::promptBuilder($input),
            'caption_generator' => self::captionGenerator($input),
            'hashtag_helper' => self::hashtagHelper($input),
            'meta_generator' => self::metaGenerator($input),
            default => 'Unsupported tool type.',
        };
    }

    /** @param array<string, string> $input */
    private static function bioGenerator(array $input): string
    {
        $name = trim($input['name'] ?? 'Creator');
        $niche = trim($input['niche'] ?? 'content');
        $vibe = trim($input['vibe'] ?? 'friendly');
        $emoji = ($input['emoji'] ?? '1') === '1' ? ' ✨' : '';
        return sprintf(
            "%s | %s tips & %s vibes%s\n📍 Creating daily\n💌 DM for collabs",
            $name,
            ucfirst($niche),
            $vibe,
            $emoji
        );
    }

    /** @param array<string, string> $input */
    private static function promptBuilder(array $input): string
    {
        $subject = trim($input['subject'] ?? 'portrait of a young woman');
        $style = trim($input['style'] ?? 'cinematic');
        $lighting = trim($input['lighting'] ?? 'soft golden hour');
        $camera = trim($input['camera'] ?? '85mm lens, shallow depth of field');
        return "Create a {$style} image: {$subject}, {$lighting}, {$camera}, ultra-detailed, natural skin texture, balanced composition.";
    }

    /** @param array<string, string> $input */
    private static function captionGenerator(array $input): string
    {
        $occasion = trim($input['occasion'] ?? 'everyday post');
        $tone = trim($input['tone'] ?? 'casual');
        return "Celebrating {$occasion} with good energy. {$tone} mood, grateful for the little wins today. What's your highlight this week?";
    }

    /** @param array<string, string> $input */
    private static function hashtagHelper(array $input): string
    {
        $topic = strtolower(trim($input['topic'] ?? 'photography'));
        $base = preg_replace('/[^a-z0-9]+/', '', $topic) ?: 'creator';
        return "#{$base} #" . $base . "tips #contentcreator #creators #socialmedia #growth #reels #explorepage";
    }

    /** @param array<string, string> $input */
    private static function metaGenerator(array $input): string
    {
        $topic = trim($input['topic'] ?? 'AI prompts for creators');
        $title = 'Free ' . ucfirst($topic) . ' | PromptsBox';
        $desc = 'Discover ' . $topic . ' with copy-ready examples, tools, and tutorials on PromptsBox — free for creators.';
        if (strlen($title) > 60) {
            $title = substr($title, 0, 57) . '...';
        }
        if (strlen($desc) > 155) {
            $desc = substr($desc, 0, 152) . '...';
        }
        return "Meta title:\n{$title}\n\nMeta description:\n{$desc}";
    }
}
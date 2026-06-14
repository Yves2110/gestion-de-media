<?php

namespace App\Support;

class VideoEmbed
{
    public static function extractYoutubeId(?string $html): ?string
    {
        if (!$html) {
            return null;
        }

        if (preg_match('/youtube\.com\/embed\/([^"?&\/]+)/', $html, $matches)) {
            return $matches[1];
        }

        if (preg_match('/youtube\.com\/watch\?v=([^"&]+)/', $html, $matches)) {
            return $matches[1];
        }

        if (preg_match('/youtu\.be\/([^"?&\/]+)/', $html, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public static function thumbnailUrl(?string $html): ?string
    {
        $id = self::extractYoutubeId($html);

        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    public static function iframe(string $youtubeId): string
    {
        $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '', $youtubeId);

        return '<iframe class="media-embed-iframe" src="https://www.youtube.com/embed/' . e($safeId) . '" '
            . 'title="Vidéo YouTube" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" '
            . 'referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
    }
}

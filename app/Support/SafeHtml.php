<?php

namespace App\Support;

class SafeHtml
{
    public static function media(?string $html): string
    {
        if (! $html) {
            return '';
        }

        if ($youtubeId = VideoEmbed::extractYoutubeId($html)) {
            return VideoEmbed::iframe($youtubeId);
        }

        if (MediaInput::isHttpsUrl(trim($html))) {
            return '<audio controls class="w-100" src="' . e(trim($html)) . '"></audio>';
        }

        if (preg_match('/<audio\b[^>]*\bsrc=["\'](https:\/\/[^"\']+)["\']/i', $html, $matches)) {
            return '<audio controls class="w-100" src="' . e($matches[1]) . '"></audio>';
        }

        return e(strip_tags($html));
    }

    public static function localisation(?string $html): string
    {
        if (! $html) {
            return '';
        }

        if (preg_match('/src=["\'](https:\/\/(?:www\.)?(?:google\.com\/maps|maps\.google\.(?:com)?|openstreetmap\.org)[^"\']*)["\']/i', $html, $matches)) {
            return '<iframe src="' . e($matches[1]) . '" width="100%" height="350" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>';
        }

        return nl2br(e(strip_tags($html)));
    }
}

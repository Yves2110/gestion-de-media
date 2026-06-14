<?php

namespace App\Support;

class MediaInput
{
    public static function normalize(int $type, string $media): string
    {
        $media = trim($media);

        if ($type === 1) {
            return $media;
        }

        if (preg_match('/<audio\b/i', $media)) {
            return $media;
        }

        if (self::isHttpsUrl($media)) {
            return '<audio controls src="' . e($media) . '"></audio>';
        }

        return $media;
    }

    public static function isValidVideo(string $media): bool
    {
        return VideoEmbed::extractYoutubeId(trim($media)) !== null;
    }

    public static function isValidAudio(string $media): bool
    {
        $media = trim($media);

        if (preg_match('/<audio\b[^>]*\bsrc=["\'](https:\/\/[^"\']+)["\']/i', $media)) {
            return true;
        }

        return self::isHttpsUrl($media);
    }

    public static function isHttpsUrl(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) && str_starts_with($value, 'https://');
    }
}

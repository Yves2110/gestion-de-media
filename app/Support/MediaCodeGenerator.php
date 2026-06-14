<?php

namespace App\Support;

use App\Models\Media;

class MediaCodeGenerator
{
    public static function generate(int $type): string
    {
        $prefix = $type === 1 ? 'VID' : 'AUD';
        $max = 0;

        Media::query()
            ->where('type', $type)
            ->whereNotNull('code_media')
            ->pluck('code_media')
            ->each(function (string $code) use ($prefix, &$max) {
                if (preg_match('/^' . preg_quote($prefix, '/') . '-(\d+)$/', $code, $matches)) {
                    $max = max($max, (int) $matches[1]);
                }
            });

        return sprintf('%s-%03d', $prefix, $max + 1);
    }
}

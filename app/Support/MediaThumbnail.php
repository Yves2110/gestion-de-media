<?php

namespace App\Support;

use App\Models\Media;

class MediaThumbnail
{
    public static function url(Media $media): ?string
    {
        if ($media->picture) {
            return asset('storage/picture/' . $media->picture);
        }

        if ((int) $media->type === 1) {
            return VideoEmbed::thumbnailUrl($media->media);
        }

        return null;
    }
}

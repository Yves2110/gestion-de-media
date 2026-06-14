<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CoverImageStorage
{
    public static function store(UploadedFile $file): string
    {
        $filename = date('YmdHis') . '_cover.' . $file->getClientOriginalExtension();
        $file->storeAs('picture', $filename, 'public');

        return $filename;
    }

    public static function delete(?string $filename): void
    {
        if ($filename && Storage::disk('public')->exists('picture/' . $filename)) {
            Storage::disk('public')->delete('picture/' . $filename);
        }
    }
}

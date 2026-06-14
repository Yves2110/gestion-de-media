<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CoverImageStorage
{
    public const DIR = 'picture';

    public const MIN_BYTES = 1024;

    public static function store(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid() . '.' . $extension;
        $file->storeAs(self::DIR, $filename, 'public');

        return $filename;
    }

    public static function delete(?string $filename): void
    {
        if ($filename && Storage::disk('public')->exists(self::path($filename))) {
            Storage::disk('public')->delete(self::path($filename));
        }
    }

    public static function path(string $filename): string
    {
        return self::DIR . '/' . $filename;
    }

    public static function isValid(?string $filename): bool
    {
        if (! $filename || ! Storage::disk('public')->exists(self::path($filename))) {
            return false;
        }

        $fullPath = Storage::disk('public')->path(self::path($filename));

        if (filesize($fullPath) < self::MIN_BYTES) {
            return false;
        }

        return @getimagesize($fullPath) !== false;
    }
}

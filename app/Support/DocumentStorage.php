<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentStorage
{
    public const DISK = 'documents';

    public const LEGACY_DISK = 'public';

    public const LEGACY_DIR = 'document';

    public static function store(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.pdf';
        $file->storeAs('', $filename, self::DISK);

        return $filename;
    }

    public static function exists(string $filename): bool
    {
        if ($filename === '') {
            return false;
        }

        return Storage::disk(self::DISK)->exists($filename)
            || Storage::disk(self::LEGACY_DISK)->exists(self::legacyPath($filename));
    }

    public static function download(string $filename, ?string $downloadName = null): StreamedResponse
    {
        if (Storage::disk(self::DISK)->exists($filename)) {
            return Storage::disk(self::DISK)->download($filename, $downloadName ?? $filename);
        }

        return Storage::disk(self::LEGACY_DISK)->download(
            self::legacyPath($filename),
            $downloadName ?? $filename
        );
    }

    public static function delete(string $filename): void
    {
        if ($filename === '') {
            return;
        }

        if (Storage::disk(self::DISK)->exists($filename)) {
            Storage::disk(self::DISK)->delete($filename);
        }

        $legacyPath = self::legacyPath($filename);
        if (Storage::disk(self::LEGACY_DISK)->exists($legacyPath)) {
            Storage::disk(self::LEGACY_DISK)->delete($legacyPath);
        }
    }

    public static function legacyPath(string $filename): string
    {
        return self::LEGACY_DIR . '/' . $filename;
    }
}

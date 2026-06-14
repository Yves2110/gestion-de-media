<?php

namespace Tests\Unit;

use App\Support\CoverImageStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CoverImageStorageTest extends TestCase
{
    public function test_store_uses_uuid_filename(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('cover.jpg', 200, 300);
        $filename = CoverImageStorage::store($file);

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.jpg$/',
            $filename
        );
        Storage::disk('public')->assertExists(CoverImageStorage::path($filename));
    }

    public function test_is_valid_rejects_tiny_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put(CoverImageStorage::path('broken.jpg'), 'not-an-image');

        $this->assertFalse(CoverImageStorage::isValid('broken.jpg'));
    }
}

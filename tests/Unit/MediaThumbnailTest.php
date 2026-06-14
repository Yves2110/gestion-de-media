<?php

namespace Tests\Unit;

use App\Models\Media;
use App\Support\MediaThumbnail;
use Tests\TestCase;

class MediaThumbnailTest extends TestCase
{
    public function test_uses_uploaded_picture_for_audio(): void
    {
        $audio = new Media([
            'type' => 0,
            'picture' => '20260101120000_cover.jpg',
            'media' => '<audio controls src="https://example.com/test.mp3"></audio>',
        ]);

        $this->assertStringContainsString('storage/picture/20260101120000_cover.jpg', MediaThumbnail::url($audio));
    }

    public function test_falls_back_to_youtube_thumbnail_for_video_without_picture(): void
    {
        $video = new Media([
            'type' => 1,
            'picture' => null,
            'media' => '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe>',
        ]);

        $this->assertSame('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg', MediaThumbnail::url($video));
    }

    public function test_prefers_uploaded_picture_over_youtube_for_video(): void
    {
        $video = new Media([
            'type' => 1,
            'picture' => 'custom_cover.jpg',
            'media' => '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe>',
        ]);

        $this->assertStringContainsString('storage/picture/custom_cover.jpg', MediaThumbnail::url($video));
    }
}

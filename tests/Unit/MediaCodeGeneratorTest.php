<?php

namespace Tests\Unit;

use App\Models\Media;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use App\Support\MediaCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MediaCodeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_generates_first_audio_and_video_codes(): void
    {
        $this->assertSame('AUD-001', MediaCodeGenerator::generate(0));
        $this->assertSame('VID-001', MediaCodeGenerator::generate(1));
    }

    public function test_increments_codes_per_type(): void
    {
        $admin = $this->createAdmin();
        $source = Source::create(['label' => 'Source test']);

        Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([]),
            'title' => 'Audio 1',
            'auteur' => 'Auteur',
            'type' => 0,
            'statut' => 1,
            'media' => '<audio controls src="https://example.com/a.mp3"></audio>',
            'code_media' => 'AUD-001',
        ]);

        Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([]),
            'title' => 'Video 1',
            'auteur' => 'Auteur',
            'type' => 1,
            'statut' => 1,
            'media' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'code_media' => 'VID-003',
        ]);

        $this->assertSame('AUD-002', MediaCodeGenerator::generate(0));
        $this->assertSame('VID-004', MediaCodeGenerator::generate(1));
    }

    private function createAdmin(): User
    {
        return User::create([
            'role_id' => 1,
            'uuid' => 'admin-code-gen',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin-code@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);
    }
}

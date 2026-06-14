<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PublicMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_guest_can_view_published_audio(): void
    {
        $audio = $this->createPublishedMedia(0);

        $this->get(route('public.audios.show', $audio))
            ->assertStatus(200)
            ->assertSee($audio->title)
            ->assertSee($audio->auteur);
    }

    public function test_guest_can_view_published_video(): void
    {
        $video = $this->createPublishedMedia(1);

        $this->get(route('public.videos.show', $video))
            ->assertStatus(200)
            ->assertSee($video->title);
    }

    public function test_guest_cannot_view_unpublished_media(): void
    {
        $audio = $this->createPublishedMedia(0, ['statut' => 0]);

        $this->get(route('public.audios.show', $audio))->assertStatus(404);
    }

    public function test_admin_can_preview_unpublished_media(): void
    {
        $admin = $this->createAdmin();
        $audio = $this->createPublishedMedia(0, ['statut' => 0], $admin);

        $this->actingAs($admin)
            ->get(route('public.audios.show', $audio))
            ->assertStatus(200)
            ->assertSee('Aperçu administrateur')
            ->assertSee($audio->title);
    }

    public function test_admin_is_redirected_to_public_preview_after_creating_audio(): void
    {
        $this->seed(\Database\Seeders\ThematiqueSeeder::class);

        $admin = $this->createAdmin();
        $source = Source::create(['label' => 'Source test']);
        $thematique = \App\Models\Thematique::first();

        $response = $this->actingAs($admin)->post(route('audios.store'), [
            'type' => 0,
            'title' => 'Nouvel audio test',
            'auteur' => 'Auteur Test',
            'source_id' => $source->id,
            'thematique_id' => [$thematique->id],
            'description' => 'Description de test pour le nouvel audio.',
            'media' => 'https://example.com/test.mp3',
            'statut' => 'on',
        ]);

        $audio = Media::where('title', 'Nouvel audio test')->first();
        $this->assertNotNull($audio);

        $response->assertRedirect(route('public.audios.show', $audio));
    }

    private function createAdmin(): User
    {
        return User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);
    }

    private function createPublishedMedia(int $type, array $overrides = [], ?User $admin = null): Media
    {
        $admin = $admin ?? User::where('email', 'admin@test.com')->first() ?? $this->createAdmin();
        $source = Source::first() ?? Source::create(['label' => 'Source test']);

        return Media::create(array_merge([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([]),
            'title' => $type === 0 ? 'Audio public test' : 'Vidéo public test',
            'description' => 'Description test',
            'auteur' => 'Auteur Test',
            'code_media' => $type === 0 ? 'AUD-001' : 'VID-001',
            'statut' => 1,
            'type' => $type,
            'media' => $type === 0
                ? '<audio controls src="https://example.com/test.mp3"></audio>'
                : '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe>',
        ], $overrides));
    }
}

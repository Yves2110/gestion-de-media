<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LearningPersonalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\ThematiqueSeeder::class);
    }

    public function test_visit_records_activity_in_session(): void
    {
        $admin = User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $source = Source::create(['label' => 'Tech Source']);

        $video = Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([1]),
            'title' => 'Introduction intelligence artificielle',
            'auteur' => 'Expert IA',
            'description' => 'Vidéo IA.',
            'type' => 1,
            'statut' => 1,
            'media' => '<iframe src="https://www.youtube.com/embed/test"></iframe>',
        ]);

        $this->get(route('public.videos.show', $video))->assertStatus(200);

        $this->assertCount(1, \App\Support\VisitorActivity::history());
    }

    public function test_session_persists_between_page_views(): void
    {
        $admin = User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid-2',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin2@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $source = Source::create(['label' => 'Tech Source']);

        $video = Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([1]),
            'title' => 'Video test session',
            'auteur' => 'Expert',
            'type' => 1,
            'statut' => 1,
            'media' => '<iframe></iframe>',
        ]);

        $this->get(route('public.videos.show', $video));
        $this->get(route('learning.index'));

        $this->assertCount(1, \App\Support\VisitorActivity::history());
    }

    public function test_learning_space_shows_personalized_suggestions_after_visit(): void
    {
        $admin = User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $source = Source::create(['label' => 'Tech Source']);

        $video = Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([5]),
            'title' => 'Introduction intelligence artificielle',
            'auteur' => 'Expert IA',
            'description' => 'Vidéo sur l intelligence artificielle.',
            'type' => 1,
            'statut' => 1,
            'media' => '<iframe src="https://www.youtube.com/embed/test"></iframe>',
        ]);

        Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([5]),
            'title' => 'Machine learning pour débutants',
            'auteur' => 'Expert IA',
            'description' => 'Suite logique sur l IA.',
            'type' => 1,
            'statut' => 1,
            'media' => '<iframe src="https://www.youtube.com/embed/test2"></iframe>',
        ]);

        $this->get(route('public.videos.show', $video))->assertStatus(200);
        $response = $this->get(route('learning.index'));

        $response->assertStatus(200);
        $response->assertSee('Parce que vous avez consulté');
        $response->assertSee('Machine learning pour débutants');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LearningSpaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
    }

    public function test_learning_space_page_is_public(): void
    {
        $response = $this->get(route('learning.index'));

        $response->assertStatus(200);
        $response->assertSee('Espace d\'apprentissage');
        $response->assertSee('Filtres avancés');
    }

    public function test_home_menu_links_to_learning_space(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('espace-apprentissage', false);
        $response->assertSee('Espace d', false);
    }

    public function test_learning_space_filters_published_resources(): void
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

        $source = Source::create(['label' => 'Source demo']);
        $thematique = Thematique::create(['label' => 'Agriculture']);

        Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([$thematique->id]),
            'title' => 'Audio apprentissage publié',
            'auteur' => 'Formateur A',
            'type' => 0,
            'statut' => 1,
            'media' => '<iframe></iframe>',
        ]);

        Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([]),
            'title' => 'Audio brouillon',
            'auteur' => 'Formateur B',
            'type' => 0,
            'statut' => 0,
            'media' => '<iframe></iframe>',
        ]);

        $response = $this->get(route('learning.index', [
            'q' => 'apprentissage',
            'types' => ['audio'],
        ]));

        $response->assertStatus(200);
        $response->assertSee('Audio apprentissage publié');
        $response->assertDontSee('Audio brouillon');
    }

    public function test_learning_suggestions_endpoint_returns_json(): void
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

        $source = Source::create(['label' => 'Institut Formation']);

        Document::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([]),
            'title' => 'Guide pratique formation',
            'auteur' => 'Expert Doc',
            'categorie' => 'Guide',
            'page' => 12,
            'edition' => '',
            'publication_date' => now(),
            'statut_publication' => 1,
            'file_doc' => 'guide.pdf',
            'picture' => '',
        ]);

        $response = $this->getJson(route('learning.suggestions', ['q' => 'formation']));

        $response->assertStatus(200);
        $response->assertJsonStructure(['titles', 'authors', 'thematiques', 'sources', 'resources']);
        $response->assertJsonFragment(['Guide pratique formation']);
    }
}

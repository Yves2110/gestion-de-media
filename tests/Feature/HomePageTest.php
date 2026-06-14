<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Media;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
    }

    public function test_home_page_is_public_and_shows_hero(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Plateforme de mutualisation');
        $response->assertSee('Dernières publications');
        $response->assertSee('Proposer un document');
        $response->assertSee('Espace membre');
    }

    public function test_home_page_shows_library_publications(): void
    {
        $user = User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $source = Source::create(['label' => 'Source demo']);
        $category = \App\Models\Category::where('label', 'Rapport')->first();

        Media::create([
            'user_id' => $user->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([]),
            'title' => 'Audio publication demo',
            'auteur' => 'Auteur Test',
            'type' => 0,
            'statut' => 1,
            'media' => '<iframe></iframe>',
        ]);

        Document::create([
            'user_id' => $user->id,
            'source_id' => $source->id,
            'category_id' => $category?->id,
            'thematique_id' => json_encode([]),
            'title' => 'Document publication demo',
            'auteur' => 'Auteur Doc',
            'categorie' => 'Rapport',
            'page' => 10,
            'edition' => '',
            'publication_date' => now(),
            'statut_publication' => 1,
            'file_doc' => 'demo.pdf',
            'picture' => '',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Audio publication demo');
        $response->assertSee('Document publication demo');
        $response->assertSee('Les dernières publications');
        $response->assertSee('Documents');
    }
}

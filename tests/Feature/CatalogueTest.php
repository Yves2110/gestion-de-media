<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CatalogueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_client_sees_only_published_media(): void
    {
        $client = User::create([
            'role_id' => 3,
            'uuid' => 'client-uuid',
            'firstname' => 'Client',
            'lastname' => 'User',
            'email' => 'client@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $admin = User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $source = Source::create(['label' => 'Test Source']);

        Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => '[]',
            'title' => 'Published Audio',
            'auteur' => 'Author',
            'type' => 0,
            'statut' => 1,
            'media' => '<audio></audio>',
        ]);

        Media::create([
            'user_id' => $admin->id,
            'source_id' => $source->id,
            'thematique_id' => '[]',
            'title' => 'Draft Audio',
            'auteur' => 'Author',
            'type' => 0,
            'statut' => 0,
            'media' => '<audio></audio>',
        ]);

        $response = $this->actingAs($client)->get(route('catalogue.audios'));

        $response->assertStatus(200);
        $response->assertSee('Published Audio');
        $response->assertDontSee('Draft Audio');
    }

    public function test_admin_cannot_access_client_catalogue_routes_as_client(): void
    {
        $admin = User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('catalogue.index'))
            ->assertRedirect(route('dashboard'));
    }
}

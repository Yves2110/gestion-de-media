<?php

namespace Tests\Feature;

use App\Models\ContentView;
use App\Models\Media;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContentViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_catalogue_show_increments_view_count(): void
    {
        $client = User::create([
            'role_id' => 3,
            'uuid' => 'client-uuid',
            'firstname' => 'Client',
            'lastname' => 'Test',
            'email' => 'client@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $source = Source::create(['label' => 'Test Source']);

        $audio = Media::create([
            'user_id' => $client->id,
            'source_id' => $source->id,
            'thematique_id' => json_encode([]),
            'title' => 'Test Audio',
            'auteur' => 'Author',
            'type' => 0,
            'statut' => 1,
            'media' => '<iframe></iframe>',
        ]);

        $this->actingAs($client)
            ->get(route('catalogue.show', ['type' => 'audio', 'id' => $audio->id]))
            ->assertStatus(200);

        $this->assertEquals(1, ContentView::where('viewable_id', $audio->id)->where('action', 'view')->count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_home_page_is_accessible_without_auth(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Les dernières publications');
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $user = User::create([
            'role_id' => 1,
            'uuid' => 'test-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
    }

    public function test_client_is_redirected_to_catalogue(): void
    {
        User::create([
            'role_id' => 3,
            'uuid' => 'client-uuid',
            'firstname' => 'Client',
            'lastname' => 'Test',
            'email' => 'client@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $this->post('/login', [
            'email' => 'client@test.com',
            'password' => 'password',
        ])->assertRedirect(route('catalogue.index'));
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }
}

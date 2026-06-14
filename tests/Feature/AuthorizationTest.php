<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_client_cannot_access_admin_crud(): void
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

        $this->actingAs($client)
            ->get(route('audios.index'))
            ->assertRedirect(route('catalogue.index'));
    }

    public function test_regular_admin_cannot_manage_users(): void
    {
        $admin = User::create([
            'role_id' => 2,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('userManage'))
            ->assertStatus(403);
    }
}

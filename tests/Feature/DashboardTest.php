<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_dashboard_shows_onboarding_checklist_for_new_admin(): void
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

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee('Premiers pas');
    }

    public function test_onboarding_can_be_dismissed(): void
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

        $this->actingAs($admin)
            ->post(route('dashboard.onboarding.dismiss'))
            ->assertRedirect(route('dashboard'));

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertDontSee('Premiers pas');
    }
}

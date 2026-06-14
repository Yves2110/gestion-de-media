<?php

namespace Tests\Feature;

use App\Mail\NewRegistrationAlertMail;
use App\Mail\RegistrationApprovedMail;
use App\Models\Role;
use App\Models\Source;
use App\Models\User;
use App\Support\DemoText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ValidationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
        Storage::fake('public');
    }

    public function test_registration_creates_pending_user(): void
    {
        Mail::fake();

        User::create([
            'role_id' => 1,
            'uuid' => 'admin-notify-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Notify',
            'email' => 'admin-notify@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $this->post(route('register.store'), [
            'firstname' => 'Jean',
            'lastname' => 'Dupont',
            'email' => 'jean@test.com',
            'password' => 'Password123',
            'confirm_password' => 'Password123',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'jean@test.com',
            'role_id' => 3,
            'statut' => 0,
        ]);

        Mail::assertSent(NewRegistrationAlertMail::class, function ($mail) {
            return $mail->hasTo('admin-notify@test.com')
                && $mail->user->email === 'jean@test.com';
        });

        $this->post('/login', [
            'email' => 'jean@test.com',
            'password' => 'Password123',
        ])->assertSessionHas('message');
    }

    public function test_admin_can_approve_pending_registration(): void
    {
        Mail::fake();

        $admin = User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        $pending = User::create([
            'role_id' => 3,
            'uuid' => 'pending-uuid',
            'firstname' => 'Client',
            'lastname' => 'Wait',
            'email' => 'pending@test.com',
            'password' => Hash::make('password'),
            'statut' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.registrations.approve', $pending))
            ->assertRedirect();

        $this->assertTrue($pending->fresh()->statut);

        Mail::assertSent(RegistrationApprovedMail::class, function ($mail) use ($pending) {
            return $mail->hasTo('pending@test.com')
                && $mail->user->is($pending);
        });
    }

    public function test_guest_can_submit_document_for_validation(): void
    {
        User::create([
            'role_id' => 1,
            'uuid' => 'admin-uuid',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 1,
        ]);

        Source::create(['label' => 'Source publique']);

        $response = $this->post(route('documents.submit.store'), [
            'submitter_name' => 'Visiteur',
            'submitter_email' => 'visiteur@test.com',
            'title' => 'Doc soumis',
            'auteur' => 'Auteur externe',
            'resume' => DemoText::words(250),
            'file_doc' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('documents', [
            'title' => 'Doc soumis',
            'is_guest_submission' => 1,
            'statut_publication' => 0,
            'submitter_email' => 'visiteur@test.com',
        ]);
    }
}

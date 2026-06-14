<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\SafeHtml;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_disabled_admin_is_logged_out_from_dashboard(): void
    {
        $admin = User::create([
            'role_id' => 1,
            'uuid' => 'disabled-admin',
            'firstname' => 'Admin',
            'lastname' => 'Off',
            'email' => 'disabled-admin@test.com',
            'password' => Hash::make('password'),
            'statut' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_login_is_rate_limited_after_failed_attempts(): void
    {
        $email = 'wrong@test.com';
        RateLimiter::clear(strtolower($email) . '|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $this->from(route('login'))->post(route('login.attempt'), [
                'email' => $email,
                'password' => 'wrong-password',
            ]);
        }

        $this->from(route('login'))->post(route('login.attempt'), [
            'email' => $email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');
    }

    public function test_public_submission_honeypot_rejects_spam(): void
    {
        Storage::fake('public');
        Storage::fake('documents');

        $this->post(route('documents.submit.store'), [
            'website' => 'https://spam.test',
            'submitter_name' => 'Bot',
            'submitter_email' => 'bot@test.com',
            'title' => 'Spam',
            'auteur' => 'Bot',
            'resume' => str_repeat('mot ', 250),
            'file_doc' => \Illuminate\Http\UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('website');
    }

    public function test_safe_html_strips_malicious_scripts_from_media(): void
    {
        $output = SafeHtml::media('<script>alert(1)</script><p>test</p>');

        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringContainsString('test', $output);
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}

<?php



namespace Tests\Feature;



use App\Models\Document;

use App\Models\Source;

use App\Models\User;

use App\Support\DemoText;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;

use Tests\TestCase;



class PublicDocumentTest extends TestCase

{

    use RefreshDatabase;



    protected function setUp(): void

    {

        parent::setUp();

        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->seed(\Database\Seeders\CategorySeeder::class);

        Storage::fake('public');
        Storage::fake('documents');

    }



    public function test_guest_can_read_published_document(): void

    {

        $doc = $this->createPublishedDocument();



        $this->get(route('public.documents.show', $doc))

            ->assertStatus(200)

            ->assertSee($doc->title)

            ->assertSee($doc->auteur)

            ->assertSee('Résumé');

    }



    public function test_guest_can_download_published_document(): void

    {

        $doc = $this->createPublishedDocument();

        Storage::disk('documents')->put($doc->file_doc, 'pdf-content');



        $this->get(route('public.documents.download', $doc))

            ->assertStatus(200);

    }



    public function test_guest_cannot_access_unpublished_document(): void

    {

        $doc = $this->createPublishedDocument(['statut_publication' => 0]);



        $this->get(route('public.documents.show', $doc))->assertStatus(404);

    }



    public function test_admin_sees_management_actions_on_public_page(): void

    {

        $doc = $this->createPublishedDocument();

        $admin = User::where('email', 'admin@test.com')->first();



        $this->actingAs($admin)

            ->get(route('public.documents.show', $doc))

            ->assertStatus(200)

            ->assertSee('Dépublier')

            ->assertSee('Supprimer')

            ->assertSee('Signaler');

    }



    public function test_anyone_can_report_document(): void

    {

        $doc = $this->createPublishedDocument();



        $this->post(route('public.documents.report', $doc), [

            'reporter_name' => 'Visiteur',

            'reporter_email' => 'visiteur@test.com',

            'message' => 'Contenu inapproprié signalé ici.',

        ])->assertRedirect();



        $this->assertDatabaseHas('document_reports', [

            'document_id' => $doc->id,

            'reporter_email' => 'visiteur@test.com',

        ]);

    }



    public function test_numeric_id_cannot_access_public_document_url(): void
    {
        $doc = $this->createPublishedDocument();

        $this->get('/bibliotheque/documents/' . $doc->id)->assertStatus(404);
    }

    private function createPublishedDocument(array $overrides = []): Document

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



        $source = Source::create(['label' => 'Source test']);

        $category = \App\Models\Category::where('label', 'Rapport')->first();



        return Document::create(array_merge([

            'user_id' => $admin->id,

            'source_id' => $source->id,

            'category_id' => $category?->id,

            'thematique_id' => json_encode([]),

            'title' => 'Doc public test',

            'auteur' => 'Auteur Test',

            'categorie' => 'Rapport',

            'page' => 5,

            'edition' => '',

            'publication_date' => now(),

            'picture' => '',

            'file_doc' => 'test.pdf',

            'resume' => DemoText::words(250),

            'statut_publication' => 1,

            'ask_form' => 0,

        ], $overrides));

    }

}


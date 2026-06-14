<?php



namespace Database\Seeders;



use App\Models\Category;
use App\Models\Document;

use App\Models\Source;

use App\Models\Thematique;

use App\Models\User;

use App\Support\DemoText;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;



class DemoDocumentsSeeder extends Seeder

{

    private array $covers = [

        'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=600&h=800&fit=crop',

        'https://images.unsplash.com/photo-1509099836639-18d360d021b2?w=600&h=800&fit=crop',

        'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=600&h=800&fit=crop',

        'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&h=800&fit=crop',

        'https://images.unsplash.com/photo-1469571486292-0fba58a3f38a?w=600&h=800&fit=crop',

        'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?w=600&h=800&fit=crop',

    ];



    public function run(): void

    {

        $admin = User::where('role_id', 1)->first();

        if (!$admin) {

            $this->command?->warn('Aucun admin trouvé. Exécutez UserSeeder d\'abord.');



            return;

        }



        $source = Source::first();

        $thematiqueIds = Thematique::pluck('id')->take(2)->values()->all();



        if (!$source || empty($thematiqueIds)) {

            $this->command?->warn('Sources ou thématiques manquantes. Exécutez SourceSeeder et ThematiqueSeeder.');



            return;

        }



        Storage::disk('public')->makeDirectory('document');

        Storage::disk('public')->makeDirectory('picture');



        $longResume = DemoText::words(250);



        $documents = [

            [

                'title' => 'Renforcer la résilience communautaire',

                'auteur' => 'Équipe PDU',

                'categorie' => 'Rapport',

                'page' => 48,

                'edition' => '2025',

                'resume' => $longResume,

                'statut_publication' => 1,

            ],

            [

                'title' => 'Guide de l\'agriculture durable',

                'auteur' => 'Ministère de l\'Agriculture',

                'categorie' => 'Guide',

                'page' => 32,

                'edition' => '2ème',

                'resume' => $longResume,

                'statut_publication' => 1,

            ],

            [

                'title' => 'Étude sur l\'accès à l\'eau potable',

                'auteur' => 'ONG Terre des Hommes',

                'categorie' => 'Étude',

                'page' => 64,

                'edition' => '1ère',

                'resume' => $longResume,

                'statut_publication' => 1,

            ],

            [

                'title' => 'Formation des leaders locaux',

                'auteur' => 'Centre de formation',

                'categorie' => 'Support pédagogique',

                'page' => 24,

                'edition' => '2024',

                'resume' => $longResume,

                'statut_publication' => 1,

            ],

            [

                'title' => 'Cartographie des ressources locales',

                'auteur' => 'Cellule SIG',

                'categorie' => 'Document technique',

                'page' => 18,

                'edition' => '1ère',

                'resume' => $longResume,

                'statut_publication' => 1,

            ],

            [

                'title' => 'Bilan semestriel des activités',

                'auteur' => 'Coordination nationale',

                'categorie' => 'Bilan',

                'page' => 40,

                'edition' => 'S1 2025',

                'resume' => $longResume,

                'statut_publication' => 1,

            ],

            [

                'title' => 'Document demo (brouillon admin)',

                'auteur' => 'Équipe Demo',

                'categorie' => 'Rapport',

                'page' => 10,

                'edition' => '1ère',

                'resume' => 'Brouillon interne, non visible sur la page d\'accueil ni le catalogue client.',

                'statut_publication' => 0,

                'is_guest_submission' => false,

            ],

            [

                'title' => 'Proposition de rapport externe',

                'auteur' => 'Chercheur indépendant',

                'categorie' => 'Soumission publique',

                'page' => 15,

                'edition' => '',

                'resume' => 'Document soumis par un visiteur sans compte, en attente de validation par un administrateur.',

                'statut_publication' => 0,

                'is_guest_submission' => true,

                'submitter_name' => 'Amadou Konaté',

                'submitter_email' => 'amadou.konate@example.com',

            ],

        ];



        foreach ($documents as $index => $data) {

            $slug = 'demo_' . ($index + 1);

            $pdfName = $slug . '.pdf';

            $pictureName = $slug . '.jpg';



            $this->ensurePdf('document/' . $pdfName, $data['title']);



            if ($index < count($this->covers)) {

                $this->ensureCover('picture/' . $pictureName, $this->covers[$index]);

            } else {

                $pictureName = '';

            }



            Document::updateOrCreate(

                ['title' => $data['title']],

                [

                    'user_id' => $admin->id,

                    'source_id' => Source::inRandomOrder()->value('id') ?? $source->id,

                    'thematique_id' => json_encode($thematiqueIds),

                    'category_id' => Category::where('label', $data['categorie'])->value('id'),

                    'auteur' => $data['auteur'],

                    'page' => $data['page'],

                    'edition' => $data['edition'],

                    'publication_date' => now()->subDays(8 - $index)->toDateString(),

                    'categorie' => $data['categorie'],

                    'picture' => $pictureName,

                    'file_doc' => $pdfName,

                    'resume' => $data['resume'],

                    'statut_publication' => $data['statut_publication'],

                    'ask_form' => 0,

                    'is_guest_submission' => $data['is_guest_submission'] ?? false,

                    'submitter_name' => $data['submitter_name'] ?? null,

                    'submitter_email' => $data['submitter_email'] ?? null,

                ]

            );

        }



        Document::where('title', 'Document demo')

            ->where('file_doc', '')

            ->delete();



        $published = Document::where('statut_publication', 1)->count();

        $this->command?->info("Documents de démo créés : {$published} publiés visibles sur l'accueil.");

    }



    private function ensurePdf(string $path, string $title): void

    {

        if (Storage::disk('public')->exists($path)) {

            return;

        }



        $safeTitle = substr(preg_replace('/[^\pL\pN\s\-]/u', '', $title) ?: 'Demo', 0, 40);

        $content = "%PDF-1.4\n"

            . "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n"

            . "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n"

            . "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n"

            . "4 0 obj << /Length 60 >> stream\n"

            . "BT /F1 18 Tf 72 720 Td (Gestion Media - {$safeTitle}) Tj ET\n"

            . "endstream endobj\n"

            . "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n"

            . "xref\n0 6\n"

            . "trailer << /Size 6 /Root 1 0 R >>\n"

            . "startxref\n0\n%%EOF";



        Storage::disk('public')->put($path, $content);

    }



    private function ensureCover(string $path, string $url): void

    {

        if (Storage::disk('public')->exists($path)) {

            return;

        }



        try {

            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {

                Storage::disk('public')->put($path, $response->body());



                return;

            }

        } catch (\Throwable) {

            // fallback below

        }



        Storage::disk('public')->put($path, base64_decode(

            '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDAREAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDBf/EABQBAQAAAAAAAAAAAAAAAAAAAAD/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAGqH//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAQUCf//EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQMBAT8Bf//EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQIBAT8Bf//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEABj8Cf//EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAT8hf//Z'

        ));

    }

}


<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Media;
use App\Models\Source;
use App\Models\Thematique;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoMediaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', 1)->first();
        if (! $admin) {
            return;
        }

        $sources = Source::pluck('id')->all();
        $techThematique = Thematique::where('label', 'Technologie')->value('id');
        $thematiqueIds = Thematique::pluck('id')->take(3)->values()->all();

        if ($sources === [] || empty($thematiqueIds)) {
            return;
        }

        $audios = [
            [
                'title' => 'Les bases de la méthode scientifique',
                'auteur' => 'Dr. Aminata Koné',
                'description' => 'Comprendre observation, hypothèse et expérimentation pour aborder les problèmes avec rigueur.',
                'code' => 'AUD-001',
                'src' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
            ],
            [
                'title' => 'Conseils pour une alimentation équilibrée',
                'auteur' => 'Nutrition sans frontières',
                'description' => 'Recommandations pratiques sur les micronutriments, l\'hydratation et les habitudes alimentaires saines.',
                'code' => 'AUD-002',
                'src' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
            ],
            [
                'title' => 'Comprendre le changement climatique',
                'auteur' => 'Institut environnement',
                'description' => 'Podcast sur les gaz à effet de serre, les impacts régionaux et les solutions d\'adaptation.',
                'code' => 'AUD-003',
                'src' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3',
            ],
            [
                'title' => 'Gestion du stress : conseils scientifiques',
                'auteur' => 'Centre santé mentale',
                'description' => 'Techniques validées par la recherche : respiration, sommeil, activité physique et routines cognitives.',
                'code' => 'AUD-004',
                'src' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3',
            ],
            [
                'title' => 'Préserver la biodiversité locale',
                'auteur' => 'Réseau écologique',
                'description' => 'Éléments essentiels sur les écosystèmes, la pollinisation et les actions communautaires.',
                'code' => 'AUD-005',
                'src' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-5.mp3',
            ],
            [
                'title' => 'Techniques d\'apprentissage efficaces',
                'auteur' => 'Lab pédagogique',
                'description' => 'Spacing, récupération active et cartes mentales : ce que la science cognitive recommande.',
                'code' => 'AUD-006',
                'src' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-6.mp3',
            ],
        ];

        foreach ($audios as $audio) {
            Media::updateOrCreate(
                ['title' => $audio['title'], 'type' => 0],
                [
                    'user_id' => $admin->id,
                    'source_id' => $sources[array_rand($sources)],
                    'thematique_id' => json_encode($thematiqueIds),
                    'description' => $audio['description'],
                    'auteur' => $audio['auteur'],
                    'code_media' => $audio['code'],
                    'statut' => 1,
                    'media' => '<audio controls src="' . $audio['src'] . '"></audio>',
                ]
            );
        }

        $videos = [
            [
                'title' => 'Introduction aux réseaux de neurones',
                'auteur' => '3Blue1Brown',
                'description' => 'Visualisation intuitive du fonctionnement d\'un réseau de neurones et de l\'apprentissage profond.',
                'code' => 'VID-001',
                'embed' => 'aircAruvnKk',
            ],
            [
                'title' => 'Qu\'est-ce que l\'intelligence artificielle ?',
                'auteur' => 'TED-Ed',
                'description' => 'Panorama des concepts clés de l\'IA, du machine learning et des systèmes intelligents.',
                'code' => 'VID-002',
                'embed' => 'TNAHwPWP_2I',
            ],
            [
                'title' => 'Machine Learning expliqué simplement',
                'auteur' => 'IBM Technology',
                'description' => 'Comment les algorithmes apprennent à partir des données pour faire des prédictions.',
                'code' => 'VID-003',
                'embed' => 'ukzFI9rgwfU',
            ],
            [
                'title' => 'ChatGPT et les grands modèles de langage',
                'auteur' => 'Chaîne tech éducative',
                'description' => 'Comprendre les LLM, leurs forces, leurs limites et les enjeux éthiques.',
                'code' => 'VID-004',
                'embed' => '5sLYAQS9sEQ',
            ],
            [
                'title' => 'L\'avenir de la robotique et de l\'IA',
                'auteur' => 'Documentaire science',
                'description' => 'Applications industrielles, médicales et sociétales des technologies intelligentes.',
                'code' => 'VID-005',
                'embed' => 'mJeNghZXt-o',
            ],
            [
                'title' => 'Cybersécurité à l\'ère du numérique',
                'auteur' => 'Institut technologie',
                'description' => 'Bonnes pratiques, menaces courantes et protection des données personnelles.',
                'code' => 'VID-006',
                'embed' => 'inWWhr5tnEk',
            ],
        ];

        $videoThematiqueIds = $techThematique
            ? array_values(array_unique(array_merge([$techThematique], $thematiqueIds)))
            : $thematiqueIds;

        foreach ($videos as $video) {
            Media::updateOrCreate(
                ['title' => $video['title'], 'type' => 1],
                [
                    'user_id' => $admin->id,
                    'source_id' => $sources[array_rand($sources)],
                    'thematique_id' => json_encode($videoThematiqueIds),
                    'description' => $video['description'],
                    'auteur' => $video['auteur'],
                    'code_media' => $video['code'],
                    'statut' => 1,
                    'media' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video['embed'] . '" frameborder="0" allowfullscreen></iframe>',
                ]
            );
        }

        Media::whereIn('title', ['Podcast demo', 'Vidéo demo'])->delete();

        Document::firstOrCreate(
            ['title' => 'Document demo (legacy)'],
            [
                'user_id' => $admin->id,
                'source_id' => $sources[0],
                'thematique_id' => json_encode($thematiqueIds),
                'auteur' => 'Équipe Demo',
                'page' => 10,
                'edition' => '1ère',
                'publication_date' => now()->toDateString(),
                'categorie' => 'Rapport',
                'picture' => '',
                'file_doc' => '',
                'resume' => 'Remplacé par DemoDocumentsSeeder.',
                'statut_publication' => 0,
                'ask_form' => 0,
            ]
        );

        $this->command?->info('Médias de démo : ' . Media::where('statut', 1)->count() . ' publiés (audios + vidéos).');
    }
}

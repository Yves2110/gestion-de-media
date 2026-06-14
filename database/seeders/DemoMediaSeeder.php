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
    public function run()
    {
        $admin = User::where('role_id', 1)->first();
        if (!$admin) {
            return;
        }

        $source = Source::first();
        $thematiqueIds = Thematique::pluck('id')->take(2)->toArray();

        if (!$source || empty($thematiqueIds)) {
            return;
        }

        Media::firstOrCreate(
            ['title' => 'Podcast demo', 'type' => 0],
            [
                'user_id' => $admin->id,
                'source_id' => $source->id,
                'thematique_id' => json_encode($thematiqueIds),
                'description' => 'Exemple de contenu audio pour démonstration.',
                'auteur' => 'Équipe Demo',
                'code_media' => 'AUD-001',
                'statut' => 1,
                'media' => '<audio controls src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"></audio>',
            ]
        );

        Media::firstOrCreate(
            ['title' => 'Vidéo demo', 'type' => 1],
            [
                'user_id' => $admin->id,
                'source_id' => $source->id,
                'thematique_id' => json_encode($thematiqueIds),
                'description' => 'Exemple de contenu vidéo pour démonstration.',
                'auteur' => 'Équipe Demo',
                'code_media' => 'VID-001',
                'statut' => 1,
                'media' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>',
            ]
        );

        Document::firstOrCreate(
            ['title' => 'Document demo (legacy)'],
            [
                'user_id' => $admin->id,
                'source_id' => $source->id,
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
    }
}

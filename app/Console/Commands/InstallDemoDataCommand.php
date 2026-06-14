<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\Media;
use App\Models\User;
use Illuminate\Console\Command;

class InstallDemoDataCommand extends Command
{
    protected $signature = 'demo:install';

    protected $description = 'Installe les données de démonstration (6 audios, 6 vidéos, 6 documents)';

    public function handle(): int
    {
        $this->info('Installation des données de démonstration…');

        if (! User::where('role_id', 1)->exists()) {
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder', '--force' => true]);
        } else {
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\SourceSeeder', '--force' => true]);
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\ThematiqueSeeder', '--force' => true]);
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\CategorySeeder', '--force' => true]);
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\DemoMediaSeeder', '--force' => true]);
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\DemoDocumentsSeeder', '--force' => true]);
        }

        $audios = Media::isAudio()->where('statut', 1)->count();
        $videos = Media::isvideo()->where('statut', 1)->count();
        $documents = Document::where('statut_publication', 1)->count();

        $this->newLine();
        $this->table(['Type', 'Publiés'], [
            ['Audios', $audios],
            ['Vidéos', $videos],
            ['Documents', $documents],
        ]);

        if ($audios + $videos + $documents === 0) {
            $this->error('Aucune ressource créée. Vérifiez database/database.sqlite et relancez php artisan migrate --seed');

            return self::FAILURE;
        }

        $this->info('Données démo prêtes → /espace-apprentissage');

        return self::SUCCESS;
    }
}

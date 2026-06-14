<?php

namespace Database\Seeders;

use App\Models\Thematique;
use Illuminate\Database\Seeder;

class ThematiqueSeeder extends Seeder
{
    public function run()
    {
        $thematiques = ['Culture', 'Éducation', 'Santé', 'Environnement', 'Technologie'];

        foreach ($thematiques as $label) {
            Thematique::firstOrCreate(['label' => $label]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    public function run()
    {
        $sources = ['Radio Nationale', 'Presse en ligne', 'Archives internes', 'Partenaires'];

        foreach ($sources as $label) {
            Source::firstOrCreate(['label' => $label]);
        }
    }
}

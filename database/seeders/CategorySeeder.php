<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $labels = [
            'Rapport',
            'Guide',
            'Étude',
            'Support pédagogique',
            'Document technique',
            'Bilan',
            'Soumission publique',
        ];

        foreach ($labels as $label) {
            Category::firstOrCreate(['label' => $label]);
        }
    }
}

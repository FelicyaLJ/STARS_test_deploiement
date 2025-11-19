<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieEvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorie_evenement')->insert([
            ['nom_categorie'=> 'Pratique', 'couleur' => '#34D399'],
            ['nom_categorie'=> 'Match', 'couleur' => '#3B82F6'],
            ['nom_categorie'=> 'Hors saison', 'couleur' => '#A855F7'],

        ]);
    }
}

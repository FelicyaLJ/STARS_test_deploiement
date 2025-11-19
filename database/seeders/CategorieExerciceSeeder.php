<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieExerciceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorie_exercice')->insert([
            ['nom_categorie'=> 'Passes', 'ordre_affichage'=>1],
            ['nom_categorie'=> 'Règlements', 'ordre_affichage'=>3],
            ['nom_categorie'=> 'Démos', 'ordre_affichage'=>2]

        ]);
    }
}

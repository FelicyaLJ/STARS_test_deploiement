<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieEquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorie_equipe')->insert([
            ['nom_categorie' => 'U9',     'capacite_max' => 16],
            ['nom_categorie' => 'U10',    'capacite_max' => 16],
            ['nom_categorie' => 'U11',    'capacite_max' => 16],
            ['nom_categorie' => 'U12',    'capacite_max' => 16],
            ['nom_categorie' => 'U13',    'capacite_max' => 18],
            ['nom_categorie' => 'U14',    'capacite_max' => 20],
            ['nom_categorie' => 'U15',    'capacite_max' => 20],
            ['nom_categorie' => 'U16',    'capacite_max' => 20],
            ['nom_categorie' => 'U17',    'capacite_max' => 20],
            ['nom_categorie' => 'U18',    'capacite_max' => 20],
            ['nom_categorie' => 'Senior', 'capacite_max' => 20],
        ]);
    }
}

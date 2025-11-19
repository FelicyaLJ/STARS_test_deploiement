<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieFAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorie_faq')->insert([
            ['nom_categorie'=> 'Document explicatif', 'ordre' => '1'],
            ['nom_categorie'=> 'Soirs de match', 'ordre' => '3'],
            ['nom_categorie'=> 'Lieux de pratique et de matchs', 'ordre' => '2'],
            ['nom_categorie'=> 'Équipement fournis et requis', 'ordre' => '4'],
        ]);
    }
}

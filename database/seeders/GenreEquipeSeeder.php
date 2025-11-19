<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreEquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genre_equipe')->insert([
            ["nom_genre"=>"Masculin"],
            ["nom_genre"=>"Féminin"],
            ["nom_genre"=>"Mixte"]
        ]);
    }
}

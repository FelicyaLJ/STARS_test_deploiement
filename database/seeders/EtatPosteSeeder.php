<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtatPosteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('etat_poste')->insert([
            ['nom_etat' => 'Non disponible'],
            ['nom_etat' => 'Disponible']
        ]);
    }
}

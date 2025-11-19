<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtatEquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('etat_equipe')->insert([
            ['nom_etat' => 'Active'],
            ['nom_etat' => 'Inactive'],
            ['nom_etat' => 'Activité Locale'],
        ]);
    }
}

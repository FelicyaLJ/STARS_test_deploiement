<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtatEvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('etat_evenement')->insert([
            ['nom_etat' => 'Normal'],
            ['nom_etat' => 'Annulé'],
            ['nom_etat' => 'Reporté'],
            ['nom_etat' => 'Activité locale'],
        ]);
    }
}

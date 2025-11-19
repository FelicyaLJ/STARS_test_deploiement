<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtatPublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('etat_publication')->insert([
            ['nom_etat' => 'Visible'],
            ['nom_etat' => 'Actualité']
        ]);
    }
}

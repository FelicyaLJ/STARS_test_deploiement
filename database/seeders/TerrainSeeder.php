<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerrainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('terrain')->insert([
            [
                'nom_terrain' => 'Alfred Desrochers',
                'description' => 'Terrain de soccer principal de l\'école secondaire Des Chutes',
                'latitude' => null,
                'longitude' => null,
                'adresse' => 'École secondaire Des Chutes 3144 18e Av, Rawdon, QC, J0K-1S0',
                'visible' => 1,
                'couleur' => '#FF5733',
                'id_parent' => null,
            ],
            [
                'nom_terrain' => 'Grand Lacs',
                'description' => 'Terrain de soccer principal au parc de Grands Lacs.',
                'latitude' => null,
                'longitude' => null,
                'adresse' => 'École secondaire Des Chutes 3144 18e Av, Rawdon, QC, J0K-1S0',
                'visible' => 1,
                'couleur' => '#33A1FF',
                'id_parent' => null,
            ],
            [
                'nom_terrain' => 'Alfred Desrochers 2',
                'description' => 'Terrain de soccer secondaire de l\'école secondaire Des Chutes.',
                'latitude' => null,
                'longitude' => null,
                'adresse' => 'École secondaire Des Chutes 3144 18e Av, Rawdon, QC, J0K-1S0',
                'visible' => 1,
                'couleur' => '#28A745',
                'id_parent' => 1,
            ],
        ]);
    }
}

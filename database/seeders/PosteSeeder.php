<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('poste')->insert([
                [
                'nom_poste' => 'Superviseur de terrain',
                'description' => '',
                'salaire' => 51.00,
                'ordre_affichage' => 1,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Animateur Timbits',
                'description' => '',
                'salaire' => 34.00,
                'ordre_affichage' => 1,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Animateur CDC',
                'description' => '',
                'salaire' => 34.00,
                'ordre_affichage' => 1,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Meneur de jeu U7 et U8',
                'description' => '',
                'salaire' => 33.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre U9 et U10',
                'description' => '',
                'salaire' => 33.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre assistant U11 et U12',
                'description' => '',
                'salaire' => 31.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre centre U11 et U12',
                'description' => '',
                'salaire' => 37.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre assistant U13 et U14',
                'description' => '',
                'salaire' => 32.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre centre U13 et U14',
                'description' => '',
                'salaire' => 39.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre assistant U15 et U16',
                'description' => '',
                'salaire' => 35.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre centre U15 et U16',
                'description' => '',
                'salaire' => 45.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre assistant U17 et U18',
                'description' => '',
                'salaire' => 40.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Arbitre centre U17 et U18',
                'description' => '',
                'salaire' => 55.00,
                'ordre_affichage' => 0,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Frais de déplacement premier 5000km',
                'description' => 'Par kilomètres',
                'salaire' => 0.68,
                'ordre_affichage' => 2,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Frais de transport pour l\'excédent',
                'description' => 'par kilomètres',
                'salaire' => 0.62,
                'ordre_affichage' => 2,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Bonus à l\'arbitre s\'il est seul assistant',
                'description' => 'U11 et +',
                'salaire' => 5.00,
                'ordre_affichage' => 2,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Bonus à l\'arbitre centre s\'il manque un assistant',
                'description' => 'U11 et +',
                'salaire' => 10.00,
                'ordre_affichage' => 2,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Bonus à l\'arbitre centre s\'il est seul',
                'description' => 'U11 et +',
                'salaire' => 25.00,
                'ordre_affichage' => 2,
                'id_etat' => 2,
                ],
                [
                'nom_poste' => 'Superviseur d\'un arbitre',
                'description' => '',
                'salaire' => 25.00,
                'ordre_affichage' => 2,
                'id_etat' => 2,
                ],
            ]);

        $newPostes = [];
        for ($i = 1; $i <= 10; $i++) {
            $newPostes[] = [
                'nom_poste' => 'Poste spécial ' . $i,
                'description' => 'Description automatique pour le poste spécial ' . $i,
                'salaire' => rand(20, 60) + (rand(0, 99) / 100), // random salaire like 45.73
                'ordre_affichage' => 3,
                'id_etat' => 2,
            ];
        }

        DB::table('poste')->insert($newPostes);
    }
}

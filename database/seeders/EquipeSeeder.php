<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipe')->insert([
            [
                'nom_equipe' => 'Cougar',
                'description' => null,
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 8,
                'id_genre' => 1,
                'id_etat' => 1
            ],
            [
                'nom_equipe' => 'Panther',
                'description' => null,
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 6,
                'id_genre' => 2,
                'id_etat' => 1
            ],
            [
                'nom_equipe' => 'Viper',
                'description' => null,
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 1,
                'id_genre' => 3,
                'id_etat' => 2
            ],
            [
                'nom_equipe' => 'Comêtes',
                'description' => null,
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 3,
                'id_genre' => 2,
                'id_etat' => 1
            ],
            [
                'nom_equipe' => 'Gazelle',
                'description' => null,
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 9,
                'id_genre' => 2,
                'id_etat' => 1
            ],
            [
                'nom_equipe' => 'Lion',
                'description' => null,
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 10,
                'id_genre' => 1,
                'id_etat' => 2
            ],
            [
                'nom_equipe' => 'Ours',
                'description' => null,
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 2,
                'id_genre' => 2,
                'id_etat' => 2
            ],
            [
                'nom_equipe' => 'Footsalle',
                'description' => 'Footsalle',
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 4,
                'id_genre' => 1,
                'id_etat' => 3
            ],
            [
                'nom_equipe' => 'Pickleball',
                'description' => 'Pickleball',
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 5,
                'id_genre' => 2,
                'id_etat' => 3
            ],
            [
                'nom_equipe' => 'Initiation soccer',
                'description' => 'Initiation soccer',
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 7,
                'id_genre' => 3,
                'id_etat' => 3
            ],
            [
                'nom_equipe' => 'Match Panthères',
                'description' => 'Billets pour les estrades',
                'prix' => null,
                'ordre_affichage' => 0,
                'id_categorie' => 7,
                'id_genre' => 3,
                'id_etat' => 3
            ],
        ]);
    }
}

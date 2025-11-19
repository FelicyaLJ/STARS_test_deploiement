<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('exercice')->insert([
                ['nom_exercice'=> 'Passes en pointe diamant', 'texte' => 'Cet exercice est une initiation aux passes.', 'fichier' => 'exercice_1.jpeg', 'image' => 'exercice_1.jpeg', 'ordre_affichage' => 3, 'id_forum'=>1, 'id_categorie'=>1],
                ['nom_exercice'=> 'Dans la zone', 'texte' => 'Cet exercice porte sur les passes intermédiaires.', 'fichier' => 'exercice_2.jpeg', 'image' => 'exercice_2.jpeg', 'ordre_affichage' => 2, 'id_forum'=>6, 'id_categorie'=>1],
                ['nom_exercice'=> 'Transférez la passe', 'texte' => 'Cet exercice porte sur les passes avancées.', 'fichier' => 'exercice_3.jpeg', 'image' => 'exercice_3.jpeg', 'ordre_affichage' => 4, 'id_forum'=>5, 'id_categorie'=>1],
                ['nom_exercice'=> 'Lois du Jeu', 'texte' => 'Document de règles.', 'fichier' => 'exercice_4.pdf', 'image' => 'exercice_4.png', 'ordre_affichage' => 6, 'id_forum'=>4, 'id_categorie'=>2],
                ['nom_exercice'=> 'Règles en compétition', 'texte' => 'Informations sur les compétitions.', 'fichier' => 'exercice_5.pdf', 'image' => 'exercice_5.png', 'ordre_affichage' => 1, 'id_forum'=>5, 'id_categorie'=>2],
                ['nom_exercice'=> 'Interceptions 1', 'texte' => 'Vidéo sur les interceptions.', 'fichier' => 'exercice_6.mp4', 'image' => 'exercice_6.png', 'ordre_affichage' => 5, 'id_forum'=>2, 'id_categorie'=>3]
        ]);
    }
}

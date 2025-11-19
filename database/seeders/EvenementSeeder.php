<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('evenement')->insert([
            [
                'nom_evenement'=> 'Pratique U15',
                'description' => 'Pratique du U15',
                'date' => '2026-04-23',
                'heure_debut' => '12:00',
                'heure_fin' => '15:00',
                'prix' => 0.00,
                'id_categorie' => 1,
                'id_etat' => 1,
                'id_terrain' => 1
            ],
            [
                'nom_evenement'=> 'Match U15',
                'description' => 'Match du U15',
                'date' => '2026-05-20',
                'heure_debut' => '12:00',
                'heure_fin' => '15:00',
                'prix' => 12.99,
                'id_categorie' => 2,
                'id_etat' => 1,
                'id_terrain' => 1
            ],
            [
                'nom_evenement'=> 'Footsalle',
                'description' => 'Footsalle',
                'date' => '2025-11-15',
                'heure_debut' => '13:00',
                'heure_fin' => '17:00',
                'prix' => 20.00,
                'id_categorie' => 3,
                'id_etat' => 4,
                'id_terrain' => 2
            ],
            [
                'nom_evenement'=> 'Pickleball',
                'description' => 'Pickleball',
                'date' => '2025-11-18',
                'heure_debut' => '10:00',
                'heure_fin' => '12:00',
                'prix' => 15.00,
                'id_categorie' => 3,
                'id_etat' => 4,
                'id_terrain' => 2
            ],
            [
                'nom_evenement'=> 'Initiation soccer',
                'description' => 'Initiation soccer',
                'date' => '2025-11-10',
                'heure_debut' => '09:00',
                'heure_fin' => '11:00',
                'prix' => 10.00,
                'id_categorie' => 3,
                'id_etat' => 4,
                'id_terrain' => 3
            ],
            [
                'nom_evenement'=> 'Match Panthères',
                'description' => 'Billets pour les estrades',
                'date' => '2025-11-01',
                'heure_debut' => '09:00',
                'heure_fin' => '11:00',
                'prix' => 10.00,
                'id_categorie' => 2,
                'id_etat' => 4,
                'id_terrain' => 3
            ],
        ]);
    }
}

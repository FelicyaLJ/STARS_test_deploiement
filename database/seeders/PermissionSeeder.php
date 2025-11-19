<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permission')->insert([
            ['nom_permission' => 'gestion_users', 'description' => null],
            ['nom_permission' => 'gestion_roles', 'description' => null],
            ['nom_permission' => 'gestion_settings', 'description' => null],
            ['nom_permission' => 'gestion_forums', 'description' => null],
            ['nom_permission' => 'gestion_messages', 'description' => null],
            ['nom_permission' => 'gestion_entrainements', 'description' => null],
            ['nom_permission' => 'consultation_entrainements', 'description' => null],
            ['nom_permission' => 'gestion_equipes', 'description' => null],
            ['nom_permission' => 'gestion_actualites', 'description' => null],
            ['nom_permission' => 'gestion_categorie_evenement', 'description' => null],
            ['nom_permission' => 'gestion_evenements', 'description' => null],
            ['nom_permission' => 'gestion_faq', 'description' => null],
            ['nom_permission' => 'gestion_inscriptions', 'description' => null],
            ['nom_permission' => 'gestion_demandes', 'description' => null],
            ['nom_permission' => 'gestion_partenaires', 'description' => null],
            ['nom_permission' => 'gestion_cout', 'description' => "Capacité à modifier le tableau des coûts d'inscription"],
            ['nom_permission' => 'gestion_terrains', 'description' => null],
            ['nom_permission' => 'gestion_salaires', 'description' => null],
        ]);
    }
}

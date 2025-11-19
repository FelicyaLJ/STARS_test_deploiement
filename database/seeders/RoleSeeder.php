<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->insert([
            ['nom_role' => 'Président', 'description' => null, 'membre_ca' => 1],
            ['nom_role' => 'Vice-Président', 'description' => null, 'membre_ca' => 1],
            ['nom_role' => 'Admin', 'description' => null, 'membre_ca' => 0],
            ['nom_role' => 'Entraineur Junior', 'description' => null, 'membre_ca' => 0],
            ['nom_role' => 'Entraineur', 'description' => null, 'membre_ca' => 0],
            ['nom_role' => 'Parent', 'description' => null, 'membre_ca' => 0],
            ['nom_role' => 'Joueur', 'description' => null, 'membre_ca' => 0],
            ['nom_role' => 'Utilisateur', 'description' => null, 'membre_ca' => 0],
            ['nom_role' => 'Modérateur', 'description' => null, 'membre_ca' => 0],
        ]);

        $president = Role::where('nom_role', 'Président')->first();
        $president->permissions()->sync(
            Permission::whereIn('nom_permission', ['gestion_cout', 'gestion_demandes', 'gestion_inscriptions', 'gestion_users', 'gestion_roles', 'gestion_settings', 'gestion_forums', 'gestion_messages', 'gestion_equipes', 'gestion_entrainements', 'consultation_entrainements', 'gestion_actualites' , 'manage_event_categories' , 'manage_events', 'gestion_faq', 'gestion_terrains'])->pluck('id')
        );

        $entraineur = Role::where('nom_role', 'Entraineur Junior')->first();
        $entraineur->permissions()->sync(
            Permission::where('nom_permission', 'consultation_entrainements')->pluck('id')
        );
    }
}

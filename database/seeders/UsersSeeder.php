<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Présidents
        $pres = User::factory()->create(
            ['nom' => 'LeBlanc', 'prenom' => 'Sylvain', 'email' => 'president@stars.ca', 'password' => 'Pres1234', 'no_telephone' => '8190000000', 'nam' => 'HHHH12345678', 'id_etat' => 1],
        );
        $rolesPres = Role::whereIn('nom_role', ['Président', 'Utilisateur', 'Vice-Président', 'Admin', 'Modérateur'])->pluck('id');
        $pres->roles()->attach($rolesPres);



        $user = User::factory()->create(
            ['nom' => 'admin', 'prenom' => 'admin', 'email' => 'admin@admin.com', 'password' => 'admin123', 'no_telephone' => null, 'nam' => null, 'id_etat' => 1],
        );

        $role = Role::where('nom_role', 'Président')->first();
        $user->roles()->attach($role->id);

        // Utilisateurs

        $entraineur = User::factory()->create(
             ['nom' => 'user', 'prenom' => '1', 'email' => '1@user.com', 'password' => Hash::make('user123'), 'no_telephone' => null, 'nam' => null, 'id_etat' => 1]
        );
        $entRole= Role::where('nom_role', 'Entraineur Junior')->pluck('id');
        $entraineur->roles()->attach($entRole);

        $autre = User::factory()->create(
        ['nom' => 'user', 'prenom' => '2', 'email' => '2@user.com', 'password' => Hash::make('user456'), 'no_telephone' => null, 'nam' => null, 'id_etat' => 1]
        );

        $users = User::factory()->count(100)->create([
            'id_etat' => 1,
        ]);
        $rolesUsers = Role::whereIn('nom_role', ['Utilisateur'])->pluck('id');

        $users->each(function ($user) use ($rolesUsers) {
            $user->roles()->attach($rolesUsers);
        });

        $players = User::factory()->count(100)->create([
            'id_etat' => 1,
        ]);
        $rolesPlayers = Role::whereIn('nom_role', ['Utilisateur', 'Joueur'])->pluck('id');
        $players->each(function ($user) use ($rolesPlayers) {
            $user->roles()->attach($rolesPlayers);
        });

        $inacs = User::factory()->count(100)->create([
            'id_etat' => 2,
        ]);
        $rolesInac = Role::whereIn('nom_role', ['Utilisateur', 'Parent'])->pluck('id');
        $inacs->each(function ($user) use ($rolesInac) {
            $user->roles()->attach($rolesInac);
        });
    }
}

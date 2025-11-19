<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Equipe;

class EquipeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activeUsers = User::where('id_etat', 1)->pluck('id')->toArray();
        $equipes = Equipe::all();

        foreach ($equipes as $equipe) {
            $randomCount = rand(5, 15);

            $selectedUsers = collect($activeUsers)
                ->shuffle()
                ->take($randomCount)
                ->values();

            foreach ($selectedUsers as $userId) {
                DB::table('equipe_user')->insert([
                    'id_equipe' => $equipe->id,
                    'id_user' => $userId,
                ]);
            }
        }
    }
}

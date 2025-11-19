<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('message')->insert([

            // Forum 7
            ['texte'=> 'Allo', 'created_at' => '2025-09-15 18:29:43', 'id_forum'=>7, 'id_user'=>1, 'id_reponse'=>null],
            ['texte'=> 'Salut', 'created_at' => '2025-09-15 18:31:00', 'id_forum'=>7, 'id_user'=>2, 'id_reponse'=>null],
            ['texte'=> 'Bonjour', 'created_at' => '2025-09-15 18:32:00', 'id_forum'=>7, 'id_user'=>3, 'id_reponse'=>null],

            ['texte'=> 'Ça va ?', 'created_at' => '2025-09-15 18:49:13', 'id_forum'=>7, 'id_user'=>1, 'id_reponse'=>1],
            ['texte'=> 'Hey', 'created_at' => '2025-09-15 18:53:00', 'id_forum'=>7, 'id_user'=>2, 'id_reponse'=>1],
            ['texte'=> 'Encore moi', 'created_at' => '2025-09-15 19:06:03', 'id_forum'=>7, 'id_user'=>3, 'id_reponse'=>6],

            // Forum 8
            ['texte'=> 'Coucou', 'created_at' => '2025-09-15 18:41:00', 'id_forum'=>8, 'id_user'=>1, 'id_reponse'=>null],
            ['texte'=> 'Deuxième msg', 'created_at' => '2025-09-15 18:42:20', 'id_forum'=>8, 'id_user'=>2, 'id_reponse'=>null],
            ['texte'=> 'Yo forum 8', 'created_at' => '2025-09-15 18:45:00', 'id_forum'=>8, 'id_user'=>3, 'id_reponse'=>null],

            ['texte'=> 'Test encore', 'created_at' => '2025-09-15 18:55:55', 'id_forum'=>8, 'id_user'=>1, 'id_reponse'=>7],
            ['texte'=> 'Salut à tous', 'created_at' => '2025-09-15 18:59:40', 'id_forum'=>8, 'id_user'=>2, 'id_reponse'=>9],
            ['texte'=> 'Message 2', 'created_at' => '2025-09-15 18:59:08', 'id_forum'=>8, 'id_user'=>3, 'id_reponse'=>10],

            // Forum 9
            ['texte'=> 'Bonjour', 'created_at' => '2025-09-15 18:53:01', 'id_forum'=>9, 'id_user'=>1, 'id_reponse'=>null],
            ['texte'=> 'Hello', 'created_at' => '2025-09-15 18:57:00', 'id_forum'=>9, 'id_user'=>2, 'id_reponse'=>null],
            ['texte'=> 'Hey', 'created_at' => '2025-09-15 18:58:02', 'id_forum'=>9, 'id_user'=>3, 'id_reponse'=>null],

            ['texte'=> 'Encore test', 'created_at' => '2025-09-15 18:59:04', 'id_forum'=>9, 'id_user'=>1, 'id_reponse'=>14],
            ['texte'=> 'Parents ici', 'created_at' => '2025-09-15 19:01:59', 'id_forum'=>9, 'id_user'=>2, 'id_reponse'=>17],
            ['texte'=> 'Réponse','created_at' => '2025-09-15 19:02:00', 'id_forum'=>9, 'id_user'=>3, 'id_reponse'=>17],
        ]);

    }
}

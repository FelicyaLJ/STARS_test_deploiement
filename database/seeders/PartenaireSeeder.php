<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartenaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('partenaires')->insert([
            [
                'nom' => 'OpenAI',
                'image' => 'openai.png',
                'lien' => 'https://openai.com',
                'ordre_affichage' => 1,
            ],
            [
                'nom' => 'Microsoft',
                'image' => 'microsoft.png',
                'lien' => 'https://microsoft.com',
                'ordre_affichage' => 2,
            ],
            [
                'nom' => 'Google',
                'image' => 'google.png',
                'lien' => 'https://google.com',
                'ordre_affichage' => 3,
            ],
            [
                'nom' => 'Amazon',
                'image' => 'amazon.png',
                'lien' => 'https://amazon.com',
                'ordre_affichage' => 4,
            ],
        ]);
    }
}

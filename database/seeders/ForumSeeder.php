<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('forum')->insert([
            ['nom_forum'=>'exercice_1'],
            ['nom_forum'=>'exercice_2'],
            ['nom_forum'=>'exercice_3'],
            ['nom_forum'=>'exercice_4'],
            ['nom_forum'=>'exercice_5'],
            ['nom_forum'=>'exercice_6'],
            ['nom_forum'=>'Loups U15'],
            ['nom_forum'=>'U14'],
            ['nom_forum'=>'Parents U17']

        ]);
    }
}

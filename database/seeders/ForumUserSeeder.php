<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForumUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('forum_user')->insert([
            ['id_forum'=>7, 'id_user'=>1],
            ['id_forum'=>7, 'id_user'=>2],
            ['id_forum'=>7, 'id_user'=>4],
            ['id_forum'=>8, 'id_user'=>1],
            ['id_forum'=>8, 'id_user'=>2],
            ['id_forum'=>8, 'id_user'=>4],
            ['id_forum'=>9, 'id_user'=>1],
            ['id_forum'=>9, 'id_user'=>2],
            ['id_forum'=>9, 'id_user'=>4]
        ]);
    }
}

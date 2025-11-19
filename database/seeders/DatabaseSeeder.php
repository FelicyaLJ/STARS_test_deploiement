<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Vous pouvez ajouter d’autres "seeders" en les séparant par des virgules.
            PermissionSeeder::class,
            RoleSeeder::class,
            EtatUsersSeeder::class,
            UsersSeeder::class,
            EtatPublicationSeeder::class,
            PublicationSeeder::class,
            TerrainSeeder::class,
            ForumSeeder::class,
            ForumUserSeeder::class,
            CategorieExerciceSeeder::class,
            ExerciceSeeder::class,
            MessageSeeder::class,
            EtatPosteSeeder::class,
            PosteSeeder::class,
            EtatEquipeSeeder::class,
            CategorieEquipeSeeder::class,
            GenreEquipeSeeder::class,
            EquipeSeeder::class,
            EtatEvenementSeeder::class,
            CategorieEvenementSeeder::class,
            EvenementSeeder::class,
            CategorieFAQSeeder::class,
            FAQSeeder::class,
            EquipeUserSeeder::class,
            PartenaireSeeder::class,
        ]);
    }
}

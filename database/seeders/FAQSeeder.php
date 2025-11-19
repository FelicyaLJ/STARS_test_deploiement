<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faq')->insert([
            [
            'titre' => 'Nouveau document explicatif (2025)',
            'texte' => 'Le club a mis au point un document explicatif qui fournis aux joueurs et parents les informations requises sur le fonctionnement du club, des séances de pratiques et sur les règles des différentes catégories.
                        Vous pouvez le consulter en suivant ce lien.',
            'fichier' => 'A.S. STARS - Présentation des règles 2024.pdf',
            'ordre_affichage' => 1,
            'id_categorie' => 1
            ],
            [
            'titre' => 'Timbits',
            'texte' => 'Mardi (Saint-Alphonse) et Mercredi (Rawdon) soir de 18h30 à 19h45. Les séances seront donc d\'une heure quinze minutes au lieu d\'une heure. Elles débuteront le 10 juin (Saint-Alphonse) et le 11 juin (Rawdon), pour se terminer le 19 août (Saint-Alphonse) et 20 août (Rawdon), avant le début des classes.
                        Il n\'y a pas de séances les samedis, et il n\'y en aura pas pendant les vacances de la construction.',
            'fichier' => null,
            'ordre_affichage' => 1,
            'id_categorie' => 2
            ],
            [
            'titre' => 'Catégorie U9 (Griffons)',
            'texte' => 'Soir de C.D.C.:  Les jeudis, à Rawdib, Les heures devraient être de 18h30 à 19h30, mais c\'est à confirmer.

                        Soir de matchs: Les lundis, débute généralement entre 18h30 ou au plus tard à 19h30. Durée: 4 quarts de 12 minutes.

                        Pour avoir congé pendant les vacances de la constructions, 2 matchs auront lieu le samedi 14 juin 2025.',
            'fichier' => null,
            'ordre_affichage' => 2,
            'id_categorie' => 2
            ],
            [
            'titre' => 'Micro-soccer Timbits',
            'texte' => 'Pour le moment, les séances Timbits devraient avoir lieu au parc Donald-Stewart de Rawdon, ou au Collège Champagneur. Il y aura du micro-soccer Timbits à Saint-Alphonse également au terrain de soccer sur la route de Sainte-Béatrix.',
            'fichier' => null,
            'ordre_affichage' => 1,
            'id_categorie' => 3
            ],
            [
            'titre' => 'Ligue juvénile',
            'texte' => 'Les séances de pratiques des équipes juvéniles vont avoir lieu au Parc Donald-Stewart.

                        Les matchs officiels de la ligue juvénile ont lieux la moitié à domicile, la moitié à l\'extérieur.',
            'fichier' => null,
            'ordre_affichage' => 2,
            'id_categorie' => 3
            ],
            [
            'titre' => 'Pour tous les joueurs, peu importe l\'âge:',
            'texte' => '#1 - Les protège-tibias sont obligatoires, de U4 à Senior. C\'est une question de sécurité pour éviter toutes blessures. Sans protège-tibias, un joueur ne peut pas participer aux activités sportives.

                        #2- Bien que non obligatoire, les souliers à crampons sont fortement recommandés pour les séances terrains. Pas besoin d\'acheter des souliers coutant une fortune, surtout pour les joueurs de 14 ans et moins. Les pieds de ces derniers grandissent rapidement. Plusieurs commerces locaux offrent un assortiment de chaussures usagés.

                        #3- Des lunettes de sports sont exigées pour les enfants portant des lunettes.

                        #4- Aucun bijoux (collier, montres, etc.) ne peux être porté lors d\'un match, d\'une pratique ou d\'une activité sportive.',
            'fichier' => null,
            'ordre_affichage' => 1,
            'id_categorie' => 4
            ],
        ]);
    }
}

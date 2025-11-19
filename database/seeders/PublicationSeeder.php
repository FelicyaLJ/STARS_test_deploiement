<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('publication')->insert([
            [
            'texte' => 'Lundi le 20 octobre 2025, à 18h30, aura lieu au Centre Metcalfe notre assemblée générale annuelle pour l\'année 2025. Venez nous questionner. Ou si vous voulez nous aider, venez donner votre nom pour rejoindre le conseil d\'administration.',
            'titre' => 'Mise à jour du document explicatif sur le fonctionnement des pratiques et des matchs.',
            'fichier' => 0,
            'from_facebook' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_etat' => 2
            ],
            [
            'texte' => 'Piège.',
            'titre' => 'Piège',
            'fichier' => 0,
            'from_facebook' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_etat' => 1
            ],
            [
            'texte' => 'Le club est présentement à la recherche de bénévoles et de candidats pour être membre du Conseil d\'administration. Nous ne pouvons malheureusement pas tout faire sans aide supplémentaire.',
            'titre' => 'Recherche de bénévoles',
            'fichier' => 0,
            'from_facebook' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_etat' => 2
            ],
             [
            'texte' => 'Lundi le 20 octobre 2025, à 18h30, aura lieu au Centre Metcalfe notre assemblée générale annuelle pour l\'année 2025. Venez nous questionner. Ou si vous voulez nous aider, venez donner votre nom pour rejoindre le conseil d\'administration.',
            'titre' => 'Assemblée Générale Annuelle 2025',
            'fichier' => 0,
            'from_facebook' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_etat' => 2
            ],
        ]);
    }
}

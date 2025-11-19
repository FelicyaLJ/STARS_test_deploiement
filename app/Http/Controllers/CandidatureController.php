<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    public function index()
    {
        $postes = Poste::whereNotIn('ordre_affichage', [0, 1, 2])->get();

        if ($postes->isEmpty()) {
            return view('/candidatures/candidatures', [
                'message' => 'Aucun poste disponible pour le moment.',
                'postes' => collect() // on envoie une collection vide
            ]);
        }

        return view('/candidatures/candidatures', [
            'postes' => $postes
        ]);
    }

}

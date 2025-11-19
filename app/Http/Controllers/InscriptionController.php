<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipe;
use App\Models\DemandeInscription;
use App\Models\Evenement;
use App\Models\CategorieEquipe;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\DemandeInscriptionController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\CategorieEquipeController;
use Illuminate\View\View;
use App\Mail\Inscription;
use App\Mail\confirmationInscription;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $userId = Auth::id();
        $equipes = Equipe::where('id_etat', 3)
        ->whereDoesntHave('joueurs', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })
        ->with(['categorie', 'genre', 'joueurs'])
        ->get();

        $nomsEquipes = $equipes->pluck('nom_equipe')->toArray();

        $evenements = Evenement::where('id_etat', 4)
            ->whereIn('nom_evenement', $nomsEquipes)
            ->with(['terrain', 'categorie'])
            ->get();
        $demandes = DemandeInscription::where('id_user', $userId)->get();
        $categories = CategorieEquipe::get();

        $equipesInscrites = $demandes->pluck('id_equipe')->filter()->toArray();

        foreach ($equipes as $equipe) {
            $equipe->deja_demande = in_array($equipe->id, $equipesInscrites);
        }

        return view('inscription/inscriptions', [
            'equipes' => $equipes,
            'evenements' => $evenements,
            'categories'=>$categories,
            'userId'=>$userId
        ]);
    }

    public function envoyerDemandeAdhesion(Request $request, $idActivite){
        $user = auth()->user();
        $data = $request->all();

        try {
            $equipe = new Equipe($data['equipe']);
            $evenement = Evenement::find($data['evenement']['id']);
            \Log::info('Store Inscription request data:', $request->all());
            $controller = new DemandeInscriptionController();
            $demande = $controller->store($idActivite, $evenement->id, $user->id);
            Mail::to('admin@example.com')->send(new Inscription($equipe, $user, $evenement, $demande->id));
            Mail::to($user->email)->send(new confirmationInscription($evenement));

            return response()->json(['message' => 'Demande envoyée avec succès.']);
        } catch (\Exception $e) {
            \Log::error('Erreur d\'envoi du email : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de l’envoi du mail.'], 500);
        }
    }

    public function show():View{
        $userId = Auth::id();
        $equipes = Equipe::where('id_etat', 3)
        ->whereHas('joueurs', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        }) ->with(['categorie', 'genre', 'joueurs']) ->get();

        $nomsEquipes = $equipes->pluck('nom_equipe')->toArray();

        $evenements = Evenement::where('id_etat', 4)
            ->whereIn('nom_evenement', $nomsEquipes)
            ->with(['terrain', 'categorie'])
            ->get();

        $demandes = DemandeInscription::where('id_user', $userId)->get();
        $categories = CategorieEquipe::get();

        $equipesInscrites = $demandes->pluck('id_equipe')->filter()->toArray();

        foreach ($equipes as $equipe) {
            $dejaDansEquipe = $equipe->joueurs->contains('id', $userId);

            $equipe->deja_inscrit = $dejaDansEquipe;
        }

        return view('inscription/inscriptions', [
            'equipes' => $equipes,
            'evenements' => $evenements,
            'categories'=>$categories,
            'userId'=>$userId
        ]);
    }

}

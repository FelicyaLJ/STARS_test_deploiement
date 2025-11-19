<?php

namespace App\Http\Controllers;

use App\Models\demandeInscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipe;
use App\Models\Evenement;

class DemandeInscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $demandes=DemandeInscription::where('id_user', $userId)->with(['user', 'equipe', 'evenement'])->orderBy('id', 'DESC')->get();

        return view('inscription/demandes', [
            'demandes'=>$demandes,
            'userId' => $userId
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($idEquipe, $idEvenement, $idUser)
    {
        $demande = new DemandeInscription();
        $demande->id_user = $idUser;
        $demande->id_equipe = $idEquipe;
        $demande->id_evenement = $idEvenement;
        $demande->save();

        return $demande;
    }

    /**
     * Display the specified resource.
     */
    public function show(?int $id = null)
    {
        if ($id){
            $demandes=DemandeInscription::where('id', $id)->with(['user', 'equipe', 'evenement'])->get();
        }
        else{
            $demandes=DemandeInscription::with(['user', 'equipe', 'evenement'])->get();
        }


        return view('inscription/demandes', [
            'demandes'=>$demandes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(demandeInscription $demandeInscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, demandeInscription $demandeInscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        \Log::info('Delete request data:', $request->all());

        $validated = $request->validate([
            'id' => 'required|integer'
        ]);

        $id = $validated['id'];

        $demande = DemandeInscription::find($id);
        $user = auth()->user();
        $equipe=Equipe::find($demande->id_equipe);
        $evenement=Evenement::find($demande->id_evenement);

        if (!$demande) {
            return response()->json(['message' => 'Demande inexistante.'], 404);
        }

        $demande->delete();
        if($request->routeIs('inscriptions.demandes.annulation')) {
            Mail::to('admin@example.com')->send(new annulationAdhesion(user: $user, equipe: $equipe));
        }
        return response()->json(['message' => 'Demande supprimée avec succès.'], 200);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DemandeAdhesion;
use App\Models\Forum;
use App\Models\User;
use App\Mail\annulationAdhesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DemandeAdhesionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $demandes=DemandeAdhesion::where('id_user', $userId)->with(['user', 'forum'])->orderBy('id', 'DESC')->get();

        return view('forum/demandes', [
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
    public function store($user, $forum, $raison)
    {

        $demande = new DemandeAdhesion();
        $demande->id_user = $user;
        $demande->id_forum = $forum;
        $demande->raison = $raison;
        $demande->save();

        return $demande;
    }

    /**
     * Display the specified resource.
     */
    public function show(?int $id = null)
    {
        if ($id){
            $demandes=DemandeAdhesion::where('id', $id)->with(['user', 'forum'])->get();
        }
        else{
            $demandes=DemandeAdhesion::with(['user', 'forum'])->get();
        }
        return view('forum/demandes', [
            'demandes'=>$demandes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(demandeAdhesion $demandeAdhesion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, demandeAdhesion $demandeAdhesion)
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

        $demande = DemandeAdhesion::find($id);
        $user = auth()->user();
        $forum=Forum::find($demande->id_forum);

        if (!$demande) {
            return response()->json(['message' => 'Demande inexistante.'], 404);
        }

        $demande->delete();
        if($request->routeIs('forums.demandes.annulation')) {
            Mail::to('admin@example.com')->send(new annulationAdhesion(user: $user, forum: $forum));
        }
        return response()->json(['message' => 'Demande supprimée avec succès.'], 200);

    }
}

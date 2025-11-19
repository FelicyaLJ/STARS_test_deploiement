<?php

namespace App\Http\Controllers;

use App\Models\CategorieExercice;
use App\Models\Exercice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CategorieExerciceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $userId = Auth::id();

        $categories = CategorieExercice::with(['exercices', 'exercices.forum', 'exercices.forum.messages', 'exercices.forum.messages.user', 'exercices.forum.messages.reponse'])->get();
        $maxOrdreExercice = Exercice::max('ordre_affichage') ?? 0;
        $maxOrdreCategorie = CategorieExercice::max('ordre_affichage') ?? 0;

         return view('exercice/exercices', ['categories'=>$categories, 'userId' => $userId, 'maxOrdreExercice'=>$maxOrdreExercice, 'maxOrdreCategorie'=>$maxOrdreCategorie]);
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
    public function store(Request $request)
    {
        \Log::info('Store categorie data:', $request->all());
         $validation = Validator::make($request->all(), [
            'nom_categorie' => 'required|string|max:255',
            'ordre_affichage' => 'integer|required',
        ]);
        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);
        $validated = $validation->validated();

        $maxOrder = Exercice::max('ordre_affichage') ?? 0;

        if ($validated['ordre_affichage']<=$maxOrder){
            CategorieExercice::where('ordre_affichage', '>=', $validated['ordre_affichage'])->increment('ordre_affichage');
        }

        $categorie = new CategorieExercice();
        $categorie->nom_categorie    = $validated['nom_categorie'];
        $categorie->ordre_affichage = $validated['ordre_affichage'];

        $categorie->save();

        return response()->json(['message' => 'Catégorie créée avec succès'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercice $exercice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercice $exercice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        \Log::info('Store categorie data:', $request->all());
         $validation = Validator::make($request->all(), [
            'id' => 'required|exists:categorie_exercice,id',
            'nom_categorie' => 'required|string|max:255',
            'ordre_affichage' => 'integer|required',
        ]);
        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);
        $validated = $validation->validated();

        $categorie = CategorieExercice::findOrFail($validated['id']);

        $oldOrder = $categorie->ordre_affichage;
        $newOrder=$validated['ordre_affichage'];

        if ($newOrder!=$oldOrder){
            if ($newOrder>$oldOrder){
                CategorieExercice::whereBetween('ordre_affichage', [$oldOrder+1, $newOrder])->decrement('ordre_affichage');
            }
            else if ($newOrder<$oldOrder){
                CategorieExercice::whereBetween('ordre_affichage', [$newOrder, $oldOrder-1])->increment('ordre_affichage');
            }
            $categorie->update([
                'ordre_affichage'=>$validated['ordre_affichage']
            ]);
        }

        $categorie->update([
            'nom_categorie' => $validated['nom_categorie']
        ]);

        return response()->json(['message' => 'Catégorie mise à jour avec succès'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        \Log::info('Destroy categorie data:', $request->all());
        $validated = $request->validate([
            'id' => 'required|integer'
        ]);

        $id = $validated['id'];

        $categorie = CategorieExercice::find($id);

        if (!$categorie) {
            return response()->json(['message' => 'Catégorie inexistante.'], 404);
        }

        foreach ($categorie->exercices as $exercice){
            if ($exercice->forum){
                $exercice->forum->messages()->delete();
                $exercice->forum->delete();
            }
            $exercice->delete();
        }
        $categorie->delete();
        return response()->json(['message' => 'Catégorie supprimée avec succès.'], 200);

    }
}

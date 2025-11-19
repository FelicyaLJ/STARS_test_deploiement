<?php

namespace App\Http\Controllers;

use App\Models\CategorieEvenement;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategorieEvenementController extends Controller
{

    /**
     *
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nom' => ['required', 'max:255', 'regex:/^[\pL\s\-\'0-9]+$/u'],
            'couleur' => ['required', 'string', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ],[
            'nom.required' => 'Le nom ne peut pas être vide.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, chiffres et ponctuation.',
            'couleur.required' => 'Il faut associer une couleur à la catégorie',
            'couleur.regex' => 'La couleur doit être entre #000000 et #FFFFFF, au format hexadécimal.',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 400);
        }

        $contenuFormulaire = $validation->validated();

        try {
            $categorie = new CategorieEvenement();

            $categorie->nom_categorie = $contenuFormulaire['nom'];
            $categorie->couleur = $contenuFormulaire['couleur'];
            $categorie->save();
        } catch (\Throwable $erreur) {
            report($erreur);
            return response()->json([
                'success' => false,
                'error' => $erreur,
                'message' => 'La catégorie n\'a pas pu être créée.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'La catégorie a été créée.',
            'categories' => CategorieEvenement::all()
        ], 200);
    }

    /**
     *
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nom' => ['required', 'max:255', 'regex:/^[\pL\s\-\'0-9]+$/u'],
            'couleur' => ['required', 'string', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ],[
            'nom.required' => 'Le nom ne peut pas être vide.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, chiffres et ponctuation.',
            'couleur.required' => 'Il faut associer une couleur à la catégorie',
            'couleur.regex' => 'La couleur doit être entre #000000 et #FFFFFF, au format hexadécimal.',
        ]);

        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        // Contenu validé
        $contenuFormulaire = $validation->validated();

        try {
            // Categorie à modifier
            $categorie = CategorieEvenement::find($request->input('id'));

            $categorie->nom_categorie = $contenuFormulaire['nom'];
            $categorie->couleur = $contenuFormulaire['couleur'];
            $categorie->save();

        } catch (\Throwable $erreur) {
            report($erreur);
            return response()->json([
                'success' => false,
                'errors' => true,
                'message' => 'La catégorie n\'a pas pu être modifiée.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'La catégorie a bien été modifiée.',
            'categories' => CategorieEvenement::all(),
            'evenements' => Evenement::all()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $categorie = CategorieEvenement::find($request->input('id'));

        $hasFutureEvents = Evenement::where('id_categorie', $categorie->id)
            ->where('date', '>=', now()->toDateString())
            ->exists();

        if ($hasFutureEvents) {
            return response()->json([
                'error' => true,
                'message' => 'Des événements futurs appartiennent à cette catégorie.'
            ], 422);
        }

        if (!$categorie) {
            return response()->json([
                'error' => true,
                'message' => 'La catégorie spécifiée n\'existe pas.'
            ], 200);
        }

        $deletedPastEvents = Evenement::where('id_categorie', $categorie->id)->delete();

        if ($categorie->delete()){
            $response = [
                'success' => true,
                'message' => 'La suppression de la catégorie a réussie.',
                'categories' => CategorieEvenement::all(),
                'evenements' => Evenement::all()
            ];

            if ($deletedPastEvents > 0) {
                $response['warning'] = "Attention : $deletedPastEvents événement(s) passé(s) ont été supprimé(s) avec cette catégorie.";
            }

            return response()->json($response, 200);
        }

        return response()->json([
            'error' => true,
            'message' => 'Échec de la suppression de la catégorie.'
        ], 500);
    }

    /**
     *
     */
    public function render(Request $request)
    {
        $ids = $request->input('categories', []);
        $categories = CategorieEvenement::whereIn('id', $ids)
                            ->get();

        $html = view('evenement.partials.categorie-list', compact('categories'))->render();

        return response($html);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Evenement;
use App\Models\CategorieEvenement;
use App\Models\EtatEvenement;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $actualite = collect();

        if($request->routeIs('accueil')){
            #Double check valeur de 'Actualité' dans 'etat_publication'
            $actualite = Publication::where('id_etat', '2')->get();
            $filterEv = $request->query('filter', 'upcoming'); // default
            $queryEv = Evenement::with(['etat', 'terrain', 'categorie', 'equipes'])
                ->where('id_etat', '!=', 4)
                ->orderBy('date', 'asc');

            $evenements = $filterEv === 'past'
                ? $queryEv->past()->paginate($request->input('perPage', 10))
                : $queryEv->upcoming()->paginate($request->input('perPage', 10));
            $etats = EtatEvenement::all();
            $categories = CategorieEvenement::all();
            $terrains = Terrain::all();
            return view('accueil.accueil', compact('actualite', 'evenements', 'terrains', 'etats', 'categories'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->routeIs('actualite_create')){

            $validation = Validator::make($request->all(), [
                'texte' => 'required|string|max:1500|regex:/^[\pL\s\-\'0-9,.?!$%()]+$/u',
                'titre' => 'required|string|max:255|regex:/^[\pL\s\-\'.?0-9!?:]+$/u',
                'texte_html' => 'required'
            ],[
                'texte.required' => 'Le texte ne peut pas être vide',
                'texte.string' => 'Le texte doit être une chaine de caractères.',
                'texte.max' => 'Le texte est trop long. Utilisez moins de styles pour drastiquement réduire la taille du texte enregistré',
                'texte.regex' => 'Le texte ne peut contenir que des lettres, des chiffres ou de la ponctuation',
                'titre.required' => 'Le titre ne peut pas être vide.',
                'titre.string' => 'Le titre doit être une chaine de caractères.',
                'titre.max' => 'Le titre ne peut pas être plus long que 255 caractères',
                'titre.regex' => 'Le titre ne peut contenir que des lettres, des chiffres ou de la ponctuation',
                'texte_html.required' => 'Problème dans l\'envoi du formulaire. Veuillez recharger la page ou contacter un administrateur'
            ]);
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {
                $now = Carbon::now();

                $publication = new Publication();
                $publication->texte = $contenuDecode['texte_html'];
                $publication->titre = $contenuDecode['titre'];
                $publication->fichier = 0;
                $publication->from_facebook = 0;
                $publication->created_at = $now;
                $publication->updated_at = $now;

                #Vraiment regarder si c'est bon ID
                $publication->id_etat = 2;

                $publication->save();
            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'error' => true,
                    'error' => $erreur,
                    'message' => 'L\'actualité n\'a pas pu être créé.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'L\'actualité a été créé. Veuillez recharger la page.'
            ], 200);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if($request->RouteIs('actualite_delete')){
            $publication = Publication::find($request->input('id'));

            if (!$publication) {
                return response()->json([
                    'error' => true,
                    'message' => 'La publication spécifié n\'existe pas!'
                ], 200);
            }

            if ($publication->delete()){
                return response()->json([
                    'success' => true,
                    'message' => 'La suppression de la publication a bien fonctionné.'
                ], 200);
            }

            return response()->json([
                'error' => true,
                'message' => 'La suppression de la publication n\'a pas fonctionné.'
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        if($request->RouteIs('actualite_edit')){

            $validation = Validator::make($request->all(), [
                'texte' => 'required|string|max:1500|regex:/^[\pL\s\-\'0-9,.?!$%()]+$/u',
                'titre' => 'required|string|max:255|regex:/^[\pL\s\-\'.?0-9!?:]+$/u',
                'id' => 'required',
                'texte_html' => 'required'
            ],[
                'texte.required' => 'Le texte ne peut pas être vide',
                'texte.string' => 'Le texte doit être une chaine de caractères.',
                'texte.max' => 'Le texte est trop long. Utilisez moins de styles pour drastiquement réduire la taille du texte enregistré',
                'texte.regex' => 'Le texte ne peut contenir que des lettres, des chiffres et de la ponctuation',
                'titre.required' => 'Le titre ne peut pas être vide.',
                'titre.string' => 'Le titre doit être une chaine de caractères.',
                'titre.max' => 'Le titre ne peut pas être plus long que 255 caractères',
                'titre.regex' => 'Le titre ne peut contenir que des lettres, des chiffres et de la ponctuation',
                'id.required' => 'Problème lors de l\'identification de la publication. Contactez un administrateur',
                'texte_html.required' => 'Problème dans l\'envoi du formulaire. Veuillez recharger la page ou contacter un administrateur'
            ]);
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {
                $now = Carbon::now();

                $publication = Publication::find($contenuDecode['id']);
                $publication->titre = $contenuDecode['titre'];
                $publication->texte = $contenuDecode['texte_html'];
                $publication->updated_at = $now;

                $publication->update();

            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'errors' => true,
                    'error' => $erreur,
                    'message' => 'La modification de la publication a échoué.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Publication modifiée avec succès!',
            ], 200);
        }
    }
}

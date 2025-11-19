<?php

namespace App\Http\Controllers;

use App\Models\CategorieEquipe;
use App\Models\DemandeInscription;
use App\Models\GenreEquipe;
use App\Models\EtatEquipe;
use App\Models\Equipe;
use App\Models\Evenement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\confirmationAjout;
use App\Mail\DemandeEquipe;
use App\Mail\RefusDemandeEquipe;

class EquipeController extends Controller
{
    /**
    * Fonction pour filtrer les équipe
    */
    public function filtrer(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'search'    => 'nullable|string|max:255|regex:/^[\pL\pN\s@._-]*$/u',
            'etats'     => 'nullable|array',
            'etats.*'   => 'required|integer|in:1,2',
        ], [
            'search.regex' => 'Les caractères spéciaux ne sont pas permis dans la recherche.',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 400);
        }

        $validated = $validation->validated();

        $query = Equipe::with(['categorie', 'genre', 'etat']);

        if (!empty($validated['search'])) {
            $search = trim($validated['search']);
            $safeSearch = str_replace(['%', '_'], ['\%', '\_'], $search);
            $parts = preg_split('/\s+/', $safeSearch);

            $query->where(function ($q) use ($parts) {
                foreach ($parts as $part) {
                    $q->where('nom_equipe', 'like', "%{$part}%");
                }
            });
        }

        if ($request->filled('id_categorie_filtre')) {
            $query->where('id_categorie', $request->id_categorie_filtre);
        }

        if ($request->filled('id_genre_filtre')) {
            $query->where('id_genre', $request->id_genre_filtre);
        }

        if ($request->filled('id_etat_filtre')) {
            $query->where('id_etat', $request->id_etat_filtre);
        }

        $equipes = $query->get();

        return response()->json(['equipes' => $equipes]);
    }

    /**
     * Montre les joueurs fesant parti de l'équipe
     */
    public function getJoueurs($id)
    {
        try {
            $equipe = Equipe::with('joueurs')->find($id);

            if (!$equipe) {
                return response()->json(['message' => 'Équipe non trouvée.'], 404);
            }

            return response()->json($equipe->joueurs);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des joueurs : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur.'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipes = Equipe::all();
        $categories = CategorieEquipe::all();
        $genres = GenreEquipe::all();
        $etats = EtatEquipe::all();

        return view('/equipe/equipes', [
            "equipes" => $equipes,
            'categories' => $categories,
            'genres' => $genres,
            'etats' => $etats,
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
     * Ajouter le joueur via son adresse email dans l'équipe
     */
    public function ajouterJoueurParEmail(Request $request, Equipe $equipe)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'L\'email est requis.',
            'email.email' => 'Format d\'email invalide.',
            'email.exists' => 'Aucun joueur avec cet email.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Vérifie s’il est déjà dans l’équipe
        if ($equipe->joueurs()->where('users.id', $user->id)->exists()) {
            // Si c’est une requête JSON/AJAX → JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ce joueur est déjà dans l\'équipe.',
                ], 409);
            }

            // Sinon → comportement normal
            return back()->with('error', 'Ce joueur est déjà dans l\'équipe.');
        }
        $demande = DemandeInscription::where('id_user', $user->id)->where('id_equipe', $equipe->id)->get();
        if ($demande->isNotEmpty()){
            foreach ($demande as $d) {
                $d->delete();
            }
            Mail::to($user->email)->send(new confirmationAjout($user, $equipe));
        }
        // Associer à l’équipe
        $equipe->joueurs()->attach($user->id);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Joueur ajouté à l\'équipe.',
                'joueur' => $user,
            ], 201);
        }

        return back()->with('success', 'Joueur ajouté à l\'équipe.');
    }

    public function addUser($idMembre, $idEquipe){
        try{
            $user = User::findOrFail($idMembre);
            $equipe = Equipe::findOrFail($idEquipe);
            $equipe->joueurs()->attach($idMembre);

            $demande = DemandeInscription::where('id_user', $idMembre)->where('id_equipe', $equipe->id)->get();
            if ($demande->isNotEmpty()){
                foreach ($demande as $d) {
                    $d->delete();
                }
                Mail::to($user->email)->send(new confirmationAjout(user: $user, equipe: $equipe));
            }
            return response()->json([
                'message' => 'Activité mise à jour avec succès',
            ]);
        }catch (\Exception $e){
                return response()->json(['error' => 'L\'utilisateur n\'a pas pu être ajouté: ' . $e->getMessage()], 404);
        }

    }

    /**
     *  Création de joueur vide
     */
    public function createBlankJoueur(Request $request, Equipe $equipe)
    {
        $request->validate([
            'prenom' => 'required|string|max:80',
            'nom' => 'required|string|max:80',
        ]);

        // Création d'un utilisateur "incomplet"
        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => uniqid('joueur_') . '@example.com', // email
            'password' => bcrypt('motdepassepardefaut'),
            'id_etat' => 1,
        ]);

        // Associer ce joueur à l'équipe
        $equipe->joueurs()->attach($user->id);

        return redirect()->back()->with('success', 'Joueur ajouté à l\'équipe.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nom_equipe' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'id_categorie' => 'required|exists:categorie_equipe,id',
            'id_genre' => 'required|exists:genre_equipe,id',
            'id_etat' => 'required|exists:etat_equipe,id',
            'joueurs' => 'nullable|array',
        ];

        $messages = [
            'nom_equipe.required' => 'Le nom de l\'équipe est requis.',
            'nom_equipe.string' => 'Le nom de l\'équipe doit être une chaîne de caractères.',
            'nom_equipe.max' => 'Le nom de l\'équipe ne peut pas dépasser 40 caractères.',

            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',

            'id_categorie.required' => 'La catégorie est obligatoire.',
            'id_categorie.exists' => 'La catégorie sélectionnée est invalide.',

            'id_genre.required' => 'Le genre est obligatoire.',
            'id_genre.exists' => 'Le genre sélectionné est invalide.',

            'id_etat.required' => 'L\'état est obligatoire.',
            'id_etat.exists' => 'L\'état sélectionné est invalide.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        $validated['ordre_affichage'] = 0;

        $equipe = Equipe::create($validated);
        if (isset($validated['joueurs'])){
            $equipe->joueurs()->sync($validated['joueurs']);
            foreach ($equipe->joueurs as $user){
                $demande = DemandeInscription::where('id_user', $user->id)->where('id_equipe', $equipe->id)->get();
                if ($demande->isNotEmpty()){
                    foreach ($demande as $d) {
                        $d->delete();
                    }
                    Mail::to($user->email)->send(new confirmationAjout($user, $equipe));
                }
            }
        }

        return response()->json([
            'message' => 'Équipe ajoutée avec succès.',
            'equipe' => $equipe->load(['categorie', 'genre', 'etat'])
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Equipe $equipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $equipe = Equipe::with(['categorie', 'genre', 'etat'])->findOrFail($id);
        return response()->json($equipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $rules = [
            'nom_equipe' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'id_categorie' => 'required|exists:categorie_equipe,id',
            'id_genre' => 'required|exists:genre_equipe,id',
            'id_etat' => 'required|exists:etat_equipe,id',
            'joueurs' => 'nullable|array',
        ];

        $messages = [
            'nom_equipe.required' => 'Le nom de l\'équipe est requis.',
            'nom_equipe.string' => 'Le nom de l\'équipe doit être une chaîne de caractères.',
            'nom_equipe.max' => 'Le nom de l\'équipe ne peut pas dépasser 40 caractères.',

            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',

            'id_categorie.required' => 'La catégorie est obligatoire.',
            'id_categorie.exists' => 'La catégorie sélectionnée est invalide.',

            'id_genre.required' => 'Le genre est obligatoire.',
            'id_genre.exists' => 'Le genre sélectionné est invalide.',

            'id_etat.required' => 'L\'état est obligatoire.',
            'id_etat.exists' => 'L\'état sélectionné est invalide.',
        ];

       $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $equipe = Equipe::findOrFail($id);
        $equipe->update($validated);

        if (isset($validated['joueurs'])){
            $equipe->joueurs()->sync($validated['joueurs']);
            foreach ($equipe->joueurs as $user){
                $demande = DemandeInscription::where('id_user', $user->id)->where('id_equipe', $equipe->id)->get();
                if ($demande->isNotEmpty()){
                    foreach ($demande as $d) {
                        $d->delete();
                    }
                    Mail::to($user->email)->send(new confirmationAjout($user, $equipe));
                }
            }
        }

        $equipe = $equipe->fresh(['categorie', 'genre', 'etat']);
        return response()->json([
            'message' => 'Équipe miss à jour avec succès',
            'equipe' => $equipe,
        ]);
    }

    /**
     * Suppression d'un joueur "blank"
     */
    public function deleteBlankJoueur(Request $request, Equipe $equipe, User $user)
    {
        // Vérifie que ce joueur appartient bien à l'équipe
        if (!$equipe->joueurs->contains($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Ce joueur n\'appartient pas à cette équipe.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Ce joueur n\'appartient pas à cette équipe.');
        }

        // On détache d'abord le joueur de l'équipe
        $equipe->joueurs()->detach($user->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Joueur supprimé avec succès.'
            ]);
        }

        return redirect()->back()->with('success', 'Joueur supprimé avec succès.');
    }

    public function quitterEquipe(Request $request, Equipe $equipe)
    {
        $user = auth()->user();

        // Vérifie que ce joueur appartient bien à l'équipe
        if (!$equipe->joueurs->contains($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Ce joueur n\'appartient pas à cette équipe.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Ce joueur n\'appartient pas à cette équipe.');
        }

        // On détache d'abord le joueur de l'équipe
        $equipe->joueurs()->detach($user->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Équipe ' . $equipe->nom_equipe . ' quittée.'
            ]);
        }

        return redirect()->back()->with('success', 'Équipe ' . $equipe->nom_equipe . ' quittée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($equipeId)
    {
        try {
            $equipe = Equipe::findOrFail($equipeId);

            $equipe->joueurs()->detach();
            $equipe->demandesInscription()->delete();

            $evenement = Evenement::where('nom_evenement', $equipe->nom_equipe)->first();

            if ($evenement) {
                $evenement->delete();
            }

            // Supprime l'équipe elle-même
            $equipe->delete();

            // Redirect with success message
            return response()->json([
                'success' => true,
                'message' => 'Équipe supprimée avec succès.'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Erreur suppression équipe : ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'La suppression a échoué. Veuillez réessayer plus tard.'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'search'    => 'nullable|regex:/^[\pL\pN\s@._-]*$/u',
            'etats'     => 'nullable|array',
            'etats.*'   => 'integer|between:1,2',
        ], [
            'search.regex' => 'Les caractères spéciaux ne sont pas permis dans la recherche'
        ]);

        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        $validated = $validation->validated();

        $query = Equipe::query();

        // 🔍 Appliquer la recherche SEULEMENT si non vide
        dd($validated['search']);
        if (!empty($validated['search'])) {
            $search = trim($validated['search']);

            $query->where(function ($q) use ($search) {
                // Si la recherche contient plusieurs mots
                $parts = preg_split('/\s+/', $search);
                foreach ($parts as $part) {
                    $q->orWhere('nom_equipe', 'like', "%{$part}%");
                }
            });
        }

        // ⚙️ Filtrer par états si fourni
        if (!empty($validated['etats'])) {
            $query->whereIn('id_etat', $validated['etats']);
        }

        return response()->json([
            'equipes' => $query->get()
        ]);
    }


    public function render(Request $request)
    {
        $ids = $request->input('equipes', []);

        if (empty($ids)) {
            $equipes = collect();
        } else {
            $idsString = implode(',', $ids);
            $equipes = Equipe::with(['categorie', 'genre', 'etat'])
                ->whereIn('id', $ids)
                ->orderByRaw("FIELD(id, $idsString)") // preserve order from /search
                ->get();
        }

        $html = view('partials.equipes-list', compact('equipes'))->render();

        return response($html);
    }

    public function envoyerDemandeRejoindre(Request $request, $idEquipe)
    {
        $user = auth()->user();
        $raison = $request->input('raison');
        $equipe = Equipe::findOrFail($idEquipe);

        try {
            Mail::to('admin@example.com')->send(new DemandeEquipe($equipe, $user, $raison));
            return back()->with('success', 'Votre demande a été envoyée à l’administrateur.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de l’envoi de la demande : ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('erreur', 'Une erreur est survenue lors de l’envoi de la demande. Veuillez réessayer plus tard.');
        }
    }

    public function refuserDemande($idMembre, $idEquipe)
    {
        $user = User::findOrFail($idMembre);
        $equipe = Equipe::findOrFail($idEquipe);

        // Send email to user
        Mail::to($user->email)->send(new RefusDemandeEquipe($equipe, $user));

        return response()->json([
            'message' => 'La demande à été refusée.'
        ]);
    }
}

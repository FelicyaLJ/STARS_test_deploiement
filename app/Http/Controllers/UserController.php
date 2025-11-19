<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EtatUser;
use App\Models\Role;
use App\Models\Equipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $page = $request->query('page', 1);

        $users = User::paginate($perPage, ['*'], 'page', $page);
        return view('users.list', [
            'users'     => $users,
            'etats'     => EtatUser::all(),
            'roles'     => Role::all(),
            'equipes'   => Equipe::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'prenom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'mdp' => ['required', 'confirmed', 'max:255', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'tel' => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
            'etat' => 'required',
            'roles'  => 'nullable|array',
            'roles.*'=> 'integer|exists:role,id',
        ], [
            'nom.required' => 'Le nom est requis.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
            'prenom.max' => 'Le prenom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'Le format de l\'adresse e-mail est invalide ou le domaine n\'existe pas.',
            'email.unique' => 'Cette adresse courriel est déjà utilisée.',
            'mdp.required' => 'Le mot de passe est requis.',
            'mdp.confirmed' => 'Les mots de passe entrés ne correspondent pas.',
            'mdp.password' => 'Le mot de passe doit contenir au moins 8 caractères, des lettres majuscules et minuscules, des chiffres et un caractère spécial.',
            'tel.regex' => 'Le format du numéro de téléphone est invalide.',
            'etat.required' => 'L\'état est requis.',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 400);
        }


        // Préparer le User
        try {
            $contenuFormulaire = $validation->validated();
            $this->authorize('create', [User::class, $contenuFormulaire['roles'] ?? []]);

            $user = new User();
            $user->nom = $contenuFormulaire['nom'];
            $user->prenom = $contenuFormulaire['prenom'];
            $user->email = $contenuFormulaire['email'];
            $user->password = Hash::make($contenuFormulaire['mdp']);
            $user->no_telephone = $contenuFormulaire['tel'] ?? '';
            $user->id_etat = 1;
            $user->save();

            $user->roles()->sync($contenuFormulaire['roles'] ?? []);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'errors' => true,
                'message' => $e->getMessage()
            ], 403);
        } catch (\Throwable $erreur) {
            report($erreur);
            return response()->json([
                'errors' => true,
                'error' => $erreur,
                'message' => 'L\'utilisateur n\'a pas pu être créé.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'L\'utilisateur a été créé.',
            'user' => $user->load('roles', 'etat')
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'prenom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'email' => ['required', 'max:255', 'regex:/^[\w\.\-]+@[\w\-]+\.[a-zA-Z]{2,}$/', Rule::unique('users', 'email')->ignore($request->input('id'))],
            'mdp' => ['nullable', 'confirmed', 'max:255', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'tel' => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
            'etat' => 'required',
            'roles'  => 'nullable|array',
            'roles.*'=> 'integer|exists:role,id',
            'equipes'  => 'nullable|array',
            'equipes.*'=> 'integer|exists:equipe,id',
        ], [
            'nom.required' => 'Le nom est requis.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
            'prenom.max' => 'Le prenom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.regex' => 'Le format de l\'adresse e-mail est invalide.',
            'email.unique' => 'Cette adresse courriel est déjà utilisée.',
            'mdp.password' => 'Le mot de passe doit contenir au moins 8 caractères, des lettres majuscules et minuscules, des chiffres et un caractère spécial.',
            'tel.regex' => 'Le format du numéro de téléphone est invalide.',
            'etat.required' => 'L\'état est requis.',
        ]);

        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        // Contenu validé
        $contenuFormulaire = $validation->validated();

        try {
            // Utilisateur à modifier
            $user = User::find($request->input('id'));

            $this->authorize('update', $user);

            $user->nom = $contenuFormulaire['nom'];
            $user->prenom = $contenuFormulaire['prenom'];
            $user->email = $contenuFormulaire['email'];
            if (isset($contenuFormulaire['mdp'])) {
                $user->password = Hash::make($contenuFormulaire['mdp']);
            }
            if (isset($contenuFormulaire['tel'])) {
                $user->no_telephone = $contenuFormulaire['tel'];
            }
            if (isset($contenuFormulaire['nam'])) {
                $user->no_telephone = $contenuFormulaire['nam'];
            }
            $user->id_etat = $contenuFormulaire['etat'];

            $user->equipes()->sync($contenuFormulaire['equipes'] ?? []);
            $user->roles()->sync($contenuFormulaire['roles'] ?? []);
            $user->save();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'errors' => true,
                'message' => $e->getMessage()
            ], 403);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'errors' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'L\'utilisateur a bien été modifié.',
            'user' => $user->load('roles', 'etat')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        \Log::info('Destroy user data:', $request->all());
        $user = User::find($request->input('id'));

        try {
            $this->authorize('delete', $user);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'errors' => true,
                'message' => $e->getMessage()
            ], 403);
        }

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'L\'utilisateur spécifié n\'existe pas.'
            ], 200);
        }

        $user->demandesInscription()->delete();
        $user->demandesAdhesion()->delete();

        if ($user->delete()){
            return response()->json([
                'success' => true,
                'message' => 'La suppression de l\'utilisateur a bien fonctionné.'
            ], 200);
        }

        return response()->json([
            'error' => true,
            'message' => 'La suppression de l\'utilisateur n\'a pas fonctionné.'
        ], 500);
    }

    public function search(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'search'    => 'nullable|regex:/^[\pL\pN\s@._-]*$/u',
            'orders'    => 'nullable|array',
            'orders.*'  => 'integer',
            'etats'     => 'nullable|array',
            'etats.*'   => 'integer',
            'roles'     => 'nullable|array',
            'roles.*'   => 'integer|exists:role,id',
        ], [
            'search.regex' => 'Les charactères spéciaux ne sont pas permis dans la recherche'
        ]);

        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        $validated = $validation->validated();

        $query = User::query();

        // Recherche nom-prenom-email
        $search = $validated['search'] ?? null;
        $query->where(function ($q) use ($search) {
            if (str_contains($search, '@') || str_contains($search, '.')) {
                // Email précisément
                $q->where('email', 'like', "%{$search}%");
            } elseif (str_contains($search, ' ')) {
                // Nom complet
                [$first, $last] = explode(' ', $search, 2);
                $q->where(function ($sub) use ($first, $last) {
                    $sub->where('prenom', 'like', "%{$first}%")
                        ->where('nom', 'like', "%{$last}%");
                });
            } else {
                // Mot seul
                $q->where('prenom', 'like', "%{$search}%")
                ->orWhere('nom', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            }
        });

        // État
        $query->when(!empty($validated['etats']), function ($q) use ($validated) {
            $q->whereIn('id_etat', $validated['etats']);
        });

        // Rôle
        $query->when(!empty($validated['roles']), function ($q) use ($validated) {
            $roles = $validated['roles'];

            foreach ($roles as $roleId) {
                $q->whereHas('roles', function ($roleQuery) use ($roleId) {
                    $roleQuery->where('id', $roleId);
                });
            }
        });

        // Ordre
        $query->when(!empty($validated['orders']), function ($q) use ($validated) {
            $orders = $validated['orders'];

            foreach($orders as $order) {
                switch ($order) {
                    case 0:
                        $q->orderBy('created_at', 'asc');
                        break;
                    case 1:
                        $q->orderBy('created_at', 'desc');
                        break;
                    case 2:
                        $q->orderBy('prenom', 'asc');
                        break;
                    case 3:
                        $q->orderBy('prenom', 'desc');
                        break;
                    case 4:
                        $q->orderBy('nom', 'asc');
                        break;
                    case 5:
                        $q->orderBy('nom', 'desc');
                        break;
                    case 6:
                        $q->orderBy('email', 'asc');
                        break;
                    case 7:
                        $q->orderBy('email', 'desc');
                        break;
                    default:
                        break;
                }
            }
        });

        $perPage = $request->input('perPage', 10);
        $page    = $request->input('page', 1);

        if ($request->expectsJson()) {
            return response()->json(
                $query->paginate($perPage, ['*'], 'page', $page)
            );
        }

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return view('users.list', [
            'users'     => $users,
            'etats'     => EtatUser::all(),
            'roles'     => Role::all(),
            'equipes'   => Equipe::all(),
        ]);
    }

    /**
     * Permet la recherche de email pour l'ajout dans une équipe (via la fenêtre équipe)
     */
    public function searchPlayers(Request $request)
    {
        $query = trim($request->input('query'));
        $equipeId = $request->input('id_equipe');
        \Log::info('equipeId', ['id' => $equipeId]);

        // Si aucun texte tapé → retourne vide
        if (empty($query)) {
            return response()->json([]);
        }

        // Requête de base : recherche sur email
        $usersQuery = User::query()
            ->where('email', 'like', "%{$query}%");
        \Log::info('Requête SQL', ['sql' => $usersQuery->toSql(), 'bindings' => $usersQuery->getBindings()]);


        // Exclure ceux déjà dans l’équipe
        if (!empty($equipeId)) {
            $usersQuery->whereDoesntHave('equipes', function ($q) use ($equipeId) {
                $q->where('id', $equipeId);
            });
        }

        // Limite & colonnes minimales
        $users = $usersQuery
            ->limit(10)
            ->get(['id', 'prenom', 'nom', 'email']);
        \Log::info('Joueurs trouvés', $users->toArray());

        return response()->json($users, 200);
    }

    /**
     *
     */
    public function getMembresCA(Request $request)
    {
        $caRoleIds  = Role::where('membre_ca', 1)->pluck('id');

        $membres = User::whereHas('roles', function ($query) use ($caRoleIds) {
            $query->whereIn('id', $caRoleIds);
        })
        ->with(['roles' => function ($query) use ($caRoleIds) {
            $query->whereIn('id', $caRoleIds);
        }])
        ->get();

        return response()->json($membres);
    }
}

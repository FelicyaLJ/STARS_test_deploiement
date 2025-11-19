<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('roles.list', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
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
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nom_role' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\'’\-]+$/u', 'unique:'.Role::class],
            'description' => 'nullable|string|max:255',
            'membre_ca' => 'required|boolean',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'integer|exists:permission,id',
        ], [
            'nom_role.required' => 'Le nom est requis.',
            'nom_role.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'nom_role.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'nom_role.unique' => 'Ce rôle existe déjà.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',
            'membre_ca.required' => 'Ce champ est requis.',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 400);
        }

        $contenuFormulaire = $validation->validated();

        // Préparer le Role
        try {
            $role = new Role();
            $role->nom_role = $contenuFormulaire['nom_role'];
            $role->description = $contenuFormulaire['description'];
            $role->membre_ca = $contenuFormulaire['membre_ca'];
            $role->save();

            $role->permissions()->sync($contenuFormulaire['permissions'] ?? []);
        } catch (\Throwable $erreur) {
            report($erreur);
            return response()->json([
                'errors' => true,
                'error' => $erreur,
                'message' => 'La création du rôle a échoué.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rôle ' . $contenuFormulaire['nom_role'] . ' créé!',
            'role' => $role->load('permissions')
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validation = Validator::make($request->all(), [
            'nom_role' => 'required|string|max:255|regex:/^[\pL\s\'’\-]+$/u',
            'description' => 'nullable|string|max:255',
            'membre_ca' => 'required|boolean',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'integer|exists:permission,id',
        ], [
            'nom_role.required' => 'Le nom est requis.',
            'nom_role.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'nom_role.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',
            'membre_ca.required' => 'Ce champ est requis.',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 400);
        }

        $contenuFormulaire = $validation->validated();

        try {
            $role = Role::find($request->input('id'));

            $this->authorize('update', $role);

            $role->nom_role = $contenuFormulaire['nom_role'];
            $role->description = $contenuFormulaire['description'];
            $role->membre_ca = $contenuFormulaire['membre_ca'];
            $role->permissions()->sync($contenuFormulaire['permissions'] ?? []);
            $role->save();

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'errors' => true,
                'message' => $e->getMessage()
            ], 403);
        } catch (\Throwable $erreur) {
            report($erreur);
            return response()->json([
                'errors' => true,
                'message' => 'La modification du rôle a échoué.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => $role->nom_role . ' modifié avec succès!',
            'role' => $role->load('permissions')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $role = Role::find($request->input('id'));

        try {
            $this->authorize('delete', $role);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'errors' => true,
                'message' => $e->getMessage()
            ], 403);
        }

        if (!$role) {
            return response()->json([
                'error' => true,
                'message' => 'Le rôle spécifié n\'existe pas.'
            ], 404);
        }

        $force = $request->boolean('force', false);

        if (!$force && $role->users()->exists()) {
            return response()->json([
                'error' => true,
                'message' => "Des utilisateurs possèdent encore ce rôle.",
                'requires_force' => true
            ], 409);
        }

        if ($force) {
            $role->users()->detach();
        }

        $roleName = $role->nom_role;

        if ($role->delete()) {
            return response()->json([
                'success' => true,
                'message' => "Le rôle '{$roleName}' a été supprimé." . ($force ? " (suppression forcée)" : "")
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'La suppression du rôle a échoué.'
        ], 500);
    }



    public function fetch(Request $request)
    {
        return response()->json(Role::all());
    }

}

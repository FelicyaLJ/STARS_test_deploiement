<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;

class PosteController extends Controller
{
    public function index()
    {
        return view('/poste/postes');
    }

    public function index_salaire()
    {
        $postes = Poste::where('ordre_affichage', 1)->get();
        $arbitres = Poste::where('ordre_affichage', 0)->get();
        $autres = Poste::where('ordre_affichage', 2)->get();
        $user = auth()->user();
        $role = $user?->roles->first()->nom_role;
        $groupedPostes = collect([$postes, $arbitres, $autres])->flatten()->groupBy('ordre_affichage');
        return view('/salaire/salaires', [
            'role' => $role,
            'groupedPostes' => $groupedPostes
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_poste' => 'required|string|max:255',
            'salaire' => 'required|numeric|min:0',
            'ordre_affichage' => 'required|integer',
            'id_etat' => 'required|integer'
        ]);

        $poste = Poste::create([
            'nom_poste' => $validated['nom_poste'],
            'description' => null,
            'salaire' => $validated['salaire'],
            'ordre_affichage' => $validated['ordre_affichage'],
            'id_etat' => $validated['id_etat']
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'poste' => $poste,
                'message' => 'Poste ajouté avec succès.'
            ]);
        }

        return redirect()->back()->with('success', 'Poste ajouté avec succès.');
    }

    public function edit($id)
    {
        $poste = Poste::findOrFail($id);
        return response()->json($poste);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nom_poste' => 'required|string|max:255',
                'salaire' => 'required|numeric|min:0',
                'ordre_affichage' => 'required|integer',
                'id_etat' => 'required|integer',
            ]);

            $poste = Poste::findOrFail($id);

            $poste->update($request->only(['nom_poste', 'salaire', 'ordre_affichage', 'id_etat']));

            return response()->json([
                'success' => true,
                'message' => 'Poste mis à jour avec succès.',
                'poste' => $poste,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Poste non trouvé.'
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du poste : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur lors de la mise à jour du poste.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $poste = Poste::findOrFail($id);
            $poste->delete();

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Poste supprimé avec succès']);
            }

            return redirect()->back()->with('success', 'Ce poste a été supprimé avec succès.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Poste non trouvé'], 404);
            }

            return redirect()->back()->with('error', 'Poste non trouvé');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du poste : ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json(['error' => 'Erreur lors de la suppression'], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression');
        }
    }
}

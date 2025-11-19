<?php

namespace App\Http\Controllers;

use App\Models\Exercice;
use App\Models\Forum;
use App\Models\CategorieExercice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ExerciceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'nom_exercice' => 'required|string|max:255',
            'texte' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:5120',
            'fichier' => 'nullable|file|max:307200',
            'lien' => 'nullable|string|max:255',
            'ordre_affichage' => 'integer|required',
            'id_forum' => 'required|integer',
            'id_categorie' => 'required|integer'
        ]);
        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);
        $validated = $validation->validated();

        $maxOrder = Exercice::max('ordre_affichage') ?? 0;

        if ($validated['ordre_affichage']<=$maxOrder){
            Exercice::where('ordre_affichage', '>=', $validated['ordre_affichage'])->increment('ordre_affichage');
        }

        $exercice = new Exercice();
        $exercice->nom_exercice    = $validated['nom_exercice'];
        $exercice->texte           = $validated['texte'];
        $exercice->lien            = $validated['lien'] ?? null;
        $exercice->ordre_affichage = $validated['ordre_affichage'];
        $exercice->id_forum        = $validated['id_forum'];
        $exercice->id_categorie    = $validated['id_categorie'];


        $imagePath = $request->file('image')->store('exercices/images', 'public');
        $exercice->image = basename($imagePath);

        if ($request->hasFile('fichier')) {
            $filePath = $request->file('fichier')->store('exercices/files', 'public');
            $exercice->fichier = basename($filePath);
        }
        $exercice->save();

        return response()->json([
                'message' => 'Exercice créé avec succès',
                'id' => $exercice->id,
            ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id) : View
    {
        $userId = Auth::id();
        $exercice = Exercice::with(['forum', 'forum.messages', 'forum.messages.user', 'forum.messages.reponse'])->find($id);

        if (is_null($exercice))
            return abort(404);
        $categories=CategorieExercice::get();
        $ordreAffichages = Exercice::where('id_categorie', $exercice->id_categorie)->orderBy('ordre_affichage', 'asc')->pluck('ordre_affichage');



        return view('exercice/exercice', ['exercice'=>$exercice, 'userId' => $userId, 'ordreAffichages'=>$ordreAffichages, 'categories'=>$categories]);
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
        $validation = Validator::make($request->all(), [
            'exerciceId'=>'required|integer',
            'nom_exercice' => 'required|string|max:255',
            'texte' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:5120',
            'fichier' => 'nullable|file|max:307200',
            'lien' => 'nullable|string|max:255',
            'ordre_affichage' => 'integer|required',
            'id_categorie' => 'required|integer'
        ]);
        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);
        $validated = $validation->validated();

        $exercice = Exercice::findOrFail($validated['exerciceId']);

        $oldOrder = $exercice->ordre_affichage;
        $newOrder=$validated['ordre_affichage'];

        if ($newOrder!=$oldOrder){
            if ($newOrder>$oldOrder){
                Exercice::whereBetween('ordre_affichage', [$oldOrder+1, $newOrder])->decrement('ordre_affichage');
            }
            else if ($newOrder<$oldOrder){
                Exercice::whereBetween('ordre_affichage', [$newOrder, $oldOrder-1])->increment('ordre_affichage');
            }
            $exercice->update([
                'ordre_affichage'=>$validated['ordre_affichage']
            ]);
        }

        $forum = Forum::findOrFail($exercice->id_forum);

        $forum->update([
            'nom_forum' => $validated['nom_exercice']
        ]);

        $exercice->update([
            'nom_exercice' => $validated['nom_exercice'],
            'texte' => $validated['texte'],
            'lien' => $validated['lien'],
            'id_categorie'=>$validated['id_categorie']
        ]);

        if ($request->hasFile('image')) {
            $newFile = $request->file('image');
            $oldFilePath = 'exercices/images/' . $exercice->image;

            $same = false;

            if (Storage::disk('public')->exists($oldFilePath)) {
                $oldHash = md5_file(Storage::disk('public')->path($oldFilePath));
                $newHash = md5_file($newFile->getRealPath());
                $same = ($oldHash === $newHash);
            }

            if (!$same) {
                Storage::disk('public')->delete($oldFilePath);
                $imagePath = $newFile->store('exercices/images', 'public');
                $exercice->update([
                'image' => basename($imagePath)]);
            }
        }

        if ($request->hasFile('fichier')) {
            $newFile = $request->file('fichier');
            $oldFilePath = 'exercices/files/' . $exercice->fichier;

            $same = false;

            if (Storage::disk('public')->exists($oldFilePath)) {
                $oldHash = md5_file(Storage::disk('public')->path($oldFilePath));
                $newHash = md5_file($newFile->getRealPath());
                $same = ($oldHash === $newHash);
            }

            if (!$same) {
                Storage::disk('public')->delete($oldFilePath);
                $imagePath = $newFile->store('exercices/files', 'public');
                $exercice->update([
                'fichier' =>basename($imagePath)]);
            }
        }

        return response()->json(['message' => 'Exercice mis à jour avec succès.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        \Log::info('Delete exercice data:', $request->all());
        $validated = $request->validate([
            'id' => 'required|integer'
        ]);

        $id = $validated['id'];

        $exercice = Exercice::find($id);

        if ($exercice) {
            $exercice->forum->delete();
            if(Storage::disk('public')->exists('exercices/images/' . $exercice->image)){
                Storage::disk('public')->delete('exercices/images/' . $exercice->image);
            }
            if(Storage::disk('public')->exists('exercices/files/' . $exercice->fichier)){
                Storage::disk('public')->delete('exercices/files/' . $exercice->fichier);
            }
            $exercice->delete();
            return response()->json(['message' => 'Exercice supprimé avec succès.'], 200);
        } else {
            return response()->json(['message' => 'Exercice inexistant.'], 404);
        }
    }
}

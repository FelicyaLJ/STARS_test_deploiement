<?php

namespace App\Http\Controllers;

use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partenaires = Partenaire::all();

        return response()->json($partenaires);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        \Log::info('RIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII:', $request->all());
        //dd($request->all());
        $validation = Validator::make($request->all(), [
            'nom_partenaire' => ['required', 'max:80','regex:/^[\pL\s\-\'0-9()]+$/u'],
            'image' => ['required','max:307200', 'file', 'mimes:jpg,jpeg,png,pdf'],
            'lien' => ['nullable', 'max:255', 'regex:/^(https?:\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})(:[0-9]+)?(\/[^\s]*)?$/u' ],
            'ordre_affichage' => ['required']
        ],[
            'nom_partenaire.required' => 'Le nom du partenaiiiiiire ne peut pas être vide' ,
            'nom_partenaire.max' => 'Le nom du partenaire ne doit pas dépasser 80 caractères',
            'nom_partenaire.regex' => 'Le nom du partenaire ne peut contenir que des lettres ou des chiffres',
            'image.required' => 'Veuillez inclure une image pour le partenaire',
            'image.max' => 'L\'image ne peut pas être plus grosse que 300 MB',
            'image.mimes' => 'L\'image doit être un jpg, jpeg, ou png',
            'image.file' => 'L\'image est non valide',
            'lien.max' => 'Le lien ne peut pas être plus long que 255 caractères',
            'lien.regex' => 'Le format du lien n\'est pas valide',
            'ordre_affichage.required' => 'Aucun ordre envoyé dans le formulaire. Recharger la page ou contactez un administrateur'
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()], 400);
        }
        $contenuDecode = $validation->validated();

        try {

            $partenaire = new Partenaire();

            $partenaire->nom = $contenuDecode['nom_partenaire'];
            $partenaire->lien = $contenuDecode['lien'];

            if ($request->hasFile('image')) {
                $filePath = $request->file('image')->store('partenaires/images', 'public');
                $partenaire->image = basename($filePath);
            }

            Partenaire::where('ordre_affichage', '>=', $contenuDecode['ordre_affichage'])
                ->increment('ordre_affichage');
            $partenaire->ordre_affichage = $contenuDecode['ordre_affichage'];

            $partenaire->save();

        } catch (Throwable $erreur) {
            report($erreur);
            return response()->json([
                'error' => true,
                'errors' => $erreur,
                'message' => 'Le partenaire n\'a pas pu être créé.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Le partenaire a été créé.',
            'partenaire' =>$partenaire
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nom_partenaire' => ['required', 'max:80','regex:/^[\pL\s\-\'0-9()]+$/u'],
            'image' => ['nullable','max:307200', 'file', 'mimes:jpg,jpeg,png,pdf'],
            'lien' => ['nullable', 'max:255', 'regex:/^(https?:\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})(:[0-9]+)?(\/[^\s]*)?$/u' ],
            'ordre_affichage' => ['required'],
            'id' => ['required']
        ],[
            'nom_partenaire.required' => 'Le nom du partenaire ne peut pas être vide' ,
            'nom_partenaire.max' => 'Le nom du partenaire ne doit pas dépasser 80 caractères',
            'nom_partenaire.regex' => 'Le nom du partenaire ne peut contenir que des lettres ou des chiffres',
            'image.max' => 'L\'image ne peut pas être plus grosse que 300 MB',
            'image.mimes' => 'L\'image doit être un jpg, jpeg, ou png',
            'image.file' => 'L\'image est non valide',
            'lien.max' => 'Le lien ne peut pas être plus long que 255 caractères',
            'lien.regex' => 'Le format du lien n\'est pas valide',
            'ordre_affichage.required' => 'Aucun ordre envoyé dans le formulaire. Recharger la page ou contactez un administrateur',
            'id.required' => 'Erreur dans la création du formulaire. Veuillez réessayer ou contacter un administrateur',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()], 400);
        }
        $contenuDecode = $validation->validated();

        try {

            $partenaire = Partenaire::find($contenuDecode['id']);

            $partenaire->nom = $contenuDecode['nom_partenaire'];
            $partenaire->lien = $contenuDecode['lien'];


            if ($request->hasFile('image')) {
                $new_file = $request->file('image');
                $old_file_path = 'partenaires/images' . $partenaire->image;

                $same = false;

                if (Storage::disk('public')->exists($old_file_path)) {
                    $oldHash = md5_file(Storage::disk('public')->path($old_file_path));
                    $newHash = md5_file($new_file->getRealPath());
                    $same = ($oldHash === $newHash);
                }

                if (!$same) {
                    Storage::disk('public')->delete($old_file_path);
                    $imagePath = $new_file->store('partenaires/images', 'public');
                    $partenaire->update([
                    'image' =>basename($imagePath)]);
                }
            }

            if ($partenaire->ordre_affichage != $contenuDecode['ordre_affichage']) {
                if ($contenuDecode['ordre_affichage'] < $partenaire->ordre_affichage) {
                    Partenaire::whereBetween('ordre_affichage', [
                        $contenuDecode['ordre_affichage'],
                        $partenaire->ordre_affichage - 1
                    ])->increment('ordre_affichage');
                } else {

                    Partenaire::whereBetween('ordre_affichage', [
                        $partenaire->ordre_affichage + 1,
                        $contenuDecode['ordre_affichage']
                    ])->decrement('ordre_affichage');
                }

                $partenaire->ordre_affichage = $contenuDecode['ordre_affichage'];
            }

            $partenaire->update();

        } catch (Throwable $erreur) {
            report($erreur);
            return response()->json([
                'error' => true,
                'errors' => $erreur,
                'message' => 'Le partenaire n\'a pas pu être modifié.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Le partenaire a été modifié.',
            'partenaire' => $partenaire
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $partenaire = Partenaire::find($request->input('id'));

            if (!$partenaire) {
                return response()->json([
                    'error' => true,
                    'message' => 'Le partenaire spécifié n\'existe pas.'
                ], 200);
            }

            if ($partenaire->delete()){
                $partenaires = Partenaire::orderBy('ordre_affichage')->get();
                $i = 1;
                foreach ($partenaires as $p) {
                    $p->ordre_affichage = $i++;
                    $p->save();
                }
                if(Storage::disk('public')->exists('partenaires/' . $partenaire->image)){
                    Storage::disk('public')->delete('partenaires/' . $partenaire->image);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'La suppression du partenaire a bien fonctionné.'
                ], 200);
            }

            return response()->json([
                'error' => true,
                'message' => 'La suppression du partenaire n\'a pas fonctionné.'
            ], 500);
    }
}

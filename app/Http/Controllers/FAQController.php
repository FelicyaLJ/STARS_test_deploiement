<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FAQController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if($request->routeIs('faq_create_api')){

            $validation = Validator::make($request->all(), [
                'nom_faq' => ['required', 'max:80','regex:/^[\pL\s\-\'0-9()]+$/u'],
                'texte' => ['required', 'max:1500', 'regex:/^[\pL\s\-\',.!?0-9()]+$/u'],
                'fichier' => ['nullable','max:307200', 'file', 'mimes:jpg,jpeg,png,pdf'],
                'categorie' => ['required', 'numeric', 'gt:0'],
                'ordre' => ['required', 'gt:0'],
                'texte_html' => ['required']
            ],[
                'nom_faq.required' => 'Le nom du FAQ ne peut pas être vide' ,
                'nom_faq.max' => 'Le nom du FAQ ne doit pas dépasser 80 caractères',
                'nom_faq.regex' => 'Le nom du FAQ ne peut contenir que des lettres ou des chiffres',
                'texte.required' => 'Le texte du FAQ ne peut pas être vide',
                'texte.regex' => 'Le texte du FAQ ne peut contenir que des lettres, des chiffres ou de la ponctuation',
                'texte.max' => 'Le texte du FAQ est trop long. Essayez d\'utiliser moins de styles pour drastiquement réduire la taille du texte enregistré',
                'fichier.max' => 'Le fichier ne peut pas être plus gros que 300 MB',
                'fichier.mimes' => 'Le fichier doit être un jpg, jpeg, png, pdf',
                'fichier.file' => 'Le fichier est non valide',
                'categorie.required' => 'Veuillez choisir une catégorie pour le FAQ',
                'categorie.gt' => 'Veuillez choisir une catégorie pour le FAQ',
                'ordre.required' => 'Aucun ordre envoyé dans le formulaire. Recharger la page ou contactez un administrateur',
                'texte_html.required' => 'Problème dans l\'envoi du formulaire. Veuillez recharger la page ou contacter un administrateur'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {
                $faq = new FAQ();
                $now = Carbon::now();

                $faq->titre = $contenuDecode['nom_faq'];
                $faq->texte = $contenuDecode['texte_html'];
                $faq->id_categorie = $contenuDecode['categorie'];
                $faq->created_at = $now;
                $faq->updated_at = $now;

                if ($request->hasFile('fichier')) {
                    $filePath = $request->file('fichier')->store('faq/files', 'public');
                    $faq->fichier = basename($filePath);
                }

                FAQ::where('id_categorie', $contenuDecode['categorie'])
                    ->where('ordre_affichage', '>=', $contenuDecode['ordre'])
                    ->increment('ordre_affichage');
                $faq->ordre_affichage = $contenuDecode['ordre'];

                $faq->save();

            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'error' => true,
                    'errors' => $erreur,
                    'message' => 'Le FAQ n\'a pas pu être créé.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Le FAQ a été créé.',
                'faq' =>$faq
            ], 200);
        }
    }

    public function edit(Request $request) {

        if($request->routeIs('faq_edit_api')){

            $validation = Validator::make($request->all(), [
                'nom_faq' => ['required', 'max:80','regex:/^[\pL\s\-\'0-9()]+$/u'],
                'texte' => ['required', 'max:1500', 'regex:/^[\pL\s\-\',.!?0-9()]+$/u'],
                'fichier' => ['nullable','max:307200', 'file', 'mimes:jpg,jpeg,png,pdf'],
                'categorie' => ['required', 'numeric', 'gt:0'],
                'ordre' => ['required', 'gt:0'],
                'id' => ['required'],
                'texte_html' => ['required']
            ],[
                'nom_faq.required' => 'Le nom du FAQ ne peut pas être vide' ,
                'nom_faq.max' => 'Le nom du FAQ ne doit pas dépasser 80 caractères',
                'nom_faq.regex' => 'Le nom du FAQ ne peut contenir que des lettres ou des chiffres',
                'texte.required' => 'Le texte du FAQ ne peut pas être vide',
                'texte.regex' => 'Le texte du FAQ ne peut contenir que des lettres, des chiffres ou de la ponctuation',
                'texte.max' => 'Le texte est trop long. Utilisez moins de styles pour drastiquement réduire la taille du texte enregistré',
                'fichier.max' => 'Le fichier ne peut pas être plus gros que 300 MB',
                'fichier.mimes' => 'Le fichier doit être un jpg, jpeg, png, pdf',
                'fichier.file' => 'Le fichier est non valide',
                'categorie.required' => 'Veuillez choisir une catégorie pour le FAQ',
                'categorie.gt' => 'Veuillez choisir une catégorie pour le FAQ',
                'ordre.required' => 'Aucun ordre envoyé dans le formulaire. Recharger la page ou contactez un administrateur',
                'ordre.gt' => 'Veuillez choisir une catégorie avant de pouvoir choisir l\'ordre',
                'id.required' => 'Erreur dans la création du formulaire. Veuillez réessayer ou contacter un administrateur',
                'texte_html.required' => 'Problème dans l\'envoi du formulaire. Veuillez recharger la page ou contacter un administrateur'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {

                $faq = FAQ::find($contenuDecode['id']);
                $now = Carbon::now();

                $faq->titre = $contenuDecode['nom_faq'];
                $faq->texte = $contenuDecode['texte_html'];
                $faq->id_categorie = $contenuDecode['categorie'];
                $faq->updated_at = $now;

                if ($request->hasFile('fichier')) {
                    $new_file = $request->file('fichier');
                    $old_file_path = 'faq/files/' . $faq->fichier;

                    $same = false;

                    if (Storage::disk('public')->exists($old_file_path)) {
                        $oldHash = md5_file(Storage::disk('public')->path($old_file_path));
                        $newHash = md5_file($new_file->getRealPath());
                        $same = ($oldHash === $newHash);
                    }

                    if (!$same) {
                        Storage::disk('public')->delete($old_file_path);
                        $imagePath = $new_file->store('faq/files', 'public');
                        $faq->update([
                        'fichier' =>basename($imagePath)]);
                    }
                }

                if ($faq->ordre_affichage != $contenuDecode['ordre']) {
                    if ($contenuDecode['ordre'] < $faq->ordre_affichage) {
                        FAQ::where('id_categorie', $contenuDecode['categorie'])
                            ->whereBetween('ordre_affichage', [$contenuDecode['ordre'], $faq->ordre_affichage - 1])
                            ->increment('ordre_affichage');
                    } else {

                        FAQ::where('id_categorie', $contenuDecode['categorie'])
                            ->whereBetween('ordre_affichage', [$faq->ordre_affichage + 1, $contenuDecode['ordre']])
                            ->decrement('ordre_affichage');
                    }

                    $faq->ordre_affichage = $contenuDecode['ordre'];
                }

                $faq->update();

            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'error' => true,
                    'errors' => $erreur,
                    'message' => 'Le FAQ n\'a pas pu être modifié.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Le FAQ a été modifié.',
                'faq' => $faq
                ], 200);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->routeIs('faq_delete_api')) {

            $faq = FAQ::find($request->input('id'));

            if (!$faq) {
                return response()->json([
                    'error' => true,
                    'message' => 'La FAQ spécifiée n\'existe pas.'
                ], 200);
            }

            if ($faq->delete()){
                $faqs = FAQ::orderBy('ordre_affichage')->get();
                $i = 1;
                foreach ($faqs as $p) {
                    $p->ordre_affichage = $i++;
                    $p->save();
                }

                if(Storage::disk('public')->exists('faq/files/' . $faq->fichier)){
                    Storage::disk('public')->delete('faq/files/' . $faq->fichier);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'La suppression de la FAQ a bien fonctionné.'
                ], 200);
            }

            return response()->json([
                'error' => true,
                'message' => 'La suppression de la FAQ n\'a pas fonctionné.'
            ], 500);
        }
    }
}

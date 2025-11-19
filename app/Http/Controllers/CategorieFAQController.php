<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use App\Models\CategorieFAQ;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CategorieFAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $categories = CategorieFAQ::orderBy('ordre', 'asc')->get();

        return view('faq/faq', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if($request->routeIs('categorie_faq_create_api')){

            $validation = Validator::make($request->all(), [
                'nom_categorie' => ['required', 'max:40','regex:/^[\pL\s\-\'0-9]+$/u']
            ],[
                'nom_categorie.required' => 'Le nom de la catégorie ne peut pas être vide' ,
                'nom_categorie.max' => 'Le nom de la catégorie ne doit pas dépasser 40 caractères',
                'nom_categorie.regex' => 'Le nom de la catégorie ne peut contenir que des lettres ou des chiffres'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {
                $categorie_faq = new CategorieFAQ();
                $categorie_faq->nom_categorie = $contenuDecode['nom_categorie'];

                $ordre_max = CategorieFAQ::max('ordre');
                $categorie_faq->ordre = $ordre_max !== null ? $ordre_max + 1 : 1;

                $categorie_faq->save();

            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'error' => true,
                    'errors' => $erreur,
                    'message' => 'La catégorie de FAQ n\'a pas pu être créée.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'La catégorie de FAQ a été créée.',
                'categorie_faq' => $categorie_faq
            ], 200);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        if($request->routeIs('categorie_faq_edit_api')){

            $validation = Validator::make($request->all(), [
                'nom_categorie' => ['required', 'max:40','regex:/^[\pL\s\-\'0-9]+$/u'],
                'ordre' => ['required', 'gt:0'],
                'id' => ['required']
            ],[
                'nom_categorie.required' => 'Le nom de la catégorie ne peut pas être vide' ,
                'nom_categorie.max' => 'Le nom de la catégorie ne doit pas dépasser 40 caractères',
                'nom_categorie.regex' => 'Le nom de la catégorie ne peut contenir que des lettres ou des chiffres',
                'ordre.required' => 'Aucun ordre envoyé dans le formulaire. Recharger la page ou contactez un administrateur',
                'ordre.gt' => 'Veuillez choisir une catégorie avant de pouvoir choisir l\'ordre',
                'id.required' => 'Erreur dans la création du formulaire. Veuillez réessayer ou contacter un administrateur'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {
                $categorie_faq = CategorieFAQ::find($contenuDecode['id']);
                $categorie_faq->nom_categorie = $contenuDecode['nom_categorie'];

                if ($categorie_faq->ordre != $contenuDecode['ordre']) {
                    if ($contenuDecode['ordre'] < $categorie_faq->ordre) {
                        CategorieFAQ::whereBetween('ordre', [$contenuDecode['ordre'], $categorie_faq->ordre - 1])
                            ->increment('ordre');
                    } else {

                        CategorieFAQ::whereBetween('ordre', [$categorie_faq->ordre + 1, $contenuDecode['ordre']])
                            ->decrement('ordre');
                    }

                    $categorie_faq->ordre = $contenuDecode['ordre'];
                }

                $categorie_faq->update();

            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'error' => true,
                    'errors' => $erreur,
                    'message' => 'La catégorie de FAQ n\'a pas pu être modifiée.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'La catégorie de FAQ a été modifiée.',
                'categorie_faq' => $categorie_faq
            ], 200);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->routeIs('categorie_faq_delete_api')) {

            $cat_faq = CategorieFAQ::find($request->input('id'));
            $contien_des_faqs = FAQ::where('id_categorie', $cat_faq->id)->exists();
            $nombre_faq = CategorieFAQ::count();

            if (!$cat_faq) {
                return response()->json([
                    'error' => true,
                    'message' => 'La catégorie FAQ spécifiée n\'existe pas.'
                ], 200);
            }

            if ($contien_des_faqs) {
                return response()->json([
                    'error' => true,
                    'message' => 'Cette catégorie possède encore des articles FAQ.'
                ], 422);
            }

            if($nombre_faq <= 1) {
                return response()->json([
                    'error' => true,
                    'message' => 'On peut pas supprimer toutes les catégories FAQ.'
                ], 422);
            }

            if ($cat_faq->delete()){
                $categories_faqs = CategorieFAQ::orderBy('ordre')->get();
                    $i = 1;
                    foreach ($categories_faqs as $p) {
                        $p->ordre = $i++;
                        $p->save();
                    }
                return response()->json([
                    'success' => true,
                    'message' => 'La suppression de la catégorie FAQ a bien fonctionné.'
                ], 200);
            }

            return response()->json([
                'error' => true,
                'message' => 'La suppression de la catégorie FAQ n\'a pas fonctionné.'
            ], 500);
        }
    }
}

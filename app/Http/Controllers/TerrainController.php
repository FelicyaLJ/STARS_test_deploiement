<?php

namespace App\Http\Controllers;

use App\Models\Terrain;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class TerrainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->routeIs('terrain')){

            return view('terrain.terrain', [
                'terrains' => Terrain::All()
            ]);
        }
        else if($request->routeIs('fetch_terrain')){

            return response()->json([
                'terrains' => Terrain::All()
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if($request->routeIs('terrain_create_api')){

            $validation = Validator::make($request->all(), [
                'nom_terrain' => ['required', 'max:80','regex:/^[\pL\s\-\'0-9]+$/u'],
                'description' => ['nullable', 'max:255','regex:/^[\pL\s\-\',.!?0-9()]+$/u'],
                'latitude' => ['nullable','max:9', 'regex:/(^\d{2}[.]\d{6}$)|^[0]$/', 'required_with:longitude'],
                'longitude' => ['nullable','max:10', 'regex:/(^\d{2}[.]\d{6}$)|^[0]$/', 'required_with:latitude'],
                'adresse_rue' => ['required','max:60', 'regex:/^[0-9\pL\s\-\']+$/u'],
                'adresse_ville' => ['required','max:80', 'regex:/^[\pL\s\-\']+$/u'],
                'adresse_postal' => ['required','max:7', 'regex:/^[A-Z]\d[A-Z][-]\d[A-Z]\d$/'],
                'terrain_visible' => ['required', 'boolean'],
                'terrain_couleur' => ['required', 'string', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
                'etat_terrain_parent' => ['nullable']
            ],[
                'nom_terrain.required' => 'Le nom du terrain ne peut pas être vide' ,
                'nom_terrain.max' => 'Le nom du terrain ne doit pas dépasser 80 caractères',
                'nom_terrain.regex' => 'Le nom du terrain ne peut contenir que des lettres, des chiffres ou de la ponctuation',
                'description.max' => 'La description ne doit pas dépasser 255 caractères',
                'description.regex' => 'La description ne peut contenir que des lettres, des chiffres ou de la ponctuation',
                'longitude.max' => 'La longitude ne devrait pas être plus long que 10 caractères en tout',
                'longitude.regex' => 'La longitude doit suivre le format suivant : -48.000000',
                'latitude.regex' => 'La longitude doit suivre le format suivant : 73.000000',
                'latitude.max' => 'La latitude ne devrait pas être plus long que 9 caractères en tout',
                'adresse_rue.required' => 'L\'adresse de rue ne peut pas être vide',
                'adresse_rue.max' => 'L\'adresse de rue ne doit pas être plus longue que 60 caractères',
                'adresse_rue.regex' => 'L\'adresse de rue ne peut pas contenir de caractères spéciaux',
                'adresse_ville.required' => 'Le nom de la de ville ne peut pas être vide',
                'adresse_ville.max' => 'Le nom de la ville ne doit pas être plus longue que 80 caractères',
                'adresse_ville.regex' => 'La nom de la ville doit commencer par une majuscule ne peut pas contenir de caractères spéciaux',
                'adresse_postal.required' => 'Le code postal ne peut pas être vide',
                'adresse_postal.max' => 'Le code postal ne doit pas être plus longue que 7 caractères',
                'adresse_postal.regex' => 'Le code postal doit suivre le format suivant J1L-1W6',
                'terrain_couleur.required' => 'Il faut associer une couleur au terrain',
                'terrain_couleur.regex' => 'La couleur doit être entre #000000 et #FFFFFF, au format hexadécimal.',
                'longitude.required_with' => 'La longitude doit toujours être accompagnée d\'une valeur de latitude',
                'latitude.required_with' => 'La latitude doit toujours être accompagnée d\'une valeur de longitude',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {
                $terrain = new Terrain();
                $terrain->nom_terrain = $contenuDecode['nom_terrain'];
                $terrain->description = $contenuDecode['description'];
                $terrain->adresse = $contenuDecode['adresse_rue'] . ', ' . $contenuDecode["adresse_ville"] . ', QC, ' . $contenuDecode['adresse_postal'];
                $terrain->couleur = $contenuDecode['terrain_couleur'];
                $terrain->visible = $contenuDecode['terrain_visible'];

                if($contenuDecode['etat_terrain_parent'] == 0 || $contenuDecode['etat_terrain_parent'] == null)
                    $terrain->id_parent = null;
                else
                    $terrain->id_parent = $contenuDecode['etat_terrain_parent'];

                if($contenuDecode['longitude'] == 0 || $contenuDecode['latitude'] == 0) {
                    $terrain->latitude = null;
                    $terrain->longitude = null;
                }
                else {
                    $terrain->longitude = "-" . $contenuDecode['longitude'];
                    $terrain->latitude = $contenuDecode['latitude'];
                }

                $terrain->save();

            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'error' => true,
                    'errors' => $erreur,
                    'message' => 'Le terrain n\'a pas pu être créé.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Le terrain a été créé.',
                'nouveau_terrain' => $terrain
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        if($request->routeIs('terrain_edit_api')){

            $validation = Validator::make($request->all(), [
                'nom_terrain' => ['required', 'max:80','regex:/^[\pL\s\-\'0-9]+$/u'],
                'description' => ['nullable', 'max:255','regex:/^[\pL\s\-\',.!?0-9()]+$/u'],
                'latitude' => ['nullable','max:9', 'regex:/(^\d{2}[.]\d{6}$)|^[0]$/', 'required_with:longitude'],
                'longitude' => ['nullable','max:10', 'regex:/(^\d{2}[.]\d{6}$)|^[0]$/', 'required_with:latitude'],
                'adresse_rue' => ['required','max:60', 'regex:/^[0-9\pL\s\-\']+$/u'],
                'adresse_ville' => ['required','max:80', 'regex:/^[\pL\s\-\']+$/u'],
                'adresse_postal' => ['required','max:7', 'regex:/^[A-Z]\d[A-Z][-]\d[A-Z]\d$/'],
                'terrain_visible' => ['required', 'boolean'],
                'terrain_couleur' => ['required', 'string', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
                'etat_terrain_parent' => ['nullable'],
                'id' => ['required']
            ],[
                'nom_terrain.required' => 'Le nom du terrain ne peut pas être vide' ,
                'nom_terrain.max' => 'Le nom du terrain ne doit pas dépasser 80 caractères',
                'nom_terrain.regex' => 'Le nom du terrain ne peut contenir que des lettres et de la ponctuation',
                'description.max' => 'La description ne doit pas dépasser 255 caractères',
                'description.regex' => 'La description ne peut contenir que des lettres, des chiffres et de la ponctuation',
                'longitude.max' => 'La longitude ne devrait pas être plus long que 10 caractères en tout',
                'longitude.regex' => 'La longitude doit suivre le format suivant : -48.000000',
                'latitude.regex' => 'La longitude doit suivre le format suivant : 73.000000',
                'latitude.max' => 'La latitude ne devrait pas être plus long que 9 caractères en tout',
                'adresse_rue.required' => 'L\'adresse de rue ne peut pas être vide',
                'adresse_rue.max' => 'L\'adresse de rue ne doit pas être plus longue que 60 caractères',
                'adresse_rue.regex' => 'L\'adresse de rue ne peut pas contenir de caractères spéciaux',
                'adresse_ville.required' => 'Le nom de la de ville ne peut pas être vide',
                'adresse_ville.max' => 'Le nom de la ville ne doit pas être plus longue que 80 caractères',
                'adresse_ville.regex' => 'La nom de la ville doit commencer par une majuscule ne peut pas contenir de caractères spéciaux',
                'adresse_postal.required' => 'Le code postal ne peut pas être vide',
                'adresse_postal.max' => 'Le code postal ne doit pas être plus longue que 7 caractères',
                'adresse_postal.regex' => 'Le code postal doit suivre le format suivant J1L-1W6',
                'terrain_couleur.required' => 'Il faut associer une couleur au terrain',
                'terrain_couleur.regex' => 'La couleur doit être entre #000000 et #FFFFFF, au format hexadécimal.',
                'longitude.required_with' => 'La longitude doit toujours être accompagnée d\'une valeur de latitude',
                'latitude.required_with' => 'La latitude doit toujours être accompagnée d\'une valeur de longitude',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {
                $terrain = Terrain::find($contenuDecode['id']);
                $terrain->nom_terrain = $contenuDecode['nom_terrain'];
                $terrain->description = $contenuDecode['description'];

                $adresse = $contenuDecode['adresse_rue'] . ', ' . $contenuDecode["adresse_ville"] . ', QC, ' . $contenuDecode['adresse_postal'];
                $terrain->adresse = $adresse;
                $terrain->couleur = $contenuDecode['terrain_couleur'];
                $terrain->visible = $contenuDecode['terrain_visible'];

                if($contenuDecode['longitude'] == 0 || $contenuDecode['latitude'] == 0) {
                    $terrain->latitude = null;
                    $terrain->longitude = null;
                }
                else {
                    $terrain->longitude = "-" . $contenuDecode['longitude'];
                    $terrain->latitude = $contenuDecode['latitude'];
                }

                if($contenuDecode['etat_terrain_parent'] == 0)
                    $terrain->id_parent = null;
                else
                    $terrain->id_parent = $contenuDecode['etat_terrain_parent'];

                $terrain->update();

                $terrains_enfant = Terrain::where('id_parent', $contenuDecode['id'])->get();

                foreach($terrains_enfant as $terrain_enfant) {
                    $terrain_enfant->adresse = $adresse;
                    $terrain_enfant->update();
                }

            } catch (Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'error' => true,
                    'errors' => $erreur,
                    'message' => 'Le terrain n\'a pas pu être modifié.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Le terrain a été modifié.',
                'terrain' => $terrain
            ], 200);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->routeIs('terrain_delete_api')) {

            $terrain = Terrain::find($request->input('id'));
            $hasFutureReservations = Evenement::where('id_terrain', $terrain->id)
                ->where('date', '>=', now()->toDateString())
                ->exists();
            $isTerrainParent = Terrain::where('id_parent', $request->input('id'))->exists();

            if (!$terrain) {
                return response()->json([
                    'error' => true,
                    'message' => 'Le terrain spécifié n\'existe pas.'
                ], 200);
            }

            if ($hasFutureReservations) {
                return response()->json([
                    'error' => true,
                    'message' => 'Ce terrain possède des réservations futures.'
                ], 422);
            }

            if ($isTerrainParent) {
                return response()->json([
                    'error' => true,
                    'message' => 'Ce terrain est un terrain parent.'
                ], 422);
            }

            if ($terrain->delete()){
                return response()->json([
                    'success' => true,
                    'message' => 'La suppression du terrain a bien fonctionné.'
                ], 200);
            }

            return response()->json([
                'error' => true,
                'message' => 'La suppression du terrain n\'a pas fonctionné.'
            ], 500);
        }

    }

    public function getTerrainsByDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->input('date');
        $terrains = Terrain::getTerrainsStatusForDate($date);

        return response()->json([
            'date' => $date,
            'terrains' => $terrains,
        ]);
    }

    public function getMonthReservations(Request $request) : JsonResponse
    {
        $year = $request->query('year');
        $month = $request->query('month');

        // Build start/end of month
        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate)); // last day of month

        // Fetch all events in that month
        $reservedDates = Terrain::getTerrainsInRange($startDate, $endDate);

        return response()->json([
            'reservedDates' => $reservedDates
        ]);
    }
}

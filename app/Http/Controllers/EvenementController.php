<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Terrain;
use App\Models\CategorieEvenement;
use App\Models\EtatEvenement;
use App\Models\Equipe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EvenementController extends Controller
{

    /**
     *
     */
    public function show(Request $request)
    {
        $filter = $request->query('filter', 'upcoming'); // default
        $query = Evenement::with(['etat', 'terrain', 'categorie', 'equipes'])
            ->where('id_etat', '!=', 4)
            ->orderBy('date', 'asc');

        $evenements = $filter === 'past'
            ? $query->past()->paginate($request->input('perPage', 10))
            : $query->upcoming()->paginate($request->input('perPage', 10));

        return view('evenement.evenement', [
            'evenements'  => $evenements,
            'filter'      => $filter,
            'etats'       => EtatEvenement::all(),
            'categories'  => CategorieEvenement::all(),
            'terrains'    => Terrain::all(),
            'equipes'     => Equipe::all(),
        ]);
    }

    /**
     *
     */
    public function create(Request $request)
    {
        if($request->routeIs('evenements.create.api')){

            //dd($request->all());

            $validation = Validator::make($request->all(), [
                'nom_evenement' => ['required', 'max:255', 'regex:/^[\pL\s\-\'0-9]+$/u'],
                'description' => ['nullable', 'max:255', 'regex:/^[\pL\s\-\'.!0-9,.()]+$/u'],
                'type_evenement' => ['required', 'in:simple,recurrent'],

                // événement simple
                'date' => [Rule::requiredIf($request->type_evenement === 'simple'), 'nullable', 'date', 'after_or_equal:today'],

                // événement recurrent
                'date_debut' => [Rule::requiredIf($request->type_evenement === 'recurrent'), 'nullable', 'date', 'after_or_equal:today'],
                'date_fin' => [Rule::requiredIf($request->type_evenement === 'recurrent'), 'nullable', 'date', 'after_or_equal:date_debut'],
                'jours' => $request->type_evenement === 'recurrent'
                    ? ['required', 'array', 'min:1']
                    : ['nullable', 'array'],
                'jours.*' => ['in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],

                'heure_debut' => ['required', 'date_format:H:i', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
                'heure_fin' => ['required' , 'date_format:H:i', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/', 'after:heure_debut'],
                'categorie_evenement' => ['required', 'not_in:0'],
                'terrain_evenement' => ['not_in:0'],
                'prix_evenement' => ['nullable', 'regex:/(^\d{1,3}[.,]\d{0,2})$|^[0]$/'],
                'equipes' => ['nullable'],
                'id_etat'=> ['integer']
                ],[
                'nom_evenement.required' => 'Le nom de l\'évenement ne peut pas être vide',
                'nom_evenement.max' => 'Le nom de l\'évenement ne peut pas être plus long que 255 caractères spéciaux',
                'nom_evenement.regex' => 'Le nom de l\'évènement ne peut contenir que des lettres, des chiffres et de la ponctuation',
                'description.max' => 'La description ne peut pas être plus longue que 255 caractères',
                'description.regex' => 'La description ne peut contenir que des lettres, des chiffres et de la ponctuation',
                'date.required' => 'La date de l\'événement est obligatoire.',
                'date.date' => 'La date doit avoir un format valide (AAAA-MM-JJ).',
                'date.after_or_equal' => 'L\'évenement doit avoir lieu dans une date future',
                'date_debut.required' => 'La date de début est obligatoire pour un événement récurrent.',
                'date_debut.after_or_equal' => 'L\'évenement doit avoir lieu dans une date future',
                'date_fin.required' => 'La date de fin est obligatoire pour un événement récurrent.',
                'date_fin.after_or_equal' => 'La date de fin doit être avant ou égale à la date de début.',
                'jours.required' => 'Veuillez choisir au moins un jour pour un événement récurrent.',
                'jours.min' => 'Veuillez sélectionner au moins un jour de la semaine.',
                'heure_debut.required' => 'L\'heure de début ne peut pas être vide',
                'heure_debut.date_format' => 'L\'heure de départ doit être au format HH:MM.',
                'heure_debut.regex' => 'L\'heure de départ doit être au format HH:MM, entre 00:00 et 23:59.',
                'heure_fin.required' => 'L\'heure de la fin ne peut pas être vide',
                'heure_fin.date_format' => 'L\'heure de la fin doit être au format HH:MM.',
                'heure_fin.regex' => 'L\'heure de la fin doit être au format HH:MM, entre 00:00 et 23:59.',
                'heure_fin.after' => 'L\'heure de la fin doit être après l\'heure du départ',
                'categorie_evenement.required' => 'La catégorie de l\'évenement ne peut pas être vide',
                'categorie_evenement.not_in' => 'Veuillez choisir une catégorie pour l\'évènement',
                'terrain_evenement.required' => 'L\'évenement doit avoir lieu sur un terrain',
                'terrain_evenement.not_in' => 'Veuillez choisir un terrain pour l\'évènement',
                'heure_debut.required' => 'La date ne peut pas être vide',
                'id_etat.integer'=> 'L\'ID doit être numérique'
            ]);

            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 400);
            }

            try {
                $contenuDecode = $validation->validated();

                if ($request->type_evenement === 'recurrent') {
                    $start = Carbon::parse($contenuDecode['date_debut']);
                    $end = Carbon::parse($contenuDecode['date_fin']);
                    $jours = $contenuDecode['jours'];

                    $dates = [];
                    foreach (CarbonPeriod::create($start, $end) as $date) {
                        if (in_array(strtolower($date->englishDayOfWeek), $jours)) {
                            $dates[] = $date;
                        }
                    }

                    if (empty($dates)) {
                        return response()->json([
                            'errors' => true,
                            'message' => 'Aucune date ne correspond aux jours choisis dans cette plage.'
                        ], 422);
                    }

                    $evenements = [];
                    foreach ($dates as $date) {
                        $evenement = new Evenement();
                        $evenement->nom_evenement = $contenuDecode['nom_evenement'];
                        if (isset($contenuDecode['description'])) {
                            $evenement->description = $contenuDecode['description'];
                        }
                        $evenement->date = $date->format('Y-m-d');
                        $evenement->heure_debut = $contenuDecode['heure_debut'];
                        $evenement->heure_fin = $contenuDecode['heure_fin'];
                        $evenement->id_categorie = $contenuDecode['categorie_evenement'];
                        $evenement->id_etat = $contenuDecode['id_etat'] ?? 1;

                        if (isset($contenuDecode['terrain_evenement'])) {
                            $evenement->id_terrain = $contenuDecode['terrain_evenement'];
                        }
                        if (isset($contenuDecode['prix_evenement'])) {
                            $evenement->prix = str_replace(',', '.', $contenuDecode['prix_evenement']);
                        }

                        $evenement->save();

                        if ($request->equipes !== null) {
                            $evenement->equipes()->attach($request->equipes);
                        }

                        $evenements[] = $evenement;
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Les évènements récurrents ont été créés.',
                        'evenements' => $evenements
                    ], 200);
                }
                else {
                    $evenement = new Evenement();
                    $evenement->nom_evenement = $contenuDecode['nom_evenement'];
                    if(isset($contenuDecode['description'])) {$evenement->description = $contenuDecode['description'];}
                    $evenement->date = $contenuDecode['date'];
                    $evenement->heure_debut = $contenuDecode['heure_debut'];
                    $evenement->heure_fin = $contenuDecode['heure_fin'];
                    if (isset($contenuDecode['prix_evenement'])) $evenement->prix = str_replace(',', '.', $contenuDecode['prix_evenement']);
                    $evenement->id_categorie = $contenuDecode['categorie_evenement'];
                    if (isset($contenuDecode['terrain_evenement'])){
                        $evenement->id_terrain = $contenuDecode['terrain_evenement'];
                    }
                    if (isset($contenuDecode['id_etat'])){
                        $evenement->id_etat = $contenuDecode['id_etat'];
                    }
                    else{
                        $evenement->id_etat = '1';
                    }

                     if($evenement->save() && $request->equipes !== null) {
                        $evenement->refresh();
                        $evenement->equipes()->attach($request->equipes);
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'L\'évènement a été créé.',
                        'evenement' => $evenement
                    ], 200);
                }

            } catch (\Throwable $erreur) {
                report($erreur);

                return response()->json([
                    'errors' => true,
                    'message' => 'L\'évènement n\'a pas pu être créé.',
                    'error_type' => get_class($erreur),
                    'error_message' => $erreur->getMessage(),
                    'error_file' => $erreur->getFile(),
                    'error_line' => $erreur->getLine(),
                ], 500);
            }

        }
    }

    public function edit(Request $request)
    {
        \Log::info('Store request data:', $request->all());
        if($request->routeIs('evenements.edit.api')){

            $validation = Validator::make($request->all(), [
                'nom_evenement' => ['required', 'max:255', 'regex:/^[\pL\s\-\'0-9]+$/u'],
                'description' => ['nullable', 'max:255', 'regex:/^[\pL\s\-\'.!0-9,.]+$/u'],
                'date' => ['required', 'date', 'after_or_equal:today'],
                'heure_debut' => ['required', 'date_format:H:i', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
                'heure_fin' => ['required' , 'date_format:H:i', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/', 'after:heure_debut'],
                'categorie_evenement' => ['required', 'not_in:0'],
                'terrain_evenement' => ['not_in:0'],
                'prix_evenement' => ['nullable', 'regex:/(^\d{1,3}[.,]\d{0,2})$|^[0]$/'],
                'repetition_evenement' => ['nullable'],
                'id' => ['required'],
                'equipes' => ['nullable']
            ],[
                'nom_evenement.required' => 'Le nom de l\'évenement ne peut pas être vide',
                'nom_evenement.max' => 'Le nom de l\'évenement ne peut pas être plus long que 255 caractères spéciaux',
                'nom_evenement.regex' => 'Le nom de l\'évènement ne peut contenir que des lettres, des chiffres et de la ponctuation',
                'description.max' => 'La description ne peut pas être plus longue que 255 caractères',
                'description.regex' => 'La description ne peut contenir que des lettres, des chiffres et de la ponctuation',
                'date.required' => 'La date ne peut pas être vide',
                'date.date' => 'La date doit avoir un format valide (AAAA-MM-JJ).',
                'date.after_or_equal' => 'L\'évenement doit avoir lieu dans une date future',
                'heure_debut.required' => 'L\'heure de début ne peut pas être vide',
                'heure_debut.date_format' => 'L\'heure de départ doit être au format HH:MM.',
                'heure_debut.regex' => 'L\'heure de départ doit être au format HH:MM, entre 00:00 et 23:59.',
                'heure_fin.required' => 'L\'heure de la fin ne peut pas être vide',
                'heure_fin.date_format' => 'L\'heure de la fin doit être au format HH:MM.',
                'heure_fin.regex' => 'L\'heure de la fin doit être au format HH:MM, entre 00:00 et 23:59.',
                'heure_fin.after' => 'L\'heure de la fin doit être après l\'heure du départ',
                'categorie_evenement.required' => 'La catégorie de l\'évenement ne peut pas être vide',
                'categorie_evenement.not_in' => 'Veuillez choisir une catégorie pour l\'évènement',
                'terrain_evenement.not_in' => 'Veuillez choisir un terrain pour l\'évènement',
                'heure_debut.required' => 'La date ne peut pas être vide',
                'prix_evenement.regex' => 'Le prix de l\'évenement doit suivre ce format 20.00 ou être égal à 0',
                'id.required' => 'Problème avec l\'envoi du formulaire. Veuillez contacter un administrateur'
            ]);
            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 400);
            }
            $contenuDecode = $validation->validated();

            try {

                $evenement = Evenement::find($contenuDecode['id']);

                $evenement->nom_evenement = $contenuDecode['nom_evenement'];
                if (isset($contenuDecode['description'])) {$evenement->description = $contenuDecode['description'];}
                $evenement->date = $contenuDecode['date'];
                $evenement->heure_debut = $contenuDecode['heure_debut'];
                $evenement->heure_fin = $contenuDecode['heure_fin'];
                if (isset($contenuDecode['prix_evenement'])) $evenement->prix = str_replace(',', '.', $contenuDecode['prix_evenement']);
                $evenement->id_categorie = $contenuDecode['categorie_evenement'];
                if (isset($contenuDecode['terrain_evenement'])){
                    $evenement->id_terrain = $contenuDecode['terrain_evenement'];
                }

                $evenement->update();
                if($request->equipes !== null) {
                    $evenement->equipes()->sync($request->equipes);
                }
            } catch (\Throwable $erreur) {
                report($erreur);
                return response()->json([
                    'errors' => true,
                    'error' => $erreur,
                    'message' => 'L\'évènement n\'a pas pu être modifié.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'L\'évènement a été modifié.',
                'evenement' => $evenement
            ], 200);
        }
    }

    public function destroy(Request $request)
    {
        if($request->routeIs('evenements.delete.api')) {

            $evenement = Evenement::find($request->input('id'));

            if (!$evenement) {
                return response()->json([
                    'error' => true,
                    'message' => 'L\'évènement spécifié n\'existe pas.'
                ], 200);
            }

            $evenement->demandesInscription()->delete();

            if ($evenement->delete()){
                return response()->json([
                    'success' => true,
                    'message' => 'La suppression de l\'évènement a bien fonctionné.'
                ], 200);
            }

            return response()->json([
                'error' => true,
                'message' => 'La suppression de l\'évènement n\'a pas fonctionné.'
            ], 500);
        }
    }

    /**
     *
     */
    public function search(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'search'    => 'nullable|regex:/^[\pL\pN\s@._-]*$/u',
            'terrains'    => 'nullable|array',
            'terrains.*'  => 'integer|between:0,7',
            'etats'     => 'nullable|array',
            'etats.*'   => 'integer',
            'categories'     => 'nullable|array',
            'categories.*'   => 'integer|exists:categorie_evenement,id',
        ], [
            'search.regex' => 'Les charactères spéciaux ne sont pas permis dans la recherche'
        ]);

        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        $validated = $validation->validated();

        $filter = $request->query('filter', 'upcoming');
        $query = Evenement::query();

        // Recherche nom événement
        $search = $validated['search'] ?? null;
        if ($search) {
            $query->where('nom_evenement', 'like', "%{$search}%");
        }

        if (!empty($validated['etats'])) {
            $query->whereHas('etat', fn($q) => $q->whereIn('id', $validated['etats']));
        }

        if (!empty($validated['terrains'])) {
            $query->whereHas('terrain', fn($q) => $q->whereIn('id', $validated['terrains']));
        }

        if (!empty($validated['categories'])) {
            $query->whereHas('categorie', fn($q) => $q->whereIn('id', $validated['categories']));
        }

        $query = $filter === 'past' ? $query->past() : $query->upcoming();

        $evenements = $query->orderBy('date', 'asc');

        $perPage = $request->input('perPage', 10);
        $page    = $request->input('page', 1);

        return response()->json(
            $query->paginate($perPage, ['*'], 'page', $page)
        );
    }

    /**
     *
     */
    public function render(Request $request)
    {
        $ids = $request->input('evenements', []);

        if (empty($ids)) {
            $evenements = collect();
        } else {
            $idsString = implode(',', $ids);
            $evenements = Evenement::with(['etat', 'terrain', 'categorie'])
                ->whereIn('id', $ids)
                ->where('id_etat', "!=", 4)
                ->orderByRaw("FIELD(id, $idsString)") // preserve order from /search
                ->get();
        }

        $html = view('partials.evenements-list', compact('evenements'))->render();

        return response($html);
    }

    /**
     *
     */
    public function getEvenementsByDate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $date = $validated['date'];
        $perPage = $request->input('perPage', 10);
        $page    = $request->input('page', 1);

        $query = Evenement::forDate($date)
            ->where('id_etat', '!=', 4)
            ->orderBy('heure_debut', 'asc');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'date' => $date,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
            'evenements' => $paginated->items(),
        ]);
    }

    /**
     *
     */
    public function getMonthEvenements(Request $request) : JsonResponse
    {
        $year = $request->query('year');
        $month = $request->query('month');

        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $reservedDates = Evenement::getEvenementsInRange($startDate, $endDate, true)->where("id_etat", "!=", 4);

        return response()->json([
            'reservedDates' => $reservedDates
        ]);
    }

    /**
     * Retourne les terrains disponibles pour une date et heure donnée
     */
    public function getAvailableTerrains(Request $request)
    {
        $date = $request->input('date');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $heureDebut = $request->input('heure_debut');
        $heureFin = $request->input('heure_fin');
        $jours = $request->input('jours', []);

        if ($dateDebut && $dateFin) {
            $terrains = Terrain::getAvailableTerrainsInRange($dateDebut, $dateFin, $heureDebut, $heureFin, $jours)
                ->where('visible', true)
                ->sortBy('nom_terrain')
                ->values();
        } elseif ($date) {
            $terrains = Terrain::getAvailableTerrains($date, $heureDebut, $heureFin)
                ->where('visible', true)
                ->sortBy('nom_terrain')
                ->values();
        } else {
            return response()->json(['error' => 'Paramètres manquants.'], 400);
        }

        return response()->json($terrains);
    }

}

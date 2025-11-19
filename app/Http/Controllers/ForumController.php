<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\DemandeAdhesion;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Mail\courrielDemandeAdhesion;
use App\Mail\confirmationAjout;
use Illuminate\Support\Facades\Mail;
use Exception;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $userId = Auth::id();

        $forums = Forum::whereHas('membres', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->with(['membres', 'messages', 'messages.user', 'messages.reponse.user'])->get();
        $autresForums = Forum::whereDoesntHave('membres', function ($query) use ($userId) {
            $query->where('id',$userId);
        }) ->whereDoesntHave('demandesAdhesion', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        }) ->has('membres') ->with(['membres'])->get();

        $mods = User::whereHas('roles.permissions', function ($query) {
            $query->where('nom_permission', 'gestion_forums');
        })->get();

        return view('forum/forums', [
            'forums' => $forums,
            'equipes' => Equipe::select('id', 'nom_equipe')->get(),
            'userId' => $userId,
            'autresForums'=>$autresForums,
            'mods'=>$mods,
        ]);
    }

    public function getMessages($id)
    {
        // Retrieve the forum with all its messages and user info
        $forum = Forum::with(['messages.user', 'messages.reponse.user'])
            ->findOrFail($id);

        // Return only the messages (with relationships)
        return response()->json($forum);
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
        /*\Log::info('Store request data:', $request->all());*/
        $validation = Validator::make($request->all(), [
            'nom_forum' => 'required|string|max:255|regex:/^[\da-zA-Z.() ]+$/u',
            'membres' => 'array'
        ], [
            'nom_forum.regex' => 'Les charactères spéciaux ne sont pas permis dans le nom',
        ]);
        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        $validated = $validation->validated();
        $forum = new Forum();
        $forum->nom_forum = $validated['nom_forum'];
        $forum->save();
        $forum->membres()->attach($validated['membres']);

        foreach ($forum->membres as $user){
                $demande = DemandeAdhesion::where('id_user', $user->id)->where('id_forum', $forum->id)->get();
            if ($demande->isNotEmpty()){
                foreach ($demande as $d) {
                    $d->delete();
                }
                Mail::to($user->email)->send(new confirmationAjout($user, $forum));
            }
        }

        return response()->json([
            'message' => 'Forum créé avec succès',
            'id' => $forum->id,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id) : View
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $forum = Forum::find($id);

        if (!$forum) {
            return response()->json(['error' => 'Le forum demandé est introuvable.'], 404);
        }
        $forum->load(['membres']);

        return response()->json($forum);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        /*\Log::info('Store request data:', $request->all());*/
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:forum,id',
            'nom_forum' => 'required|string|max:255|regex:/^[\da-zA-Z.() ]+$/u',
            'membres' => 'array|required'
        ], [
            'nom_forum.regex' => 'Les caractères spéciaux ne sont pas permis dans le nom',
            'membres.required' => 'Un forum ne peut pas être vide',
        ]);
        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        $validated = $validation->validated();

        $forum = Forum::findOrFail($validated['id']);

        $forum->update([
            'nom_forum' => $validated['nom_forum']
        ]);

        $forum->membres()->sync($validated['membres']);
        foreach ($forum->membres as $user){
                $demande = DemandeAdhesion::where('id_user', $user->id)->where('id_forum', $forum->id)->get();
            if ($demande->isNotEmpty()){
                foreach ($demande as $d) {
                    $d->delete();
                }
                Mail::to($user->email)->send(new confirmationAjout(user: $user, forum: $forum));
            }
        }
        return response()->json([
            'message' => 'Forum mis à jour avec succès',
            'forum' => $forum->load('membres'),
        ]);
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        \Log::info('Delete request data:', $request->all());

        $validated = $request->validate([
            'id' => 'required|integer'
        ]);

        $id = $validated['id'];

        $forum = Forum::find($id);

        if ($forum) {
            $forum->membres()->detach();
            $forum->demandesAdhesion()->delete();
            $forum->delete();
            return response()->json(['message' => 'Forum supprimé avec succès.'], 200);
        } else {
            return response()->json(['message' => 'Forum inexistant.'], 404);
        }
    }

    public function envoyerDemandeAdhesion(Request $request, Forum $forum){
        $user = auth()->user();
        $raison = $request->input('raison');

        try {
            $controller = new DemandeAdhesionController();
            $demandeAdhesion=$controller->store($user->id, $forum->id, $raison);
            Mail::to('admin@example.com')->send(new courrielDemandeAdhesion($forum, $user, $raison, $demandeAdhesion->id));
            return back()->with('success', 'Votre demande a été envoyée à l’administrateur.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de l’envoi de la demande : ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('erreur', 'Une erreur est survenue lors de l’envoi de la demande. Veuillez réessayer plus tard.');
        }
    }

    public function addUser($idMembre, $idForum){
        try{
            $user = User::findOrFail($idMembre);
            $forum = Forum::findOrFail($idForum);
            $forum->membres()->attach($idMembre);

            $demande = DemandeAdhesion::where('id_user', $idMembre)->where('id_forum', $forum->id)->get();
            if ($demande->isNotEmpty()){
                foreach ($demande as $d) {
                    $d->delete();
                }
                Mail::to($user->email)->send(new confirmationAjout(user: $user, forum: $forum));
            }
            return response()->json([
            'message' => 'Forum mis à jour avec succès',
            ]);
        }catch (\Exception $e){
                return response()->json(['error' => 'L\'utilisateur n\'a pas pu être ajouté: ' . $e->getMessage()], 404);
        }

    }


}

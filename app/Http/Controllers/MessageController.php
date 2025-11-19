<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Mail\signalement;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
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
         \Log::info('Store request data for message:', $request->all());
        $validation = Validator::make($request->all(), [
            'texte' => 'required|string|max:1000',
            'id_forum' => 'integer|required',
            'id_reponse' => 'integer',
        ], [
            'texte.required' => 'Le message ne peut pas être vide',
            'texte.string' => 'Format invalide pour le message',
            'texte.max' => 'Message trop long',
            'id_forum.required' => 'Le forum doit être spécifié',
            'id_forum.integer' => 'Format invalide pour l\'identifiant du forum',
            'id_reponse.integer' => 'Format invalide pour la réponse',
        ]);
        if ($validation->fails())
            return response()->json(['errors' => $validation->errors()], 400);

        $validated = $validation->validated();
        $message = new Message();
        $message->texte = $validated['texte'];

        $userId = Auth::id();
        $message->id_user = $userId;

        $message->id_forum = $validated['id_forum'];

        if (isset($validated['id_reponse'])){
            $message->id_reponse = $validated['id_reponse'];
        }

        $message->save();

         return response()->json([
        'message' => 'Message envoyé',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        \Log::info('Delete message request data:', $request->all());
        $validated = $request->validate([
            'id' => 'required|integer'
        ]);

        $id = $validated['id'];

        $message = Message::find($id);

        if ($message){
            Message::where('id_reponse', $id)->update(['id_reponse' => null]);
            $message->delete();
            return response()->json(['message' => 'Message supprimé avec succès.'], 200);
        } else {
            return response()->json(['message' => 'Message inexistant.'], 404);
        }
    }

    public function signaler(Request $request, Message $message)
    {
        try {
             $user = auth()->user();
            $raison = $request->input('raisonSignalement');
            Mail::to('admin@example.com')->send(new signalement($message, $user, $raison));
            return back()->with('success', 'Votre signalement a été envoyé à l’administrateur.');
        } catch (Exception $e) {
            \Log::error('Erreur lors de l’envoi du signalement : ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('erreur', 'Une erreur est survenue lors de l’envoi du signalement. Veuillez réessayer plus tard.');
        }

    }

}

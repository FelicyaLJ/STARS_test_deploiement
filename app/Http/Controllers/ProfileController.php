<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse // Possibilité de modifier ProfileUpdateRequest
    {
        /**
         *  $request->user()->fill($request->validated());

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
         */


        $validation = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'prenom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'tel' => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
            'nam' => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
        ], [
            'nom.required' => 'Le nom est requis.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
            'prenom.max' => 'Le prenom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'Le format de l\'adresse e-mail est invalide ou le domaine n\'existe pas.',
            'email.unique' => 'Cette adresse courriel est déjà utilisée.',
            'tel.regex' => 'Le format du numéro de téléphone est invalide.',
            'nam.regex' => 'Le format du numéro d\'assurance maladie est invalide.',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation->errors())->withInput();
        }

        $contenuFormulaire = $validation->validated();

        $user = $request->user();

        $user->nom = $contenuFormulaire['nom'];
        $user->prenom = $contenuFormulaire['prenom'];
        $user->email = $contenuFormulaire['email'];
        if (isset($contenuFormulaire['tel'])) {
            $user->no_telephone = $contenuFormulaire['tel'];
        }
        if (isset($contenuFormulaire['nam'])) {
            $user->no_telephone = $contenuFormulaire['nam'];
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

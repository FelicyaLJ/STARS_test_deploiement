<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Role;
use App\Mail\NewRegisteredUser;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validation = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'prenom' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'max:255', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'tel' => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
            'nam' => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
            'captcha' => ['required', 'captcha'],
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
            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'Les mots de passe entrés ne correspondent pas.',
            'password.password' => 'Le mot de passe doit contenir au moins 8 caractères, des lettres majuscules et minuscules, des chiffres et un caractère spécial.',
            'tel.regex' => 'Le format du numéro de téléphone est invalide.',
            'nam.regex' => 'Le format du numéro d\'assurance maladie est invalide.',
            'captcha.required'  => 'Le captcha est requis.',
            'captcha.captcha'   => 'Le captcha est invalide.',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation->errors())->withInput();
        }

        $contenuFormulaire = $validation->validated();

        $user = User::create([
            'nom' => $contenuFormulaire['nom'],
            'prenom' => $contenuFormulaire['prenom'],
            'email' => $contenuFormulaire['email'],
            'password' => Hash::make($contenuFormulaire['password']),
            'no_telephone' => $contenuFormulaire['tel'] ?? null,
            'nam' => $contenuFormulaire['nam'] ?? null,
            'id_etat' => 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        Mail::to('admin@example.com')->send(new NewRegisteredUser($user));

        return redirect()
            ->route('accueil')
            ->with('success', 'Un courriel de vérification a été envoyé à votre addresse!');
    }
}

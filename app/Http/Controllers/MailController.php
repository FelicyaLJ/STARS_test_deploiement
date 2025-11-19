<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\Contact;
use App\Mail\ContactCandidature;
use App\Models\Poste;
use Exception;

class MailController extends Controller
{
    /**
     *
     */
    public function send(Request $request) {
        $rules = [
            'nom'       => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'prenom'    => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'email'     => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'tel'       => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
            'addresse'  => ['nullable', 'regex:/^(?=.*\d)(?=.*[A-Za-z])[\dA-Za-z\s.,\'#\/()\-]{5,100}$/'],
            'sujet'     => ['required', 'string', 'min:3', 'max:150', 'regex:/^[^<>$@#%^*{}]+$/u'],
            'message'   => ['required', 'string', 'min:10', 'max:2000'],
        ];

        // Captcha seulement si pas de user connecté
        if (!Auth::check()) {
            $rules['captcha'] = 'required|captcha';
        }

        $messages = [
            'nom.required'      => 'Le nom est requis.',
            'nom.regex'         => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'nom.max'           => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required'   => 'Le prénom est requis.',
            'prenom.regex'      => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
            'prenom.max'        => 'Le prenom ne peut pas dépasser 255 caractères.',
            'email.required'    => 'L\'adresse courriel est requise.',
            'email.email'       => 'Le format de l\'adresse courriel est invalide ou le domaine n\'existe pas.',
            'tel.regex'         => 'Le format du numéro de téléphone est invalide.',
            'addresse.regex'    => 'Le format de l\'adresse est invalide.',
            'sujet.required'    => 'Le sujet est requis.',
            'sujet.min'         => 'Le sujet doit contenir au moins 3 caractères.',
            'sujet.max'         => 'Le sujet ne peut pas dépasser 150 caractères.',
            'sujet.regex'       => 'Le sujet contient des caractères non autorisés.',
            'message.required'  => 'Le message est requis.',
            'message.min'       => 'Le message doit contenir au moins 10 caractères.',
            'message.max'       => 'Le message ne peut pas dépasser 2000 caractères.',
            'captcha.required'  => 'Le captcha est requis.',
            'captcha.captcha'   => 'Le captcha est invalide.',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        $contenuFormulaire = $validation->validated();

        $prenom   = $request->input('prenom');
        $nom      = $request->input('nom');
        $email    = $request->input('email');
        $tel      = $request->input('tel');
        $addresse = $request->input('addresse');
        $sujet    = $request->input('sujet');
        $message  = $request->input('message');

        try {
            Mail::to('admin@example.com')->send(
                new Contact($prenom, $nom, $email, $tel, $addresse, $sujet, $message)
            );

            return back()->with('success', 'Votre courriel a été envoyé à l\'administrateur.');
        } catch (Exception $e) {
            return back()->with('erreur', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.');
        }

    }

    public function send_candidature(Request $request) {
        $rules = [
            'nom'       => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'prenom'    => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'email'     => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'tel'       => ['nullable', 'regex:/^(?:((\d{3}(-|\.)){2}\d{4})|(\(\d{3}\) ?\d{3}-\d{4})|(\d{10}))$/'],
            'addresse'  => ['nullable', 'regex:/^(?=.*\d)(?=.*[A-Za-z])[\dA-Za-z\s.,\'#\/()\-]{5,100}$/'],
            'poste_id'  => 'required|exists:poste,id',
            'cv'        => 'required|file|mimes:pdf,doc,docx|max:4096',
            'message'   => ['nullable', 'string', 'min:10', 'max:2000'],
        ];

        // Captcha seulement si pas de user connecté
        if (!Auth::check()) {
            $rules['captcha'] = 'required|captcha';
        }

        $messages = [
            'nom.required'      => 'Le nom est requis.',
            'nom.regex'         => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'nom.max'           => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required'   => 'Le prénom est requis.',
            'prenom.regex'      => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
            'prenom.max'        => 'Le prenom ne peut pas dépasser 255 caractères.',
            'email.required'    => 'L\'adresse courriel est requise.',
            'email.email'       => 'Le format de l\'adresse courriel est invalide ou le domaine n\'existe pas.',
            'tel.regex'         => 'Le format du numéro de téléphone est invalide.',
            'addresse.regex'    => 'Le format de l\'adresse est invalide.',
            'poste_id.required' => 'Veuillez sélectionner un poste.',
            'poste_id.exists'   => 'Le poste sélectionné est invalide.',
            'cv.required'       => 'Le CV est requis.',
            'cv.mimes'          => 'Le CV doit être un fichier PDF, DOC ou DOCX.',
            'cv.max'            => 'Le fichier ne peut pas dépasser 4MB.',
            'adresse.regex'     => 'Le format de l’adresse est invalide.',
            'message.min'       => 'Le message doit contenir au moins 10 caractères.',
            'message.max'       => 'Le message ne peut pas dépasser 2000 caractères.',
            'captcha.required'  => 'Le captcha est requis.',
            'captcha.captcha'   => 'Le captcha est invalide.',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        // Récupération des données validées
        $data = $validation->validated();

        try {
            // Traitement du CV
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('candidatures_cv', 'public');
            }

            $poste = Poste::find($request->poste_id);
            $nomPoste = $poste ? $poste->nom_poste : null;

            // Envoi du mail
            Mail::to('admin@example.com')->send(
                new ContactCandidature(
                    $data['prenom'],
                    $data['nom'],
                    $data['email'],
                    $data['tel'] ?? null,
                    $data['adresse'] ?? null,
                    $nomPoste,
                    $data['message'],
                    $cvPath
                )
            );

            return back()->with('success', 'Votre courriel a été envoyé à l\'administrateur.');
        } catch (Exception $e) {
            return back()->with('erreur', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.');
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Role;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $role = Role::where('nom_role', 'Utilisateur')->first();

        if ($role) {
            $request->user()->roles()->syncWithoutDetaching([$role->id]);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()
                ->intended(route('accueil', absolute: false).'?verified=1')
                ->with('success', 'Votre courriel à bien été vérifié!');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()
            ->intended(route('accueil', absolute: false).'?verified=1')
            ->with('success', 'Votre courriel à bien été vérifié!');
    }
}

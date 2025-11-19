<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        if (! session()->has('url.intended')) {
            $previousUrl = url()->previous();

            if ($previousUrl
                && $previousUrl !== route('login')
                && $previousUrl !== route('register')
                && parse_url($previousUrl, PHP_URL_HOST) === $request->getHost()) {

                $request->session()->put('url.intended', $previousUrl);
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();

        if ($user->id_etat === 3) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('erreur', 'Votre compte a été banni. Vous ne pouvez pas vous connecter.');
        }

        $user->update([
            'last_login_at' => now(),
            'id_etat' => 1,
        ]);

        // Redirect to intended URL or home
        return redirect()->intended('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $previousUrl = url()->previous();

        // Vérifier si on a le même hôte pour les routes
        $host = parse_url($previousUrl, PHP_URL_HOST);
        if ($host && $host !== $request->getHost()) {
            $route = null;
        } else {
            try {
                $prevRequest = Request::create($previousUrl, 'GET');
                $route = app('router')->getRoutes()->match($prevRequest);
            } catch (\Throwable $e) {
                $route = null;
            }
        }

        // Vérifier si route prend une permission
        $requiresAuthOrPermission = false;
        if ($route) {
            $middlewares = $route->gatherMiddleware();

            $blockingPatterns = [
                'auth',
                'can',
                'permission',
                'verified',
                'authorize',
            ];

            foreach ($middlewares as $m) {
                $mLower = strtolower($m);
                foreach ($blockingPatterns as $p) {
                    if (str_contains($mLower, $p)) {
                        $requiresAuthOrPermission = true;
                        break 2;
                    }
                }

                if (str_contains($m, 'Authenticate') ||
                    str_contains($m, 'Permission') ||
                    str_contains($m, 'Ensure') ||
                    str_contains($m, 'Authorize')) {
                    $requiresAuthOrPermission = true;
                    break;
                }
            }
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($route && ! $requiresAuthOrPermission) {
            return redirect()->to($previousUrl);
        }

        return redirect('/');
    }
}

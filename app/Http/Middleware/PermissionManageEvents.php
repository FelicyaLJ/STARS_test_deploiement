<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionManageEvents
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $utilisateur = $request->user();

        if ($utilisateur !== null)
        {
            $hasPermission = $utilisateur->roles()
                             ->whereHas('permissions', function ($q) {
                                 $q->where('nom_permission', 'gestion_evenements');
                             })->exists();

            if ($hasPermission)
            {
                return $next($request);
            }
        }

        return redirect()
            ->route('accueil')
            ->with('erreur', 'Vous devez être administrateur pour accéder à cette page.');
    }
}

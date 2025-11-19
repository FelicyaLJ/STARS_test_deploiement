<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionManageUsersOrForums
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

            if ($utilisateur->hasPermission('gestion_users') || $utilisateur->hasPermission('gestion_forums'))
            {
                return $next($request);
            }
        }

        return redirect()
            ->route('accueil')
            ->with('erreur', 'Vous devez être administrateur pour accéder à cette page.');

    }
}

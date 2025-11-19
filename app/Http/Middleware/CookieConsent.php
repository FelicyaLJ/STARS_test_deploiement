<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CookieConsent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookie = $request->cookie('cookie_consent');
        $consent = null;

        if ($cookie !== null) {
            $data = json_decode($cookie, true);

            if (is_array($data) && isset($data['status'])) {
                $consent = $data['status'] === 'accepted';
            }
        }

        view()->share('cookie_consent', $consent);

        return $next($request);
    }
}

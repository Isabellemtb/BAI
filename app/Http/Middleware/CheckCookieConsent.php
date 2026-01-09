<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCookieConsent
{
    /**
     * Gérer une requête entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (Auth::check()) {
            $user = Auth::user();

            // Si cookie_consent est NULL (pas de choix fait)
            if ($user->cookie_consent === null) {
                // Ne pas rediriger si déjà sur la page de consentement
                if (!$request->is('cookie-consent')) {
                    return redirect()->route('cookie.consent');
                }
            }
        }

        return $next($request);
    }
}

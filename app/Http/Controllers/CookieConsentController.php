<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieConsentController extends Controller
{
    /**
     * Afficher la page du bandeau de consentement.
     */
    public function show()
    {
        return view('cookie-consent');
    }

    /**
     * Enregistrer le choix de l'utilisateur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'consent' => 'required|boolean'
        ]);

        $user = auth()->user();
        $user->cookie_consent = $request->consent;
        $user->save();

        return redirect()->route('ideas.index');
    }
}

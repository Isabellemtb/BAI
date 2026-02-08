<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistrement des services de l'application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Amorçage des services de l'application.
     *
     * Configure les règles de mot de passe par défaut selon les recommandations ANSSI :
     * - Minimum 12 caractères
     * - Au moins une majuscule et une minuscule
     * - Au moins un chiffre
     * - Au moins un caractère spécial
     * - Vérification contre les mots de passe compromis (Have I Been Pwned)
     */
    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }
}

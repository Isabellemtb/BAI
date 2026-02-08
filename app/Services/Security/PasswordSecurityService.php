<?php

namespace App\Services\Security;

/**
 * Service dédié à la validation de la sécurité des mots de passe.
 *
 * Implémente les règles ANSSI :
 * - Longueur minimale de 12 caractères
 * - Au moins une lettre majuscule
 * - Au moins une lettre minuscule
 * - Au moins un chiffre
 * - Au moins un caractère spécial
 * - Rejet des mots de passe courants
 */
class PasswordSecurityService
{
    /**
     * Liste de mots de passe courants à rejeter.
     * La vérification complète est assurée par Password::uncompromised()
     * via l'API Have I Been Pwned.
     */
    private const COMMON_PASSWORDS = [
        'password', '123456', '12345678', '123456789', '1234567890',
        'qwerty', 'abc123', 'azerty', 'password1', 'admin',
        'letmein', 'welcome', 'monkey', 'master', 'dragon',
        'login', 'princess', 'football', 'shadow', 'sunshine',
        'trustno1', 'iloveyou', 'batman', 'access', 'hello',
    ];

    /**
     * Valide la robustesse d'un mot de passe selon les recommandations ANSSI.
     *
     * Règles appliquées :
     * - Longueur minimale : 12 caractères
     * - Au moins une majuscule
     * - Au moins une minuscule
     * - Au moins un chiffre
     * - Au moins un caractère spécial
     * - Pas de mot de passe courant ou trivial
     *
     * @return array<string> Liste des erreurs (vide = valide)
     */
    public function validatePasswordStrength(string $password): array
    {
        $errors = [];

        if (mb_strlen($password) < 12) {
            $errors[] = 'Le mot de passe doit contenir au moins 12 caractères (recommandation ANSSI).';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre majuscule.';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre minuscule.';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial.';
        }

        if (in_array(mb_strtolower($password), self::COMMON_PASSWORDS, true)) {
            $errors[] = 'Ce mot de passe est trop courant et ne peut pas être utilisé.';
        }

        return $errors;
    }
}

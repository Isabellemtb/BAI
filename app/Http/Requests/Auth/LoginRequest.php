<?php

namespace App\Http\Requests\Auth;

use App\Services\Logging\ActionLogService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Nombre maximal de tentatives avant verrouillage temporaire.
     */
    private const MAX_ATTEMPTS = 3;

    /**
     * Durée du verrouillage en secondes (60 secondes).
     */
    private const DECAY_SECONDS = 60;

    /**
     * Détermine si la requête est autorisée.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation de la requête de connexion.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Tente d'authentifier les identifiants de la requête.
     *
     * En cas d'échec :
     * - Incrémente le compteur de tentatives (rate limiter)
     * - Journalise la tentative échouée via ActionLogService
     * - Renvoie un message d'erreur générique (pas d'indication sur le champ erroné)
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), self::DECAY_SECONDS);

            // Journalisation de la tentative échouée
            app(ActionLogService::class)->log(
                userId: null,
                action: 'login_failed',
                request: $this,
                dataAfter: json_encode(['email' => $this->input('email')]),
            );

            // Message générique : ne pas indiquer si c'est l'email ou le mot de passe qui est incorrect
            throw ValidationException::withMessages([
                'email' => 'Identifiants incorrects.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Journalisation de la connexion réussie
        app(ActionLogService::class)->log(
            userId: Auth::id(),
            action: 'login_success',
            request: $this,
        );
    }

    /**
     * Vérifie que la requête n'est pas limitée par le rate limiter.
     *
     * Après MAX_ATTEMPTS (3) tentatives échouées, le compte est verrouillé
     * temporairement pendant DECAY_SECONDS (60) secondes.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        // Journalisation du verrouillage
        app(ActionLogService::class)->log(
            userId: null,
            action: 'login_locked',
            request: $this,
            dataAfter: json_encode(['email' => $this->input('email')]),
        );

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Trop de tentatives de connexion. Réessayez dans ' . $seconds . ' secondes.',
        ]);
    }

    /**
     * Clé de rate limiting basée sur l'email et l'adresse IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}

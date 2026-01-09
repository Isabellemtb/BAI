<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    {{-- SECURITY NOTE:
         No CSP, no security headers → intentional vulnerabilities !!!!!!!!!!!! n--}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sandbox</title>

    {{-- Tailwind (from Breeze build) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

{{-- Top navigation bar --}}
<nav class="bg-white shadow mb-6">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between">

        <div class="flex space-x-4">
            <a href="{{ route('ideas.index') }}" class="font-bold">Ideas</a>
            <a href="{{ route('logs.index') }}">Logs</a>
            <a href="{{ route('redirect.vulnerable', ['url' => 'https://google.com']) }}">
                Open Redirect Test
            </a>
        </div>

        <div class="flex space-x-4">
            @auth
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>

    </div>
</nav>

{{-- Main content section --}}
<main class="max-w-7xl mx-auto px-4 mb-12 flex-1">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-gray-800 text-white py-4">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center text-sm">
            <span>© 2026 BAI Sandbox</span>

            <button onclick="document.getElementById('rgpdModal').style.display='flex'" class="hover:underline cursor-pointer">
                Vie privée / Charte RGPD
            </button>

            @auth
                {{-- Bouton toggle on/off pour les cookies --}}
                <div class="flex items-center space-x-3">
                    <span>Gérer mes cookies</span>
                    <button onclick="document.getElementById('cookieModal').style.display='flex'"
                            style="position: relative; width: 50px; height: 26px; border-radius: 13px; cursor: pointer; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: all 0.3s;
                            @if(auth()->user()->cookie_consent === 1)
                                background-color: #22c55e;
                            @else
                                background-color: #ef4444;
                            @endif">
                        <span style="position: absolute; top: 3px;
                                     @if(auth()->user()->cookie_consent === 1)
                                         left: 27px;
                                     @else
                                         left: 3px;
                                     @endif
                                     width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: left 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.3);"></span>
                    </button>
                </div>
            @endauth
        </div>
    </div>
</footer>

{{-- Modal de gestion des cookies --}}
@auth
<div id="cookieModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.75); align-items: center; justify-content: center; z-index: 1000;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; margin: 20px;">
        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 15px; color: #333;">Gestion des cookies</h2>

        @if(auth()->user()->cookie_consent === 1)
            <p style="color: green; margin-bottom: 20px;">✓ Vous avez accepté les cookies</p>
        @else
            <p style="color: red; margin-bottom: 20px;">✗ Vous avez refusé les cookies</p>
        @endif

        <p style="margin-bottom: 20px; color: #666;">Vous pouvez changer votre choix à tout moment :</p>

        <form method="POST" action="{{ route('cookie.consent.store') }}" style="margin-bottom: 15px;">
            @csrf
            <button type="submit" name="consent" value="1"
                    style="width: 48%; display: inline-block; background-color: green; color: white; padding: 12px; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; margin-right: 2%;">
                Accepter
            </button>
            <button type="submit" name="consent" value="0"
                    style="width: 48%; display: inline-block; background-color: red; color: white; padding: 12px; font-weight: bold; border: none; border-radius: 8px; cursor: pointer;">
                Refuser
            </button>
        </form>

        <button onclick="document.getElementById('cookieModal').style.display='none'"
                style="width: 100%; background-color: #ccc; color: #333; padding: 10px; border: none; border-radius: 8px; cursor: pointer; margin-top: 10px;">
            Fermer
        </button>
    </div>
</div>
@endauth

{{-- Modal RGPD --}}
<div id="rgpdModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.75); align-items: center; justify-content: center; z-index: 1000; overflow-y: auto;">
    <div style="background: white; padding: 40px; border-radius: 10px; max-width: 800px; margin: 40px 20px; max-height: 90vh; overflow-y: auto;">
        <h2 style="font-size: 28px; font-weight: bold; margin-bottom: 20px; color: #333;">Politique de confidentialité et protection des données</h2>

        <div style="color: #555; line-height: 1.8; font-size: 15px;">
            <p style="margin-bottom: 20px;">
                BAI s'engage à protéger vos données personnelles conformément au Règlement Général sur la Protection des Données (RGPD).
                Cette politique explique comment nous collectons, utilisons et protégeons vos informations.
            </p>

            <h3 style="font-size: 20px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #333;">1. Identité du responsable de traitement</h3>
            <p style="margin-bottom: 15px;">
                <strong>Responsable du traitement :</strong> BTSSIOBloc3.com<br>
                <strong>Contact :</strong> contact@BTSSIOBloc3.com
            </p>

            <h3 style="font-size: 20px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #333;">2. Données collectées</h3>
            <p style="margin-bottom: 10px;">Dans le cadre de l'utilisation de la Boîte à Idées, nous collectons les données suivantes :</p>
            <ul style="margin-left: 25px; margin-bottom: 15px; line-height: 2;">
                <li><strong>Données d'identification :</strong> nom, prénom, adresse email</li>
                <li><strong>Données de connexion :</strong> logs de connexion, adresse IP</li>
                <li><strong>Données relatives aux idées :</strong> contenu des idées soumises, commentaires, votes</li>
                <li><strong>Préférences :</strong> consentement aux cookies, paramètres de confidentialité</li>
            </ul>

            <h3 style="font-size: 20px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #333;">3. Finalités du traitement</h3>
            <p style="margin-bottom: 10px;">Vos données personnelles sont collectées et traitées pour les finalités suivantes :</p>
            <ul style="margin-left: 25px; margin-bottom: 15px; line-height: 2;">
                <li><strong>Gestion de votre compte :</strong> création et authentification de votre compte utilisateur</li>
                <li><strong>Fonctionnement de la plateforme :</strong> permettre la soumission, la consultation et le partage d'idées</li>
                <li><strong>Communication :</strong> vous informer des activités liées à vos idées (commentaires, votes)</li>
                <li><strong>Amélioration du service :</strong> analyser l'utilisation de la plateforme pour l'améliorer</li>
                <li><strong>Sécurité :</strong> prévenir les abus et garantir la sécurité de la plateforme</li>
            </ul>

            <h3 style="font-size: 20px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #333;">4. Durée de conservation</h3>
            <p style="margin-bottom: 10px;">Vos données sont conservées pendant les durées suivantes :</p>
            <ul style="margin-left: 25px; margin-bottom: 15px; line-height: 2;">
                <li><strong>Données de compte :</strong> tant que votre compte est actif</li>
                <li><strong>Idées et commentaires :</strong> 3 ans après la dernière activité</li>
                <li><strong>Logs de connexion :</strong> 12 mois maximum</li>
                <li><strong>Données supprimées :</strong> suppression définitive sous 30 jours après demande</li>
            </ul>

            <h3 style="font-size: 20px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #333;">5. Vos droits</h3>
            <p style="margin-bottom: 10px;">Conformément au RGPD (articles 15 à 22), vous disposez des droits suivants :</p>
            <ul style="margin-left: 25px; margin-bottom: 15px; line-height: 2;">
                <li><strong>Droit d'accès :</strong> obtenir une copie de vos données personnelles</li>
                <li><strong>Droit de rectification :</strong> corriger vos données inexactes ou incomplètes</li>
                <li><strong>Droit à l'effacement :</strong> demander la suppression de vos données</li>
                <li><strong>Droit à la limitation :</strong> limiter le traitement de vos données</li>
                <li><strong>Droit à la portabilité :</strong> récupérer vos données dans un format structuré</li>
                <li><strong>Droit d'opposition :</strong> vous opposer au traitement de vos données</li>
                <li><strong>Droit de retirer votre consentement :</strong> retirer votre consentement aux cookies à tout moment</li>
            </ul>

            <h3 style="font-size: 20px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #333;">6. Contact pour exercer vos droits</h3>
            <p style="margin-bottom: 15px;">
                Pour exercer vos droits ou pour toute question concernant le traitement de vos données personnelles, vous pouvez nous contacter :
            </p>
            <p style="margin-bottom: 10px;">
                <strong>Email :</strong> contact@BTSSIOBloc3.com<br>
                <strong>Responsable :</strong> BTSSIOBloc3.com
            </p>

            <h3 style="font-size: 20px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #333;">7. Sécurité des données</h3>
            <p style="margin-bottom: 15px;">
                Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour garantir la sécurité de vos données
                et protéger celles-ci contre toute destruction, perte, altération, divulgation ou accès non autorisé.
            </p>

            <p style="margin-top: 20px; font-size: 13px; color: #999;">
                Dernière mise à jour : {{ date('d/m/Y') }}
            </p>
        </div>

        <button onclick="document.getElementById('rgpdModal').style.display='none'"
                style="width: 100%; background-color: #4F46E5; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; margin-top: 30px; font-weight: bold;">
            J'ai compris
        </button>
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentement aux cookies</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<!-- Overlay bloquant -->
<div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">

    <!-- Modal du bandeau -->
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-2xl mx-4">
        <h2 class="text-2xl font-bold mb-4">Consentement aux cookies</h2>

        <p class="text-gray-700 mb-6">
            Ce site utilise des cookies pour améliorer votre expérience.
            Veuillez choisir si vous acceptez ou refusez l'utilisation de cookies.
        </p>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-sm text-gray-700">
                <strong>Si vous acceptez :</strong> Nous collecterons des données pour améliorer nos services.<br>
                <strong>Si vous refusez :</strong> Aucune donnée ne sera collectée.
            </p>
        </div>

        <form method="POST" action="{{ route('cookie.consent.store') }}">
            @csrf

            <button type="submit" name="consent" value="1"
                    style="width: 48%; display: inline-block; background-color: green; color: white; padding: 15px; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; margin-right: 2%;">
                Accepter les cookies
            </button>

            <button type="submit" name="consent" value="0"
                    style="width: 48%; display: inline-block; background-color: red; color: white; padding: 15px; font-weight: bold; border: none; border-radius: 8px; cursor: pointer;">
                Refuser les cookies
            </button>
        </form>

        <p class="text-xs text-gray-500 mt-4 text-center">
            Vous devez faire un choix pour continuer à utiliser l'application.
        </p>
    </div>

</div>

</body>
</html>

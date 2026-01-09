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
    <div class="max-w-7xl mx-auto px-4 text-center">
        <div class="flex justify-center space-x-6 text-sm">
            @auth
                <button onclick="document.getElementById('cookieModal').style.display='flex'" class="hover:underline">
                    Gérer mes cookies
                </button>
            @endauth
            <span>© 2026 BAI Sandbox</span>
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

</body>
</html>

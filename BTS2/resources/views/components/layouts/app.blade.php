<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Suivi des absences' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('accueil') }}">Suivi<span>.</span></a>
        <nav class="site-nav" aria-label="Navigation principale">
            <a class="{{ request()->routeIs('absence.*') ? 'active' : '' }}" href="{{ route('absence.index') }}">Absences</a>
            <a class="{{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">Utilisateurs</a>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-button" type="submit">Se déconnecter</button>
                </form>
            @endauth
        </nav>
    </header>
    <main class="page-shell">{{ $slot }}</main>
</body>
</html>
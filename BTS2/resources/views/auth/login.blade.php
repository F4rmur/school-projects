@extends('layouts.layout')

@section('title', 'Connexion')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Espace sécurisé</p>
            <h1>Connexion</h1>
            <p class="intro">Accédez au suivi des absences.</p>
        </div>
    </div>

    <form class="form-panel panel" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-field">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="checkbox-field">
            <label for="remember">
                <input id="remember" name="remember" type="checkbox" value="1">
                Se souvenir de moi
            </label>
        </div>

        <div class="form-actions">
            <button class="button-link" type="submit">Se connecter</button>
        </div>
    </form>
@endsection

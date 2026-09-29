@extends('layouts.layout')

@section('title', 'Créer un compte')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Nouveau compte</p>
            <h1>Créer un compte</h1>
            <p class="intro">Rejoignez le suivi des absences.</p>
        </div>
    </div>

    <form class="form-panel panel" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-row">
            <div class="form-field">
                <label for="prenom">Prénom</label>
                <input id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" required autofocus autocomplete="given-name">
                @error('prenom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="nom">Nom</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required autocomplete="family-name">
                @error('nom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-field">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" required autocomplete="new-password">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="password_confirmation">Confirmation</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
            </div>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('login') }}">Déjà un compte ? Se connecter</a>
            <button class="button-link" type="submit">Créer le compte</button>
        </div>
    </form>
@endsection

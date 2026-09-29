@extends('layouts.layout')

@section('title', __('ui.auth.register'))

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.auth.register_eyebrow') }}</p>
            <h1>{{ __('ui.auth.register') }}</h1>
            <p class="intro">{{ __('ui.auth.register_intro') }}</p>
        </div>
    </div>

    <form class="form-panel panel" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-row">
            <div class="form-field">
                <label for="prenom">{{ __('ui.auth.first_name') }}</label>
                <input id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" required autofocus autocomplete="given-name">
                @error('prenom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="nom">{{ __('ui.auth.last_name') }}</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required autocomplete="family-name">
                @error('nom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-field">
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="password">{{ __('ui.auth.password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="password_confirmation">{{ __('ui.auth.password_confirmation') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
            </div>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('login') }}">{{ __('ui.auth.already_registered') }} {{ __('ui.auth.login') }}</a>
            <button class="button-link" type="submit">{{ __('ui.auth.create_account') }}</button>
        </div>
    </form>
@endsection

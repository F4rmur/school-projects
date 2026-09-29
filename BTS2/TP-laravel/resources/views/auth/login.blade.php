@extends('layouts.layout')

@section('title', __('ui.auth.login'))

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.auth.secure_area') }}</p>
            <h1>{{ __('ui.auth.login') }}</h1>
            <p class="intro">{{ __('ui.auth.login_intro') }}</p>
        </div>
    </div>

    <form class="form-panel panel" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-field">
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="password">{{ __('ui.auth.password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="checkbox-field">
            <label for="remember">
                <input id="remember" name="remember" type="checkbox" value="1">
                {{ __('ui.auth.remember_me') }}
            </label>
        </div>

        <div class="form-actions">
            <button class="button-link" type="submit">{{ __('ui.auth.login') }}</button>
        </div>
    </form>
@endsection

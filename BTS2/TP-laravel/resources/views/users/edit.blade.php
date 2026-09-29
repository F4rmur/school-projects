<x-layouts.app :title="__('ui.users.edit_title')">
    <a class="back-link" href="{{ route('user.show', $user) }}">&larr; {{ __('ui.users.back_to_profile') }}</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.users.team') }}</p>
            <h1>{{ __('ui.users.edit_title') }}</h1>
            <p class="intro">{{ __('ui.users.edit_intro') }}</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('user.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-field">
                <label for="prenom">{{ __('ui.auth.first_name') }}</label>
                <input id="prenom" name="prenom" type="text" value="{{ old('prenom', $user->prenom) }}" required autofocus>
                @error('prenom') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-field">
                <label for="nom">{{ __('ui.auth.last_name') }}</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom', $user->nom) }}" required>
                @error('nom') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-field">
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="sexe">{{ __('ui.users.gender') }}</label>
            <select id="sexe" name="sexe" required>
                <option value="">{{ __('ui.common.select') }}</option>
                <option value="homme" @selected(old('sexe', $user->sexe) === 'homme')>{{ __('ui.users.male') }}</option>
                <option value="femme" @selected(old('sexe', $user->sexe) === 'femme')>{{ __('ui.users.female') }}</option>
            </select>
            @error('sexe') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="role">{{ __('ui.users.role') }}</label>
            <select id="role" name="role" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(old('role', $currentRole) === $role->name)>{{ trans()->has('ui.roles.names.'.$role->name) ? __('ui.roles.names.'.$role->name) : ($role->title ?? ucfirst($role->name)) }}</option>
                @endforeach
            </select>
            @error('role') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password">{{ __('ui.users.new_password') }}</label>
            <input id="password" name="password" type="password" autocomplete="new-password">
            @error('password') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">{{ __('ui.users.confirm_new_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('user.show', $user) }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ __('ui.common.save') }}</button>
        </div>
    </form>
</x-layouts.app>
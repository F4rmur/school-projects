<x-layouts.app :title="__('ui.users.create_title')">
    <a class="back-link" href="{{ route('user.index') }}">&larr; {{ __('ui.users.all') }}</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.users.team') }}</p>
            <h1>{{ __('ui.users.create_title') }}</h1>
            <p class="intro">{{ __('ui.users.create_intro') }}</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('user.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-field">
                <label for="prenom">{{ __('ui.auth.first_name') }}</label>
                <input id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" required autofocus>
                @error('prenom') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-field">
                <label for="nom">{{ __('ui.auth.last_name') }}</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required>
                @error('nom') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-field">
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            @error('email') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="sexe">{{ __('ui.users.gender') }}</label>
            <select id="sexe" name="sexe" required>
                <option value="">{{ __('ui.common.select') }}</option>
                <option value="homme" @selected(old('sexe') === 'homme')>{{ __('ui.users.male') }}</option>
                <option value="femme" @selected(old('sexe') === 'femme')>{{ __('ui.users.female') }}</option>
            </select>
            @error('sexe') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="role">{{ __('ui.users.role') }}</label>
            <select id="role" name="role" required>
                <option value="">{{ __('ui.users.select_role') }}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(old('role', 'utilisateur') === $role->name)>{{ trans()->has('ui.roles.names.'.$role->name) ? __('ui.roles.names.'.$role->name) : ($role->title ?? ucfirst($role->name)) }}</option>
                @endforeach
            </select>
            @error('role') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password">{{ __('ui.auth.password') }}</label>
            <input id="password" name="password" type="password" required>
            @error('password') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">{{ __('ui.users.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('user.index') }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ __('ui.users.create_action') }}</button>
        </div>
    </form>
</x-layouts.app>
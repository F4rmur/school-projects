<div class="form-row">
    <div class="form-field">
        <label for="prenom">{{ __('ui.auth.first_name') }}</label>
        <input id="prenom" name="prenom" type="text" value="{{ old('prenom', $user?->prenom) }}" required autofocus>
        @error('prenom') <small class="form-error">{{ $message }}</small> @enderror
    </div>

    <div class="form-field">
        <label for="nom">{{ __('ui.auth.last_name') }}</label>
        <input id="nom" name="nom" type="text" value="{{ old('nom', $user?->nom) }}" required>
        @error('nom') <small class="form-error">{{ $message }}</small> @enderror
    </div>
</div>

<div class="form-field">
    <label for="email">{{ __('ui.auth.email') }}</label>
    <input id="email" name="email" type="email" value="{{ old('email', $user?->email) }}" required>
    @error('email') <small class="form-error">{{ $message }}</small> @enderror
</div>

<div class="form-field">
    <label for="sexe">{{ __('ui.users.gender') }}</label>
    <select id="sexe" name="sexe" required>
        <option value="">{{ __('ui.common.select') }}</option>
        <option value="homme" @selected(old('sexe', $user?->sexe) === 'homme')>{{ __('ui.users.male') }}</option>
        <option value="femme" @selected(old('sexe', $user?->sexe) === 'femme')>{{ __('ui.users.female') }}</option>
    </select>
    @error('sexe') <small class="form-error">{{ $message }}</small> @enderror
</div>

<div class="form-field">
    <label for="role">{{ __('ui.users.role') }}</label>
    <select id="role" name="role" required>
        @if (! $user)
            <option value="">{{ __('ui.users.select_role') }}</option>
        @endif
        @foreach ($roles as $role)
            <option value="{{ $role->name }}" @selected(old('role', $selectedRole) === $role->name)>{{ trans()->has('ui.roles.names.'.$role->name) ? __('ui.roles.names.'.$role->name) : ($role->title ?? ucfirst($role->name)) }}</option>
        @endforeach
    </select>
    @error('role') <small class="form-error">{{ $message }}</small> @enderror
</div>
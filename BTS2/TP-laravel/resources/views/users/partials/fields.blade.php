<x-form-row>
    <x-form-field for="prenom" :label="__('ui.auth.first_name')" :error="$errors->first('prenom')">
        <input id="prenom" name="prenom" type="text" value="{{ old('prenom', $user?->prenom) }}" required autofocus>
    </x-form-field>

    <x-form-field for="nom" :label="__('ui.auth.last_name')" :error="$errors->first('nom')">
        <input id="nom" name="nom" type="text" value="{{ old('nom', $user?->nom) }}" required>
    </x-form-field>
</x-form-row>

<x-form-field for="email" :label="__('ui.auth.email')" :error="$errors->first('email')">
    <input id="email" name="email" type="email" value="{{ old('email', $user?->email) }}" required>
</x-form-field>

<x-form-field for="sexe" :label="__('ui.users.gender')" :error="$errors->first('sexe')">
    <select id="sexe" name="sexe" required>
        <option value="">{{ __('ui.common.select') }}</option>
        <option value="homme" @selected(old('sexe', $user?->sexe) === 'homme')>{{ __('ui.users.male') }}</option>
        <option value="femme" @selected(old('sexe', $user?->sexe) === 'femme')>{{ __('ui.users.female') }}</option>
    </select>
</x-form-field>

<x-form-field for="role" :label="__('ui.users.role')" :error="$errors->first('role')">
    <select id="role" name="role" required>
        @if (! $user)
            <option value="">{{ __('ui.users.select_role') }}</option>
        @endif
        @foreach ($roles as $role)
            <option value="{{ $role->name }}" @selected(old('role', $selectedRole) === $role->name)>{{ trans()->has('ui.roles.names.'.$role->name) ? __('ui.roles.names.'.$role->name) : ($role->title ?? ucfirst($role->name)) }}</option>
        @endforeach
    </select>
</x-form-field>
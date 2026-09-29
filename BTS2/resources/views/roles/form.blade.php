<x-layouts.app :title="($role ? 'Modifier' : 'Créer').' un rôle | Suivi'">
    <a class="back-link" href="{{ route('roles.index') }}">&larr; Tous les rôles</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">Accès</p>
            <h1>{{ $role ? 'Modifier un rôle' : 'Créer un rôle' }}</h1>
            <p class="intro">Choisissez les autorisations associées à ce rôle.</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ $role ? route('roles.update', $role) : route('roles.store') }}">
        @csrf
        @if ($role)
            @method('PUT')
        @endif

        <div class="form-field">
            <label for="title">Nom affiché</label>
            <input id="title" name="title" type="text" value="{{ old('title', $role?->title) }}" required autofocus>
            @error('title') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="name">Identifiant</label>
            <input id="name" name="name" type="text" value="{{ old('name', $role?->name) }}" pattern="[A-Za-z0-9_-]+" required @readonly($role?->name === 'admin')>
            @error('name') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <fieldset class="form-field">
            <legend>Autorisations</legend>
            @php($selectedAbilities = old('abilities', $roleAbilities))
            @foreach ($abilities as $ability)
                <label>
                    <input type="checkbox" name="abilities[]" value="{{ $ability->name }}" @checked(in_array($ability->name, $selectedAbilities, true) || ($role?->name === 'admin' && $ability->name === 'manage-roles')) @disabled($role?->name === 'admin' && $ability->name === 'manage-roles')>
                    {{ $ability->title ?? \Illuminate\Support\Str::headline($ability->name) }}
                    <small>{{ $ability->name }}</small>
                </label>
                @if ($role?->name === 'admin' && $ability->name === 'manage-roles')
                    <input type="hidden" name="abilities[]" value="manage-roles">
                @endif
            @endforeach
            @error('abilities') <small class="form-error">{{ $message }}</small> @enderror
            @error('abilities.*') <small class="form-error">{{ $message }}</small> @enderror
        </fieldset>

        <div class="form-actions">
            <a class="back-link" href="{{ route('roles.index') }}">Annuler</a>
            <button class="button-link" type="submit">{{ $role ? 'Enregistrer' : 'Créer le rôle' }}</button>
        </div>
    </form>
</x-layouts.app>
<x-layouts.app title="Modifier un utilisateur | Suivi">
    <a class="back-link" href="{{ route('user.show', $user) }}">&larr; Retour à la fiche</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">Équipe</p>
            <h1>Modifier un utilisateur</h1>
            <p class="intro">Mettez à jour ses informations et son rôle.</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('user.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-field">
                <label for="prenom">Prénom</label>
                <input id="prenom" name="prenom" type="text" value="{{ old('prenom', $user->prenom) }}" required autofocus>
                @error('prenom') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-field">
                <label for="nom">Nom</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom', $user->nom) }}" required>
                @error('nom') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-field">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="sexe">Sexe</label>
            <select id="sexe" name="sexe" required>
                <option value="">Sélectionner</option>
                <option value="homme" @selected(old('sexe', $user->sexe) === 'homme')>Homme</option>
                <option value="femme" @selected(old('sexe', $user->sexe) === 'femme')>Femme</option>
            </select>
            @error('sexe') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="role">Rôle</label>
            <select id="role" name="role" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(old('role', $currentRole) === $role->name)>{{ $role->title ?? ucfirst($role->name) }}</option>
                @endforeach
            </select>
            @error('role') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password">Nouveau mot de passe</label>
            <input id="password" name="password" type="password" autocomplete="new-password">
            @error('password') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">Confirmation du nouveau mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('user.show', $user) }}">Annuler</a>
            <button class="button-link" type="submit">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>
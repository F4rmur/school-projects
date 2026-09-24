<x-layouts.app title="Nouvel utilisateur | Suivi">
    <a class="back-link" href="{{ route('user.index') }}">&larr; Tous les utilisateurs</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">Équipe</p>
            <h1>Nouvel utilisateur</h1>
            <p class="intro">Ajoutez une personne au suivi des absences.</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('user.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-field">
                <label for="prenom">Prénom</label>
                <input id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" required autofocus>
                @error('prenom') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-field">
                <label for="nom">Nom</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required>
                @error('nom') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-field">
            <label for="email">Adresse e-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            @error('email') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="sexe">Sexe</label>
            <select id="sexe" name="sexe" required>
                <option value="">Sélectionner</option>
                <option value="homme" @selected(old('sexe') === 'homme')>Homme</option>
                <option value="femme" @selected(old('sexe') === 'femme')>Femme</option>
            </select>
            @error('sexe') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" required>
            @error('password') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">Confirmation du mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('user.index') }}">Annuler</a>
            <button class="button-link" type="submit">Créer l'utilisateur</button>
        </div>
    </form>
</x-layouts.app>
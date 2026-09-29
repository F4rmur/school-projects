<x-layouts.app title="Nouvelle absence | Suivi">
    <a class="back-link" href="{{ $selectedUserId ? route('user.show', $selectedUserId) : route('absence.index') }}">&larr; Retour</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">Planning</p>
            <h1>Nouvelle absence</h1>
            <p class="intro">Enregistrez une période d'absence.</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('absence.store') }}">
        @csrf

        <div class="form-field">
            <label for="user_id">Utilisateur</label>
            <select id="user_id" name="user_id" required>
                <option value="">Sélectionner un utilisateur</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) $selectedUserId === (string) $user->id)>{{ trim($user->prenom.' '.$user->nom) }}</option>
                @endforeach
            </select>
            @error('user_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="motif_id">Motif</label>
            <select id="motif_id" name="motif_id" required>
                <option value="">Sélectionner un motif</option>
                @foreach ($motifs as $motif)
                    <option value="{{ $motif->id }}" @selected((string) old('motif_id') === (string) $motif->id)>{{ $motif->libelle }}</option>
                @endforeach
            </select>
            @error('motif_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="type_conge">Type de congé</label>
            <select id="type_conge" name="type_conge">
                <option value="">Autre absence</option>
                <option value="conges_payes" @selected(old('type_conge') === 'conges_payes')>Congés payés (maximum 25 jours par année)</option>
                <option value="paternite" @selected(old('type_conge') === 'paternite')>Congé paternité</option>
                <option value="maternite" @selected(old('type_conge') === 'maternite')>Congé maternité</option>
            </select>
            @error('type_conge') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="date_debut">Du</label>
                <input id="date_debut" name="date_debut" type="date" value="{{ old('date_debut') }}" required>
                @error('date_debut') <small class="form-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-field">
                <label for="date_fin">Au</label>
                <input id="date_fin" name="date_fin" type="date" value="{{ old('date_fin') }}" required>
                @error('date_fin') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ $selectedUserId ? route('user.show', $selectedUserId) : route('absence.index') }}">Annuler</a>
            <button class="button-link" type="submit">Créer l'absence</button>
        </div>
    </form>
</x-layouts.app>
<x-layouts.app title="Modifier une absence | Suivi">
    <a class="back-link" href="{{ route('absence.show', $absence) }}">&larr; Retour</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">Planning</p>
            <h1>Modifier une absence</h1>
            <p class="intro">Mettez à jour la période d'absence.</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('absence.update', $absence) }}">
        @csrf
        @method('PUT')

        <div class="form-field">
            <label for="user_id">Utilisateur</label>
            <select id="user_id" name="user_id" required @disabled(! auth()->user()->isAdmin())>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) old('user_id', $absence->user_id) === (string) $user->id)>{{ trim($user->prenom.' '.$user->nom) }}</option>
                @endforeach
            </select>
            @if (! auth()->user()->isAdmin())
                <input type="hidden" name="user_id" value="{{ $absence->user_id }}">
            @endif
            @error('user_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="motif_id">Motif</label>
            <select id="motif_id" name="motif_id" required>
                @foreach ($motifs as $motif)
                    <option value="{{ $motif->id }}" @selected((string) old('motif_id', $absence->motif_id) === (string) $motif->id)>{{ $motif->libelle }}</option>
                @endforeach
            </select>
            @error('motif_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="type_conge">Type de congé</label>
            <select id="type_conge" name="type_conge">
                <option value="">Autre absence</option>
                <option value="conges_payes" @selected(old('type_conge', $absence->type_conge) === 'conges_payes')>Congés payés</option>
                <option value="paternite" @selected(old('type_conge', $absence->type_conge) === 'paternite')>Congé paternité</option>
                <option value="maternite" @selected(old('type_conge', $absence->type_conge) === 'maternite')>Congé maternité</option>
            </select>
            @error('type_conge') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="date_debut">Du</label>
                <input id="date_debut" name="date_debut" type="date" value="{{ old('date_debut', $absence->date_debut?->format('Y-m-d')) }}" required>
                @error('date_debut') <small class="form-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-field">
                <label for="date_fin">Au</label>
                <input id="date_fin" name="date_fin" type="date" value="{{ old('date_fin', $absence->date_fin?->format('Y-m-d')) }}" required>
                @error('date_fin') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('absence.show', $absence) }}">Annuler</a>
            <button class="button-link" type="submit">Enregistrer</button>
        </div>
    </form>
</x-layouts.app>

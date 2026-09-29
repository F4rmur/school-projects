<x-layouts.app :title="__('ui.absences.create_title')">
    <a class="back-link" href="{{ $selectedUserId ? route('user.show', $selectedUserId) : route('absence.index') }}">&larr; {{ __('ui.common.back') }}</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.absences.planning') }}</p>
            <h1>{{ __('ui.absences.create_title') }}</h1>
            <p class="intro">{{ __('ui.absences.create_intro') }}</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('absence.store') }}">
        @csrf

        <div class="form-field">
            <label for="user_id">{{ __('ui.absences.user') }}</label>
            <select id="user_id" name="user_id" required>
                <option value="">{{ __('ui.absences.select_user') }}</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) $selectedUserId === (string) $user->id)>{{ trim($user->prenom.' '.$user->nom) }}</option>
                @endforeach
            </select>
            @error('user_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="motif_id">{{ __('ui.absences.reason') }}</label>
            <select id="motif_id" name="motif_id" required>
                <option value="">{{ __('ui.absences.select_reason') }}</option>
                @foreach ($motifs as $motif)
                    <option value="{{ $motif->id }}" @selected((string) old('motif_id') === (string) $motif->id)>{{ $motif->libelle }}</option>
                @endforeach
            </select>
            @error('motif_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="type_conge">{{ __('ui.absences.leave_type') }}</label>
            <select id="type_conge" name="type_conge">
                <option value="">{{ __('ui.absences.other') }}</option>
                <option value="conges_payes" @selected(old('type_conge') === 'conges_payes')>{{ __('ui.absences.paid_leave_limit') }}</option>
                <option value="paternite" @selected(old('type_conge') === 'paternite')>{{ __('ui.absences.paternity_leave') }}</option>
                <option value="maternite" @selected(old('type_conge') === 'maternite')>{{ __('ui.absences.maternity_leave') }}</option>
            </select>
            @error('type_conge') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="date_debut">{{ __('ui.common.from') }}</label>
                <input id="date_debut" name="date_debut" type="date" value="{{ old('date_debut') }}" required>
                @error('date_debut') <small class="form-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-field">
                <label for="date_fin">{{ __('ui.common.to') }}</label>
                <input id="date_fin" name="date_fin" type="date" value="{{ old('date_fin') }}" required>
                @error('date_fin') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ $selectedUserId ? route('user.show', $selectedUserId) : route('absence.index') }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ __('ui.absences.create_action') }}</button>
        </div>
    </form>
</x-layouts.app>
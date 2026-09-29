<x-layouts.app :title="__('ui.absences.edit_title')">
    <a class="back-link" href="{{ route('absence.show', $absence) }}">&larr; {{ __('ui.common.back') }}</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.absences.planning') }}</p>
            <h1>{{ __('ui.absences.edit_title') }}</h1>
            <p class="intro">{{ __('ui.absences.edit_intro') }}</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('absence.update', $absence) }}">
        @csrf
        @method('PUT')

        <div class="form-field">
            <label for="user_id">{{ __('ui.absences.user') }}</label>
            <select id="user_id" name="user_id" required @disabled(! auth()->user()->can('manage-all-absences'))>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) old('user_id', $absence->user_id) === (string) $user->id)>{{ trim($user->prenom.' '.$user->nom) }}</option>
                @endforeach
            </select>
            @if (! auth()->user()->can('manage-all-absences'))
                <input type="hidden" name="user_id" value="{{ $absence->user_id }}">
            @endif
            @error('user_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="motif_id">{{ __('ui.absences.reason') }}</label>
            <select id="motif_id" name="motif_id" required>
                @foreach ($motifs as $motif)
                    <option value="{{ $motif->id }}" @selected((string) old('motif_id', $absence->motif_id) === (string) $motif->id)>{{ $motif->libelle }}</option>
                @endforeach
            </select>
            @error('motif_id') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="type_conge">{{ __('ui.absences.leave_type') }}</label>
            <select id="type_conge" name="type_conge">
                <option value="">{{ __('ui.absences.other') }}</option>
                <option value="conges_payes" @selected(old('type_conge', $absence->type_conge) === 'conges_payes')>{{ __('ui.absences.paid_leave_limit') }}</option>
                <option value="paternite" @selected(old('type_conge', $absence->type_conge) === 'paternite')>{{ __('ui.absences.paternity_leave') }}</option>
                <option value="maternite" @selected(old('type_conge', $absence->type_conge) === 'maternite')>{{ __('ui.absences.maternity_leave') }}</option>
            </select>
            @error('type_conge') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="date_debut">{{ __('ui.common.from') }}</label>
                <input id="date_debut" name="date_debut" type="date" value="{{ old('date_debut', $absence->date_debut?->format('Y-m-d')) }}" required>
                @error('date_debut') <small class="form-error">{{ $message }}</small> @enderror
            </div>
            <div class="form-field">
                <label for="date_fin">{{ __('ui.common.to') }}</label>
                <input id="date_fin" name="date_fin" type="date" value="{{ old('date_fin', $absence->date_fin?->format('Y-m-d')) }}" required>
                @error('date_fin') <small class="form-error">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('absence.show', $absence) }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ __('ui.common.save') }}</button>
        </div>
    </form>
</x-layouts.app>

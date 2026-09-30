<div class="form-field">
    <label for="motif_id">{{ __('ui.absences.reason') }}</label>
    <select id="motif_id" name="motif_id" required>
        @if (! $absence)
            <option value="">{{ __('ui.absences.select_reason') }}</option>
        @endif
        @foreach ($motifs as $motif)
            <option value="{{ $motif->id }}" @selected((string) old('motif_id', $absence?->motif_id) === (string) $motif->id)>{{ $motif->libelle }}</option>
        @endforeach
    </select>
    @error('motif_id') <small class="form-error">{{ $message }}</small> @enderror
</div>

<div class="form-field">
    <label for="type_conge">{{ __('ui.absences.leave_type') }}</label>
    <select id="type_conge" name="type_conge">
        <option value="">{{ __('ui.absences.other') }}</option>
        <option value="conges_payes" @selected(old('type_conge', $absence?->type_conge) === 'conges_payes')>{{ __('ui.absences.paid_leave_limit') }}</option>
        <option value="paternite" @selected(old('type_conge', $absence?->type_conge) === 'paternite')>{{ __('ui.absences.paternity_leave') }}</option>
        <option value="maternite" @selected(old('type_conge', $absence?->type_conge) === 'maternite')>{{ __('ui.absences.maternity_leave') }}</option>
    </select>
    @error('type_conge') <small class="form-error">{{ $message }}</small> @enderror
</div>

<div class="form-row">
    <div class="form-field">
        <label for="date_debut">{{ __('ui.common.from') }}</label>
        <input id="date_debut" name="date_debut" type="date" value="{{ old('date_debut', $absence?->date_debut?->format('Y-m-d')) }}" required>
        @error('date_debut') <small class="form-error">{{ $message }}</small> @enderror
    </div>
    <div class="form-field">
        <label for="date_fin">{{ __('ui.common.to') }}</label>
        <input id="date_fin" name="date_fin" type="date" value="{{ old('date_fin', $absence?->date_fin?->format('Y-m-d')) }}" required>
        @error('date_fin') <small class="form-error">{{ $message }}</small> @enderror
    </div>
</div>
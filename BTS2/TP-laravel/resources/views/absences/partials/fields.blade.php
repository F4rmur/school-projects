<x-form-field for="motif_id" :label="__('ui.absences.reason')" :error="$errors->first('motif_id')">
    <select id="motif_id" name="motif_id" required>
        @if (! $absence)
            <option value="">{{ __('ui.absences.select_reason') }}</option>
        @endif
        @foreach ($motifs as $motif)
            <option value="{{ $motif->id }}" @selected((string) old('motif_id', $absence?->motif_id) === (string) $motif->id)>{{ $motif->libelle }}</option>
        @endforeach
    </select>
</x-form-field>

<x-form-field for="type_conge" :label="__('ui.absences.leave_type')" :error="$errors->first('type_conge')">
    <select id="type_conge" name="type_conge">
        <option value="">{{ __('ui.absences.other') }}</option>
        <option value="conges_payes" @selected(old('type_conge', $absence?->type_conge) === 'conges_payes')>{{ __('ui.absences.paid_leave_limit') }}</option>
        <option value="paternite" @selected(old('type_conge', $absence?->type_conge) === 'paternite')>{{ __('ui.absences.paternity_leave') }}</option>
        <option value="maternite" @selected(old('type_conge', $absence?->type_conge) === 'maternite')>{{ __('ui.absences.maternity_leave') }}</option>
    </select>
</x-form-field>

<x-form-row>
    <x-form-field for="date_debut" :label="__('ui.common.from')" :error="$errors->first('date_debut')">
        <input id="date_debut" name="date_debut" type="date" value="{{ old('date_debut', $absence?->date_debut?->format('Y-m-d')) }}" required>
    </x-form-field>

    <x-form-field for="date_fin" :label="__('ui.common.to')" :error="$errors->first('date_fin')">
        <input id="date_fin" name="date_fin" type="date" value="{{ old('date_fin', $absence?->date_fin?->format('Y-m-d')) }}" required>
    </x-form-field>
</x-form-row>
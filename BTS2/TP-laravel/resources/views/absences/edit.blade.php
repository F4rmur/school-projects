<x-layouts.app :title="__('ui.absences.edit_title')">
    <x-back-link :href="route('absence.show', $absence)">
        &larr; {{ __('ui.common.back') }}
    </x-back-link>

    <x-page-header
        :eyebrow="__('ui.absences.planning')"
        :title="__('ui.absences.edit_title')"
        :intro="__('ui.absences.edit_intro')"
    />

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

        @include('absences.partials.fields', ['absence' => $absence, 'motifs' => $motifs])

        <x-form-actions :cancel-href="route('absence.show', $absence)" :submit-label="__('ui.common.save')" />
    </form>
</x-layouts.app>

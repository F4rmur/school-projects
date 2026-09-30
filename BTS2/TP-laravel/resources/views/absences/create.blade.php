<x-layouts.app :title="__('ui.absences.create_title')">
    <x-back-link :href="$selectedUserId ? route('user.show', $selectedUserId) : route('absence.index')">
        &larr; {{ __('ui.common.back') }}
    </x-back-link>

    <x-page-header
        :eyebrow="__('ui.absences.planning')"
        :title="__('ui.absences.create_title')"
        :intro="__('ui.absences.create_intro')"
    />

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

        @include('absences.partials.fields', ['absence' => null, 'motifs' => $motifs])

        <x-form-actions
            :cancel-href="$selectedUserId ? route('user.show', $selectedUserId) : route('absence.index')"
            :submit-label="__('ui.absences.create_action')"
        />
    </form>
</x-layouts.app>
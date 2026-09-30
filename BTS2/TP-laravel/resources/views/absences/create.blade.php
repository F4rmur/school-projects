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

        @include('absences.partials.fields', ['absence' => null, 'motifs' => $motifs])

        <div class="form-actions">
            <a class="back-link" href="{{ $selectedUserId ? route('user.show', $selectedUserId) : route('absence.index') }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ __('ui.absences.create_action') }}</button>
        </div>
    </form>
</x-layouts.app>
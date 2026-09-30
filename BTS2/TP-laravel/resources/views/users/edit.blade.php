<x-layouts.app :title="__('ui.users.edit_title')">
    <a class="back-link" href="{{ route('user.show', $user) }}">&larr; {{ __('ui.users.back_to_profile') }}</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.users.team') }}</p>
            <h1>{{ __('ui.users.edit_title') }}</h1>
            <p class="intro">{{ __('ui.users.edit_intro') }}</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('user.update', $user) }}">
        @csrf
        @method('PUT')

        @include('users.partials.fields', ['user' => $user, 'roles' => $roles, 'selectedRole' => $currentRole])

        <div class="form-field">
            <label for="password">{{ __('ui.users.new_password') }}</label>
            <input id="password" name="password" type="password" autocomplete="new-password">
            @error('password') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">{{ __('ui.users.confirm_new_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('user.show', $user) }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ __('ui.common.save') }}</button>
        </div>
    </form>
</x-layouts.app>
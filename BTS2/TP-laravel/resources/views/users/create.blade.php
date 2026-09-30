<x-layouts.app :title="__('ui.users.create_title')">
    <a class="back-link" href="{{ route('user.index') }}">&larr; {{ __('ui.users.all') }}</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.users.team') }}</p>
            <h1>{{ __('ui.users.create_title') }}</h1>
            <p class="intro">{{ __('ui.users.create_intro') }}</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('user.store') }}">
        @csrf

        @include('users.partials.fields', ['user' => null, 'roles' => $roles, 'selectedRole' => 'utilisateur'])

        <div class="form-field">
            <label for="password">{{ __('ui.auth.password') }}</label>
            <input id="password" name="password" type="password" required>
            @error('password') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="password_confirmation">{{ __('ui.users.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <div class="form-actions">
            <a class="back-link" href="{{ route('user.index') }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ __('ui.users.create_action') }}</button>
        </div>
    </form>
</x-layouts.app>
<x-layouts.app :title="__('ui.users.create_title')">
    <x-back-link :href="route('user.index')">
        &larr; {{ __('ui.users.all') }}
    </x-back-link>

    <x-page-header
        :eyebrow="__('ui.users.team')"
        :title="__('ui.users.create_title')"
        :intro="__('ui.users.create_intro')"
    />

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

        <x-form-actions :cancel-href="route('user.index')" :submit-label="__('ui.users.create_action')" />
    </form>
</x-layouts.app>
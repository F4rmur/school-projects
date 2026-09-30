<x-layouts.app :title="__('ui.users.edit_title')">
    <x-back-link :href="route('user.show', $user)">
        &larr; {{ __('ui.users.back_to_profile') }}
    </x-back-link>

    <x-page-header
        :eyebrow="__('ui.users.team')"
        :title="__('ui.users.edit_title')"
        :intro="__('ui.users.edit_intro')"
    />

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

        <x-form-actions :cancel-href="route('user.show', $user)" :submit-label="__('ui.common.save')" />
    </form>
</x-layouts.app>
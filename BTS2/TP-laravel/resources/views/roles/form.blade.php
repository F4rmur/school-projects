<x-layouts.app :title="($role ? __('ui.roles.edit') : __('ui.roles.create')).' | '.__('ui.brand')">
    <x-back-link :href="route('roles.index')">
        &larr; {{ __('ui.roles.all') }}
    </x-back-link>

    <x-page-header
        :eyebrow="__('ui.roles.access')"
        :title="$role ? __('ui.roles.edit') : __('ui.roles.create')"
        :intro="__('ui.roles.create_intro')"
    />

    <form class="panel form-panel" method="POST" action="{{ $role ? route('roles.update', $role) : route('roles.store') }}">
        @csrf
        @if ($role)
            @method('PUT')
        @endif

        <x-form-field for="title" :label="__('ui.roles.display_name')" :error="$errors->first('title')">
            <input id="title" name="title" type="text" value="{{ old('title', $role?->title) }}" required autofocus>
        </x-form-field>

        <x-form-field for="name" :label="__('ui.roles.identifier')" :error="$errors->first('name')">
            <input id="name" name="name" type="text" value="{{ old('name', $role?->name) }}" pattern="[A-Za-z0-9_-]+" required @readonly($role?->name === 'admin')>
        </x-form-field>

        <fieldset class="form-field">
            <legend>{{ __('ui.roles.abilities') }}</legend>
            @php($selectedAbilities = old('abilities', $roleAbilities))
            @foreach ($abilities as $ability)
                <label>
                    <input type="checkbox" name="abilities[]" value="{{ $ability->name }}" @checked(in_array($ability->name, $selectedAbilities, true) || ($role?->name === 'admin' && $ability->name === 'manage-roles')) @disabled($role?->name === 'admin' && $ability->name === 'manage-roles')>
                    {{ trans()->has('ui.roles.ability_names.'.$ability->name) ? __('ui.roles.ability_names.'.$ability->name) : ($ability->title ?? \Illuminate\Support\Str::headline($ability->name)) }}
                    <small>{{ $ability->name }}</small>
                </label>
                @if ($role?->name === 'admin' && $ability->name === 'manage-roles')
                    <input type="hidden" name="abilities[]" value="manage-roles">
                @endif
            @endforeach
            @error('abilities') <small class="form-error">{{ $message }}</small> @enderror
            @error('abilities.*') <small class="form-error">{{ $message }}</small> @enderror
        </fieldset>

        <x-form-actions
            :cancel-href="route('roles.index')"
            :submit-label="$role ? __('ui.common.save') : __('ui.roles.create')"
        />
    </form>
</x-layouts.app>
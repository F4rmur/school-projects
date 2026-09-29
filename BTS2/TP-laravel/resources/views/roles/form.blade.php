<x-layouts.app :title="($role ? __('ui.roles.edit') : __('ui.roles.create')).' | '.__('ui.brand')">
    <a class="back-link" href="{{ route('roles.index') }}">&larr; {{ __('ui.roles.all') }}</a>

    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.roles.access') }}</p>
            <h1>{{ $role ? __('ui.roles.edit') : __('ui.roles.create') }}</h1>
            <p class="intro">{{ __('ui.roles.create_intro') }}</p>
        </div>
    </div>

    <form class="panel form-panel" method="POST" action="{{ $role ? route('roles.update', $role) : route('roles.store') }}">
        @csrf
        @if ($role)
            @method('PUT')
        @endif

        <div class="form-field">
            <label for="title">{{ __('ui.roles.display_name') }}</label>
            <input id="title" name="title" type="text" value="{{ old('title', $role?->title) }}" required autofocus>
            @error('title') <small class="form-error">{{ $message }}</small> @enderror
        </div>

        <div class="form-field">
            <label for="name">{{ __('ui.roles.identifier') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name', $role?->name) }}" pattern="[A-Za-z0-9_-]+" required @readonly($role?->name === 'admin')>
            @error('name') <small class="form-error">{{ $message }}</small> @enderror
        </div>

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

        <div class="form-actions">
            <a class="back-link" href="{{ route('roles.index') }}">{{ __('ui.common.cancel') }}</a>
            <button class="button-link" type="submit">{{ $role ? __('ui.common.save') : __('ui.roles.create') }}</button>
        </div>
    </form>
</x-layouts.app>
<x-layouts.app :title="__('ui.roles.title')">
    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.roles.access') }}</p>
            <h1>{{ __('ui.navigation.roles') }}</h1>
            <p class="intro">{{ __('ui.roles.intro') }}</p>
        </div>
        <a class="button-link" href="{{ route('roles.create') }}">{{ __('ui.roles.create') }}</a>
    </div>

    <section class="panel">
        @forelse ($roles as $role)
            <div class="absence-row">
                <span>
                    <strong>{{ trans()->has('ui.roles.names.'.$role->name) ? __('ui.roles.names.'.$role->name) : ($role->title ?? \Illuminate\Support\Str::headline($role->name)) }}</strong>
                    <small>{{ $role->name }} · {{ trans_choice('ui.roles.count', $role->abilities->count(), ['count' => $role->abilities->count()]) }}</small>
                </span>
                <a class="text-link" href="{{ route('roles.edit', $role) }}">{{ __('ui.common.manage') }}</a>
            </div>
        @empty
            <div class="empty-state">{{ __('ui.roles.empty') }}</div>
        @endforelse
    </section>
</x-layouts.app>
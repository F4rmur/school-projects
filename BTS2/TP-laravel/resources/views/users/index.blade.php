<x-layouts.app :title="__('ui.users.title')">
    <div class="page-heading">
        <div><p class="eyebrow">{{ __('ui.users.team') }}</p><h1>{{ __('ui.navigation.users') }}</h1><p class="intro">{{ __('ui.users.intro') }}</p></div>
        @can('create', \App\Models\users::class)
            <a class="button-link" href="{{ route('user.create') }}">{{ __('ui.users.add') }}</a>
        @endcan
        <div class="stat"><strong>{{ $users->count() }}</strong><span>{{ trans_choice('ui.users.count', $users->count(), ['count' => $users->count()]) }}</span></div>
    </div>
    <section class="user-grid">
        @forelse ($users as $user)
            <a class="user-card" href="{{ route('user.show', $user) }}">
                <span class="avatar">{{ strtoupper(substr($user->prenom ?: $user->nom, 0, 1)) }}</span>
                <span class="user-info"><strong>{{ trim($user->prenom.' '.$user->nom) }}</strong><small>{{ $user->email }}</small></span>
                <span class="absence-count">{{ trans_choice('ui.users.absence_count', $user->absences_count, ['count' => $user->absences_count]) }}</span>
            </a>
        @empty
            <div class="panel empty-state">{{ __('ui.users.empty') }}</div>
        @endforelse
    </section>
</x-layouts.app>
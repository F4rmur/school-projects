<x-layouts.app :title="__('ui.users.profile')">
    <a class="back-link" href="{{ route('user.index') }}">&larr; {{ __('ui.users.all') }}</a>
    <div class="detail-heading">
        <div>
            <p class="eyebrow">{{ __('ui.users.profile') }}</p>
            <h1>{{ trim($user->prenom.' '.$user->nom) }}</h1>
            <p class="intro">{{ $user->email }}</p>
        </div>
        <div class="detail-actions">
            @can('update', $user)
                <a class="button-link" href="{{ route('user.edit', $user) }}">{{ __('ui.users.edit') }}</a>
            @endcan
            @can('create', \App\Models\absence::class)
                @if (auth()->user()->can('manage-all-absences') || auth()->id() === $user->id)
                    <a class="button-link" href="{{ route('absence.create', ['user_id' => $user->id]) }}">{{ __('ui.users.add_absence') }}</a>
                @endif
            @endcan
        </div>
        <span class="avatar avatar-large">{{ strtoupper(substr($user->prenom ?: $user->nom, 0, 1)) }}</span>
    </div>
    <section class="panel">
        <div class="section-title"><h2>{{ __('ui.users.history') }}</h2><span class="muted">{{ $user->absences->count() }} {{ __('ui.users.total') }}</span></div>
        @forelse ($user->absences->sortByDesc('date_debut') as $absence)
            <a class="absence-row" href="{{ route('absence.show', $absence) }}"><span><strong>{{ $absence->motif?->libelle ?? __('ui.absences.unspecified_reason') }}</strong><small>{{ $absence->date_debut?->format('d/m/Y') ?? $absence->date_debut }} {{ __('ui.common.to') }} {{ $absence->date_fin?->format('d/m/Y') ?? $absence->date_fin }}</small></span><span class="text-link">{{ __('ui.common.view') }}</span></a>
        @empty
            <div class="empty-state">{{ __('ui.users.empty_absences') }}</div>
        @endforelse
    </section>
</x-layouts.app>
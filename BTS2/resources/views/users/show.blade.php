<x-layouts.app title="Utilisateur | Suivi">
    <a class="back-link" href="{{ route('user.index') }}">&larr; Tous les utilisateurs</a>
    <div class="detail-heading">
        <div>
            <p class="eyebrow">Profil utilisateur</p>
            <h1>{{ trim($user->prenom.' '.$user->nom) }}</h1>
            <p class="intro">{{ $user->email }}</p>
        </div>
        <div class="detail-actions">
            @can('create', \App\Models\absence::class)
                @if (auth()->user()->isAdmin() || auth()->id() === $user->id)
                    <a class="button-link" href="{{ route('absence.create', ['user_id' => $user->id]) }}">Ajouter une absence</a>
                @endif
            @endcan
        </div>
        <span class="avatar avatar-large">{{ strtoupper(substr($user->prenom ?: $user->nom, 0, 1)) }}</span>
    </div>
    <section class="panel">
        <div class="section-title"><h2>Historique des absences</h2><span class="muted">{{ $user->absences->count() }} au total</span></div>
        @forelse ($user->absences->sortByDesc('date_debut') as $absence)
            <a class="absence-row" href="{{ route('absence.show', $absence) }}"><span><strong>{{ $absence->motif?->libelle ?? 'Motif non précisé' }}</strong><small>{{ $absence->date_debut?->format('d/m/Y') ?? $absence->date_debut }} au {{ $absence->date_fin?->format('d/m/Y') ?? $absence->date_fin }}</small></span><span class="text-link">Voir</span></a>
        @empty
            <div class="empty-state">Cet utilisateur n'a aucune absence.</div>
        @endforelse
    </section>
</x-layouts.app>
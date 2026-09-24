<x-layouts.app title="Utilisateurs | Suivi">
    <div class="page-heading">
        <div><p class="eyebrow">Équipe</p><h1>Utilisateurs</h1><p class="intro">Une vue rapide des personnes suivies.</p></div>
        @can('create', \App\Models\users::class)
            <a class="button-link" href="{{ route('user.create') }}">Ajouter un utilisateur</a>
        @endcan
        <div class="stat"><strong>{{ $users->count() }}</strong><span>utilisateurs</span></div>
    </div>
    <section class="user-grid">
        @forelse ($users as $user)
            <a class="user-card" href="{{ route('user.show', $user) }}">
                <span class="avatar">{{ strtoupper(substr($user->prenom ?: $user->nom, 0, 1)) }}</span>
                <span class="user-info"><strong>{{ trim($user->prenom.' '.$user->nom) }}</strong><small>{{ $user->email }}</small></span>
                <span class="absence-count">{{ $user->absences_count }} absence{{ $user->absences_count > 1 ? 's' : '' }}</span>
            </a>
        @empty
            <div class="panel empty-state">Aucun utilisateur enregistré.</div>
        @endforelse
    </section>
</x-layouts.app>
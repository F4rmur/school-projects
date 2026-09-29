<x-layouts.app title="Rôles | Suivi">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Accès</p>
            <h1>Rôles</h1>
            <p class="intro">Rôles et autorisations attribuables aux utilisateurs.</p>
        </div>
        <a class="button-link" href="{{ route('roles.create') }}">Créer un rôle</a>
    </div>

    <section class="panel">
        @forelse ($roles as $role)
            <div class="absence-row">
                <span>
                    <strong>{{ $role->title ?? \Illuminate\Support\Str::headline($role->name) }}</strong>
                    <small>{{ $role->name }} · {{ $role->abilities->count() }} autorisation{{ $role->abilities->count() > 1 ? 's' : '' }}</small>
                </span>
                <a class="text-link" href="{{ route('roles.edit', $role) }}">Gérer</a>
            </div>
        @empty
            <div class="empty-state">Aucun rôle enregistré.</div>
        @endforelse
    </section>
</x-layouts.app>
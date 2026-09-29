<x-layouts.app title="Absence | Suivi">
    <a class="back-link" href="{{ route('absence.index') }}">&larr; Toutes les absences</a>
    <div class="detail-heading"><div><p class="eyebrow">Fiche absence</p><h1>{{ $absence->user ? trim($absence->user->prenom.' '.$absence->user->nom) : 'Utilisateur supprimé' }}</h1></div><span class="tag">{{ $absence->motif?->libelle ?? 'Motif non précisé' }}</span></div>
    <section class="detail-grid">
        <div class="detail-item"><span>Début</span><strong>{{ $absence->date_debut?->format('d/m/Y') ?? $absence->date_debut }}</strong></div>
        <div class="detail-item"><span>Fin</span><strong>{{ $absence->date_fin?->format('d/m/Y') ?? $absence->date_fin }}</strong></div>
    </section>
    @can('update', $absence)
        <a class="button-link" href="{{ route('absence.edit', $absence) }}">Modifier l'absence</a>
        <form method="POST" action="{{ route('absence.destroy', $absence) }}" style="display:inline">
            @csrf
            @method('DELETE')
            <button class="text-link" type="submit">Supprimer l'absence</button>
        </form>
    @endcan
    @if ($absence->user)
        <a class="button-link" href="{{ route('user.show', $absence->user) }}">Voir le profil utilisateur</a>
    @endif
</x-layouts.app>
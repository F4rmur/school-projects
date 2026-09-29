<x-layouts.app title="Absences | Suivi">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Planning</p>
            <h1>Absences</h1>
            <p class="intro">Retrouvez les absences enregistrées et leur motif.</p>
        </div>
        <div class="stat"><strong>{{ $absences->count() }}</strong><span>enregistrées</span></div>
    </div>

    <section class="panel">
        @if ($absences->isEmpty())
            <div class="empty-state">Aucune absence enregistrée.</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Personne</th><th>Motif</th><th>Du</th><th>Au</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($absences as $absence)
                            <tr>
                                <td><a class="person-link" href="{{ route('user.show', $absence->user) }}">{{ $absence->user ? trim($absence->user->prenom.' '.$absence->user->nom) : 'Utilisateur supprimé' }}</a></td>
                                <td><span class="tag">{{ $absence->motif?->libelle ?? 'Non précisé' }}</span></td>
                                <td>{{ $absence->date_debut?->format('d/m/Y') ?? $absence->date_debut }}</td>
                                <td>{{ $absence->date_fin?->format('d/m/Y') ?? $absence->date_fin }}</td>
                                <td class="action-cell"><a class="text-link" href="{{ route('absence.show', $absence) }}">Détails</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-layouts.app>
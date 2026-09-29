<x-layouts.app :title="__('ui.absences.title')">
    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ __('ui.absences.planning') }}</p>
            <h1>{{ __('ui.navigation.absences') }}</h1>
            <p class="intro">{{ __('ui.absences.intro') }}</p>
        </div>
        <div class="stat"><strong>{{ $absences->count() }}</strong><span>{{ trans_choice('ui.absences.count', $absences->count(), ['count' => $absences->count()]) }}</span></div>
    </div>

    <section class="panel">
        @if ($absences->isEmpty())
            <div class="empty-state">{{ __('ui.absences.empty') }}</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>{{ __('ui.absences.person') }}</th><th>{{ __('ui.absences.reason') }}</th><th>{{ __('ui.common.from') }}</th><th>{{ __('ui.common.to') }}</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($absences as $absence)
                            <tr>
                                <td><a class="person-link" href="{{ route('user.show', $absence->user) }}">{{ $absence->user ? trim($absence->user->prenom.' '.$absence->user->nom) : __('ui.absences.deleted_user') }}</a></td>
                                <td><span class="tag">{{ $absence->motif?->libelle ?? __('ui.absences.unspecified_reason') }}</span></td>
                                <td>{{ $absence->date_debut?->format('d/m/Y') ?? $absence->date_debut }}</td>
                                <td>{{ $absence->date_fin?->format('d/m/Y') ?? $absence->date_fin }}</td>
                                <td class="action-cell"><a class="text-link" href="{{ route('absence.show', $absence) }}">{{ __('ui.absences.details') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-layouts.app>
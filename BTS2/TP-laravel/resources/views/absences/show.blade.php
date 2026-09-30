<x-layouts.app :title="__('ui.absences.record')">
    <a class="back-link" href="{{ route('absence.index') }}">&larr; {{ __('ui.absences.all') }}</a>
    <div class="detail-heading">
        <div>
            <p class="eyebrow">{{ __('ui.absences.record') }}</p>
            <h1>{{ $absence->user ? trim($absence->user->prenom.' '.$absence->user->nom) : __('ui.absences.deleted_user') }}</h1>
        </div>
        <div class="absence-summary">
            <span class="tag">{{ $absence->motif?->libelle ?? __('ui.absences.unspecified_reason') }}</span>
            <span class="tag {{ $absence->status === \App\Models\absence::STATUS_PENDING ? 'tag-pending' : 'tag-approved' }}">{{ __('ui.absences.statuses.'.$absence->status) }}</span>
            @if ($absence->approvedBy)
                <small>{{ __('ui.absences.approved_by', ['name' => trim($absence->approvedBy->prenom.' '.$absence->approvedBy->nom)]) }}</small>
            @endif
        </div>
    </div>
    <section class="detail-grid">
        <div class="detail-item"><span>{{ __('ui.absences.start') }}</span><strong>{{ $absence->date_debut?->format('d/m/Y') ?? $absence->date_debut }}</strong></div>
        <div class="detail-item"><span>{{ __('ui.absences.end') }}</span><strong>{{ $absence->date_fin?->format('d/m/Y') ?? $absence->date_fin }}</strong></div>
    </section>
    @can('manage-all-absences')
        @if ($absence->status === \App\Models\absence::STATUS_PENDING)
            <form method="POST" action="{{ route('absence.approve', $absence) }}" class="approval-form">
                @csrf
                <button class="button-link" type="submit">{{ __('ui.absences.approve') }}</button>
            </form>
        @endif
    @endcan
    @can('update', $absence)
        <a class="button-link" href="{{ route('absence.edit', $absence) }}">{{ __('ui.absences.edit') }}</a>
        <form method="POST" action="{{ route('absence.destroy', $absence) }}" style="display:inline">
            @csrf
            @method('DELETE')
            <button class="text-link" type="submit">{{ __('ui.absences.delete') }}</button>
        </form>
    @endcan
    @if ($absence->user)
        <a class="button-link" href="{{ route('user.show', $absence->user) }}">{{ __('ui.absences.user_profile') }}</a>
    @endif
</x-layouts.app>
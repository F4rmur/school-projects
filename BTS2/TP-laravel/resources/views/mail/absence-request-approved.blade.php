<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('ui.mail.absence_approved_subject') }}</title>
</head>
<body>
    <p>{{ __('ui.mail.absence_request_greeting', ['name' => trim($absence->user->prenom.' '.$absence->user->nom)]) }}</p>
    <p>{{ __('ui.mail.absence_approved_intro') }}</p>
    <ul>
        <li><strong>{{ __('ui.mail.requester') }}:</strong> {{ trim($absence->user->prenom.' '.$absence->user->nom) }}</li>
        <li><strong>{{ __('ui.mail.period') }}:</strong> {{ $absence->date_debut->format('d/m/Y') }} {{ __('ui.common.to') }} {{ $absence->date_fin->format('d/m/Y') }}</li>
        <li><strong>{{ __('ui.mail.reason') }}:</strong> {{ $absence->motif?->libelle ?? __('ui.absences.unspecified_reason') }}</li>
    </ul>
    @if ($absence->approvedBy)
        <p>{{ __('ui.mail.absence_approved_by', ['name' => trim($absence->approvedBy->prenom.' '.$absence->approvedBy->nom)]) }}</p>
    @endif
    <p><a href="{{ route('absence.show', $absence) }}">{{ __('ui.mail.absence_request_action') }}</a></p>
</body>
</html>
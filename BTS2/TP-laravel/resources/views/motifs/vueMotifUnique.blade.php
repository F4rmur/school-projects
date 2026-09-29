@extends('layouts.layout')
@section('content')
    <div class="motif">
        <h1>{{ __('ui.motifs.item') }} : {{ $motif->libelle }}</h1>

        <p>{{ __('ui.common.id') }} : {{ $motif->id }}</p>
        <p>{{ __('ui.common.created_at') }} : {{ $motif->created_at }}</p>
        <p>{{ __('ui.common.updated_at') }} : {{ $motif->updated_at }}</p>
    </div>

@endsection
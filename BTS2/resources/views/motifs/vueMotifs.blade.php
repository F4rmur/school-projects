@extends('layouts.layout')
@section('content')

    @foreach ($motifs as $motif)
        <div class="motif">
            <h1>Motif : {{ $motif->libelle }}</h1>

            <p>ID : {{ $motif->id }}</p>
            <p>Créé le : {{ $motif->created_at }}</p>
            <p>Modifié le : {{ $motif->updated_at }}</p>
        </div>

    @endforeach

@endsection
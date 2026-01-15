@extends('layout')

@section('content')

    <h1>Modifier : {{ $ressource->nom }}</h1>

    <form class="data-form" method="POST" action="{{ route('ressources.update', $ressource->id) }}">
        @csrf @method('PUT')

        <input type="text" name="nom" value="{{ $ressource->nom }}" required><br>
        <textarea name="description">{{ $ressource->description }}</textarea><br>

        <select name="categorie_id" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $cat->id == $ressource->categorie_id ? 'selected' : '' }}>
                    {{ $cat->nom }}
                </option>
            @endforeach
        </select><br>

        <input type="text" name="cpu" value="{{ $ressource->cpu }}" placeholder="CPU"><br>
        <input type="text" name="ram" value="{{ $ressource->ram }}" placeholder="RAM"><br>
        <input type="text" name="stockage" value="{{ $ressource->stockage }}" placeholder="Stockage"><br>
        <input type="text" name="os" value="{{ $ressource->os }}" placeholder="OS"><br>

        <select name="statut" required>
            <option value="actif" {{ $ressource->statut == 'actif' ? 'selected' : '' }}>Actif</option>
            <option value="maintenance" {{ $ressource->statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            <option value="hors_service" {{ $ressource->statut == 'hors_service' ? 'selected' : '' }}>Hors service</option>
        </select><br>

        <button type="submit">Mettre à jour</button>
    </form>

@endsection
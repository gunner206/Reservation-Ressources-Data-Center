@extends('layout')

@section('content')

    <h1>Modifier : {{ $ressource->name }}</h1>

    <form class="data-form" method="POST" action="{{ route('ressources.update', $ressource->id) }}">
        @csrf 
        @method('PUT')

        {{-- Nom avec Placeholder --}}
        <label>Nom de la ressource</label>
        <input type="text" name="name" value="{{ $ressource->name }}" placeholder="Ex: Dell PowerEdge R740" required><br>

        {{-- Description avec Placeholder --}}
        <label>Description</label>
        <textarea name="description" placeholder="Entrez une brève description technique...">{{ $ressource->description }}</textarea><br>

        {{-- Catégorie --}}
        <label>Catégorie</label>
        <select name="category_id" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $cat->id == $ressource->category_id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select><br>

        @php
            // On décode le JSON des specs pour remplir les champs
            $specs = is_array($ressource->specs) ? $ressource->specs : json_decode($ressource->specs, true);
        @endphp

        {{-- Spécifications techniques avec Placeholders --}}
        <label>Spécifications techniques</label>
        <input type="text" name="specs[cpu]" value="{{ $specs['cpu'] ?? '' }}" placeholder="Processeur (ex: Intel Xeon 2.4GHz)"><br>
        <input type="text" name="specs[ram]" value="{{ $specs['ram'] ?? '' }}" placeholder="Mémoire vive (ex: 64 Go DDR4)"><br>
        <input type="text" name="specs[stockage]" value="{{ $specs['stockage'] ?? '' }}" placeholder="Disque dur (ex: 1.2 To SAS)"><br>
        <input type="text" name="specs[os]" value="{{ $specs['os'] ?? '' }}" placeholder="Système (ex: Windows Server 2022)"><br>

        {{-- Statut (is_active) --}}
        <label>État du matériel</label>
        <select name="is_active" required>
            <option value="1" {{ $ressource->is_active == 1 ? 'selected' : '' }}>Disponible / Opérationnel</option>
            <option value="0" {{ $ressource->is_active == 0 ? 'selected' : '' }}>En maintenance / Hors service</option>
        </select><br>

        <button type="submit" class="btn btn-add">Mettre à jour la ressource</button>
        <a href="{{ route('ressources.index') }}" style="margin-left: 10px;">Annuler</a>
    </form>

@endsection
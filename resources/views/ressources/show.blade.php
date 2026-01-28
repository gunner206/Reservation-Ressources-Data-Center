@extends('layout')

@section('content')
    <h1>Fiche Technique : {{ $ressource->name }}</h1>

    <div class="details-card">
        @php
            // On décode le JSON des spécifications
            $specs = is_array($ressource->specs) ? $ressource->specs : json_decode($ressource->specs, true);
        @endphp

        {{-- Section Description mise en avant --}}
        <div class="description-box">
            <strong>Description :</strong><br>
            {{ $ressource->description ?? 'Aucune description disponible pour cette ressource.' }}
        </div>

        <div class="spec-item">
            <span class="spec-label">Catégorie:</span> {{ $ressource->category->name ?? 'N/A' }}
        </div>

        {{-- Affichage des données issues du JSON --}}
        <div class="spec-item">
            <span class="spec-label">CPU:</span> {{ $specs['cpu'] ?? 'N/A' }}
        </div>
        <div class="spec-item">
            <span class="spec-label">RAM:</span> {{ $specs['ram'] ?? 'N/A' }}
        </div>
        <div class="spec-item">
            <span class="spec-label">Stockage:</span> {{ $specs['stockage'] ?? 'N/A' }}
        </div>
        <div class="spec-item">
            <span class="spec-label">Système:</span> {{ $specs['os'] ?? 'N/A' }}
        </div>

        <div class="spec-item">
            <span class="spec-label">Statut:</span>
            <span class="{{ $ressource->is_active ? 'status-active' : 'status-inactive' }}">
                {{ $ressource->is_active ? '● Opérationnel' : '○ Hors-service' }}
            </span>
        </div>

        <div class="actions">               
            <a href="{{ route('ressources.index') }}" class="btn-back">← Retour à la liste</a>

            {{-- SEULS ADMIN ET MANAGER VOIENT LE BOUTON MODIFIER --}}
            @auth
                @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'manager' && $ressource->manager_id == auth()->id()))
                    <a href="{{ route('ressources.edit', $ressource->id) }}" class="btn-edit">Modifier la fiche</a>
                @endif
            @endauth
        </div>
    </div>
@endsection
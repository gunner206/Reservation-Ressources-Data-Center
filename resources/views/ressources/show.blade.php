<!DOCTYPE html>
<html>
<head>
    <title>Détails de la ressource</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f7f6; }
        .details-card { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        .spec-item { margin-bottom: 12px; border-bottom: 1px solid #eee; padding-bottom: 8px; }
        .spec-label { font-weight: bold; color: #555; width: 120px; display: inline-block; }
        .status-active { color: #28a745; font-weight: bold; }
        .status-inactive { color: #dc3545; font-weight: bold; }
        .actions { margin-top: 20px; }
        .btn-edit { 
            background: #ffc107; color: black; padding: 8px 15px; 
            text-decoration: none; border-radius: 4px; margin-left: 10px; 
        }
    </style>
</head>
<body>
    <h1>Fiche Technique : {{ $ressource->name }}</h1>
    
    <div class="details-card">
        @php
            // On décode le JSON des spécifications
            $specs = is_array($ressource->specs) ? $ressource->specs : json_decode($ressource->specs, true);
        @endphp

        <div class="spec-item">
            <span class="spec-label">Description:</span> {{ $ressource->description ?? 'Aucune description' }}
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
                {{ $ressource->is_active ? 'Opérationnel' : 'Hors-service' }}
            </span>
        </div>

        <div class="actions">
            <a href="{{ route('ressources.index') }}">← Retour à la liste</a>

            {{-- SEULS ADMIN ET MANAGER VOIENT LE BOUTON MODIFIER --}}
            @auth
                @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'manager' && $ressource->manager_id == auth()->id()))
                    <a href="{{ route('ressources.edit', $ressource->id) }}" class="btn-edit">Modifier la fiche</a>
                @endif
            @endauth
        </div>
    </div>
</body>
</html>
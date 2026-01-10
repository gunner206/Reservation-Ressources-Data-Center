 <!DOCTYPE html>
<html>
<head>
    <title>Détails</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .details { background: #f8f9fa; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>{{ $ressource->nom }}</h1>
    
    <div class="details">
        <p><strong>Description:</strong> {{ $ressource->description ?? 'N/A' }}</p>
        <p><strong>Catégorie:</strong> {{ $ressource->categorie->nom ?? 'N/A' }}</p>
        <p><strong>CPU:</strong> {{ $ressource->cpu ?? 'N/A' }}</p>
        <p><strong>RAM:</strong> {{ $ressource->ram ?? 'N/A' }}</p>
        <p><strong>Stockage:</strong> {{ $ressource->stockage ?? 'N/A' }}</p>
        <p><strong>OS:</strong> {{ $ressource->os ?? 'N/A' }}</p>
        <p><strong>Statut:</strong> {{ $ressource->statut }}</p>
    </div>
    
    <a href="{{ route('ressources.index') }}">← Retour à la liste</a>
</body>
</html>

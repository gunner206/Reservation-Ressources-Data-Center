  <!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Ressource</title>
    <style>
        body { font-family: Arial; margin: 20px; max-width: 500px; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; }
    </style>
</head>
<body>
    <h1>Nouvelle Ressource</h1>
    
    <form method="POST" action="{{ route('ressources.store') }}">
        @csrf
        <input type="text" name="nom" placeholder="Nom" required><br>
        <textarea name="description" placeholder="Description"></textarea><br>
        <select name="categorie_id" required>
            <option value="">Choisir catégorie</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
            @endforeach
        </select><br>
        <input type="text" name="cpu" placeholder="CPU"><br>
        <input type="text" name="ram" placeholder="RAM"><br>
        <input type="text" name="stockage" placeholder="Stockage"><br>
        <input type="text" name="os" placeholder="OS"><br>
        <select name="statut" required>
            <option value="actif">Actif</option>
            <option value="maintenance">Maintenance</option>
            <option value="hors_service">Hors service</option>
        </select><br>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html> 

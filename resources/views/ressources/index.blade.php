<!DOCTYPE html>
<html>
<head>
    <title>Ressources</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .btn { padding: 10px 15px; background: #28a745; color: white; 
               text-decoration: none; border-radius: 5px; }
        .btn-small { padding: 5px 10px; font-size: 14px; }
        .btn-edit { background: #ffc107; }
        .btn-delete { background: #dc3545; }
        .btn-view { background: #17a2b8; }
    </style>
</head>
<body>
    <h1>Liste des Ressources</h1>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    <a href="{{ route('ressources.create') }}" class="btn">+ Nouvelle Ressource</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>CPU</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ressources as $ressource)
            <tr>
                <td>{{ $ressource->id }}</td>
                <td>{{ $ressource->nom }}</td>
                <td>{{ $ressource->categorie->nom ?? 'N/A' }}</td>
                <td>{{ $ressource->cpu ?? 'N/A' }}</td>
                <td>{{ $ressource->statut }}</td>
                <td>
                    <a href="{{ route('ressources.show', $ressource->id) }}" class="btn-small btn-view">Voir</a>
                    <a href="{{ route('ressources.edit', $ressource->id) }}" class="btn-small btn-edit">Modifier</a>
                    <form action="{{ route('ressources.destroy', $ressource->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-small btn-delete" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:20px;">
                    Aucune ressource. <a href="{{ route('ressources.create') }}">Créez-en une !</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
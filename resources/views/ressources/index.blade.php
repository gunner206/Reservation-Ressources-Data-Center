<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion du Parc - Ressources</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 20px; background-color: #f8f9fa; color: #333; }
        h1 { color: #2c3e50; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
        tr:hover { background-color: #f1f1f1; }

        /* Boutons */
        .btn { padding: 10px 15px; color: white; text-decoration: none; border-radius: 5px; display: inline-block; border: none; cursor: pointer; }
        .btn-add { background: #28a745; margin-bottom: 20px; }
        .btn-small { padding: 5px 10px; font-size: 13px; margin-right: 5px; }
        
        .btn-view { background: #17a2b8; }      /* Bleu info */
        .btn-edit { background: #ffc107; color: #000; } /* Jaune */
        .btn-delete { background: #dc3545; }    /* Rouge */
        .btn-reserve { background: #6f42c1; }   /* Violet (Pour Yassine) */
        .btn-secondary { background: #6c757d; margin-top: 20px; } /* Gris */

        .alert { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <h1>Liste des Ressources du Parc</h1>
    
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    
    {{-- 1. Bouton Ajouter (Visible uniquement par Admin et Manager) --}}
    @auth
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
            <a href="{{ route('ressources.create') }}" class="btn btn-add">+ Ajouter une ressource</a>
        @endif
    @endauth

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Statut</th>
                @auth 
                    @if(auth()->user()->role !== 'guest') <th>Actions</th> @endif 
                @endauth
            </tr>
        </thead>
        <tbody>
            @forelse($ressources as $ressource)
            <tr>
                <td>{{ $ressource->id }}</td>
                <td><strong>{{ $ressource->name }}</strong></td>
                <td>{{ $ressource->category->name ?? 'N/A' }}</td>
                <td>
                    @if($ressource->is_active)
                        <span class="badge badge-success">Disponible</span>
                    @else
                        <span class="badge badge-danger">Maintenance</span>
                    @endif
                </td>

                @auth
                    @if(auth()->user()->role !== 'guest')
                    <td>
                        {{-- Bouton VOIR (Pour tous les connectés) --}}
                        <a href="{{ route('ressources.show', $ressource->id) }}" class="btn btn-small btn-view">Détails</a>

                        {{-- BOUTON RÉSERVER (Uniquement pour Internal) --}}
                        @if(auth()->user()->role === 'internal')
                            @if($ressource->is_active)
                                <a href="{{ route('reservations.create', ['ressource_id' => $ressource->id]) }}" class="btn btn-small btn-reserve">Réserver</a>
                            @else
                                <small style="color: #666;">Indisponible</small>
                            @endif
                        @endif

                        {{-- BOUTONS MODIFIER/SUPPRIMER (Admin & Manager seulement) --}}
                        @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'manager' && $ressource->manager_id == auth()->id()))
                            <a href="{{ route('ressources.edit', $ressource->id) }}" class="btn btn-small btn-edit">Modifier</a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('ressources.destroy', $ressource->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-small btn-delete" onclick="return confirm('Supprimer définitivement ?')">Supprimer</button>
                            </form>
                        @endif
                    </td>
                    @endif
                @endauth
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">Aucune ressource trouvée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 2. Boutons sous le tableau --}}
    <div style="margin-top: 20px;">
        @auth
            @if(auth()->user()->role === 'internal')
                <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Voir mes réservations</a>
            @endif
        @endauth

        @guest
            <p><i>Veuillez <a href="{{ route('login') }}">vous connecter</a> pour réserver du matériel.</i></p>
        @endguest
    </div>

</body>
</html>
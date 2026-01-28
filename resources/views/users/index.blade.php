@extends('layout')

@section('content')
    <title>Gestion des Utilisateurs</title>
    
    <style>
        /* Styles Généraux */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: white;
        }

        /* Tableau Styling */
        table { width: 100%; border-collapse: collapse; background: #030230; color: white; }
        th, td { padding: 12px; border: 1px solid #14e4b7; text-align: left; }
        
        /* En-tête du tableau (Corrigé pour lisibilité) */
        th { background-color: #1a202c; color: white; text-transform: uppercase; font-size: 0.85rem; }
        
        /* Corps du tableau */
        tbody tr:hover { background-color: #0a0a40; } /* Effet de survol */

        /* Badges des Rôles */
        .badge { padding: 5px 12px; border-radius: 20px; color: white; font-size: 0.75em; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .admin { background-color: #ed8936; box-shadow: 0 0 5px #ed8936; } /* Orange */
        .tech { background-color: #ecc94b; color: black; box-shadow: 0 0 5px #ecc94b; } /* Jaune */
        .user { background-color: #4299e1; box-shadow: 0 0 5px #4299e1; } /* Bleu */

        /* Boutons d'action */
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.8em; color: white; text-decoration: none; transition: 0.3s; }
        .btn-back { background-color: #4a5568; padding: 10px 20px; font-size: 1rem; }
        .btn-back:hover { background-color: #2d3748; }

        .btn-edit { background-color: #3182ce; margin-right: 5px; }
        .btn-edit:hover { background-color: #2b6cb0; }
        
        .btn-delete { background-color: #e53e3e; }
        .btn-delete:hover { background-color: #c53030; }

        .action-group { display: flex; align-items: center; }
    </style>

    <div class="header-container">
        <h1>👥 Gestion des Utilisateurs</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-back">⬅ Retour au Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date d'inscription</th>
                <th>Actions</th> </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
               <td>
    <span style="color: white; font-size: 17px;">
        ( "{{ $user->role }}")
    </span>

    @php 
        // Si le rôle est vide ou null, on force 'user'
        $roleBrut = $user->role;
        if(empty($roleBrut)) { 
            $roleFinal = 'user'; 
        } else {
            $roleFinal = strtolower($roleBrut);
        }

        $badgeClass = match($roleFinal) {
            'admin' => 'badge-admin',
            'manager' => 'badge-manager',
            'technicien' => 'badge-tech',
            default => 'badge-user',
        };
    @endphp

    <span class="badge {{ $badgeClass }}">
        {{ ucfirst($roleFinal) }}
    </span>
</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                
                <td>
                    <div class="action-group">
                       <a href="{{ route('users.edit', $user->id) }}" class="action-btn btn-edit" style="text-decoration:none;">
                                Modifier
                            </a>

                        @if(Auth::id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment bannir cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Supprimer</button>
                            </form>
                        @else
                            <span style="color: #718096; font-size: 0.8em; font-style: italic;">(Vous)</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
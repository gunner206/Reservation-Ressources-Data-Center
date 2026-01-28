@extends('layout')

@section('content')

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
    <div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Description</th>
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

                {{-- Affichage de la description COMPLETE --}}
                <td class="desc-cell">
                    {{ $ressource->description }}
                </td>

                <td>
                    @if($ressource->is_active)
                        <span>Disponible</span>
                    @else
                        <span>Maintenance</span>
                    @endif
                </td>

                @auth
                    @if(auth()->user()->role !== 'guest')
                    <td class="actions-cell">
                        {{-- Bouton VOIR --}}
                        <a href="{{ route('ressources.show', $ressource->id) }}" class="btn btn-small btn-view">Détails</a>

                        {{-- BOUTON RÉSERVER --}}
                        @if(auth()->user()->role === 'internal')
                            @if($ressource->is_active)
                                <a href="{{ route('reservations.create', ['ressource_id' => $ressource->id]) }}" class="btn btn-small btn-reserve">Réserver</a>
                            @else
                                <small style="color: #666;">Indisponible</small>
                            @endif
                        @endif

                        {{-- BOUTONS MODIFIER/SUPPRIMER --}}
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
                <td colspan="6" style="text-align:center;">Aucune ressource trouvée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

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

@endsection
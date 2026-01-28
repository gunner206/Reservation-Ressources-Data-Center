@extends('layout')

@section('content')

    <div class="hero-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Liste des Ressources du Parc</h1>
        
        {{-- Bouton Ajouter Professionnel --}}
        @auth
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <a href="{{ route('ressources.create') }}" class="btn-primary-action">
                    <span>+</span> NOUVELLE RESSOURCE
                </a>
            @endif
        @endauth
    </div>

    @if(session('success'))
        <div class="alert" style="background: rgba(86, 179, 163, 0.2); color: #56b3a3; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #56b3a3;">
            {{ session('success') }}
        </div>
    @endif

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
                    <td><span style="color: var(--text-gray);">#{{ $ressource->id }}</span></td>
                    <td><strong>{{ $ressource->name }}</strong></td>
                    <td>{{ $ressource->category->name ?? 'N/A' }}</td>

                    <td class="desc-cell" style="max-width: 250px; font-size: 0.85rem; color: var(--text-gray);">
                        {{ Str::limit($ressource->description, 50) }}
                    </td>

                    <td>
                        @if($ressource->is_active)
                            <span class="status-badge approved">Disponible</span>
                        @else
                            <span class="status-badge refused">Maintenance</span>
                        @endif
                    </td>

                    @auth
                        @if(auth()->user()->role !== 'guest')
                        <td class="actions-cell">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                {{-- Détails --}}
                                <a href="{{ route('ressources.show', $ressource->id) }}" title="Voir" style="color: var(--teal-button);">
                                    <i class="fas fa-eye"></i> Détails
                                </a>

                                {{-- Réserver --}}
                                @if(auth()->user()->role === 'internal')
                                    @if($ressource->is_active)
                                        <a href="{{ route('reservations.create', ['ressource_id' => $ressource->id]) }}" class="btn-ressource-action" style="padding: 5px 12px; font-size: 0.8rem;">Réserver</a>
                                    @else
                                        <span style="font-size: 0.8rem; color: #e74c3c; opacity: 0.7;">Indisponible</span>
                                    @endif
                                @endif

                                {{-- Admin / Manager Actions --}}
                                @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'manager' && $ressource->manager_id == auth()->id()))
                                    <a href="{{ route('ressources.edit', $ressource->id) }}" style="color: #f1c40f; text-decoration: none; font-size: 0.9rem;">Modifier</a>
                                @endif

                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('ressources.destroy', $ressource->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:none; border:none; color: #e74c3c; cursor:pointer; font-size: 0.9rem;" onclick="return confirm('Supprimer définitivement ?')">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    @endauth
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 40px; color: var(--text-gray);">Aucune ressource trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; text-align: center;">
        @auth
            @if(auth()->user()->role === 'internal')
                <a href="{{ route('reservations.index') }}" class="btn-ressource-cancel">Voir mes réservations</a>
            @endif
        @endauth

        @guest
            <p style="color: var(--text-gray);"><i>Veuillez <a href="{{ route('login') }}" style="color: var(--teal-button);">vous connecter</a> pour réserver du matériel.</i></p>
        @endguest
    </div>

@endsection
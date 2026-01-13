@extends('layout')
@section('content')
<div class="dashboard-container">
    <div class="header-flex">
        <h2>
            @if(auth()->user()->role === 'manager')
                Gestion des Demandes
            @else
                Mes Réservations
            @endif
        </h2>
        
        <a href="{{ route('reservations.create') }}" class="btn-create">
            + Nouvelle Réservation
        </a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Ressource</th>
                
                @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                    <th>Demandeur</th>
                @endif

                <th>Dates</th>
                <th>Justification</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $res)
            <tr>
                <td><strong>{{ $res->resource->name }}</strong></td>

                @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                    <td>
                        {{ $res->user->name }} <br>
                        <small style="color: gray;">{{ $res->user->email }}</small>
                    </td>
                @endif

                <td>
                    Du {{ $res->start_date->format('d/m H:i') }} <br>
                    Au {{ $res->end_date->format('d/m H:i') }}
                </td>

                <td>{{ Str::limit($res->justification, 30) }}</td>

                <td>
                    <span class="status-badge status-{{ $res->status }}">
                        {{ $res->status == 'pending' ? 'En attente' : ($res->status == 'approved' ? 'Validé' : 'Refusé') }}
                    </span>
                </td>

                <td>
                    
                    @if((auth()->user()->role === 'manager' || auth()->user()->role === 'admin') && $res->status === 'pending')
                        <div class="action-buttons">
                            
                            <form action="{{ route('reservations.approve', $res->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-icon btn-approve" title="Valider">✔</button>
                            </form>

                            <form action="{{ route('reservations.reject', $res->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-icon btn-reject" title="Refuser">✖</button>
                            </form>
                        </div>

                    @elseif(auth()->id() === $res->user_id && $res->status === 'pending')
                        <form action="{{ route('reservations.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-cancel">Annuler</button>
                        </form>
                    
                    @else
                        <small style="color: #aaa;">Aucune action</small>
                    @endif

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($reservations->isEmpty())
        <p style="text-align: center; margin-top: 20px; color: gray;">Aucune réservation trouvée.</p>
    @endif
</div>
@endsection
@extends('layout')
@section('content')
<div class="dashboard-container">
    <div class="header-flex">
        <h2>
            @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
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
                            
                            <button type="submit" class="btn-icon btn-reject" title="Refuser" onclick="openRejectModal({{ $res->id }})">✖</button>
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
<div id="rejectModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3 style="color: #dc3545;">Refuser la réservation</h3>
        <p>Veuillez indiquer le motif du refus pour l'étudiant :</p>

        <form id="rejectForm" method="POST" action="">
            @csrf
            @method('PATCH')
            
            <textarea name="reject_reason" id="rejectReason" rows="4" 
                      placeholder="Ex: Maintenance imprévue..." required 
                      style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
            
            <br><br>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal()" 
                        style="background: #ccc; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                    Annuler
                </button>
                
                <button type="submit" 
                        style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                    Confirmer le Refus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        // 1. On trouve le formulaire dans la modale
        let form = document.getElementById('rejectForm');
        
        // 2. On change l'action du formulaire dynamiquement
        // Ça va devenir : /reservations/15/reject
        form.action = '/reservations/' + id + '/refuse';
        
        // 3. On vide le textarea pour qu'il soit propre
        document.getElementById('rejectReason').value = '';

        // 4. On affiche la modale
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    // Fermer si on clique sur le fond gris
    window.onclick = function(event) {
        let modal = document.getElementById('rejectModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<style>
    .modal-overlay {
        position: fixed; /* Reste fixe même si on scrolle */
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Fond gris semi-transparent */
        z-index: 9999; /* Au-dessus de tout */
        display: flex;
        justify-content: center; /* Centre horizontalement */
        align-items: center; /* Centre verticalement */
    }

    .modal-content {
        background: white;
        padding: 25px;
        border-radius: 8px;
        width: 400px; /* Largeur de la boîte */
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
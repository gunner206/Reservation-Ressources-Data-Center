@extends('layout')

@section('content')
<div class="dashboard-container">
    <h2>Details de la Reservation #{{ $reservation->id }}</h2>
    <br>

    <div class="card" style="background:  rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 8px;">
        <p><strong>Demandeur :</strong> {{ $reservation->user->name }} ({{ $reservation->user->email }})</p><br>
        <p><strong>Ressource :</strong> {{ $reservation->resource->name }}</p><br>
        <p><strong>Du :</strong> {{ $reservation->start_date->format('d/m/Y H:i') }}</p><br>
        <p><strong>Au :</strong> {{ $reservation->end_date->format('d/m/Y H:i') }}</p><br>
        <p><strong>Statut :</strong> {{ $reservation->status }}</p><br>
        
        <hr>
        <br>
        <h4>Justification complete :</h4>
        <br>
        <p style="background:  rgba(255, 255, 255, 0.1); padding: 15px; border-left: 4px solid #333;">
            {{ $reservation->justification }}
        </p>

        <br>

        @if((auth()->user()->role === 'manager' || auth()->user()->role === 'admin') && $reservation->status === 'pending')
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                
                <form action="{{ route('reservations.approve', $reservation->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-edit"> Valider</button>
                </form>
                <button type="submit" class="btn-edit" title="Refuser" style="background-color: orange" onclick="openRejectModal({{ $reservation->id }})">Refuser</button>
            </div>
        @endif
        
        <br>
        <a href="{{ route('reservations.index') }}" style="color: grey;">← Retour à la liste</a>
    </div>
    <div id="rejectModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3 style="color: orange;">Refuser la réservation</h3>
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
                        style="background: orange; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
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
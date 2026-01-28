@extends('layout')

@section('content')

<div class="container" style="max-width: 800px; margin: 50px auto; color: white;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>📬 Vos Notifications</h2>
        <a href="{{ route('dashboard') }}" style="color: #63b3ed; text-decoration: none;">← Retour au Dashboard</a>
    </div>

    @if($notifications->isEmpty())
        <div style="text-align: center; padding: 40px; background: #1a202c; border-radius: 10px; color: #a0aec0;">
            <p>Aucune notification pour le moment.</p>
        </div>
    @else
        <ul style="list-style: none; padding: 0;">
            @foreach($notifications as $notif)
            <div>
                <a href="{{ route('reservations.index') }}" style="text-decoration: none; color: inherit; display: block;">
                    <li style="background: #fff; border-bottom: 1px solid #ddd; padding: 15px; {{ $notif->read_at ? 'opacity: 0.7;' : 'border-left: 4px solid #007bff;' }}">
                        
                        <strong style="color: black;font-size: 1.1em;">
                            {{ $notif->data['message'] ?? 'Notification système' }}
                        </strong>

                        <br>
                        <small style="color: grey;">
                            Reçu le {{ $notif->created_at->format('d/m/Y à H:i') }}
                        </small>
                    </li>
            </div>
            @endforeach
        </ul>
    @endif
</div>
<style>
    .notif-item {
        background: #1a202c;
        border-bottom: 1px solid #2d3748;
        padding: 20px;
        margin-bottom: 10px;
        border-radius: 8px;
        transition: 0.3s;
    }
    
    /* Style pour les messages lus (un peu transparents) */
    .is-read {
        opacity: 0.6;
    }

    /* Style pour les messages NON lus (Bordure rouge à gauche) */
    .is-unread {
        border-left: 4px solid #e53e3e;
        background-color: #2d3748; /* Un peu plus clair pour ressortir */
    }
    li:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endsection
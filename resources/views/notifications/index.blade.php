@extends('layout')

@section('content')

{{-- 1. On définit le CSS proprement ici pour éviter les erreurs de l'éditeur --}}
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
</style>

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
                
                {{-- 
                   CORRECTION : Au lieu de mettre de la logique dans 'style', 
                   on ajoute simplement une classe CSS (is-read ou is-unread) 
                --}}
                <li class="notif-item {{ $notif->is_read ? 'is-read' : 'is-unread' }}">
                    
                    <div style="display: flex; justify-content: space-between;">
                        <strong style="color: white; font-size: 1.1em;">
                            {{ $notif->data['message'] ?? 'Notification système' }}
                        </strong>
                        
                        {{-- Badge 'Nouveau' si non lu --}}
                        @if(!$notif->is_read)
                            <span style="font-size: 0.8em; background: #e53e3e; color: white; padding: 2px 8px; border-radius: 4px; height: fit-content;">
                                Nouveau
                            </span>
                        @endif
                    </div>

                    <div style="margin-top: 5px;">
                        <small style="color: #a0aec0;">
                            📅 Reçu le {{ $notif->created_at->format('d/m/Y à H:i') }}
                        </small>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
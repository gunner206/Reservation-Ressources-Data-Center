@extends('layout')

@section('content')
<div class="container" style="max-width: 800px; margin: 30px auto;">
    <h2>📬 Vos Notifications</h2>

    @if($notifications->isEmpty())
        <p>Aucune notification pour le moment.</p>
    @else
        <ul style="list-style: none; padding: 0;">
            @foreach($notifications as $notif)
                <li style="background: #fff; border-bottom: 1px solid #ddd; padding: 15px; {{ $notif->read_at ? 'opacity: 0.7;' : 'border-left: 4px solid #007bff;' }}">
                    
                    <strong style="color: black;font-size: 1.1em;">
                        {{ $notif->data['message'] ?? 'Notification système' }}
                    </strong>

                    <br>
                    <small style="color: grey;">
                        Reçu le {{ $notif->created_at->format('d/m/Y à H:i') }}
                    </small>
                </li>
            @endforeach
        </ul>
    @endif
    
    <a href="{{ route('dashboard') }}">← Retour au Dashboard</a>
</div>
@endsection
 
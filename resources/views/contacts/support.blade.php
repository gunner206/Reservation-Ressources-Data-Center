@extends('layout')

@section('content')
<div class="support-container" style="max-width: 800px; margin: 50px auto; color: white;">
    
    <div style="background-color: #1a202c; padding: 40px; border-radius: 15px; border: 1px solid #2d3748;">
        <h2 style="margin-bottom: 10px; border-bottom: 2px solid #24547aff; padding-bottom: 10px;">
            🛠 Signaler un incident
        </h2>
        <p style="color: #a0aec0; margin-bottom: 30px;">
            Un problème avec un serveur ou une salle ? Remplissez ce formulaire pour alerter l'équipe technique.
        </p>

        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf

            {{-- Champ SUJET --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">Sujet de l'incident</label>
                <input type="text" name="subject" placeholder="Ex: Surchauffe Salle A" required
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #4a5568; background-color: #2d3748; color: white;">
            </div>

            {{-- Champ PRIORITÉ --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">Niveau d'urgence</label>
                <select name="priority" required
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #4a5568; background-color: #2d3748; color: white;">
                    <option value="low">🟢 Basse (Information)</option>
                    <option value="medium" selected>🟠 Moyenne (Problème gênant)</option>
                    <option value="high">🔴 Haute (Critique / Panne totale)</option>
                </select>
            </div>

            {{-- Champ MESSAGE --}}
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">Description détaillée</label>
                <textarea name="message" rows="6" placeholder="Décrivez le problème..." required
                          style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #4a5568; background-color: #2d3748; color: white;"></textarea>
            </div>

            {{-- Bouton --}}
            <button type="submit" 
                    style="width: 100%; background-color: #2091ddff; color: white; padding: 15px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                🚀 Envoyer le signalement
            </button>
        </form>
    </div>
</div>

@endsection
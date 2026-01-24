@extends('layout')

@section('content')
{{-- Lien pour les icônes (FontAwesome) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Carte Profil Design */
    .team-card {
        background-color: #2d3748;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid #4a5568;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        border-color: #63b3ed;
    }

    /* Photo */
    .avatar-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid #63b3ed;
        box-shadow: 0 0 15px rgba(99, 179, 237, 0.3);
    }

    /* Initiales (si pas de photo) */
    .avatar-initials {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 2rem;
        color: #1a202c;
        border: 3px solid #ecc94b;
    }

    /* Textes */
    .member-name {
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .member-role {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(255,255,255,0.1);
    }

    .member-bio {
        color: #a0aec0;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 20px;
        font-style: italic;
        flex-grow: 1; /* Pousse les icônes vers le bas */
    }

    /* Icônes Réseaux Sociaux */
    .social-links {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: auto; /* Colle au bas de la carte */
    }

    .social-btn {
        width: 35px;
        height: 35px;
        background: #1a202c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: background 0.3s, color 0.3s;
        border: 1px solid #4a5568;
    }

    .social-btn:hover {
        background: #63b3ed; /* Bleu au survol */
        color: white;
        border-color: #63b3ed;
    }
    
    /* Couleurs spécifiques au survol */
    .social-btn.linkedin:hover { background: #0077b5; border-color: #0077b5; }
    .social-btn.github:hover { background: #333; border-color: #333; }
    .social-btn.email:hover { background: #e53e3e; border-color: #e53e3e; }

</style>

<div class="container" style="max-width: 1100px; margin: 50px auto; color: white;">

    {{-- En-tête --}}
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="color: #63b3ed; font-size: 2.5rem; font-weight: 800;">L'Équipe Technique</h1>
        <p style="color: #a0aec0; max-width: 600px; margin: 10px auto;">
            Des experts dédiés à la performance et à la sécurité de votre infrastructure.
            Nous sommes là pour garantir la continuité de vos services.
        </p>
    </div>

    {{-- Grille des Cartes --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
        
        @foreach($team as $member)
            <div class="team-card">
                
                {{-- 1. PHOTO ou INITIALES --}}
                @if($member->avatar)
                    <img src="{{ asset($member->avatar) }}" alt="{{ $member->name }}" class="avatar-photo">
                @else
                    <div class="avatar-initials" style="background-color: {{ $member->role == 'admin' ? '#ed8936' : '#ecc94b' }};">
                        {{ substr($member->name, 0, 2) }}
                    </div>
                @endif

                {{-- 2. NOM --}}
                <h3 class="member-name">{{ $member->name }}</h3>

                {{-- 3. RÔLE (Badge coloré selon le rôle) --}}
                <span class="member-role" style="color: {{ $member->role == 'admin' ? '#63b3ed' : '#ecc94b' }}; border: 1px solid {{ $member->role == 'admin' ? '#63b3ed' : '#ecc94b' }};">
                    {{ $member->role == 'admin' ? 'Administrateur' : 'Technicien' }}
                </span>

                {{-- 4. BIO / DESCRIPTION --}}
                <p class="member-bio">
                    {{ $member->bio ?? "Membre de l'équipe technique du Centrum." }}
                </p>

                {{-- 5. ICÔNES SOCIAUX --}}
                <div class="social-links">
                    {{-- Email (Toujours là) --}}
                    <a href="mailto:{{ $member->email }}" class="social-btn email" title="Envoyer un email">
                        <i class="fas fa-envelope"></i>
                    </a>

                    {{-- LinkedIn (Si existe en BDD) --}}
                    @if($member->linkedin_url)
                        <a href="{{ $member->linkedin_url }}" target="_blank" class="social-btn linkedin" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif

                    {{-- GitHub (Si existe en BDD) --}}
                    @if($member->github_url)
                        <a href="{{ $member->github_url }}" target="_blank" class="social-btn github" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    @endif
                </div>

            </div>
        @endforeach

    </div>

    {{-- Section Règlement (Plus bas, en plus petit) --}}
  <div class="max-w-4xl mx-auto px-4 mt-12 mb-20">
        
        <div class="flex items-center mb-10">
            <div class="flex-grow border-t border-gray-600"></div>
            <span class="mx-4 text-gray-400 text-sm uppercase tracking-widest">Liens Utiles</span>
            <div class="flex-grow border-t border-gray-600"></div>
        </div>

        <div class="flex flex-row justify-center items-center gap-10">

            <button onclick="window.location.href='{{ url('/rules') }}'" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow-lg transition duration-200">
                Voir le Règlement
            </button>

            <button onclick="window.location.href='{{ url('/support') }}'" class="px-8 py-3 bg-gray-600 hover:bg-gray-500 text-white font-bold rounded shadow-lg transition duration-200 border border-gray-500">
                Contacter le Support
            </button>

        </div>
    </div>
@endsection
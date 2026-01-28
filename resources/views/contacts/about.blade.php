@extends('layout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/contact.css') }}">

<div class="container" style="max-width: 1100px; margin: 50px auto; color: white;">

    {{-- En-tête --}}
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="color: #63b3ed; font-size: 2.5rem; font-weight: 800;">L'Équipe Technique</h1>
        <p style="color: #a0aec0; max-width: 600px; margin: 10px auto;">
            Des experts dédiés à la performance et à la sécurité de votre infrastructure.
            Nous sommes là pour garantir la continuité de vos services.
        </p>
    </div>
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

                {{-- 3. RÔLE --}}
                <span class="member-role" style="color: {{ $member->role == 'admin' ? '#63b3ed' : '#ecc94b' }}; border: 1px solid {{ $member->role == 'admin' ? '#63b3ed' : '#ecc94b' }};">
                    {{ $member->role == 'admin' ? 'Administrateur' : 'Technicien' }}
                </span>

                {{-- 4. BIO --}}
                <p class="member-bio">
                    {{ $member->bio ?? "Membre de l'équipe technique du Centrum." }}
                </p>

                {{-- 5. ICÔNES SOCIAUX (AVEC IMAGES) --}}
                <div class="social-links" style="display: flex; justify-content: center; gap: 15px; margin-top: 20px;">
                    
                    {{-- Email --}}
                    <a href="mailto:{{ $member->email }}" class="social-btn" title="Envoyer un email">
                        <img src="{{ asset('images/email.png') }}" alt="Email" style="width: 24px; height: 24px;">
                    </a>

                    {{-- LinkedIn --}}
                    @if($member->linkedin_url)
                        <a href="{{ $member->linkedin_url }}" target="_blank" class="social-btn" title="LinkedIn">
                            <img src="{{ asset('images/linkedin.png') }}" alt="LinkedIn" style="width: 24px; height: 24px;">
                        </a>
                    @endif

                    {{-- GitHub (Seulement si le lien existe) --}}
                    @if($member->github_url)
                        <a href="{{ $member->github_url }}" target="_blank" class="social-btn" title="GitHub">
                            <img src="{{ asset('images/git.png') }}" alt="GitHub" style="width: 24px; height: 24px;">
                        </a>
                    @endif
                </div>

            </div>
        @endforeach 

    </div> 
    
    {{-- SECTION LIENS UTILES --}}
    <div class="links-section">
        
        <div class="links-title-wrapper">
            <p class="links-title">Liens Utiles</p>
        </div>

        {{-- Conteneur des Boutons --}}
        <div class="buttons-container">
            
            {{-- Bouton Règlement --}}
            <form action="{{ url('/rules') }}" method="GET">
                <button type="submit" class="btn-custom btn-blue">
                    Voir le Règlement
                </button>
            </form>

            {{-- Bouton Support --}}
            <form action="{{ url('/support') }}" method="GET">
                <button type="submit" class="btn-custom btn-gray">
                    Contacter le Support
                </button>
            </form>

        </div>
    </div>

</div> {{-- Fin du container principal --}}
@endsection
@extends('layout')

@section('content')
    <title>Règlement Intérieur - Centrum</title>
    <link rel="stylesheet" href="{{ asset('css/rules.css') }}">

    <nav class="rules-nav">
        <a href="{{ url('/about') }}" class="back-link">
            {{-- SVG icône flèche retour --}}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour à l'équipe
        </a>
    </nav>

    <div class="rules-container">
        
        {{-- Header avec Titre Dégradé --}}
        <div class="rules-header">
            <h1 class="rules-title">
                📜 Règlement Intérieur
            </h1>
            <p class="rules-subtitle">Les règles à respecter pour l'utilisation du Data Center.</p>
        </div>

        {{-- Liste des Cartes --}}
        <div class="rules-list">
            
            <div class="rule-card">
                <h3>1. Accès Sécurisé</h3>
                <p>L'accès aux serveurs est strictement réservé au personnel autorisé. Tout partage de mot de passe est interdit.</p>
            </div>

            <div class="rule-card">
                <h3>2. Respect du Matériel</h3>
                <p>Il est interdit de modifier la configuration physique des machines sans un ticket validé par un Super Admin.</p>
            </div>

            <div class="rule-card">
                <h3>3. Confidentialité</h3>
                <p>Les données stockées sur Centrum sont confidentielles. Aucune extraction de données n'est permise sans autorisation.</p>
            </div>
            
        </div>
        
        <div class="rules-footer">
            Dernière mise à jour : Janvier 2026
        </div>
    </div>
@endsection
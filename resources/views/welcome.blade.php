@extends('layout') @section('content')
    <section class="hero">
        <h1>Gérez vos ressources Data Center en temps réel</h1>
        <p>Accédez à une infrastructure de pointe : serveurs physiques, machines virtuelles et stockage réseau haute performance.</p>
        <div class="hero-actions">
            <a href="/register" class="btn-signup">Demander un compte</a>
            <a href="#resources" class="btn-outline">Voir le catalogue</a>
        </div>
    </section>

    <section id="resources" class="resource-preview">
        <h2>Catalogue des Ressources</h2>
        <div class="resource-grid">
            <div class="resource-card">
                <div class="badge status-available">Disponible</div>
                <h3>Serveur Dell PowerEdge R740</h3>
                <p>CPU: 24 Cores | RAM: 128 Go | Stockage: 2To SSD</p>
                <span class="category-tag">Serveur Physique</span>
            </div>

            <div class="resource-card">
                <div class="badge status-busy">Occupé</div>
                <h3>VM - Instance Linux Ubuntu</h3>
                <p>vCPU: 4 | RAM: 16 Go | Bande passante: 1Gbps</p>
                <span class="category-tag">Machine Virtuelle</span>
            </div>
            </div>
    </section>

    <section class="rules">
        <h2>Règles d'utilisation</h2>
        <div class="rules-container">
            <div class="rule-item">
                <p>Les réservations sont soumises à la validation d'un responsable technique.</p>
            </div>
            <div class="rule-item">
                <p>Toute demande doit être justifiée (Projet, Recherche, TP).</p>
            </div>
        </div>
    </section>
@endsection
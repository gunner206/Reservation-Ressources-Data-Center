@extends('layout') @section('content')
    <section class="hero">
        <h1>Gérez vos ressources Data Center en temps réel</h1>
        <p>Accédez à une infrastructure de pointe : serveurs physiques, machines virtuelles et stockage réseau haute performance.</p>
        <div class="hero-actions">
            <a href="{{ route('login', ['action' => 'signup']) }}" class="btn-signup">Demander un compte</a>
            <a href="#resources" class="btn-outline">Voir le catalogue</a>
        </div>
    </section>

    <section id="resources" class="resource-preview">
        <h2>Catalogue des Ressources</h2>

        <div class="resource-grid">
            @forelse($resources as $resource)
                <div class="resource-card">
                    {{-- Calcul simple de la disponibilité (Optionnel) --}}
                    @php
                        // On vérifie si la ressource est disponible MAINTENANT
                        $isAvailable = \App\Models\Reservation::isAvailable($resource->id, now(), now());
                    @endphp

                    @if($isAvailable)
                        <div class="badge status-available">Disponible</div>
                    @else
                        <div class="badge status-busy">Occupé</div>
                    @endif

                    <h3>{{ $resource->name }}</h3>

                    {{-- Affichage des spécifications (si stockées en JSON/Array) --}}
                    <p>
                        @if(is_array($resource->specs))
                            @foreach($resource->specs as $key => $value)
                                {{ ucfirst($key) }}: {{ $value }}
                                @if(!$loop->last) | @endif
                            @endforeach
                        @else
                            {{-- Cas où specs est juste du texte --}}
                            {{ Str::limit($resource->description, 50) }}
                        @endif
                    </p>

                    <span class="category-tag">
                        {{ $resource->category->name ?? 'Non classé' }}
                    </span>
                </div>
            @empty
                <p>Aucune ressource disponible pour le moment.</p>
            @endforelse
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
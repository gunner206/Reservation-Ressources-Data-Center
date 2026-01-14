@extends('layout')

@section('content')
<div class="dashboard-container">
    {{-- En-tête de bienvenue --}}
    <div class="hero-header">
        <h1>Reporting & Statistiques</h1>
        <p>Analyse en temps réel de votre infrastructure Data Center.</p>
    </div>

    {{-- Grille des Statistiques (Hero Cards) --}}
    <div class="stats-grid">

        {{-- Carte 1 : Santé de l'Infrastructure --}}
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Santé Infrastructure</span>
                <span class="stat-icon">🛠️</span>
            </div>
            <div class="stat-value">{{ $stats['infra_health'] }}%</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $stats['infra_health'] }}%"></div>
            </div>
            <div class="stat-footer">Ressources actives sur le total.</div>
        </div>

        {{-- Carte 2 : Approbations en Attente (Dynamique : devient jaune s'il y a des demandes) --}}
        <div class="stat-card {{ $stats['reservations_pending'] > 0 ? 'warning-card' : '' }}">
            <div class="stat-header">
                <span class="stat-label">Attente Approbation</span>
                <span class="stat-icon">⏳</span>
            </div>
            <div class="stat-value">{{ $stats['reservations_pending'] }}</div>
            <div class="stat-footer">Demandes de réservation à valider.</div>
        </div>

        {{-- Carte 3 : Taux d'Occupation --}}
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Taux d'Occupation</span>
                <span class="stat-icon">📈</span>
            </div>
            <div class="stat-value">{{ $stats['occupancy'] }}%</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $stats['occupancy'] }}%; background: #3498db; box-shadow: 0 0 10px #3498db;"></div>
            </div>
            <div class="stat-footer">Volume des ressources réservées.</div>
        </div>

        {{-- Carte 4 : Alertes Critiques (Dynamique : clignote en rouge s'il y a des incidents) --}}
        <div class="stat-card {{ $stats['critical_incidents'] > 0 ? 'danger-card' : '' }}">
            <div class="stat-header">
                <span class="stat-label">Incidents Critiques</span>
                <span class="stat-icon">🔥</span>
            </div>
            <div class="stat-value">{{ $stats['critical_incidents'] }}</div>
            <div class="stat-footer">Priorité Haute ou Critique ouverte.</div>
        </div>

    </div>

    {{-- Section Activités Récentes (Tableau) --}}
    <div class="activity-section">
        <div class="stat-header">
            <h2>Activités Récentes</h2>
            <a href="{{ route('reservations.index') }}" class="btn-outline" style="font-size: 0.8rem; padding: 8px 20px;">Voir tout</a>
        </div>

        <div class="activity-table-wrapper">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Ressource</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Date de début</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $activity)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar-circle" style="width: 30px; height: 30px; font-size: 0.8rem; margin: 0;">
                                        {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                                    </div>
                                    {{ $activity->user->name }}
                                </div>
                            </td>
                            <td>{{ $activity->resource->name }} <small style="color: var(--text-gray);">({{ $activity->resource->code }})</small></td>
                            <td><span style="text-transform: capitalize;">{{ $activity->type }}</span></td>
                            <td>
                                <span class="status-badge {{ $activity->status }}">
                                    {{ $activity->status }}
                                </span>
                            </td>
                            <td>{{ $activity->start_date->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-gray); padding: 30px;">
                                Aucune activité récente enregistrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
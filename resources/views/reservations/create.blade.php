@extends('layout')

@section('content')

<div class="split-container">
    <div class="form-section">
        <div class="card">
            <h3>Réserver une Ressource</h3>
            <br>
            @if ($errors->any())
                <div class="alert-error">
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <form class="data-form" method="POST" action="{{ route('reservations.store') }}">
                @csrf

                <div class="form-group">
                    <label>Choisir la ressource :</label>
                    <select name="resource_id" required class="form-control">
                        @php
                            $value = 0;
                            if (isset($_GET['ressource_id'])){
                                $value = $_GET['ressource_id'];
                            }
                        @endphp
                        <option value="" disabled selected>-- Sélectionner --</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach ($category->resources as $resource)
                                    @if ($value != 0 && $value == $resource->id )
                                        <option selected value="{{ $resource->id }}" {{ old('resource_id') == $resource->id ? 'selected' : ''}}>
                                            {{ $resource->name }}
                                        </option>
                                    @else
                                        <option value="{{ $resource->id }}" {{ old('resource_id') == $resource->id ? 'selected' : ''}}>
                                            {{ $resource->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Début :</label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Fin :</label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Motif :</label>
                    <textarea name="justification" rows="2" placeholder="Ex: Projet final..." required class="form-control">{{ old('justification') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">Valider la réservation</button>
            </form>
        </div>
    </div>

    <div class="container-section">
        <div class="card">
            <div class="list-header">
                <h3> État des Ressources (Aujourd'hui)</h3>
                <span class="date-badge">{{ now()->format('d/m/Y') }}</span>
                <br>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>État Actuel</th>
                        <th>Créneaux Occupés</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resourcesList as $resource)
                        @php
                            $isOccupiedNow = $resource->reservations->contains(function ($res) {
                                return now()->between($res->start_date, $res->end_date);
                            });
                        @endphp
                        <tr>
                            <td style="font-weight: bold;">{{ $resource->name }}</td>

                            <td>
                                @if($isOccupiedNow)
                                    <span class="badge-status badge-busy">Occupé</span>
                                @else
                                    <span class="badge-status badge-free">Libre</span>
                                @endif
                            </td>

                            <td class="slots-cell">
                                @if($resource->reservations->isEmpty())
                                    <span class="text-muted">✅ Disponible toute la journée</span>
                                @else
                                    <div class="slots-list">
                                        @foreach($resource->reservations as $res)
                                            <span class="slot-tag">
                                                🕒 {{ $res->start_date->format('H:i') }}-{{ $res->end_date->format('H:i') }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
@if (!$resourcesList->isEmpty())
<div class="gantt-container">
    <h3 style="text-align: center; color:white; margin-bottom: 20px;">📅 Planning des Réservations</h3>

    @php
        $startHour = 8;
        $endHour = 24;
        $totalMinutes = ($endHour - $startHour) * 60; // 960 minutes totales
    @endphp
    <div class="chart-scroll-wrapper">
        <div class="gantt-chart">
            
            <div class="gantt-row header-row">
                <div class="gantt-sidebar">Ressource</div> 
                <div class="gantt-timeline">
                    @for ($i = 0; $i <= ($endHour - $startHour); $i++)
                        @php 
                            // Position en %
                            $leftPos = ($i / ($endHour - $startHour)) * 100;
                            
                            // Gestion de l'alignement pour ne pas couper le texte
                            $transform = 'translateX(-50%)'; // Par défaut : centré
                            
                            if ($i == 0) {
                                $transform = 'translateX(0%)'; // Premier : aligné gauche
                            } 
                            elseif ($i == ($endHour - $startHour)) {
                                $transform = 'translateX(-100%)'; // Dernier : aligné droite
                            }

                            // Affichage heure (00:00 au lieu de 24:00)
                            $hour = $startHour + $i;
                            $displayTime = ($hour == 24) ? '00:00' : $hour . ':00';
                        @endphp

                        <div class="time-marker" style="left: {{ $leftPos }}%; transform: {{ $transform }}">
                            {{ $displayTime }}
                        </div>
                    @endfor
                </div>
            </div>

            @foreach($resourcesList as $resource)
            <div class="gantt-row">
                <div class="gantt-sidebar">
                    <strong>{{ $resource->name }}</strong>
                </div>

                <div class="gantt-timeline">
                    <div class="grid-background">
                        @for ($i = 0; $i <= ($endHour - $startHour); $i++)
                            <div class="grid-line" style="left: {{ ($i / ($endHour - $startHour)) * 100 }}%"></div>
                        @endfor
                    </div>

                    @foreach($resource->reservations as $res)
                        @php
                            // Calcul précis en minutes
                            $resStartMinute = ($res->start_date->hour * 60) + $res->start_date->minute;
                            $resEndMinute   = ($res->end_date->hour * 60) + $res->end_date->minute;
                            
                            // Décalage par rapport à 8h00
                            $startOffset = max(0, $resStartMinute - ($startHour * 60));
                            $duration = $resEndMinute - $resStartMinute;

                            // Conversion en Pourcentage CSS
                            $leftPercent = ($startOffset / $totalMinutes) * 100;
                            $widthPercent = ($duration / $totalMinutes) * 100;
                        @endphp

                        @if($widthPercent > 0)
                        <div class="gantt-bar" 
                            style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%;"
                            title="{{ $res->user->name }} : {{ $res->start_date->format('H:i') }} - {{ $res->end_date->format('H:i') }}">
                            
                            <span style="padding: 0 5px; overflow: hidden; text-overflow: ellipsis;">
                                {{ $res->user->name }} 
                                <span style="font-size: 0.9em; opacity: 0.8;">
                                    ({{ $res->start_date->format('H:i') }} - {{ $res->end_date->format('H:i') }})
                                </span>
                            </span>

                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

    <style>
        .split-container{
            display: flex;
            flex-wrap: wrap;
            gap:20px;
            flex-direction: row;
        }
        .card{
            margin: 0;
        }
        .form-section{
            display: block;
            flex:1;
            min-width: 300px;
        }
        .container-section{
            flex: 2;
            min-width: 300px;
        }
        /* gant styling */
        .gantt-chart {
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #56b3a3;
            overflow: hidden;
            font-family: sans-serif;
            min-width: 800px;
        }

        /* Une Ligne (Header ou Ressource) */
        .chart-scroll-wrapper {
            overflow-x: auto; /* Active le scroll horizontal */
            -webkit-overflow-scrolling: touch; /* Scroll fluide sur iPhone */
            width: 100%;
            padding-bottom: 5px; /* Espace pour la barre de défilement */
        }
        .gantt-row {
            display: flex;
            border-bottom: 1px solid white;
            height: 50px; /* Hauteur d'une ligne */
            position: relative;
        }

        .header-row {
            background-color: #131c2e;
            color: #56b3a3;
            height: 40px;
            font-size: 0.65em;
        }

        /* Colonne Gauche (Noms) */
        .gantt-sidebar {
            width: 150px; /* Largeur fixe pour les noms */
            min-width: 100px;
            padding: 0 15px;
            display: flex;
            align-items: center;
            border-right: 1px solid #ddd;
            background: #131c2e;
            color: #56b3a3;
            font-size: 15px;
            z-index: 2; /* Reste au dessus */
        }

        /* Colonne Droite (Timeline) */
        .gantt-timeline {
            flex-grow: 1; /* Prend tout le reste de la place */
            position: relative; /* Important pour le positionnement absolute des barres */
        }

        /* Les chiffres des heures */
        .time-marker {
            position: absolute;
            transform: translateX(-50%); /* Pour centrer le texte sur le trait */
            top: 10px;
            font-weight: bold;
        }

        /* Lignes verticales grises */
        .grid-background {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
        }
        .grid-line {
            position: absolute;
            top: 0; bottom: 0;
            border-left: 1px solid #f0f0f0;
        }

        /* La Barre de Réservation */
        .gantt-bar {
            position: absolute;
            top: 10px; bottom: 10px; /* Marges haut/bas */
            background-color: #c98159;
            color: white;
            border-radius: 4px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .gantt-bar:hover {
            transform: scale(1.02);
            z-index: 10;
            background-color: #b56e48;
        }
    </style>

@endsection
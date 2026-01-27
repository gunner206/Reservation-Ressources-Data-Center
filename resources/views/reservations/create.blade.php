@extends('layout')

@section('content')
{{-- @if (!$resourcesList->isEmpty())
<h3 style="text-align: center;"> Chart des Reservation (Aujourd'hui) </h3>
<br>
<div id="gant">
    <script>
        let gant = document.getElementById("gant");
        for (let i = 0 ; i <= 16 ; i++){
            let header = document.createElement("div");
            header.className = "head";
            if (i == 16) header.innerHTML = "00:00";
            else header.innerHTML = i + 8 + ":00";
            gant.appendChild(header);
        }
    </script>
    @php
        static $row_count = 2; 
    @endphp
    @foreach($resourcesList as $resource)
        @foreach($resource->reservations as $res)
            @php
                $resStart = $res->start_date->hour - 7;
                $resEnd = $res->start_date->hour;
                $span = min(($res->end_date->hour - $res->start_date->hour),(16 - $resStart));
            @endphp
            <div style="margin:5px 0 5px 0; text-align: center; color: #242437; background-color: #c98159;grid-row: {{ $row_count }}; grid-column:{{ $resStart }} / span {{ $span }}">{{ $resource->name }}</div>
        @endforeach
        @php $row_count++ @endphp
    @endforeach
</div>
<br><br>
@endif --}}
@if (!$resourcesList->isEmpty())
<div class="gantt-container">
    <h3 style="text-align: center; color: #242437; margin-bottom: 20px;">📅 Planning des Réservations</h3>

    @php
        $startHour = 8;
        $endHour = 24;
        $totalMinutes = ($endHour - $startHour) * 60; // 960 minutes totales
    @endphp

    <div class="gantt-chart">
        <div class="gantt-row header-row">
            <div class="gantt-sidebar">Ressource</div> <div class="gantt-timeline">
                @for ($i = 0; $i <= ($endHour - $startHour); $i++)
                    <div class="time-marker" style="left: {{ ($i / ($endHour - $startHour)) * 100 }}%">
                        {{ $startHour + $i }}:00
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
                        
                        // Décalage par rapport à 8h00 (début journée)
                        $startOffset = max(0, $resStartMinute - ($startHour * 60));
                        $duration = $resEndMinute - $resStartMinute;

                        // Conversion en Pourcentage CSS
                        $leftPercent = ($startOffset / $totalMinutes) * 100;
                        $widthPercent = ($duration / $totalMinutes) * 100;
                    @endphp

                    <div class="gantt-bar" 
                         style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%;"
                         title="{{ $res->start_date->format('H:i') }} - {{ $res->end_date->format('H:i') }}">
                        <span class="bar-label">{{ $res->user->name ?? 'Réservé' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
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


    <style>
        h3 ,span{
            text-align : center;
        }
        .split-container{
            display: flex;
            flex-wrap: wrap;
            gap:20px;
            flex-direction: column;
        }
        .card{
            margin: 0;
        }
        .form-section{
            flex:1;
            min-width: 300px;
        }
        .container-section{
            flex: 2;
            min-width: 300px;
        }
        /* #gant {
            display: grid;
            grid-template-columns: repeat(17, minmax(0, 1fr));
            border: #242437 1px solid;
        }
        #gant .head {
            border: 1px #242437 solid;
            padding: 0 10px 0 10px;
            text-align: center;
            font-weight: 700;
            color: #56b3a3;
            background : #131c2e;
        } */
         /* Conteneur principal */
        .gantt-chart {
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            overflow: hidden;
            font-family: sans-serif;
        }

        /* Une Ligne (Header ou Ressource) */
        .gantt-row {
            display: flex;
            border-bottom: 1px solid #eee;
            height: 50px; /* Hauteur d'une ligne */
            position: relative;
        }

        .header-row {
            background-color: #131c2e;
            color: #56b3a3;
            height: 40px;
            font-size: 0.85em;
        }

        /* Colonne Gauche (Noms) */
        .gantt-sidebar {
            width: 200px; /* Largeur fixe pour les noms */
            min-width: 200px;
            padding: 0 15px;
            display: flex;
            align-items: center;
            border-right: 1px solid #ddd;
            background: #f9f9f9;
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
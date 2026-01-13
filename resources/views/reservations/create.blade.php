@extends('layout')
@section('content')
<div class="planning-container">
    <h3>Planning d'Aujourd'hui (08h - 18h)</h3>

    <div class="timeline-header">
        <div class="header-name">Ressource</div>
        <div class="header-hours">
            @for($h = 8; $h <= 18; $h++)
                <span style="left: {{ ($h - 8) * 10 }}%">{{ $h }}h</span>
            @endfor
        </div>
    </div>

    @foreach($resources as $resource)
        <div class="timeline-row">
            <div class="row-name">
                {{ $resource->name }}
            </div>

            <div class="row-track">
                @foreach($resource->reservations as $res)
                    @php
                        // --- TON ALGORITHME DE CALCUL --- //
                        
                        // Bornes de la journée (en minutes)
                        $startDay = 8 * 60;  // 480 min (08h00)
                        $endDay = 18 * 60;   // 1080 min (18h00)
                        $totalDay = $endDay - $startDay; // 600 min

                        // Conversion des dates de la réservation en minutes
                        $startRes = ($res->start_date->hour * 60) + $res->start_date->minute;
                        $endRes   = ($res->end_date->hour * 60) + $res->end_date->minute;

                        // Sécurité : Si ça dépasse les bornes 8h-18h
                        $startRes = max($startRes, $startDay);
                        $endRes = min($endRes, $endDay);

                        // Calcul des Pourcentages (Règle de 3)
                        $leftPercent = ($startRes - $startDay) / $totalDay * 100;
                        $widthPercent = ($endRes - $startRes) / $totalDay * 100;
                    @endphp

                    <div class="event-block status-{{ $res->status }}" 
                         style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%;"
                         title="{{ $res->start_date->format('H:i') }} - {{ $res->end_date->format('H:i') }}">
                        <small>{{ $res->user->name }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
<div class="reservation-form">
    <fieldset>
        <legend>Reservation</legend>
        <form method="post" action="/reservation">
            <label for='resource'>Choisire un resource </label>
            <select name='resource_id' id='resource'>
                @foreach($categories as $categorie)
                    <optgroup label="{{ categorie->name }}">
                        @foreach ($categorie->resources as resource)
                            <option value="{{ resource->id }}"
                                {{ old('resource_id') == $resource->id ? 'selected' : ''}}>
                                {{ resource->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <inpud type="date" name="start_date" value="{{ old('start_date') }}">
            <input type="date" name="end_date" value="{{ old('end_date') }}">
            <input type="time" name="start_time" value="{{ old('start_time') }}">
            <input type="time" name="end_time" value="{{ old('end_time') }}">
            <input type="text" name="justification" placeholder="Justification" value="{{ old('justification') }}">
            <button type="submit">Reserver</button>
        </form>
    </fieldset>
</div>
@endsection
@extends('layout')

@section('content')
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
            <div style="margin:5px 0 5px 0; text-align: center; border-radius: 10px; color: #242437; background-color: #c98159;grid-row: {{ $row_count }}; grid-column:{{ $resStart }} / span {{ $span }}">{{ $resource->name }}</div>
        @endforeach
        @php $row_count++ @endphp
    @endforeach
</div>
<br><br>

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
        #gant {
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
        }
    </style>

@endsection
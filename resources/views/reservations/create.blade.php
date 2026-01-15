@extends('layout')

@section('content')
<div class="split-container">

    <div class="form-section">
        <div class="card">
            <h3>📝 Réserver une Ressource</h3>

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
                        <option value="" disabled selected>-- Sélectionner --</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach ($category->resources as $resource)
                                    <option value="{{ $resource->id }}" {{ old('resource_id') == $resource->id ? 'selected' : ''}}>
                                        {{ $resource->name }}
                                    </option>
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

    <div class="table-container">
        <div class="card">
            <div class="list-header">
                <h3>🚦 État des Ressources (Aujourd'hui)</h3>
                <span class="date-badge">{{ now()->format('d/m/Y') }}</span>
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
                            // On vérifie si la ressource est occupée "maintenant"
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

@endsection
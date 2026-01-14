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

            <form method="POST" action="{{ route('reservations.store') }}">
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

    <div class="list-section">
        <div class="card">
            <div class="list-header">
                <h3>🚦 État des Ressources (Aujourd'hui)</h3>
                <span class="date-badge">{{ now()->format('d/m/Y') }}</span>
            </div>

            <table class="availability-table">
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
<style>
    /* Layout Global */
.split-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    max-width: 1200px;
    margin: 20px auto;
    align-items: flex-start;
}
.form-section { flex: 1; min-width: 300px; }
.list-section { flex: 2; min-width: 400px; }

/* Style Card */
.card {
    color: black;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

/* Formulaire */
.form-control {
    width: 100%; padding: 10px; margin-bottom: 15px;
    border: 1px solid #ddd; border-radius: 5px;
}
.btn-submit {
    width: 100%; background: #007bff; color: white;
    padding: 12px; border: none; border-radius: 5px;
    font-weight: bold; cursor: pointer;
}
.btn-submit:hover { background: #0056b3; }

/* --- TABLEAU DE DISPONIBILITÉ --- */
.list-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 15px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;
}
.date-badge {
    background: #e9ecef; color: #495057; padding: 5px 10px;
    border-radius: 20px; font-size: 0.9em; font-weight: bold;
}

.availability-table {
    width: 100%; border-collapse: collapse;
}
.availability-table th {
    text-align: left; padding: 10px; color: #6c757d; font-size: 0.85em;
    text-transform: uppercase; border-bottom: 1px solid #eee;
}
.availability-table td {
    padding: 12px 10px; border-bottom: 1px solid #f8f9fa; vertical-align: middle;
}

/* Badges État */
.badge-status {
    padding: 5px 10px; border-radius: 5px; font-size: 0.8em; font-weight: bold;
    display: inline-block; min-width: 60px; text-align: center;
}
.badge-free { background-color: #d4edda; color: #155724; } /* Vert */
.badge-busy { background-color: #f8d7da; color: #721c24; } /* Rouge */

/* Liste des Créneaux (Tags gris) */
.slots-list { display: flex; flex-wrap: wrap; gap: 5px; }
.slot-tag {
    background: #f1f3f5; color: #333; padding: 3px 8px;
    border-radius: 4px; font-size: 0.85em; border: 1px solid #e9ecef;
}
.text-muted { color: #adb5bd; font-style: italic; font-size: 0.9em; }
</style>   
@endsection
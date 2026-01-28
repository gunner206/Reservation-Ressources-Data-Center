@extends('layout')

@section('content')

    <div class="form-container">
        <h1>Nouvelle Ressource</h1>

        <form class="data-form" method="POST" action="{{ route('ressources.store') }}">
            @csrf

            <label>Nom de l'équipement :</label>
            <input type="text" name="name" placeholder="Ex: Serveur HP ProLiant" required>

            <label>Code d'inventaire :</label>
            <input type="text" name="code" placeholder="Ex: SRV-001" required>

            <label>Description :</label>
            <textarea name="description" placeholder="Détails de l'emplacement ou usage"></textarea>

            <label>Catégorie :</label>
            <select name="category_id" required>
                <option value="">-- Choisir une catégorie --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <fieldset style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                <legend>Spécifications Techniques</legend>
                <input type="text" name="cpu" placeholder="CPU (ex: Intel Xeon)">
                <input type="text" name="ram" placeholder="RAM (ex: 64GB)">
                <input type="text" name="stockage" placeholder="Stockage (ex: 2TB SSD)">
                <input type="text" name="os" placeholder="Système d'exploitation">
            </fieldset>

            <label>État de la ressource :</label>
            <select name="is_active" required>
                <option value="1">Actif (Disponible)</option>
                <option value="0">Inactif (Maintenance/HS)</option>
            </select>

            <button type="submit">ENREGISTRER LA RESSOURCE</button>
        </form>
    </div>

@endsection
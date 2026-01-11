@extends('layout')
@section('content')
    <fieldset>
        <legend>Reservation</legend>
        <form method="post" action="/reservation">
            <label for='resource'>Choisire un resource </label>
            <select name='resource_id' id='resource'>
                @foreach($categories as $categorie)
                    <optgroup label="{{ categorie->name }}">
                        @foreach ($categore->resources as resource)
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
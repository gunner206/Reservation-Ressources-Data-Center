@extends('layout')
@section('content')
    <h2> Mes Reservations </h2>
    {{--@if (auth()->user()->role === 'internal') 
        <a href="{{ route('reservations.create') }}"> Creer une reservation </a>
    @else

    @endif --}}
    <a href="{{ route('reservations.create') }}"> Creer une reservation </a>

@endsection
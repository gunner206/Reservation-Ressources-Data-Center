@extends('layout')
@section('content')
<h1>Bienvenue, {{ Auth::user()->name }} !</h1>
<p>Vous êtes connecté en tant que : <strong>{{ Auth::user()->role }}</strong></p>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Se déconnecter</button>
</form>
@endsection


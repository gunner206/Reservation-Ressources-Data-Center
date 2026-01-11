<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <title>centrum</title>
</head>
<body>
  <header class="navbar">
        <div class="logo">
            <img src="{{asset('images/logo.png')}}" alt="Centrum Logo" width="250" height="auto">
        </div>
        <nav class="menu">
            <a href="/dashboard">Dashboard</a>
            <a href="/reservation">Reservation</a>
            <a href="/contacts">Contacts</a>
        </nav>
        <div class="auth-buttons">
            <a href="/register" class="btn-signup">Sign up</a>
            <a href="/login" class="btn-login">Login</a>
        </div>
    </header>
    <main>
        <div class="content">
        @yield('content')
    </div>
    </main>
    <footer>
       <div class="description">
            <img src="{{asset('images/logo.png')}}" alt="Centrum Logo" width="120" height="auto">
             <p>Système complet pour consulter, réserver et administrer les serveurs, VM, stockages et équipements réseau d’un Data Center, avec gestion avancée des rôles et permissions.</p>
       </div>
       <div class="activitie">
         <h3>Activie</h3>
        <a href="/dashboard">Dashboard</a>
        <a href="/reservation">Reservation</a>

       </div>
       <div class="Contacts">
        <h3>Contact</h3>
        <p>Département Informatique - Université</p>
        <p>Email: <a href="mailto:reservation-info@fstt.uae.ma">reservation-info@fstt.uae.ma</a></p>
        <p>Téléphone: <a href="tel:+2120666123456">+212(0)666123456</a></p>
       </div>
       <div class="social">
            <a href="https://github.com/gunner206/Reservation-Ressources-Data-Center"><img src="{{asset('images/git.png')}}" alt="Centrum Logo" width="120" height="auto"></a>
            <img src="{{asset('images/tube.png')}}" alt="Centrum Logo" width="120" height="auto">
            <img src="{{asset('images/F.png')}}" alt="Centrum Logo" width="120" height="auto">
       </div>
        </footer>
</body>
</html>

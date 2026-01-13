<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Data Center</title>
     <link rel="stylesheet" href="{{asset('css/login.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>

    <div class="container" id="container">
        
        <div class="form-container sign-up">
            <form action="{{ route('register') }}" method="POST">
                    @csrf <h1>Créer un compte</h1>
                    
                    <input type="text" name="name" placeholder="Nom" required />
                    
                    <input type="email" name="email" placeholder="Email" required />
                    
                    <input type="password" name="password" placeholder="Mot de passe" required />
                    
                    <button type="submit">S'INSCRIRE</button>

                <div class="social-icons">
                    <a href="www.google.com" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="www.facebook.com" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="www.github.com" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="www.linkedin.com" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                
              </form> 
        </div>

        <div class="form-container sign-in">
            <form action="{{ route('login') }}" method="POST">
                @csrf <h1>Se connecter</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>ou utilisez votre email et mot de passe</span>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                <input type="password" name="password" placeholder="Mot de passe" required>
                <a href="#">Mot de passe oublié ?</a>
                <button type="submit">Se connecter</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Content de vous revoir !</h1>
                    <p>Entrez vos informations personnelles pour utiliser toutes les fonctionnalités du site</p>
                    <button class="hidden" id="login">Se connecter</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bonjour, Admin !</h1>
                    <p>Inscrivez-vous avec vos informations personnelles pour commencer</p>
                    <button class="hidden" id="register">S'inscrire</button>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('css/login.js') }}"></script>
</body>
</html>
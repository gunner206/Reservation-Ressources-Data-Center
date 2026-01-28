<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Data Center</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="container" id="container">
        
        <div class="form-container sign-up">
            <form action="{{ route('register') }}" method="POST">
                @csrf 
                <h1>Créer un compte</h1>
                <p class="social-text">Utilisez votre email universitaire</p>
                
                <input type="text" name="name" placeholder="Nom complet" required />
                <input type="text" name="cne" placeholder="Code CNE" required />
                <input type="email" name="email" placeholder="Email" required />

                {{-- Champ Mot de Passe --}}
                <div class="input-group">
                    <input type="password" name="password" id="reg-pass" placeholder="Mot de passe" required />
                    
                    <img src="{{ asset('images/eye.png') }}" 
                         id="eye-icon-reg"
                         onclick="togglePassword('reg-pass', 'eye-icon-reg')" 
                         alt="Voir"
                         style="width: 20px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                </div>

                {{-- Champ Confirmation Mot de Passe --}}
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="reg-confirm" placeholder="Confirmer mot de passe" required />
                    
                    <img src="{{ asset('images/eye.png') }}" 
                         id="eye-icon-confirm"
                         onclick="togglePassword('reg-confirm', 'eye-icon-confirm')" 
                         alt="Voir"
                         style="width: 20px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                </div>
                
                <button type="submit">S'INSCRIRE</button>
            </form> 
        </div>

        <div class="form-container sign-in">
            <form action="{{ route('login') }}" method="POST">
                @csrf 
                <h1>Se connecter</h1>
                <p class="social-text">Accédez à votre espace</p>

                @if ($errors->any())
                    <div style="color: red; font-size: 0.8rem; margin-bottom: 10px;">
                        {{ $errors->first() }}
                    </div>
                @endif

               <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">

                {{-- Champ Mot de Passe Connexion --}}
                <div class="input-group">
                    <input type="password" name="password" id="login-pass" placeholder="Mot de passe" required>
                    
                    <img src="{{ asset('images/eye.png') }}" 
                         id="eye-icon-login"
                         onclick="togglePassword('login-pass', 'eye-icon-login')" 
                         alt="Voir"
                         style="width: 20px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                </div>
                   <p> Mot de passe oublié ? Contactez un Administrateur.</p>
                                    
                <button type="submit">SE CONNECTER</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Content de vous revoir !</h1>
                    <p class="p">Pour rester connecté avec nous, veuillez vous connecter</p>
                    <button class="hidden" id="login">Se connecter</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bonjour !</h1>
                    <p class="p">Entrez vos détails personnels et commencez votre voyage</p>
                    <button class="hidden" id="register">S'inscrire</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script chargé à la fin --}}
    <script src="{{ asset('css/login.js') }}"></script> 
</body>
</html>
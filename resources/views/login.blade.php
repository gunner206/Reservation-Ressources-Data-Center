<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Data Center</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* Colle le CSS que je t'ai donné au-dessus ici si besoin */
    </style>
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

                <div class="input-group">
                    <input type="password" name="password" id="reg-pass" placeholder="Mot de passe" required />
                    <i class="fa-solid fa-eye" onclick="togglePassword('reg-pass', this)"></i>
                </div>

                <div class="input-group">
                    <input type="password" name="password_confirmation" id="reg-confirm" placeholder="Confirmer mot de passe" required />
                    <i class="fa-solid fa-eye" onclick="togglePassword('reg-confirm', this)"></i>
                </div>
                
                <button type="submit">S'INSCRIRE</button>

                <div class="separator">
                    <span>ou</span>
                </div>

                <a href="{{ url('/auth/google') }}" class="google-btn">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
                    Continuer avec Google
                </a>
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

                <div class="input-group">
                    <input type="password" name="password" id="login-pass" placeholder="Mot de passe" required>
                    <i class="fa-solid fa-eye" onclick="togglePassword('login-pass', this)"></i>
                </div>
                
                <a href="{{ route('password.request') }}" style="margin-top: 10px; display: inline-block; color: #333; text-decoration: none;">Mot de passe oublié ?</a>
                
                <button type="submit">SE CONNECTER</button>

                <div class="separator">
                    <span>ou</span>
                </div>

                <a href="{{ url('/auth/google') }}" class="google-btn" style="display: flex; align-items: center; justify-content: center; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background-color: white; text-decoration: none; color: #333; margin-top: 15px;">
    
    <img src="https://www.svgrepo.com/show/475656/google-color.svg" 
         alt="Google" 
         style="width: 20px !important; height: 20px !important; margin-right: 10px;">
    
    Continuer avec Google
</a>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Content de vous revoir !</h1>
                    <p>Pour rester connecté avec nous, veuillez vous connecter avec vos informations personnelles</p>
                    <button class="hidden" id="login">Se connecter</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bonjour, Étudiant !</h1>
                    <p>Entrez vos détails personnels et commencez votre voyage avec nous</p>
                    <button class="hidden" id="register">S'inscrire</button>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('css/login.js') }}"></script> 
</body>
</body>
</html>
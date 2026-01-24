<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - DataCenter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
        }
        .container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            width: 400px;
            padding: 40px;
            text-align: center;
        }
        h1 { margin-bottom: 20px; color: #333; }
        p { color: #666; font-size: 14px; margin-bottom: 30px; }
        
        input {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
            box-sizing: border-box;
        }
        
        button {
            background-color: #512da8;
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }
        button:hover { background-color: #432290; }
        
        .back-link {
            margin-top: 20px;
            display: block;
            color: #333;
            text-decoration: none;
            font-size: 12px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Récupération</h1>
        <p>Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        
        @if (session('status'))
            <div style="color: green; margin-bottom: 10px; font-size: 13px;">
                {{ session('status') }}
            </div>
        @endif

        @error('email')
            <div style="color: red; margin-bottom: 10px; font-size: 13px;">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" placeholder="Entrez votre email" required>
            <button type="submit">Envoyer le lien</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">← Retour à la connexion</a>
    </div>

</body>
</html>
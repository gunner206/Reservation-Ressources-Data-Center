<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe</title>
    <style>
        body {
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: sans-serif;
            margin: 0;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
            width: 350px;
        }
        h2 { margin-top: 0; color: #333; }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: #eee;
            border: none;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: #512da8;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            margin-top: 10px;
        }
        button:hover { background-color: #432290; }
    </style>
</head>
<body>

<div class="card">
    <h2>Réinitialisation</h2>
    
    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px; font-size: 14px;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input type="email" name="email" placeholder="Confirmez votre email" required>
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>

        <button type="submit">Changer le mot de passe</button>
    </form>
</div>

</body>
</html>
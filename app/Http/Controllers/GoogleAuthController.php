<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite; // Important pour utiliser Socialite
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    // Fonction qui redirige vers Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Fonction qui gère le retour de Google
    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();

            // Ici, on vérifie si l'utilisateur existe déjà, sinon on le crée
            $user = User::where('email', $google_user->getEmail())->first();

            if (!$user) {
                // Création d'un nouvel utilisateur si inexistant
                $user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'password' => bcrypt('123456dummy') // Un mot de passe bidon car il se connecte via Google
                ]);
            }

            // On connecte l'utilisateur
            Auth::login($user);

            // On le redirige vers l'accueil (ou le dashboard)
            return redirect()->intended('dashboard');

        } catch (\Throwable $th) {
            // En cas d'erreur, on redirige vers le login avec un message
            return redirect()->route('login')->with('error', 'Erreur de connexion Google : ' . $th->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Afficher la page de login
    public function showLoginForm()
    {
        return view('login');
    }

    // 2. Traiter la connexion
    public function login(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirection selon le rôle (On fera mieux après, pour l'instant : dashboard)
            return redirect()->intended('dashboard');
        }

        // Si échec
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas.',
        ])->onlyInput('email');
    }

    // 3. Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
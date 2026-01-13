<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- Vérifie que cette ligne est là aussi
use App\Models\User;

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
    // 4. TRAITER L'INSCRIPTION
    public function register(Request $request) {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // unique vérifie si l'email existe déjà
            'password' => 'required|min:6',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // On crypte le mot de passe
            'role' => 'internal', // Rôle par défaut
            'status' => 'active',
            'department' => 'Nouveau',
        ]);

        // Connexion automatique après inscription
        Auth::login($user);

        // Redirection vers le dashboard
        return redirect()->route('dashboard');
    }
}
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Page d'accueil (Publique)
Route::get('/', function () {
    return view('login');
});

// Routes pour les invités (Non connectés)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes protégées (Connectés seulement)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Page Dashboard temporaire pour tester
    Route::get('/dashboard', function () {
        return "Bienvenue " . auth()->user()->name . " ! Votre rôle est : " . auth()->user()->role;
    });
});
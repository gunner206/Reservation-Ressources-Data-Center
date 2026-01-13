<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RessourceController;
use Illuminate\Support\Facades\Auth; // <-- N'oublie pas d'ajouter ça pour le check Auth

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PAGE D'ACCUEIL (Modifiée)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('layout');
})->name('home');

// --------------------
// ZONE INVITÉS (Guest)
// --------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// --------------------
// ZONE SÉCURISÉE (Auth)
// --------------------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        // Compteurs pour le dashboard
        $totalUsers = \App\Models\User::count(); 
        $totalResources = 0; // Remplace par \App\Models\Ressource::count() quand tu auras le modèle
        
        return view('dashboard', compact('totalUsers', 'totalResources')); 
    })->name('dashboard');

    Route::resource('reservations', ReservationController::class);
    Route::patch('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{id}/refuse', [ReservationController::class, 'refuse'])->name('reservations.refuse');

    Route::resource('ressources', RessourceController::class);
});
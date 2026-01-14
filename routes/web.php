<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RessourceController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PAGE D'ACCUEIL
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('layout');
})->name('home');

// --------------------
// ZONE INVITÉS (Non connectés)
// --------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// --------------------
// ZONE SÉCURISÉE (Utilisateurs connectés)
// --------------------
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $totalUsers = \App\Models\User::count(); 
        $totalResources = \App\Models\Resource::count(); 
        return view('dashboard', compact('totalUsers', 'totalResources')); 
    })->name('dashboard');

    // --- ROUTES RÉSERVATIONS ---
    Route::resource('reservations', ReservationController::class);
    Route::patch('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{id}/refuse', [ReservationController::class, 'refuse'])->name('reservations.refuse');

    // --- ROUTES RESSOURCES ---

    // A. Routes Accessibles à TOUS les connectés (Admin, Manager, Internal)
    Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources.index');

    // B. Routes Création et Edition (Admin & Manager uniquement)
    // NOTE : Placées AVANT la route {id} pour éviter l'erreur 404
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/ressources/create', [RessourceController::class, 'create'])->name('ressources.create');
        Route::post('/ressources', [RessourceController::class, 'store'])->name('ressources.store');
        Route::get('/ressources/{id}/edit', [RessourceController::class, 'edit'])->name('ressources.edit');
        Route::put('/ressources/{id}', [RessourceController::class, 'update'])->name('ressources.update');
    });

    // C. Route Détails (Doit rester après le /create)
    Route::get('/ressources/{id}', [RessourceController::class, 'show'])->name('ressources.show');

    // D. Route Suppression (Admin seulement)
    Route::delete('/ressources/{id}', [RessourceController::class, 'destroy'])
          ->middleware('role:admin')
          ->name('ressources.destroy');
});

// --------------------
// ZONE TESTS
// --------------------
Route::get('/test-simple', fn() => "TEST SIMPLE - OK");
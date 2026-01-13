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

    // Action de validation
    Route::patch('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{id}/refuse', [ReservationController::class, 'refuse'])->name('reservations.refuse');

    Route::resource('ressources', RessourceController::class); } )  ;

// --------------------
// ZONE RESSOURCES
// --------------------

// TESTS (à supprimer plus tard)
Route::get('/test-simple', function() {
    return "TEST SIMPLE - OK";
});

// ROUTES RESSOURCES
Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources.index');
Route::get('/ressources/create', [RessourceController::class, 'create'])->name('ressources.create');
Route::post('/ressources', [RessourceController::class, 'store'])->name('ressources.store');

// Routes pour CRUD complet
Route::get('/ressources/{id}', [RessourceController::class, 'show'])->name('ressources.show');
Route::get('/ressources/{id}/edit', [RessourceController::class, 'edit'])->name('ressources.edit');
Route::put('/ressources/{id}', [RessourceController::class, 'update'])->name('ressources.update');
Route::delete('/ressources/{id}', [RessourceController::class, 'destroy'])->name('ressources.destroy');



// Test ultra-simple
Route::get('/test-ressources', function() {
    return "TEST RESSOURCES - DIRECT";
});


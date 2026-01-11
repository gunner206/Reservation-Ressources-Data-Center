<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;      
use App\Http\Controllers\RessourceController; 

// Page d'accueil (Publique) -> Affiche le Login
Route::get('/', function () {
    return view('layout');
});

// Routes protégées (Connectés seulement)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Page Dashboard temporaire
    Route::get('/dashboard', function () {
        return "Bienvenue " . auth()->user()->name . " ! Votre rôle est : " . auth()->user()->role;
    });
});

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

// Routes Réservation
Route::get('/reservation/create', [ReservationController::class, 'create']);
Route::post('/reservation', [ReservationController::class, 'store']);

// Test ultra-simple
Route::get('/test-ressources', function() {
    return "TEST RESSOURCES - DIRECT";
});
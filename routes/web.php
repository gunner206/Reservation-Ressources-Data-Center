<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessourceController;

Route::get('/', function () {
    return view('welcome');
});

// TESTS (à supprimer plus tard)
Route::get('/test-simple', function() {
    return "TEST SIMPLE - OK";
});

// ROUTES RESSOURCES (version manuelle)
Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources.index');
Route::get('/ressources/create', [RessourceController::class, 'create'])->name('ressources.create');
Route::post('/ressources', [RessourceController::class, 'store'])->name('ressources.store');

// Test ultra-simple
Route::get('/test-ressources', function() {
    return "TEST RESSOURCES - DIRECT";
});
// Routes manquantes pour CRUD complet
Route::get('/ressources/{id}', [RessourceController::class, 'show'])->name('ressources.show');
Route::get('/ressources/{id}/edit', [RessourceController::class, 'edit'])->name('ressources.edit');
Route::put('/ressources/{id}', [RessourceController::class, 'update'])->name('ressources.update');
Route::delete('/ressources/{id}', [RessourceController::class, 'destroy'])->name('ressources.destroy');
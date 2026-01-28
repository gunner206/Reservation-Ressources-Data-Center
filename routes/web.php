<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Importation des Contrôleurs
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ContactController;

// Importation des Modèles
use App\Models\User;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Incident;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTES PUBLIQUES & ACCUEIL
// ==========================================

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Récupération des données pour la landing page
    $reservations = Reservation::with(['user', 'resource'])->latest()->get();
    $resources = Resource::with('category')->where('is_active', true)->get();

    return view('welcome', compact('reservations', 'resources'));
})->name('home');

// Pages d'information
Route::get('/about', [ContactController::class, 'about'])->name('about');
Route::get('/rules', function () {
    return view('rules');
});


// ==========================================
// 2. ZONE INVITÉS (GUEST)
// Connexion & Inscription uniquement
// ==========================================
Route::middleware('guest')->group(function () {

    // Affichage du formulaire de connexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    // Traitement de la connexion
    Route::post('/login', [AuthController::class, 'login']);
    
    // Traitement de l'inscription
    Route::post('/register', [AuthController::class, 'register'])->name('register');

});


// ==========================================
// 3. ZONE SÉCURISÉE (UTILISATEURS CONNECTÉS)
// ==========================================
Route::middleware('auth')->group(function () {

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- DASHBOARD ---
    Route::get('/dashboard', function () {
        $totalResources = Resource::count();
        $activeResources = Resource::where('is_active', true)->count();
        
        // Calcul des statistiques
        $stats = [
            'total_users'          => User::count(),
            'reservations_pending' => Reservation::where('status', 'pending')->count(),
            'critical_incidents'   => Incident::whereIn('priority', ['high', 'critical'])->where('status', 'open')->count(),
            'infra_health'         => $totalResources > 0 ? round(($activeResources / $totalResources) * 100) : 0,
            'occupancy'            => $totalResources > 0 ? round((Reservation::where('status', 'approved')->count() / $totalResources) * 100) : 0,
        ];
        
        // Activités récentes
        $recentActivities = Reservation::with(['user', 'resource'])->latest()->take(6)->get();

        return view('dashboard', compact('stats', 'recentActivities'));
    })->name('dashboard');

    // --- RÉSERVATIONS ---
    Route::resource('reservations', ReservationController::class);
    // Actions spécifiques (Approuver / Refuser)
    Route::patch('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{id}/refuse', [ReservationController::class, 'refuse'])->name('reservations.refuse');

    // --- RESSOURCES ---
    // 1. Lecture pour tous (Voir le matériel)
    Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources.index');
    Route::get('/ressources/{id}', [RessourceController::class, 'show'])->name('ressources.show');

    // 2. Gestion (Création/Edition) -> Admin & Manager uniquement
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/ressources/create', [RessourceController::class, 'create'])->name('ressources.create');
        Route::post('/ressources', [RessourceController::class, 'store'])->name('ressources.store');
        Route::get('/ressources/{id}/edit', [RessourceController::class, 'edit'])->name('ressources.edit');
        Route::put('/ressources/{id}', [RessourceController::class, 'update'])->name('ressources.update');
    });

    // 3. Suppression -> Admin uniquement
    Route::delete('/ressources/{id}', [RessourceController::class, 'destroy'])
          ->middleware('role:admin')
          ->name('ressources.destroy');

    // --- SUPPORT / TICKETS ---
    Route::get('/support', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/support', [TicketController::class, 'store'])->name('tickets.store');

    // --- NOTIFICATIONS ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

// ==========================================
// 4. ZONE TEST (Optionnel - Pour Debug)
// ==========================================
Route::get('/test-db', function() {
    return [
        'status' => 'OK',
        'database_check' => 'Connected',
        'counts' => [
            'users'        => User::count(),
            'resources'    => Resource::count(),
        ]
    ];
});
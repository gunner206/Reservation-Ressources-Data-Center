<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RessourceController;
use Illuminate\Support\Facades\Auth;

// Importation des modèles pour les statistiques et le dashboard
use App\Models\User;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Incident;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PAGE D'ACCUEIL & REDIRECTION
// Redirige vers le dashboard si l'utilisateur est déjà connecté, sinon affiche la vue welcome
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
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

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // DASHBOARD DYNAMIQUE (Hero Cards & Activités)
    Route::get('/dashboard', function () {
        // Calcul des ressources et de la santé de l'infrastructure
        $totalResources = Resource::count();
        $activeResources = Resource::where('is_active', true)->count();
        $availableResources = Resource::where('is_active', true)
            ->whereDoesntHave('reservations', function($query) {
                $query->whereIn('status', ['pending', 'approved'])
                      ->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            })->count();

        // Préparation des statistiques avancées (Stats Cards)
        $stats = [
            'total_users'          => User::count(),
            'reservations_pending' => Reservation::where('status', 'pending')->count(),
            'critical_incidents'   => Incident::whereIn('priority', ['high', 'critical'])
                                              ->where('status', 'open')
                                              ->count(),
            // % de ressources actives
            'infra_health'         => $totalResources > 0 ? round(($activeResources / $totalResources) * 100) : 0,
            // % de ressources occupées (approuvées)
            'occupancy'            => $totalResources > 0
                ? round((Reservation::where('status', 'approved')->count() / $totalResources) * 100)
                : 0,
        ];

        // Récupération des dernières activités avec relations User et Resource (Tableau)
        $recentActivities = Reservation::with(['user', 'resource'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('stats', 'recentActivities'));
    })->name('dashboard');

    // GESTION DES RÉSERVATIONS (CRUD complet + Actions de validation)
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
    // GESTION DES RESSOURCES (CRUD complet protégé)
    Route::resource('ressources', RessourceController::class);

    //ststeme de notification
    Route::get('/notifications', function () {
    $notifications = \App\Models\Notification::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();
    
    // Marquer tout comme lu dès qu'on ouvre la page
    \App\Models\Notification::where('user_id', auth()->id())
        ->whereNull('read_at')
        ->update(['read_at' => now()]);
        
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

// --------------------
// ZONE DE TEST & DIAGNOSTIC
// --------------------
Route::get('/test-db', function() {
    return [
        'status' => 'OK',
        'counts' => [
            'users'        => User::count(),
            'resources'    => Resource::count(),
            'reservations' => Reservation::count(),
            'incidents'    => Incident::count()
        ]
    ];
});


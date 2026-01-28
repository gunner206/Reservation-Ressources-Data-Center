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
use App\Http\Controllers\UserController;

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
    $reservations = Reservation::with(['user', 'resource'])->latest()->get();
    $resources = Resource::with('category')->where('is_active', true)->get();

    return view('welcome', compact('reservations', 'resources'));
})->name('home');

Route::get('/about', [ContactController::class, 'about'])->name('about');
Route::get('/rules', function () {
    return view('rules');
})->name('rules');


// ==========================================
// 2. ZONE INVITÉS (GUEST)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});


// ==========================================
// 3. ZONE SÉCURISÉE (UTILISATEURS CONNECTÉS)
// ==========================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- DASHBOARD ---
    Route::get('/dashboard', function () {
        $totalResources = Resource::count();
        $activeResources = Resource::where('is_active', true)->count();
        
        $stats = [
            'total_users'          => User::count(),
            'reservations_pending' => Reservation::where('status', 'pending')->count(),
            'critical_incidents'   => Incident::whereIn('priority', ['high', 'critical'])->where('status', 'open')->count(),
            'infra_health'         => $totalResources > 0 ? round(($activeResources / $totalResources) * 100) : 0,
            'occupancy'            => $totalResources > 0 ? round((Reservation::where('status', 'approved')->count() / $totalResources) * 100) : 0,
        ];
        
        $recentActivities = Reservation::with(['user', 'resource'])->latest()->take(6)->get();

        return view('dashboard', compact('stats', 'recentActivities'));
    })->name('dashboard');

    // --- RÉSERVATIONS ---
    Route::resource('reservations', ReservationController::class);
    Route::patch('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{id}/refuse', [ReservationController::class, 'refuse'])->name('reservations.refuse');

    // --- RESSOURCES ---
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/ressources/create', [RessourceController::class, 'create'])->name('ressources.create');
        Route::post('/ressources', [RessourceController::class, 'store'])->name('ressources.store');
        Route::get('/ressources/{id}/edit', [RessourceController::class, 'edit'])->name('ressources.edit');
        Route::put('/ressources/{id}', [RessourceController::class, 'update'])->name('ressources.update');
    });

    Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources.index');
    Route::get('/ressources/{id}', [RessourceController::class, 'show'])->name('ressources.show');
    Route::delete('/ressources/{id}', [RessourceController::class, 'destroy'])->middleware('role:admin')->name('ressources.destroy');

    // --- SUPPORT / TICKETS ---
    Route::get('/support', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/support', [TicketController::class, 'store'])->name('tickets.store');
    // Ajout de la route index pour éviter l'erreur
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');

   // --- UTILISATEURS (ADMIN) ---
    // 1. Liste des utilisateurs
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // 2. Modification (pour changer le rôle)
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

    // 3. Suppression (Bannir)
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // --- NOTIFICATIONS ---
    Route::get('/notifications', function () {
        $user = auth()->user();
        $notifications = \App\Models\Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        \App\Models\Notification::where('user_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');
});

// ==========================================
// 5. ZONE TEST
// ==========================================
Route::get('/test-db', function() {
    return [
        'status' => 'OK',
        'database_check' => 'Connected',
        'counts' => ['users' => User::count(), 'resources' => Resource::count()]
    ];
});
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

// Importation des Contrôleurs
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GoogleAuthController;
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
    $reservations = Reservation::with(['user', 'resource'])->latest()->get();
    $resources = Resource::with('category')->where('is_active', true)->get();

    return view('welcome', compact('reservations', 'resources'));

})->name('home');

Route::get('/about', [ContactController::class, 'about'])->name('about');

Route::get('/rules', function () {
    return view('rules');
});

Route::get('/test-simple', fn() => "TEST SIMPLE - OK");

// ==========================================
// 2. ZONE INVITÉS (GUEST)
// Connexion, Inscription, Reset MDP, Google
// ==========================================
Route::middleware('guest')->group(function () {

    // Authentification Classique
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Authentification Google
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
    Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

    // --- MOT DE PASSE OUBLIÉ (Doit être ici, pas dans 'auth') ---

    // 1. Formulaire demande lien
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    // 2. Envoi du mail
    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    // 3. Formulaire nouveau mot de passe (depuis le mail)
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    // 4. Enregistrement nouveau mot de passe
    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    })->name('password.update');
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
        // Stats
        $stats = [
            'total_users'          => User::count(),
            'reservations_pending' => Reservation::where('status', 'pending')->count(),
            'critical_incidents'   => Incident::whereIn('priority', ['high', 'critical'])->where('status', 'open')->count(),
            'infra_health'         => $totalResources > 0 ? round(($activeResources / $totalResources) * 100) : 0,
            'occupancy'            => $totalResources > 0 ? round((Reservation::where('status', 'approved')->count() / $totalResources) * 100) : 0,
        ];
        // Tableau Activités
        $recentActivities = Reservation::with(['user', 'resource'])->latest()->take(6)->get();

        return view('dashboard', compact('stats', 'recentActivities'));
    })->name('dashboard');

    // --- RÉSERVATIONS ---
    Route::resource('reservations', ReservationController::class);
    Route::patch('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{id}/refuse', [ReservationController::class, 'refuse'])->name('reservations.refuse');

    
    // --- RESSOURCES ---
    
    // 1. Routes de création (DOIVENT être avant {id})
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/ressources/create', [RessourceController::class, 'create'])->name('ressources.create');
        Route::post('/ressources', [RessourceController::class, 'store'])->name('ressources.store');
    });

    // 2. Consultation (Lecture seule)
    Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources.index');
    Route::get('/ressources/{id}', [RessourceController::class, 'show'])->name('ressources.show');

    // 3. Edition (Admin/Manager uniquement)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/ressources/{id}/edit', [RessourceController::class, 'edit'])->name('ressources.edit');
        Route::put('/ressources/{id}', [RessourceController::class, 'update'])->name('ressources.update');
    });

    // 4. Suppression (Admin uniquement)
    Route::delete('/ressources/{id}', [RessourceController::class, 'destroy'])
          ->middleware('role:admin')
          ->name('ressources.destroy');

    // --- SUPPORT / TICKETS ---
    Route::get('/support', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/support', [TicketController::class, 'store'])->name('tickets.store');

    // --- NOTIFICATIONS ---
    Route::get('/notifications', function () {
        $user = auth()->user();

        // 1. ACTION D'ABORD : On marque tout comme "Lu" dans la base
        \App\Models\Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // 2. RÉCUPÉRATION ENSUITE : On charge les notifs (qui sont maintenant à jour)
        $notifications = \App\Models\Notification::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');
});

// ==========================================
// 4. ZONE TEST & DEBUG (A retirer en prod)
// ==========================================
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
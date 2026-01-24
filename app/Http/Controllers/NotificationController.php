<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification; // N'oublie pas le modèle !

class NotificationController extends Controller
{
    public function index()
    {
        // On récupère les notifications de l'utilisateur connecté, de la plus récente à la plus ancienne
        $notifications = Notification::where('user_id', auth()->id())
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('notifications.index', compact('notifications'));
    }
}
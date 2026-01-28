<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Notification; // Ton modèle de notification existant

class TicketController extends Controller
{
    // Affiche le formulaire
    public function create()
    {
        return view('contacts.support');
    }

    // Enregistre le ticket et notifie les admins
public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string|min:5',
        ]);

        // 2. Création du Ticket
        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'priority' => $request->priority,
            'message' => $request->message,
            'status' => 'open'
        ]);

        // 3. Notification aux Admins (ADAPTÉ À TON MODÈLE)
        $admins = User::whereIn('role', ['admin', 'manager'])->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'ticket_created', // On donne un nom au type d'alerte
                'is_read' => false,
                // C'est ici que ça change : on met les infos dans 'data'
                'data'    => [
                    'message'   => "🔔 Nouveau Ticket Urgent : " . $ticket->subject,
                    'user_name' => auth()->user()->name,
                    'ticket_id' => $ticket->id
                ]
            ]);
        }

        return back()->with('success', 'Votre signalement a été envoyé aux administrateurs !');
    }
}
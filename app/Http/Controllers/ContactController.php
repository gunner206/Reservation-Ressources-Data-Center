<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Important pour récupérer la liste des admins

class ContactController extends Controller
{
    // Fonction pour afficher la page "À propos & Règlement"
    public function about()
    {
        // On récupère les utilisateurs qui sont 'admin' ou 'manager'
        $team = User::whereIn('role', ['admin', 'manager'])->get();

        // On retourne la vue 'pages.about' en lui donnant la liste $team
        return view('contacts.about', compact('team'));
    }
}
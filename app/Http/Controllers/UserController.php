<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. AFFICHER LA LISTE DES UTILISATEURS
    public function index()
    {
        $users = User::all(); // On récupère tout le monde
        return view('users.index', compact('users'));
    }

    // 2. AFFICHER LE FORMULAIRE DE MODIFICATION
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // 3. SAUVEGARDER LA MODIFICATION (RÔLE)
    public function update(Request $request, $id)
    {
        // Sécurité : On empêche de modifier son propre rôle
        if (Auth::id() == $id && $request->role !== Auth::user()->role) {
            return back()->with('error', 'Sécurité : Vous ne pouvez pas modifier votre propre rôle.');
        }

        $user = User::findOrFail($id);
        $user->role = $request->role; // On met à jour le rôle
        $user->save();

        return redirect()->route('users.index')->with('success', 'Rôle mis à jour avec succès !');
    }

    // 4. SUPPRIMER UN UTILISATEUR (BANNIR)
    public function destroy($id)
    {
        // Sécurité : On empêche de se supprimer soi-même
        if (Auth::id() == $id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte !');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
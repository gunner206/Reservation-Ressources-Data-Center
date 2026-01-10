<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use App\Models\Categorie;
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    public function index()
    {
        $ressources = Ressource::with('categorie')->get();
        return view('ressources.index', ['ressources' => $ressources]);
    }
    
    public function create()
    {
        $categories = Categorie::all();
        return view('ressources.create', ['categories' => $categories]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'statut' => 'required|in:actif,maintenance,hors_service'
        ]);
        
        Ressource::create($validated);
        
        return redirect()->route('ressources.index')
            ->with('success', 'Ressource créée avec succès !');
    }
    
    public function show($id)
    {
        $ressource = Ressource::with('categorie')->findOrFail($id);
        return view('ressources.show', ['ressource' => $ressource]);
    }
    
    public function edit($id)
    {
        $ressource = Ressource::findOrFail($id);
        $categories = Categorie::all();
        return view('ressources.edit', [
            'ressource' => $ressource,
            'categories' => $categories
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'statut' => 'required|in:actif,maintenance,hors_service'
        ]);
        
        $ressource = Ressource::findOrFail($id);
        $ressource->update($validated);
        
        return redirect()->route('ressources.index')
            ->with('success', 'Ressource mise à jour !');
    }
    
    public function destroy($id)
    {
        $ressource = Ressource::findOrFail($id);
        $ressource->delete();
        
        return redirect()->route('ressources.index')
            ->with('success', 'Ressource supprimée !');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Resource;  
use App\Models\Category;    
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    public function index()
    {
        $ressources = Resource::with('category')->get();
        return view('ressources.index', compact('ressources'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('ressources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:RESOURCES',  // Table RESOURCES
            'description' => 'nullable|string',
            'category_id' => 'required|exists:CATEGORIES,id',  // Table CATEGORIES
            'is_active' => 'boolean',
        ]);

        // Gestion JSON specs
        $specs = [];
        if ($request->has('cpu')) $specs['cpu'] = $request->cpu;
        if ($request->has('ram')) $specs['ram'] = $request->ram;
        if ($request->has('stockage')) $specs['stockage'] = $request->stockage;
        if ($request->has('os')) $specs['os'] = $request->os;
        
        $validated['specs'] = !empty($specs) ? json_encode($specs) : null;
        $validated['is_active'] = $request->boolean('is_active');

        Resource::create($validated);

        return redirect()->route('ressources.index')
            ->with('success', 'Ressource créée avec succès !');
    }

    public function show($id)
    {
        $ressource = Resource::with('category')->findOrFail($id);
        return view('ressources.show', compact('ressource'));
    }

    public function edit($id)
    {
        $ressource = Resource::findOrFail($id);
        $categories = Category::all();
        return view('ressources.edit', compact('ressource', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $ressource = Resource::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:RESOURCES,code,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:CATEGORIES,id',
            'is_active' => 'boolean',
        ]);

        // Gestion JSON specs
        $specs = $ressource->specs ?? [];
        if ($request->has('cpu')) $specs['cpu'] = $request->cpu;
        if ($request->has('ram')) $specs['ram'] = $request->ram;
        if ($request->has('stockage')) $specs['stockage'] = $request->stockage;
        if ($request->has('os')) $specs['os'] = $request->os;
        
        $validated['specs'] = !empty($specs) ? json_encode($specs) : null;
        $validated['is_active'] = $request->boolean('is_active');

        $ressource->update($validated);

        return redirect()->route('ressources.index')
            ->with('success', 'Ressource mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $ressource = Resource::findOrFail($id);
        $ressource->delete();

        return redirect()->route('ressources.index')
            ->with('success', 'Ressource supprimée avec succès !');
    }
}
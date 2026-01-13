<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Ressource;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = Reservation::with(['resource', 'user']);

        if ($user->role == 'admin' || $user->role == 'manager') {
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')");
        } else {
            $query->where('user_id', $user->id);
        }
        $reservations = $query->orderBy('created_at', 'desc')->get();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('resources')->get();

        return view('reservations.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des donnes
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'justification' => 'required|string|min:10'
        ]);

        // verifier si le resource est disponible a ca date
        if (!Reservation::isAvailable($request->resource_id, $request->start_date, $request->end_date)){
            back()->withInput()
            ->withErrors(['start_date' => 'Desole, cette resource est deja reserver dans ca date']);
        }

        // Enregistrement dans la base de donne
        Reservation::create([
            'user_id' => auth()->id(),
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'justification' => $request->justification,
            'status' => 'pending',
            'type' => 'standard'
        ]);

        // la confirmation
        return redirect()->route('reservations.index')->with('succes', 'Votre demande a ete envoyee au responsable !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    // Methodes de validation et refuse de reservation (Manager)
    public function approve($id){
        $reservation = Reservation::findOrFail($id);
        if (auth()->user()->role != 'manager' && auth()->user()->role != 'admin'){
            abort(403, 'Access Denied');
        }
        $reservation->update([
            'status' => 'approved',
            'validated_by' => auth()->user()->id
        ]);

        \App\Models\Notification::create([
            'user_id' => $reservation->user_id,
            'message'=> "Votre reservation pour" .$reservation->resource->name." est Approvee",
            'is_read' => false
        ]);
        return back()->with('Succes', 'Reservation validee avec succes !');

    }
    public function refuse($id){
        $reservation = Reservation::findOrFail($id);

        if(auth()->user()->role !== 'manager' && auth()->user()->role !== 'admin'){
            abort(403, 'Access Denied');
        }

        $reservation->update([
            'status' => 'rejected'
        ]);
        \App\Models\Notification::create([
            'user_id' => $reservation->user_id,
            'message'=> "Votre reservation pour" .$reservation->resource->name." est Refusee",
            'is_read' => false
        ]);
        
        return back()->with('Succes', 'Reservation Refusee !');
    }
}

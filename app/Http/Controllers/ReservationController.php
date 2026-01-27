<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

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
        $startHour = 8;
        $endHour = 24;
        $categories = \App\Models\Category::with('resources')->get();

        $resourcesList = \App\Models\Resource::with(['reservations' => function($q) {
            $q->whereDate('start_date', now())
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('start_date');
        }])->get();

        return view('reservations.create', compact('categories', 'resourcesList', 'startHour', 'endHour'));
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
            return back()->withInput()
            ->withErrors(['start_date' => 'Desole, cette resource est deja reserver dans ca date']);
        }

        // Enregistrement dans la base de donne
        $reservation = Reservation::create([
            'user_id' => auth()->user()->id,
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'justification' => $request->justification,
            'status' => 'pending',
            'type' => 'standard'
        ]);

        $managers = \App\Models\User::whereIn('role', ['manager', 'admin'])->get();

        // Notifications pour les managers
        $formattedDate = Carbon::parse($request->start_date)->format('d/m/Y H:i A');
        foreach ($managers as $manager) {
            \App\Models\Notification::create([
                'user_id' => $manager->id,
                'type'    => 'new_request',
                'data'    => [
                    'message' => 'Nouvelle demande de ' . auth()->user()->name . ' pour ' . $formattedDate
                ],
                'read_at' => null
            ]);
        }

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
        if(auth()->id() !== $reservation->user_id && auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager'){
            abort(403, 'Access Denied');
        }
        if (auth()->user()->role === 'internal' && $reservation->status !== 'pending'){
            return back()->with('error', 'Imposible de Annuler une reservation deja traitee..');
        }

        $reservation->delete();
        return back()->with('succes', 'Reservation annulee avec succes !');
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
            'type'    => 'reservation_approved', // On définit un type
            'data'    => [
                'message' => "Votre réservation pour " . $reservation->resource->name . " a été validée ✅"
            ],
            'read_at' => null // NULL signifie "Non lu"        
        ]);
        return back()->with('Succes', 'Reservation validee avec succes !');

    }
    public function refuse(Request $request, $id){
        $reservation = Reservation::findOrFail($id);

        if(auth()->user()->role !== 'manager' && auth()->user()->role !== 'admin'){
            abort(403, 'Access Denied');
        }

        $reason = $request->input('reject_reason', "Aucune raison n'a été donnée");

        $reservation->update([
            'status' => 'rejected',
            'validated_by' => auth()->user()->id
        ]);
        \App\Models\Notification::create([
            'user_id' => $reservation->user_id,
            'type'    => 'reservation_rejected',
            'data'    => [
                'message' => "Votre réservation pour " . $reservation->resource->name . " a été rejetée ❌. Raison : " .$reason
            ],
            'read_at' => null
        ]);
        
        return back()->with('Succes', 'Reservation Refusee !');
    }
}

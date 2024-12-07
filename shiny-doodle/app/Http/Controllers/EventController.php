<?php

namespace App\Http\Controllers;


use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all(); // Minden esemény lekérdezése
        return view('events.index', compact('events'));
    }
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function buy(Request $request, $id)
    {
        $event = Event::findOrFail($id);
    
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $event->tickets_available,
        ]);
    
        $quantity = $request->input('quantity');
        $totalPrice = $quantity * $event->price;
    
        // Csökkentsük a jegyek számát
        $event->tickets_available -= $quantity;
        $event->save();
    
        // Hozzunk létre egy új jegyet
        auth()->user()->tickets()->create([
            'event_id' => $event->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
        ]);
    
        return redirect()->route('events.index')->with('success', 'Sikeres vásárlás!');
    }

}

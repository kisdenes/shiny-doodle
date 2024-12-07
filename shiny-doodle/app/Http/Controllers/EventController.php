<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all(); 
        return view('events.index', compact('events'));
    }
    
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function buy(Request $request, $id)
    {
        $user = auth()->user();
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', 'Kérlek, regisztrálj vagy jelentkezz be a vásárláshoz!');
        }
        
        $event = Event::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $event->tickets_available,
        ]);

        $quantity = $request->input('quantity');
        $totalPrice = $quantity * $event->price;

        $event->tickets_available -= $quantity;
        $event->save();

        $user->tickets()->create([
            'event_id' => $event->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('events.index')->with('success', 'Sikeres vásárlás!');
    }
}

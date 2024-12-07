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
            return redirect()->route('events.registerAndBuyForm', ['eventId' => $id])
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

        // Hozzunk létre egy új jegyet
        $user->tickets()->create([
            'event_id' => $event->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('events.index')->with('success', 'Sikeres vásárlás!');
    }

    public function registerAndBuyForm($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('events.register_and_buy', compact('event'));
    }

    public function registerAndBuy(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'quantity' => 'required|integer|min:1|max:' . $event->tickets_available,
        ]);

        DB::beginTransaction(); // Tranzakció indítása
        try {
            // Felhasználó létrehozása
            $userController = new UserController();
            $user = $userController->createUser($request);

            // Automatikus beléptetés
            auth()->login($user);

            // Jegyvásárlás
            $quantity = $request->input('quantity');
            $totalPrice = $quantity * $event->price;

            if ($event->tickets_available < $quantity) {
                throw new \Exception('Nincs elég elérhető jegy.');
            }

            // Jegyek levonása
            $event->tickets_available -= $quantity;
            $event->save();

            // Jegy létrehozása
            $user->tickets()->create([
                'event_id' => $event->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
            ]);

            DB::commit(); // Tranzakció véglegesítése

            return redirect()->route('events.index')->with('success', 'Sikeres regisztráció és vásárlás!');
        } catch (\Exception $e) {
            DB::rollBack(); // Tranzakció visszagörgetése
            return redirect()->back()
                ->withInput($request->all()) // Az űrlapadatok visszaállítása
                ->with('error', 'Hiba történt: ' . $e->getMessage());
        }
    }
}

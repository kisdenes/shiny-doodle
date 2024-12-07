<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('events', EventController::class);
Route::resource('tickets', TicketController::class);


Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{id}/buy', [EventController::class, 'buy'])->name('events.buy');


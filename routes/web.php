<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    Route::post(
        '/events/{event}/reserve',
        [ReservationController::class, 'store']
    )->name('reservations.store');

    Route::get(
        '/my-reservations',
        [ReservationController::class, 'index']
    )->name('reservations.index');

});

require __DIR__.'/auth.php';
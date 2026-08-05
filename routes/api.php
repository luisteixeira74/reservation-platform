<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ReservationController;
use Illuminate\Support\Facades\Route;


// Público
Route::post('/login', [AuthController::class, 'login']);

Route::get('/events', [
    EventController::class,
    'index'
]);


// Autenticado
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/events/{event}/reservations', [
        ReservationController::class,
        'store'
    ]);

    Route::get('/my-reservations', [
        ReservationController::class,
        'index'
    ]);

});
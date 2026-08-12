<?php

use App\Jobs\ReservationCreatedJob;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a notification when a reservation is processed', function () {
    $user = User::factory()->create();

    $event = Event::factory()->create([
        'name' => 'Laravel Conference',
    ]);

    $reservation = Reservation::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => 'confirmed',
    ]);

    $job = new ReservationCreatedJob($reservation->id);

    $job->handle();

    $this->assertDatabaseHas('notifications', [
        'user_id' => $user->id,
        'type' => 'reservation_confirmed',
        'title' => 'Reservation confirmed',
        'message' => 'Your reservation for Laravel Conference was confirmed.',
    ]);
});
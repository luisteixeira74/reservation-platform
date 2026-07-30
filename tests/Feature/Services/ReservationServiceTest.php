<?php

use App\Models\Event;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\AlreadyReservedException;
use App\Exceptions\NoSeatsAvailableException;
use App\Exceptions\EventNotAvailableException;
use App\Models\Reservation;

uses(RefreshDatabase::class);

it('creates a reservation successfully', function () {
    // Arrange
    $user = User::factory()->create();

    $event = Event::factory()->create([
        'available_seats' => 10,
        'total_seats' => 10,
    ]);

    $service = app(ReservationService::class);

    // Act
    $reservation = $service->reserve($user, $event);

    // Assert
    expect($reservation)->not->toBeNull();

    expect($reservation->user_id)->toBe($user->id);

    expect($reservation->event_id)->toBe($event->id);

    expect($reservation->status)->toBe('confirmed');

    $event->refresh();

    expect($event->available_seats)->toBe(9);

    $this->assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'event_id' => $event->id, 
        'status' => 'confirmed',
    ]);
});

it('throws exception when user already reserved the event', function () {

    $user = User::factory()->create();

    $event = Event::factory()->create([
        'available_seats' => 10,
        'total_seats' => 10,
    ]);

    $service = app(ReservationService::class);

    // primeira reserva: deve funcionar
    $service->reserve($user, $event);

    // segunda tentativa: deve falhar
    expect(fn () => $service->reserve($user, $event))
        ->toThrow(AlreadyReservedException::class);

    // Assert that only one reservation exists for the user and event
    $this->assertDatabaseCount('reservations', 1);
});

it('throws exception when event has no seats available', function () {
    $user = User::factory()->create();

    $event = Event::factory()->create([
        'available_seats' => 0,
        'total_seats' => 10,
    ]);

    $service = app(ReservationService::class);

    expect(fn () => $service->reserve($user, $event))
        ->toThrow(NoSeatsAvailableException::class);

    $this->assertDatabaseMissing('reservations', [
        'user_id' => $user->id,
        'event_id' => $event->id,
    ]);
});

it('throws exception when event is inactive', function () {
    $user = User::factory()->create();

    $event = Event::factory()->create([
        'active' => false,
        'available_seats' => 10,
    ]);

    $service = app(ReservationService::class);

    expect(fn () => $service->reserve($user, $event))
        ->toThrow(EventNotAvailableException::class);

    $this->assertDatabaseMissing('reservations', [
        'user_id' => $user->id,
        'event_id' => $event->id,
    ]);
});
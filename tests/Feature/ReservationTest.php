<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('authenticated user can reserve an event', function () {

    $user = User::factory()->create();

    $event = Event::factory()->create([
        'available_seats' => 10,
        'total_seats' => 10,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('reservations.store', $event));

    $response->assertRedirect(route('events.index'));

    $this->assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => 'confirmed',
    ]);
});

it('guest user cannot reserve an event', function () {

    $event = Event::factory()->create();

    $response = $this->post(
        route('reservations.store', $event)
    );

    $response->assertRedirect(route('login'));
});

it('shows error when user tries to reserve the same event twice', function () {

    $user = User::factory()->create();

    $event = Event::factory()->create([
        'available_seats' => 10,
        'total_seats' => 10,
    ]);

    $this
        ->actingAs($user)
        ->post(route('reservations.store', $event));

    $response = $this
        ->actingAs($user)
        ->post(route('reservations.store', $event));

    $response->assertSessionHas('error');

    $this->assertDatabaseCount('reservations', 1);
});
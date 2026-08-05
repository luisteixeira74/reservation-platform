<?php

use App\Models\Event;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('authenticated user can reserve an event', function () {

    $user = User::factory()->create();

    $event = Event::factory()->create([
        'available_seats' => 100,
        'active' => true,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson(
        "/api/events/{$event->id}/reservations"
    );

    $response
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'status',
                'created_at',
                'event' => [
                    'id',
                    'name',
                    'event_date',
                ],
            ],
            'message',
        ]);

    expect($event->fresh()->available_seats)
        ->toBe(99);
});
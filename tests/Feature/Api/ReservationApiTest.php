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


test('user can only see their own reservations', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $event = Event::factory()->create([
        'available_seats' => 10,
        'active' => true,
    ]);

    $otherEvent = Event::factory()->create([
        'available_seats' => 10,
        'active' => true,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/events/{$event->id}/reservations")
        ->assertCreated();

    Sanctum::actingAs($otherUser);

    $this->postJson("/api/events/{$otherEvent->id}/reservations")
        ->assertCreated();

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/my-reservations');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.event.id', $event->id);
});
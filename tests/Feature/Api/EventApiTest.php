<?php

use App\Models\Event;

test('returns public events', function () {

    Event::factory()->create([
        'name' => 'Laravel Conference',
    ]);

    $response = $this->getJson('/api/events');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'description',
                    'event_date',
                    'available_seats',
                ]
            ],
            'links',
            'meta',
        ]);
});
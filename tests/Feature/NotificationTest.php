<?php

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an authenticated user to list their notifications', function () {
    $user = User::factory()->create();

    Notification::create([
        'user_id' => $user->id,
        'type' => 'reservation_confirmed',
        'title' => 'Reservation confirmed',
        'message' => 'Your reservation was confirmed.',
    ]);

    $response = $this->actingAs($user)
        ->getJson('/notifications');

    $response
        ->assertOk()
        ->assertJsonCount(1, 'notifications')
        ->assertJsonPath(
            'notifications.0.title',
            'Reservation confirmed'
        );
});

it('allows an authenticated user to get their unread notification count', function () {
    $user = User::factory()->create();

    Notification::create([
        'user_id' => $user->id,
        'type' => 'reservation_confirmed',
        'title' => 'Unread notification',
        'message' => 'Your reservation was confirmed.',
    ]);

    Notification::create([
        'user_id' => $user->id,
        'type' => 'reservation_confirmed',
        'title' => 'Read notification',
        'message' => 'This notification was already read.',
        'read_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->getJson('/notifications/unread-count');

    $response
        ->assertOk()
        ->assertJson([
            'unread_count' => 1,
        ]);
});

it('allows an authenticated user to mark their notification as read', function () {
    $user = User::factory()->create();

    $notification = Notification::create([
        'user_id' => $user->id,
        'type' => 'reservation_confirmed',
        'title' => 'Reservation confirmed',
        'message' => 'Your reservation was confirmed.',
    ]);

    $response = $this->actingAs($user)
        ->patchJson("/notifications/{$notification->id}/read");

    $response
        ->assertOk()
        ->assertJson([
            'message' => 'Notification marked as read.',
        ]);

    $this->assertDatabaseMissing('notifications', [
        'id' => $notification->id,
        'read_at' => null,
    ]);
});

it('prevents a user from marking another users notification as read', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $notification = Notification::create([
        'user_id' => $otherUser->id,
        'type' => 'reservation_confirmed',
        'title' => 'Private notification',
        'message' => 'This notification belongs to another user.',
    ]);

    $response = $this->actingAs($user)
        ->patchJson("/notifications/{$notification->id}/read");

    $response->assertForbidden();

    $this->assertDatabaseHas('notifications', [
        'id' => $notification->id,
        'user_id' => $otherUser->id,
        'read_at' => null,
    ]);
});

it('prevents guests from accessing notification endpoints', function () {
    $notification = Notification::create([
        'user_id' => User::factory()->create()->id,
        'type' => 'reservation_confirmed',
        'title' => 'Reservation confirmed',
        'message' => 'Your reservation was confirmed.',
    ]);

    $this->getJson('/notifications')
        ->assertUnauthorized();

    $this->getJson('/notifications/unread-count')
        ->assertUnauthorized();

    $this->patchJson("/notifications/{$notification->id}/read")
        ->assertUnauthorized();
});
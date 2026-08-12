<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReservationCreatedJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $reservationId
    ) {}

    public function handle(): void
    {
        $reservation = Reservation::with('event')
            ->findOrFail($this->reservationId);

        Notification::create([
            'user_id' => $reservation->user_id,
            'type' => 'reservation_confirmed',
            'title' => 'Reservation confirmed',
            'message' => "Your reservation for {$reservation->event->name} was confirmed.",
        ]);
    }
}
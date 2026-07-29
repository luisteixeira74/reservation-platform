<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AlreadyReservedException;
use App\Exceptions\NoSeatsAvailableException;

class ReservationService
{
    /**
     * Undocumented function
     *
     * @param User $user
     * @param Event $event
     * @return Reservation
     */
    public function reserve(User $user, Event $event): Reservation
    {
        return DB::transaction(function () use ($user, $event) {

            if ($event->available_seats <= 0) {
                throw new NoSeatsAvailableException(
                    'Não existem vagas disponíveis para este evento.'
                );
            }

            $exists = Reservation::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->exists();

            if ($exists) {
                throw new AlreadyReservedException(
                    'Você já possui uma reserva para este evento.'
                );
            }

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => 'confirmed',
            ]);

            $event->decrement('available_seats');

            return $reservation;
        });
    }
}
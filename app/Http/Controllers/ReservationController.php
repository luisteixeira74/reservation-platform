<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AlreadyReservedException;
use App\Exceptions\NoSeatsAvailableException;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {
    }

    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $reservations = $user
            ->reservations()
            ->with('event')
            ->latest()
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function store(Event $event): RedirectResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                abort(403, 'Usuário não autenticado.');
            }

            $this->reservationService->reserve(
                $user,
                $event
            );

            return redirect()
                ->route('events.index')
                ->with('success', 'Reserva realizada com sucesso.');
         } catch (NoSeatsAvailableException | AlreadyReservedException $e) {

            return back()
                ->with('error', $e->getMessage());
        }
    }
}
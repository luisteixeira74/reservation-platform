<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\ReservationService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ReservationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $reservations = $user
            ->reservations()
            ->with('event')
            ->paginate(10);

        return ReservationResource::collection($reservations);
    }
    
    public function store(
        Request $request,
        Event $event
    ): JsonResponse {
        
        /** @var User $user */
        $user = $request->user();

        $reservation = $this->reservationService->reserve(
            $user,
            $event
        );

        return (new ReservationResource($reservation))
            ->additional([
                'message' => 'Reservation created successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }
}
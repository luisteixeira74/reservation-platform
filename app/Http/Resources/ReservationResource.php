<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,

            'event' => [
                'id' => $this->event->id,
                'name' => $this->event->name,
                'event_date' => $this->event->event_date,
            ],
        ];
    }
}
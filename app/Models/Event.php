<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'event_date',
        'total_seats',
        'available_seats',
        'active',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'active' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
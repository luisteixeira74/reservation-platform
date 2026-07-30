<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;
    
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

    public function hasReservationFromUser(User $user): bool
    {
        return $this->reservations()
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->exists();
    }
}
<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'name' => 'Rock Festival',
            'description' => 'Festival com bandas nacionais e internacionais.',
            'event_date' => '2026-09-20 20:00:00',
            'total_seats' => 5000,
            'available_seats' => 5000,
            'active' => true,
        ]);

        Event::create([
            'name' => 'Go Summit Brasil',
            'description' => 'Evento sobre desenvolvimento em Go.',
            'event_date' => '2026-10-10 09:00:00',
            'total_seats' => 800,
            'available_seats' => 800,
            'active' => true,
        ]);

        Event::create([
            'name' => 'PHP Conference',
            'description' => 'Conferência para desenvolvedores PHP.',
            'event_date' => '2026-11-15 08:30:00',
            'total_seats' => 1200,
            'available_seats' => 1200,
            'active' => true,
        ]);

        Event::factory(30)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventDataSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('email', 'organizer@example.com')->first();
        if (! $organizer) {
            return;
        }

        $now = now();
        $events = [
            ['title' => 'Intro to AI', 'offsetDays' => 7, 'durationHours' => 2],
            ['title' => 'Business Pitch Night', 'offsetDays' => 14, 'durationHours' => 3],
            ['title' => 'UX Hackathon', 'offsetDays' => 21, 'durationHours' => 8],
        ];

        foreach ($events as $idx => $e) {
            $start = $now->copy()->addDays($e['offsetDays'])->setTime(9, 0);
            $end = $start->copy()->addHours($e['durationHours']);
            Event::firstOrCreate(
                ['slug' => Str::slug($e['title'])],
                [
                    'title' => $e['title'],
                    'organizer_id' => $organizer->id,
                    'start_at' => $start,
                    'end_at' => $end,
                    'capacity' => 100,
                    'seats_left' => 100,
                    'status' => 'published',
                ]
            );
        }
    }
}



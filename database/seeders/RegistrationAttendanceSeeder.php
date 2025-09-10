<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegistrationAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::take(2)->get();
        $students = User::where('email', 'like', 'student%')->get();

        foreach ($events as $event) {
            foreach ($students as $student) {
                $reg = Registration::firstOrCreate(
                    ['event_id' => $event->id, 'user_id' => $student->id],
                    [
                        'status' => 'confirmed',
                        'on_waitlist' => false,
                        'fee_paid' => (bool) random_int(0, 1),
                        'checkin_code' => (string) Str::uuid(),
                    ]
                );

                if (random_int(0, 1)) {
                    Attendance::firstOrCreate(
                        ['registration_id' => $reg->id],
                        ['checked_in_at' => now()]
                    );
                }
            }
        }
    }
}



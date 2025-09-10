<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'checkin_code' => ['required', 'string'],
            'event_id' => ['required', 'integer', 'exists:events,id'],
        ]);

        $registration = Registration::where('checkin_code', $validated['checkin_code'])->first();
        if (! $registration) {
            return response()->json(['message' => 'invalid_qr'], 422);
        }

        if ($registration->event_id !== (int) $validated['event_id']) {
            return response()->json(['message' => 'wrong_event'], 422);
        }

        $event = Event::find($validated['event_id']);
        $now = now();
        if ($now->lt($event->start_at) || $now->gt($event->end_at)) {
            return response()->json(['message' => 'invalid_time_window'], 422);
        }

        $attendance = Attendance::firstOrCreate(
            ['registration_id' => $registration->id],
            ['checked_in_at' => $now]
        );

        if (! $attendance->checked_in_at) {
            $attendance->update(['checked_in_at' => $now]);
        }

        return response()->json($attendance->fresh());
    }
}



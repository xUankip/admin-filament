<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function myRegistrations(Request $request)
    {
        return Registration::with('event')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);
    }

    public function register(Request $request, Event $event)
    {
        $user = $request->user();
        if (! $user->hasVerifiedEmail()) {
            return response()->json(['message' => 'email_unverified'], 403);
        }

        $already = Registration::where('event_id', $event->id)->where('user_id', $user->id)->first();
        if ($already) {
            return response()->json($already);
        }

        // Capacity enforcement and optional waitlist
        $onWaitlist = false;
        if ($event->seats_left <= 0) {
            if (! $event->waitlist_enabled) {
                return response()->json(['message' => 'event_full'], 409);
            }
            $onWaitlist = true;
        } else {
            // reserve a seat
            $event->decrement('seats_left');
        }

        $registration = Registration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => $onWaitlist ? 'pending' : 'confirmed',
            'on_waitlist' => $onWaitlist,
            'fee_paid' => false,
            'checkin_code' => (string) Str::uuid(),
            'fields_snapshot' => [],
        ]);

        return response()->json($registration->fresh());
    }

    public function unregister(Request $request, Event $event)
    {
        Registration::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'unregistered']);
    }
}



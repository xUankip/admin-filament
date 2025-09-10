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

        $registration = Registration::firstOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id],
            [
                'status' => 'pending',
                'checkin_code' => (string) Str::uuid(),
                'fields_snapshot' => [],
            ]
        );

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




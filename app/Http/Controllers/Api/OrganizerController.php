<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function registrants(Request $request, Event $event)
    {
        // Ownership check: organizer or admin roles
        if (! ($request->user()->hasRole('super_admin') || $request->user()->hasRole('staff_admin') || $event->organizer_id === $request->user()->id)) {
            abort(403);
        }

        return Registration::with('user')
            ->where('event_id', $event->id)
            ->latest()
            ->paginate(50);
    }
}



<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return UserNotification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);
    }

    public function markRead(Request $request, UserNotification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403);
        }
        $notification->update(['read_at' => now()]);
        return response()->json(['message' => 'ok']);
    }
}



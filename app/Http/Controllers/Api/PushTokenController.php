<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushToken;
use Illuminate\Http\Request;

class PushTokenController extends Controller
{
    public function upsert(Request $request)
    {
        $validated = $request->validate([
            'provider' => ['required', 'in:fcm,onesignal'],
            'token' => ['required', 'string', 'max:512'],
            'device_id' => ['nullable', 'string', 'max:128'],
            'device_os' => ['nullable', 'string', 'max:32'],
            'device_model' => ['nullable', 'string', 'max:96'],
            'app_version' => ['nullable', 'string', 'max:32'],
            'language' => ['nullable', 'string', 'max:12'],
        ]);

        $attributes = [
            'user_id' => $request->user()->id,
            'provider' => $validated['provider'],
            'token' => $validated['token'],
        ];

        $values = [
            'device_id' => $request->string('device_id'),
            'device_os' => $request->string('device_os'),
            'device_model' => $request->string('device_model'),
            'app_version' => $request->string('app_version'),
            'language' => $request->string('language'),
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            'last_seen_at' => now(),
            'revoked_at' => null,
        ];

        $pushToken = PushToken::updateOrCreate($attributes, $values);

        return response()->json($pushToken);
    }

    public function revoke(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'max:512'],
        ]);

        $record = PushToken::where('token', $validated['token'])
            ->where('user_id', $request->user()->id)
            ->first();

        if ($record) {
            $record->update(['revoked_at' => now()]);
        }

        return response()->json(['message' => 'ok']);
    }
}



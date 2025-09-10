<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Registration;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function mine(Request $request)
    {
        return Certificate::with('event')
            ->where('student_id', $request->user()->id)
            ->latest('issued_on')
            ->paginate(15);
    }

    public function issue(Request $request)
    {
        $validated = $request->validate([
            'registration_id' => ['required', 'integer', 'exists:registrations,id'],
            'certificate_id' => ['required', 'string', 'max:128'],
            'pdf_url' => ['required', 'url'],
        ]);

        $registration = Registration::with(['event', 'user'])->findOrFail($validated['registration_id']);

        if (! $registration->fee_paid) {
            return response()->json(['message' => 'fee_unpaid'], 422);
        }

        $cert = Certificate::create([
            'event_id' => $registration->event_id,
            'student_id' => $registration->user_id,
            'certificate_id' => $validated['certificate_id'],
            'pdf_url' => $validated['pdf_url'],
            'issued_on' => now(),
        ]);

        return response()->json($cert);
    }
}



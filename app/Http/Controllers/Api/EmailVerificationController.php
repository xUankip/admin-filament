<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailOtp;
use App\Models\EmailVerificationOtp;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'already_verified']);
        }
        // Switch to OTP-based verification by default
        return $this->sendOtp($request);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'already_verified']);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json(['message' => 'verified']);
    }

    // Public verify (stateless) via signed URL
    public function verifyPublic(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }
        if ($user->hasVerifiedEmail()) {
            return view('pages.init-page', ['message' => 'already_verified']);
        }
        $user->markEmailAsVerified();
        event(new Verified($user));
        return view('pages.init-page', ['message' => 'verified']);
    }

    public function sendOtp(Request $request)
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'already_verified']);
        }

        $code = (string) random_int(100000, 999999);
        EmailVerificationOtp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new VerifyEmailOtp($code));
        return response()->json(['message' => 'otp_sent']);
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'already_verified']);
        }

        $otp = EmailVerificationOtp::where('user_id', $user->id)
            ->where('email', $user->email)
            ->where('code', $validated['code'])
            ->whereNull('verified_at')
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (! $otp) {
            return response()->json(['message' => 'invalid_or_expired_code'], 422);
        }

        $otp->update(['verified_at' => now()]);
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'verified']);
    }
}



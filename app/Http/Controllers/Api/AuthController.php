<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\System\ZiConfig;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role_hint' => ['nullable', 'string'],
        ]);

        // Staff domain regex from settings (optional)
        $settings = ZiConfig::getConfigAndParserValue('ORG_DOMAIN_SETTING');
        $orgRegex = $settings['email_regex'] ?? null;
        if (!empty($validated['role_hint']) && str_starts_with($validated['role_hint'], 'staff') && $orgRegex) {
            if (! preg_match("/{$orgRegex}/i", $validated['email'])) {
                return response()->json(['message' => 'invalid_org_email_domain'], 422);
            }
        }
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_hint' => $request->string('role_hint'),
            'status' => 'active',
        ]);

        event(new Registered($user));

        return response()->json(['message' => 'registered'], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'invalid_credentials'], 422);
        }

        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        return response()->json(['message' => 'logged_out']);
    }
}



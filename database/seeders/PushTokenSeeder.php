<?php

namespace Database\Seeders;

use App\Models\PushToken;
use App\Models\User;
use Illuminate\Database\Seeder;

class PushTokenSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(5)->get();
        foreach ($users as $user) {
            PushToken::firstOrCreate(
                ['token' => 'demo-fcm-token-'.$user->id],
                [
                    'user_id' => $user->id,
                    'provider' => 'fcm',
                    'device_os' => 'Android',
                    'device_model' => 'Emulator',
                    'app_version' => '1.0.0',
                    'last_seen_at' => now(),
                ]
            );
        }
    }
}



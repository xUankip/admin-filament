<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
                'role_hint' => 'staff_admin',
            ]
        );
        $admin->assignRole('super_admin');
        $admin->assignRole('staff_admin');

        // Organizer
        $organizer = User::firstOrCreate(
            ['email' => 'organizer@example.com'],
            [
                'name' => 'Organizer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
                'role_hint' => 'staff_organizer',
            ]
        );
        $organizer->assignRole('staff_organizer');

        // Students
        for ($i = 1; $i <= 10; $i++) {
            $student = User::firstOrCreate(
                ['email' => "student{$i}@example.com"],
                [
                    'name' => "Student {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'role_hint' => 'student_participant',
                ]
            );
            $student->assignRole('student_participant');
        }
    }
}



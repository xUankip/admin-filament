<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'student_viewer',
            'student_participant',
            'staff_organizer',
            'staff_admin',
        ];

        foreach ($roles as $name) {
            Role::findOrCreate($name, 'web');
        }
    }
}




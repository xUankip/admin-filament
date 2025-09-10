<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $departments = ['IT', 'Business', 'Design', 'Languages'];
        foreach ($departments as $name) {
            $code = Str::upper(Str::slug($name, '_'));
            Department::firstOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
        }

        $categories = ['Seminar', 'Workshop', 'Competition', 'Volunteer'];
        foreach ($categories as $name) {
            $code = Str::slug($name);
            Category::firstOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
        }
    }
}



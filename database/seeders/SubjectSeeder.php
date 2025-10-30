<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Bahasa Melayu',
            'English',
            'Mathematics',
            'Science',
            'Sejarah',
            'Physics',
            'Chemistry',
            'Biology',
            'Add Maths',
            'Accounts',
            'Ekonomi Asas',
            'Perniagaan',
            'Geografi',
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'name' => $subject,
                'slug' => Str::slug($subject, '-'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $forms = [
            'FORM 5',
            'FORM 4',
            'FORM 3',
            'FORM 2',
            'FORM 1',
            'YEAR 6',
            'YEAR 5',
        ];

        foreach ($forms as $form) {
            DB::table('forms')->insert([
                'name' => $form,
                'slug' => Str::slug($form, '-'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

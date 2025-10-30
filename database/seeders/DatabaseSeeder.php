<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\FormSeeder;
use Database\Seeders\SubjectSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FormSeeder::class,
            SubjectSeeder::class,
        ]);
    }
}

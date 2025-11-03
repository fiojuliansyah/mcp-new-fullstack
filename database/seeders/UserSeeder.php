<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        $tutorName = 'Mr. Ben Tutor';
        User::create([
            'slug' => Str::slug($tutorName),
            'name' => $tutorName,
            'email' => 'tutor@example.com',
            'phone' => '0123456789',
            'ic_number' => '900101010011',
            'gender' => 'male',
            'account_type' => 'tutor',
            'password' => $defaultPassword,
            'status' => 'active',
            'language' => 'english',
        ]);

        $parentName = 'Mrs. Aishah Binti Ahmad';
        $parent = User::create([
            'slug' => Str::slug($parentName),
            'name' => $parentName,
            'email' => 'parent@example.com',
            'phone' => '0198765432',
            'ic_number' => '850505050055',
            'postal_code' => '50000',
            'gender' => 'female',
            'account_type' => 'parent',
            'password' => $defaultPassword,
            'status' => 'active',
            'language' => 'english',
        ]);
        
        $parentId = $parent->id;

        $student1Name = 'Ali Bin Ahmad';
        User::create([
            'slug' => Str::slug($student1Name),
            'name' => $student1Name,
            'email' => 'student1@example.com',
            'phone' => '0198765432',
            'ic_number' => '090101140011',
            'postal_code' => '50000',
            'form_id' => 1,
            'gender' => 'male',
            'account_type' => 'student',
            'password' => $defaultPassword,
            'status' => 'active',
            'parent_id' => $parentId,
            'language' => 'english',
        ]);
        
        $student2Name = 'Siti Binti Ahmad';
        User::create([
            'slug' => Str::slug($student2Name),
            'name' => $student2Name,
            'email' => 'student2@example.com',
            'phone' => '0198765432',
            'ic_number' => '110303140022',
            'postal_code' => '50000',
            'form_id' => 2,
            'gender' => 'female',
            'account_type' => 'student',
            'password' => $defaultPassword,
            'status' => 'active',
            'parent_id' => $parentId,
            'language' => 'english',
        ]);
    }
}
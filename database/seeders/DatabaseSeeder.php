<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '$2y$12$4cz4l0XB8j3lHK6UIrZ6/eyTIK1nXFRbhDMygtis5S4RxfVDCzzdK',
            'role' => 1,
        ]);
    }
}

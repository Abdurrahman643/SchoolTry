<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * This seeder creates initial users and lessons for development/testing.
     */
    public function run(): void
    {
        // Truncate users table to avoid duplicate email errors (for development only)
        User::truncate();

        // Create an admin user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Set a known password
            'role' => 'admin',
        ]);

        // Create a student user
        User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password123'), // Set a known password
            'role' => 'student',
        ]);

        // Seed lessons using LessonSeeder
        $this->call(LessonSeeder::class);
    }
}

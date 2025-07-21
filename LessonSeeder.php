<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder populates the lessons table with initial data for testing and development.
     */
    public function run(): void
    {
        // Create sample lessons for the platform
        Lesson::create([
            'title' => 'Introduction to AI',
            'content' => 'This lesson covers the basics of Artificial Intelligence, its history, and applications.'
        ]);
        Lesson::create([
            'title' => 'Machine Learning Fundamentals',
            'content' => 'This lesson introduces machine learning concepts, types of learning, and real-world use cases.'
        ]);
        Lesson::create([
            'title' => 'Deep Learning Overview',
            'content' => 'This lesson explains deep learning, neural networks, and their impact on modern AI.'
        ]);
    }
}

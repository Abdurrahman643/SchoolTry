<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade'); // Link to lesson
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Link to user (student)
            $table->text('question'); // The student's question
            $table->text('answer')->nullable(); // The AI's answer
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

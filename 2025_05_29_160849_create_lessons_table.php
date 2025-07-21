<?php

// Import necessary Laravel classes for migrations and schema building
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Define an anonymous migration class for creating the lessons table
return new class extends Migration
{
    /**
     * Run the migrations.
     * This method is called when you run 'php artisan migrate'.
     * It creates the 'lessons' table with the specified columns.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key 'id'
            $table->string('title'); // String column for the lesson title
            $table->text('content'); // Text column for the lesson content
            $table->timestamps(); // Adds 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     * This method is called when you run 'php artisan migrate:rollback'.
     * It drops the 'lessons' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons'); // Drops the 'lessons' table
    }
};

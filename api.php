<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\QuestionController;

// =====================
// Public API Endpoints
// =====================

// Lesson routes
Route::get('/lessons', [LessonController::class, 'index']); // List all lessons
Route::get('/lessons/{id}', [LessonController::class, 'show']); // Show single lesson

// =====================
// Authentication Routes
// =====================

// Authentication routes
Route::post('/register', [AuthController::class, 'register']); // Register a new user
Route::post('/login', [AuthController::class, 'login']);       // Login a user

// =====================
// Protected Routes
// =====================

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']); // Logout (protected)
    Route::get('/me', [AuthController::class, 'me']); // Get the current authenticated user (requires auth)

    // Admin-only routes
    Route::middleware('ability:lesson:create')->group(function () {
        Route::post('/lessons', [LessonController::class, 'store']); // Create a new lesson (admin upload, requires authentication)
    });

    // Student routes
    Route::middleware('ability:question:create')->group(function () {
        Route::post('/ai/answer', [AIController::class, 'answer']); // Ask questions about a lesson
        Route::post('/questions', [QuestionController::class, 'store']); // Store a question and answer
    });

    // General authenticated routes
    Route::get('/ai/lessons/{lessonId}/history', [AIController::class, 'getHistory']); // Get Q&A history for a lesson
    Route::get('/lessons/{lesson_id}/questions', [QuestionController::class, 'index']); // List all Q&A for a lesson
    Route::post('/ai/recommend', [AIController::class, 'recommend']); // Get lesson recommendations
});

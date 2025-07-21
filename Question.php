<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Allow mass assignment for lesson_id, user_id, question, and answer
    protected $fillable = ['lesson_id', 'user_id', 'question', 'answer'];

    /**
     * Get the user (student) who asked the question.
     * Defines an inverse relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson this question is about.
     * Defines an inverse relationship to the Lesson model.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}

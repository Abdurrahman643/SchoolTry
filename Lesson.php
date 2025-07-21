<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    // Allow mass assignment for title and content
    protected $fillable = ['title', 'content'];

    /**
     * Get the questions for the lesson.
     * Defines a one-to-many relationship with Question.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get a preview of the lesson content (first 200 chars).
     */
    protected function getContentPreviewAttribute(): string
    {
        return strlen($this->content) > 200 
            ? substr($this->content, 0, 200) . '...' 
            : $this->content;
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['content_preview'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'sort_order',
    ];

    /**
     * Get the course that owns the chapter.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the lessons for the chapter.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'chapter_id')->orderBy('sort_order');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'chapter_id',
        'title',
        'content_type',
        'content_url',
        'sort_order',
    ];

    /**
     * Get the chapter that owns the lesson.
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }
}

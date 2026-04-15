<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'payment_status',
        'price_at_purchase',
        'enrolled_at',
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'enrolled_at' => 'datetime',
    ];

    /**
     * Mối quan hệ: Enrollment thuộc về User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ: Enrollment thuộc về Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
}

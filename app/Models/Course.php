<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Chapter;
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'category_id',
        'title',
        'description',
        'price',
        'duration',
        'thumbnail',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'rejected_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the user (provider) that owns the course.
     */
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments() {
    return $this->hasMany(Enrollment::class);
}

    /**
     * Get the users that have enrolled in this course.
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Enrollment::class,
            'course_id',    // Foreign key on Enrollment pointing to Course
            'user_id',      // Foreign key on Enrollment pointing to User
            'id',           // Local key on Course
            'id'            // Primary key on User
        );
    }

    /**
     * Get the chapters for the course.
     */
    public function chapters()
    {
        // Phải là hasMany và trỏ đúng vào Model Chapter
        return $this->hasMany(Chapter::class, 'course_id');
    }

    /**
     * Get the lessons for the course through chapters.
     */
    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }

    /**
     * Get the admin who approved this course.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if course is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if course is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'active' && $this->approved_at !== null;
    }

    /**
     * Check if course is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the approval status formatted for display.
     */
    public function getApprovalStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Chờ phê duyệt',
            'active' => 'Đã phê duyệt',
            'rejected' => 'Bị từ chối',
            default => 'Không xác định',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;
use App\Models\Enrollment;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'avatar',
        'role',
        'status',
        'provider_info',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
                    ->withPivot('payment_status', 'price_at_purchase', 'enrolled_at')
                    ->withTimestamps();
    }
public function createdCourses()
{
    return $this->hasMany(Course::class, 'provider_id');
}
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Khóa học mà người này làm giảng viên (Provider)
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'provider_id');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is provider.
     */
    public function isProvider()
    {
        return $this->role === 'provider';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Get provider profile (provider_info from users table).
     */
    public function getProviderInfoAttribute()
    {
        if ($this->role === 'provider') {
            return $this->attributes['provider_info'] ?? null;
        }
        return null;
    }
}
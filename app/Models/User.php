<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        public function courses()
        {
            return $this->belongsToMany(Course::class, 'course_user')
                        ->withPivot('full_name', 'email', 'note', 'status', 'enrolled_at')
                        ->withTimestamps();
        }



// Khóa học mà người này làm giảng viên (Provider)
public function taughtCourses(): HasMany
{
    return $this->hasMany(Course::class, 'provider_id');
}

// Giữ nguyên hàm enrollments của bạn để quản lý việc đăng ký
public function enrollments(): HasMany
{
    return $this->hasMany(Enrollment::class);
}
}
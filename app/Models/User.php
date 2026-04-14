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

    /**
     * Get the enrollments for the user.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    /**
     * Get the courses that the user has enrolled in.
     */
    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
            {
                return $this->belongsToMany(
                    Course::class,
                    'enrollments', // Tên bảng trung gian
                    'user_id',     // Khóa ngoại của User trong bảng enrollments
                    'course_id'    // Khóa ngoại của Course trong bảng enrollments
                );
            }

    /**
     * Get the provider profile of the user.
     */
    public function provider()
    {
        return $this->hasOne(Provider::class, 'user_id');
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
}

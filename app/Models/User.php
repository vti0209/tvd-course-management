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
    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(
            Course::class,
            Enrollment::class,
            'user_id',      // Foreign key on Enrollment pointing to User
            'course_id',    // Foreign key on Enrollment pointing to Course
            'id',           // Local key on User
            'id'            // Primary key on Course
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
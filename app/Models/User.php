<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
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

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
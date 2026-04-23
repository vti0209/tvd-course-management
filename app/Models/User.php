<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Provider;
use App\Models\Withdrawal;
/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string|null $full_name
 * @property string|null $phone
 * @property string|null $avatar
 * @property string $role
 * @property string $status
 * @property string|null $provider_info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course> $createdCourses
 * @property-read int|null $created_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Enrollment> $enrollments
 * @property-read int|null $enrollments_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course> $taughtCourses
 * @property-read int|null $taught_courses_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @mixin \Eloquent
 */
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
                    ->withPivot('status','payment_status', 'price_at_purchase', 'enrolled_at')
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

    public function providerProfile()
    {
        return $this->hasOne(Provider::class, 'user_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'provider_id');
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

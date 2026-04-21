<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $cv_file
 * @property string|null $bio
 * @property string|null $bank_account
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereCvFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereUserId($value)
 * @mixin \Eloquent
 */
class Provider extends Model
{
    use HasFactory;

    protected $table = 'provider_profiles';

    protected $fillable = [
        'user_id',
        'cv_file',
        'bio',
        'bank_account',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the provider profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses of the provider.
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'provider_id', 'user_id');
    }

    /**
     * Check if provider is approved.
     */
    public function isApproved()
    {
        return $this->approved_at !== null;
    }

    /**
     * Check if provider is pending approval.
     */
    public function isPending()
    {
        return $this->approved_at === null;
    }
}

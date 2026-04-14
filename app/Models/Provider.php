<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

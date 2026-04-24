<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'amount',
        'bank_account',
        'bank_name',
        'account_holder',
        'status',
        'processed_by',
        'rejection_reason',
        'requested_at',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    protected $appends = [
        'fee_amount',
        'net_amount',
    ];

    public const FEE_RATE = 0.2;

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function getFeeAmountAttribute()
    {
        return round($this->amount * self::FEE_RATE, 2);
    }

    public function getNetAmountAttribute()
    {
        return round($this->amount - $this->fee_amount, 2);
    }
}

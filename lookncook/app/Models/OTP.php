<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;


    protected $table = 'otps';

    protected $fillable = [
        'email',
        'otp',
        'type',
        'expires_at',
        'attempts',
        'is_verified',
        'verified_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    /**
     * Check if OTP is valid and not expired
     */
    public function isValid()
    {
        return !$this->expires_at->isPast() && !$this->is_verified;
    }

    /**
     * Check if OTP has expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Mark OTP as verified
     */
    public function markAsVerified()
    {
        $this->is_verified = true;
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Increment attempts counter
     */
    public function incrementAttempts()
    {
        $this->increment('attempts');
    }

    /**
     * Check if max attempts reached
     */
    public function maxAttemptsReached($max = 3)
    {
        return $this->attempts >= $max;
    }

    /**
     * Delete expired OTPs (cleanup)
     */
    public static function deleteExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
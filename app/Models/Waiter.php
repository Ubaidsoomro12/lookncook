<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waiter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'cnic',
        'image',
        'cnic_front_image',
        'cnic_back_image',
        'cv_image',
        'appointment_letter_image',
        'address',
        'hire_date',
        'salary',
        'status'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    // Check if waiter is active
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Get full name with title
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    // Get profile image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }
        return asset('assets/images/default-avatar.png');
    }

    // Get CNIC front image URL
    public function getCnicFrontUrlAttribute()
    {
        if ($this->cnic_front_image) {
            return asset($this->cnic_front_image);
        }
        return null;
    }

    // Get CNIC back image URL
    public function getCnicBackUrlAttribute()
    {
        if ($this->cnic_back_image) {
            return asset($this->cnic_back_image);
        }
        return null;
    }

    // Get CV image URL
    public function getCvUrlAttribute()
    {
        if ($this->cv_image) {
            return asset($this->cv_image);
        }
        return null;
    }

    // Get appointment letter image URL
    public function getAppointmentLetterUrlAttribute()
    {
        if ($this->appointment_letter_image) {
            return asset($this->appointment_letter_image);
        }
        return null;
    }
}
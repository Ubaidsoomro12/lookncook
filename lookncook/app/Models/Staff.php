<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'user_id', 'employee_id', 'name', 'email', 'phone', 'cnic',
        'gender', 'date_of_birth', 'image', 'cnic_front_image', 'cnic_back_image',
        'cv_image', 'appointment_letter_image', 'address',
        'emergency_contact_name', 'emergency_contact_number', 'blood_group',
        'hire_date', 'salary', 'salary_type', 'hourly_rate',
        'bank_account_no', 'bank_name', 'employee_type',
        'department', 'designation', 'branch', 'work_shift',
        'reporting_manager_id', 'status'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'date_of_birth' => 'date',
        'salary' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(Staff::class, 'reporting_manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Staff::class, 'reporting_manager_id');
    }

    // Accessors for images
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : asset('assets/images/default-avatar.png');
    }
}
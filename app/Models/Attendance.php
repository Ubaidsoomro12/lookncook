<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'staff_id', 'date', 'clock_in', 'clock_out',
        'status', 'late_minutes', 'early_minutes',
        'is_reopened', 'reopened_by', 'reopened_at'
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
        'is_reopened' => 'boolean',
        'reopened_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function reopenedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'reopened_by');
    }
}
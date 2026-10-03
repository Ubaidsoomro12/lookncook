<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'total_leaves',
        'month',
        'year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
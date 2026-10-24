<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [

        // ✅ Customer Fields

        'name', 
        'email', 
        'profession', 
        'message', 
        'rating',
        'status',
        
        // ✅ Staff Fields
        'staff_id',
        'staff_name',
        'table_id',      // 👈 table_no se badal kar table_id kar diya
        'staff_review',
        'staff_rating',
        'staff_status',
        'status'  // ✅ Status added

    ];
}
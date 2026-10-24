<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
        // ✅ Customer Fields
=======
>>>>>>> muzamil_dev
        'name', 
        'email', 
        'profession', 
        'message', 
        'rating',
<<<<<<< HEAD
        'status',
        
        // ✅ Staff Fields
        'staff_id',
        'staff_name',
        'table_id',      // 👈 table_no se badal kar table_id kar diya
        'staff_review',
        'staff_rating',
        'staff_status'
=======
        'status'  // ✅ Status added
>>>>>>> muzamil_dev
    ];
}
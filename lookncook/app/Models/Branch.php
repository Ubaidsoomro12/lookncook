<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = false;  // Disable timestamps
    
    protected $primaryKey = 'branch_id';
    
    protected $fillable = [
        'branch_name',
        'address',
        'phone',
        'status'
    ];
}
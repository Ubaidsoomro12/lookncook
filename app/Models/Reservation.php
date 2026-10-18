<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'customer_name', 'customer_phone', 'hall_name', 'address', 'event_date', 'total_guests',
        'food_subtotal', 'hall_payment', 'stage_decoration', 'front_decoration', 'overall_decoration',
        'decoration_total', 'tax_percent', 'tax_amount', 'discount', 'grand_total', 'per_head_amount',
        'status', 'notes', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'event_date' => 'date',
        'food_subtotal' => 'decimal:2',
        'hall_payment' => 'decimal:2',
        'stage_decoration' => 'decimal:2',
        'front_decoration' => 'decimal:2',
        'overall_decoration' => 'decimal:2',
        'decoration_total' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'per_head_amount' => 'decimal:2',
        'total_guests' => 'integer',
    ];

    public function items() 
    { 
        return $this->hasMany(ReservationItem::class); 
    }
    
    public function waiters() 
    { 
        return $this->belongsToMany(Staff::class, 'reservation_waiters', 'reservation_id', 'staff_id'); 
    }
    
    public function creator() 
    { 
        return $this->belongsTo(User::class, 'created_by'); 
    }
    
    public function updater() 
    { 
        return $this->belongsTo(User::class, 'updated_by'); 
    }
}
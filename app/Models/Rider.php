<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'image',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'cnic',
        'emergency_contact',
        'joining_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'joining_date' => 'date',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }

    // If you later add an "orders" table with a rider_id foreign key,
    // uncomment this so the controller can block deleting riders that
    // still have assigned orders (same pattern as PaymentMethod::orders()).
    //
    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }
}
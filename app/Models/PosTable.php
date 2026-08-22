<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosTable extends Model
{
    use HasFactory;

    protected $table = 'pos_tables';

    protected $fillable = [
        'table_number',
        'table_name',
        'capacity',
        'location',
        'section',
        'description',
        'qr_code',
        'status',
        'is_active'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied')->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Status Badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'available' => '<span class="badge bg-success">Available</span>',
            'occupied' => '<span class="badge bg-warning">Occupied</span>',
            'reserved' => '<span class="badge bg-danger">Reserved</span>',
            'maintenance' => '<span class="badge bg-secondary">Maintenance</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    // // Relationships
    // public function orders()
    // {
    //     return $this->hasMany(PosOrder::class, 'pos_table_id');
    // }

    // public function activeOrder()
    // {
    //     return $this->hasOne(PosOrder::class, 'pos_table_id')
    //                 ->whereIn('order_status', ['pending', 'confirmed', 'preparing', 'ready']);
    // }
}
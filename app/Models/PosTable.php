<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class PosTable extends Model
{
    use HasFactory;  // ← Remove , SoftDeletes

    protected $table = 'pos_tables';

    protected $fillable = [
        'branch_id',
        'branch_name',
        'branch_location',
        'table_name',
        'table_number',
        'capacity',
        'table_type',
        'description',
        'zone',
        'qr_code',
        'floor',
        'status',
        'is_active'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================================
    // 🔍 SCOPES
    // ========================================
    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied')->where('is_active', true);
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved')->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    public function scopeByTableType($query, $type)
    {
        return $query->where('table_type', $type);
    }

    // ========================================
    // 🎯 METHODS
    // ========================================
    
    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    public function isInMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    // ========================================
    // 🏷️ ACCESSORS
    // ========================================
    
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

    public function getStatusTextAttribute()
    {
        $statuses = [
            'available' => 'Available',
            'occupied' => 'Occupied',
            'reserved' => 'Reserved',
            'maintenance' => 'Maintenance',
        ];
        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getTableTypeBadgeAttribute()
    {
        $badges = [
            'dining' => '<span class="badge bg-primary">Dining</span>',
            'bar' => '<span class="badge bg-info">Bar</span>',
            'lounge' => '<span class="badge bg-purple">Lounge</span>',
            'private' => '<span class="badge bg-danger">Private</span>',
            'booth' => '<span class="badge bg-warning">Booth</span>',
            'outdoor' => '<span class="badge bg-success">Outdoor</span>',
            'indoor' => '<span class="badge bg-secondary">Indoor</span>',
        ];
        return $badges[$this->table_type] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getFullLocationAttribute()
    {
        $parts = [];
        if ($this->floor) $parts[] = $this->floor;
        if ($this->zone) $parts[] = $this->zone;
        if ($this->branch_location) $parts[] = $this->branch_location;
        return implode(' - ', $parts);
    }

    // ========================================
    // 📊 RELATIONSHIPS
    // ========================================
    
    // Uncomment when you have Order model
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
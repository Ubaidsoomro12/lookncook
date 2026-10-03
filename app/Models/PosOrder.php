<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosOrder extends Model
{
    use HasFactory;

    protected $table = 'pos_orders';

    protected $fillable = [
        'order_number', 'pos_table_id', 'waiter_id', 'customer_name',
        'status', 'subtotal', 'tax', 'discount', 'total_amount',
        'notes', 'created_by',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'tax'          => 'decimal:2',
        'discount'     => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at'   => 'datetime',
    ];

    // ---------- Relations ----------
    public function table()
    {
        return $this->belongsTo(PosTable::class, 'pos_table_id');
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function items()
    {
        return $this->hasMany(PosOrderItem::class, 'pos_order_id');
    }

    // ---------- Accessors ----------
    /**
     * `tax` column stores a percentage (e.g. 5 = 5%).
     * This returns the actual rupee amount.
     */
    public function getTaxAmountAttribute(): float
    {
        return round((float) $this->subtotal * (float) $this->tax / 100, 2);
    }

    /**
     * `discount` column stores a percentage (e.g. 10 = 10%).
     * This returns the actual rupee amount.
     */
    public function getDiscountAmountAttribute(): float
    {
        return round((float) $this->subtotal * (float) $this->discount / 100, 2);
    }

    // ---------- Helpers ----------
    public static function generateOrderNumber(): string
    {
        $prefix = 'POS-' . now()->format('ymd');
        $last = static::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('order_number');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Recalculate subtotal + total_amount.
     * `tax` and `discount` columns are PERCENTAGES.
     */
    public function recalculateTotals(): void
    {
        $subtotal = (float) $this->items()->sum('total_price');

        $taxPercent      = (float) $this->tax;
        $discountPercent = (float) $this->discount;

        $taxAmount      = round($subtotal * $taxPercent / 100, 2);
        $discountAmount = round($subtotal * $discountPercent / 100, 2);

        $this->subtotal     = $subtotal;
        $this->total_amount = max(0, $subtotal + $taxAmount - $discountAmount);
        $this->save();
    }
}
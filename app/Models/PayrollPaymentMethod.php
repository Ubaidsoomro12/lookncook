<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollPaymentMethod extends Model
{
    protected $table = 'payroll_payment_methods';

    protected $fillable = [
        'name', 'code', 'requires_reference', 'fields_schema',
        'description', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'requires_reference' => 'boolean',
        'is_active'          => 'boolean',
        'fields_schema'      => 'array',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
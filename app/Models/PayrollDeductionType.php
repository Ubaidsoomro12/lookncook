<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDeductionType extends Model
{
    protected $table = 'payroll_deduction_types';

    protected $fillable = ['name', 'default_amount', 'description', 'is_active', 'sort_order'];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function entries()
    {
        return $this->hasMany(StaffPayrollDeduction::class, 'deduction_type_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
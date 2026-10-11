<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollAllowanceType extends Model
{
    protected $table = 'payroll_allowance_types';

    protected $fillable = ['name', 'default_amount', 'description', 'is_active', 'sort_order'];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function entries()
    {
        return $this->hasMany(StaffPayrollAllowance::class, 'allowance_type_id');
    }

    public function companies()
    {
        return $this->hasMany(PayrollAllowanceCompany::class, 'allowance_type_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollAllowanceCompany extends Model
{
    protected $table = 'payroll_allowance_companies';

    protected $fillable = [
        'allowance_type_id',
        'name', 'contact_person', 'contact_number',
        'email', 'notes', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function allowanceType()
    {
        return $this->belongsTo(PayrollAllowanceType::class, 'allowance_type_id');
    }

    public function allowanceEntries()
    {
        return $this->hasMany(StaffPayrollAllowance::class, 'company_id');
    }
}
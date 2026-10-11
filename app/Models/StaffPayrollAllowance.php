<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPayrollAllowance extends Model
{
    protected $table = 'staff_payroll_allowances';

    protected $fillable = [
        'staff_payroll_id', 'allowance_type_id', 'company_id', 'amount',
    ];

    protected $casts = ['amount' => 'decimal:2'];

    public function payroll()
    {
        return $this->belongsTo(StaffPayroll::class, 'staff_payroll_id');
    }

    public function type()
    {
        return $this->belongsTo(PayrollAllowanceType::class, 'allowance_type_id');
    }

    public function company()
    {
        return $this->belongsTo(PayrollAllowanceCompany::class, 'company_id');
    }

    public function expenses()
    {
        return $this->hasMany(StaffAllowanceExpense::class, 'staff_payroll_allowance_id');
    }

    // ⭐ Requests against this allowance
    public function requests()
    {
        return $this->hasMany(StaffAllowanceRequest::class, 'staff_payroll_allowance_id');
    }

    // ⭐ Only approved requests
    public function approvedRequests()
    {
        return $this->hasMany(StaffAllowanceRequest::class, 'staff_payroll_allowance_id')
            ->where('status', 'approved');
    }

    // ⭐ Only pending requests
    public function pendingRequests()
    {
        return $this->hasMany(StaffAllowanceRequest::class, 'staff_payroll_allowance_id')
            ->where('status', 'pending');
    }
}
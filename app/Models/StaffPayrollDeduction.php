<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPayrollDeduction extends Model
{
    protected $table = 'staff_payroll_deductions';

    protected $fillable = ['staff_payroll_id', 'deduction_type_id', 'amount'];

    protected $casts = ['amount' => 'decimal:2'];

    public function payroll()
    {
        return $this->belongsTo(StaffPayroll::class, 'staff_payroll_id');
    }

    public function type()
    {
        return $this->belongsTo(PayrollDeductionType::class, 'deduction_type_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAllowanceExpense extends Model
{
    protected $table = 'staff_allowance_expenses';

    protected $fillable = [
        'staff_id',
        'staff_payroll_allowance_id',
        'amount',
        'description',
        'expense_date',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function allowanceEntry()
    {
        return $this->belongsTo(StaffPayrollAllowance::class, 'staff_payroll_allowance_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffPayroll extends Model
{
    use HasFactory;

    protected $table = 'staff_payrolls';

    protected $fillable = [
        'staff_id', 'period_month', 'period_year',
        'basic_salary', 'total_working_days', 'present_days', 'absent_days',
        'overtime_hours', 'overtime_rate', 'overtime_amount',
        'bonus',
        'total_allowances', 'total_deductions', 'gross_salary', 'net_salary',
        'payment_status', 'payment_date', 'payment_method', 'notes', 'created_by',
    ];

    protected $casts = [
        'basic_salary'    => 'decimal:2',
        'overtime_hours'  => 'decimal:2',
        'overtime_rate'   => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'bonus'           => 'decimal:2',
        'total_allowances'=> 'decimal:2',
        'total_deductions'=> 'decimal:2',
        'gross_salary'    => 'decimal:2',
        'net_salary'      => 'decimal:2',
        'payment_date'    => 'date',
    ];

    /* ---------- Relations ---------- */

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function allowances()
    {
        return $this->hasMany(StaffPayrollAllowance::class, 'staff_payroll_id');
    }

    public function deductions()
    {
        return $this->hasMany(StaffPayrollDeduction::class, 'staff_payroll_id');
    }

    /* ---------- Logic ---------- */

    public function recalculate(): void
    {
        $this->overtime_amount = round((float) $this->overtime_hours * (float) $this->overtime_rate, 2);

        $this->total_allowances = round((float) $this->allowances()->sum('amount'), 2);
        $this->total_deductions = round((float) $this->deductions()->sum('amount'), 2);

        $this->gross_salary = round(
            (float) $this->basic_salary
            + $this->total_allowances
            + (float) $this->overtime_amount
            + (float) $this->bonus,
            2
        );

        $this->net_salary = max(0, round($this->gross_salary - $this->total_deductions, 2));
    }

    /* ---------- Accessors ---------- */

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, (int) $this->period_month, 1));
    }

    public function getPeriodLabelAttribute(): string
    {
        return $this->month_name . ' ' . $this->period_year;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'      => '<span class="badge bg-success">Paid</span>',
            'pending'   => '<span class="badge bg-warning text-dark">Pending</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            default     => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
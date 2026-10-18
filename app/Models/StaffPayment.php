<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPayment extends Model
{
    protected $table = 'staff_payments';

    protected $fillable = [
        'staff_id', 'staff_payroll_id', 'payment_method_id',
        'amount', 'payment_date', 'reference_number', 
        'status', 'notes', 'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function payroll()
    {
        return $this->belongsTo(StaffPayroll::class, 'staff_payroll_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PayrollPaymentMethod::class, 'payment_method_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
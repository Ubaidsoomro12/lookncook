<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAllowanceRequest extends Model
{
    protected $table = 'staff_allowance_requests';

    protected $fillable = [
        'staff_id', 'staff_payroll_allowance_id', 'amount', 'expense_date',
        'description', 'receipt_image', 'status', 'admin_notes',
        'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
        'reviewed_at'  => 'datetime',
    ];

    /* ---------- Relations ---------- */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function allowanceEntry()
    {
        return $this->belongsTo(StaffPayrollAllowance::class, 'staff_payroll_allowance_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /* ---------- Accessors ---------- */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            default    => '<span class="badge bg-warning text-dark">Pending</span>',
        };
    }

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_image ? asset($this->receipt_image) : null;
    }
}
<div class="modal-header" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#fff;border:none;padding:18px 22px;">
    <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-invoice me-2"></i>Allowance Breakdown - {{ $payroll->period_label }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(2);"></button>
</div>
<div class="modal-body p-4" style="background:#f8fafc;">
    
    <div class="row g-3">
        {{-- ALLOWANCES --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold small text-success border-0 pt-3">
                    <i class="fa-solid fa-plus-circle me-1"></i> Allowances Given
                </div>
                <ul class="list-group list-group-flush small">
                    @forelse($payroll->allowances as $a)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $a->type?->name ?? 'Allowance' }}
                                @if($a->company)
                                    <div class="text-muted" style="font-size:11px;">{{ $a->company->name }}</div>
                                @endif
                            </div>
                            <span class="fw-semibold">+ Rs {{ number_format($a->amount, 2) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No allowances</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- DEDUCTIONS --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold small text-danger border-0 pt-3">
                    <i class="fa-solid fa-minus-circle me-1"></i> Deductions
                </div>
                <ul class="list-group list-group-flush small">
                    @forelse($payroll->deductions as $d)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $d->type?->name ?? 'Deduction' }}
                            <span class="fw-semibold text-danger">- Rs {{ number_format($d->amount, 2) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No deductions</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    @if($payroll->bonus > 0 || $payroll->overtime_amount > 0)
    <div class="mt-3 p-3 bg-white rounded border shadow-sm">
        <h6 class="fw-bold small text-muted mb-2">Additional Earnings</h6>
        <div class="d-flex justify-content-between small mb-1">
            <span class="text-muted">Bonus</span>
            <span class="fw-semibold text-success">+ Rs {{ number_format($payroll->bonus, 2) }}</span>
        </div>
        <div class="d-flex justify-content-between small">
            <span class="text-muted">Overtime ({{ $payroll->overtime_hours }} hrs @ Rs {{ number_format($payroll->overtime_rate, 2) }})</span>
            <span class="fw-semibold text-success">+ Rs {{ number_format($payroll->overtime_amount, 2) }}</span>
        </div>
    </div>
    @endif

</div>
<div class="modal-footer" style="border-top:1px solid #eef2f7;padding:14px 22px;background:#fff;">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
</div>
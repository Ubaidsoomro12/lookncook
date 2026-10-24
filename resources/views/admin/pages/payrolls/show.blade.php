@extends('admin.layouts.master')

@section('title', 'Payroll — ' . $payroll->period_label)

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Payroll Details</h4>
            <p class="text-muted small m-0">{{ $payroll->staff?->name }} — {{ $payroll->period_label }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.payrolls.slip', $payroll->id) }}" target="_blank"
               class="btn btn-outline-dark">
                <i class="fa-solid fa-print me-2"></i>Print Slip
            </a>
            <a href="{{ route('admin.payrolls.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-3">

        {{-- STAFF INFO --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Staff Information</h6>
                    <table class="table table-borderless mb-0 small">
                        <tr><td class="text-muted" style="width:40%;">Name</td><td class="fw-semibold">{{ $payroll->staff?->name ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Employee ID</td><td>{{ $payroll->staff?->employee_id ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Designation</td><td>{{ $payroll->staff?->designation ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Department</td><td>{{ $payroll->staff?->department ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Period</td><td><span class="badge bg-light text-dark">{{ $payroll->period_label }}</span></td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- PAYMENT INFO --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Payment Information</h6>
                    <table class="table table-borderless mb-0 small">
                        <tr><td class="text-muted" style="width:40%;">Status</td><td>{!! $payroll->status_badge !!}</td></tr>
                        <tr><td class="text-muted">Payment Date</td><td>{{ $payroll->payment_date?->format('d M Y') ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Method</td><td>{{ $payroll->payment_method ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Created By</td><td>{{ $payroll->creator?->name ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Created At</td><td>{{ $payroll->created_at?->format('d M Y, h:i A') ?? '—' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- EARNINGS --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Earnings</h6>
                    <table class="table table-sm small mb-0">
                        <tr><td>Basic Salary</td><td class="text-end fw-semibold">{{ number_format($payroll->basic_salary, 2) }}</td></tr>

                        @forelse($payroll->allowances as $entry)
                            <tr>
                                <td>{{ $entry->type?->name ?? '—' }}</td>
                                <td class="text-end">{{ number_format($entry->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted small text-center py-2">No allowances</td></tr>
                        @endforelse

                        <tr>
                            <td>Overtime
                                <small class="text-muted">({{ $payroll->overtime_hours }} h × {{ number_format($payroll->overtime_rate, 2) }})</small>
                            </td>
                            <td class="text-end">{{ number_format($payroll->overtime_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Bonus</td>
                            <td class="text-end">{{ number_format($payroll->bonus, 2) }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="fw-bold">Gross Salary</td>
                            <td class="text-end fw-bold">{{ number_format($payroll->gross_salary, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- DEDUCTIONS --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Deductions</h6>
                    <table class="table table-sm small mb-0">
                        @forelse($payroll->deductions as $entry)
                            <tr>
                                <td>{{ $entry->type?->name ?? '—' }}</td>
                                <td class="text-end text-danger">- {{ number_format($entry->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted small text-center py-2">No deductions</td></tr>
                        @endforelse
                        <tr class="border-top">
                            <td class="fw-bold">Total Deductions</td>
                            <td class="text-end fw-bold text-danger">- {{ number_format($payroll->total_deductions, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- NET --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background:#111827;color:#f3f4f6;">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="text-uppercase small" style="opacity:.7;">NET PAYABLE</div>
                        <div style="font-size:34px;font-weight:800;color:#ff2d7a;">
                            Rs {{ number_format($payroll->net_salary, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($payroll->notes)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-2 text-uppercase small text-muted">Notes</h6>
                    <p class="mb-0 small">{{ $payroll->notes }}</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
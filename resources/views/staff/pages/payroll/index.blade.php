@extends('staff.layouts.master')

@section('title', 'My Payroll')
@section('page-title', 'My Payroll')

@section('content')
<div class="container-fluid px-4 py-4">
    
    {{-- HEADER --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Payroll</h4>
            <p class="text-muted small m-0">View your salary history and payment transactions</p>
        </div>
        @if($staff)
        <div class="text-end">
            <div class="fw-bold">{{ $staff->name }}</div>
            <div class="text-muted small">{{ $staff->employee_id }} • {{ $staff->designation }}</div>
        </div>
        @endif
    </div>

    @if(!$staff)
        <div class="alert alert-warning border-0 d-flex align-items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation fs-3"></i>
            <div>
                <div class="fw-bold">No staff record found</div>
                <div class="small">Your user account isn't linked to a staff profile yet.</div>
            </div>
        </div>
    @else
        {{-- SUMMARY CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:12px;background:#fff5f8;color:#ff2d7a;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-calendar-day fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">This Month</div>
                            <div class="fw-bold fs-5">Rs {{ number_format($summary['this_month'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:12px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-sack-dollar fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Base Salary</div>
                            <div class="fw-bold fs-5">Rs {{ number_format($summary['base_salary'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:12px;background:#ecfdf5;color:#10b981;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-circle-check fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Paid</div>
                            <div class="fw-bold fs-5">Rs {{ number_format($summary['total_paid'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;color:#f59e0b;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-hourglass-half fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Pending Payout</div>
                            <div class="fw-bold fs-5">Rs {{ number_format($summary['pending'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTERS (Dynamic - No Page Reload) --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select id="filterYear" class="form-select" onchange="applyPayrollFilters()">
                            <option value="">All Years</option>
                            @for($y = date('Y'); $y >= $hireYear; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filterStatus" class="form-select" onchange="applyPayrollFilters()">
                            <option value="">All Statuses</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" onclick="applyPayrollFilters()" class="btn w-100" style="background:#ff2d7a;color:#fff;border:none;">
                            <i class="fa-solid fa-filter me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" onclick="resetPayrollFilters()" class="btn btn-outline-secondary w-100">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Payment Date</th>
                                <th>Type</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="payrollTableBody">
                            @foreach($payments as $index => $p)
                                <tr class="payment-row" data-year="{{ $p->payment_date->format('Y') }}" data-status="{{ $p->status }}">
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>{{ $p->payment_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">Salary Payment</span>
                                    </td>
                                    <td>{{ $p->paymentMethod?->name ?? '—' }}</td>
                                    <td>{{ $p->reference_number ?? '—' }}</td>
                                    <td class="text-end fw-bold" style="color:#ff2d7a;">Rs {{ number_format($p->amount, 2) }}</td>
                                    <td>
                                        @if($p->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($p->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $p->notes ?? '—' }}</td>
                                </tr>
                            @endforeach

                            {{-- Empty state row --}}
                            <tr id="noRecordsRow" style="display: none;">
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                                    No payment records found for the selected filters.
                                </td>
                            </tr>

                            {{-- Server-side empty state (if they have zero payments at all) --}}
                            @if($payments->isEmpty())
                                <tr id="emptyServerRow">
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-receipt fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                                        No payment transactions found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between mt-4" id="paginationInfo">
            <p class="text-muted small mb-0" id="recordCountText">
                Showing <strong>{{ $payments->count() }}</strong> entries
            </p>
        </div>

    @endif
</div>

<script>
    function applyPayrollFilters() {
        const yearValue = document.getElementById('filterYear').value;
        const statusValue = document.getElementById('filterStatus').value;
        
        const rows = document.querySelectorAll('.payment-row');
        const noRecordsRow = document.getElementById('noRecordsRow');
        const recordCountText = document.getElementById('recordCountText');
        const emptyServerRow = document.getElementById('emptyServerRow');

        // If there were truly no records to begin with, do nothing
        if (rows.length === 0) return;
        
        // Hide the server empty row just in case
        if (emptyServerRow) emptyServerRow.style.display = 'none';

        let visibleCount = 0;

        rows.forEach(row => {
            const rowYear = row.getAttribute('data-year');
            const rowStatus = row.getAttribute('data-status');

            const matchYear = !yearValue || rowYear === yearValue;
            const matchStatus = !statusValue || rowStatus === statusValue;

            if (matchYear && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Handle empty state
        if (visibleCount === 0) {
            noRecordsRow.style.display = '';
        } else {
            noRecordsRow.style.display = 'none';
        }

        // Update text
        if (recordCountText) {
            recordCountText.innerHTML = 'Showing <strong>' + visibleCount + '</strong> entries';
        }
    }

    function resetPayrollFilters() {
        document.getElementById('filterYear').value = '';
        document.getElementById('filterStatus').value = '';
        applyPayrollFilters();
    }
</script>
@endsection
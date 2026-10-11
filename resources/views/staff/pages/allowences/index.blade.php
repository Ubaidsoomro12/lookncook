{{-- ============================================================
     FILE: resources/views/staff/pages/allowences/index.blade.php
     PURPOSE: Staff's own allowances dashboard
     LAST UPDATED: 2026-10-11
     ============================================================ --}}
@extends('staff.layouts.master')

@section('title', 'My Allowances')
@section('page-title', 'My Allowances')

@section('content')

<style>
    .alw-summary-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .alw-summary-card.clickable {
        cursor: pointer;
        transition: all .2s ease;
    }
    .alw-summary-card.clickable:hover {
        border-color: #ffd6e5;
        box-shadow: 0 10px 24px -10px rgba(255,45,122,.35);
        transform: translateY(-2px);
    }
    .alw-summary-card .alw-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .alw-summary-card .alw-label {
        font-size: 11px; text-transform: uppercase; letter-spacing: 1px;
        color: #6b7280; font-weight: 700; margin-bottom: 3px;
    }
    .alw-summary-card .alw-value {
        font-size: 22px; font-weight: 800; color: #111827; line-height: 1.1;
    }
    .alw-summary-card .alw-sub {
        font-size: 11px; color: #9ca3af; margin-top: 3px;
    }

    .alw-company-tile {
        background: #fff;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 14px 16px;
        height: 100%;
        transition: all .2s ease;
    }
    .alw-company-tile:hover {
        border-color: #ffd6e5;
        box-shadow: 0 6px 18px -8px rgba(255,45,122,.25);
    }
    .alw-company-tile .ac-name {
        font-size: 13px; font-weight: 700; color: #111827;
        margin-bottom: 3px;
        word-break: break-word;
    }
    .alw-company-tile .ac-row {
        display: flex; justify-content: space-between;
        font-size: 12px; padding: 2px 0;
        color: #4b5563;
    }
    .alw-company-tile .ac-row strong { color: #111827; }

    .alw-progress {
        height: 6px;
        background: #f3f4f6;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 8px;
    }
    .alw-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #34d399);
        transition: width .3s ease;
    }
    .alw-progress-bar.warn { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .alw-progress-bar.danger { background: linear-gradient(90deg, #ef4444, #f87171); }

    .alw-table {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .alw-table table { margin: 0; }
    .alw-table thead th {
        background: #fafafa;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #6b7280;
        font-weight: 700;
        padding: 12px 16px;
        border-bottom: 1px solid #eef2f7;
    }
    .alw-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f6f8fb;
        font-size: 13px;
        vertical-align: middle;
    }
    .alw-table tbody tr:last-child td { border-bottom: none; }
    .alw-table tbody tr:hover { background: #fbfcfe; }

    .btn-record {
        background: linear-gradient(135deg, #ff2d7a, #ff4b91);
        color: #fff;
        border: none;
        padding: 11px 22px;
        border-radius: 11px;
        font-weight: 700;
        font-size: 13px;
        box-shadow: 0 8px 18px -8px rgba(255,45,122,.5);
        transition: all .2s ease;
    }
    .btn-record:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 12px 24px -8px rgba(255,45,122,.7);
    }
    .btn-record:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    /* ── Companies popup ── */
    .comp-list-item {
        background: #fff;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 10px;
        transition: all .2s ease;
    }
    .comp-list-item:last-child { margin-bottom: 0; }
    .comp-list-item:hover {
        border-color: #ffd6e5;
        background: #fffbfd;
    }
    .comp-list-item .cl-name {
        font-weight: 700;
        color: #111827;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .comp-list-item .cl-name i { color: #10b981; font-size: 12px; }
    .comp-list-item .cl-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }
    .comp-list-item .cl-stats strong { color: #111827; }
    .comp-list-item .cl-stats .lbl { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: #9ca3af; font-weight: 700; }
    .comp-list-item .cl-stats .given strong { color: #10b981; }
    .comp-list-item .cl-stats .spent strong { color: #ef4444; }
    .comp-list-item .cl-stats .left strong { color: #ff2d7a; }

    /* ── Show-records toolbar ── */
    .req-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }
    .req-toolbar .req-toolbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .req-toolbar label {
        font-size: 12.5px;
        font-weight: 700;
        color: #4b5563;
        margin: 0;
        white-space: nowrap;
    }
    .req-toolbar select {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        color: #111827;
        background: #fff;
        min-width: 190px;
        cursor: pointer;
        transition: all .2s ease;
    }
    .req-toolbar select:focus {
        outline: none;
        border-color: #ff2d7a;
        box-shadow: 0 0 0 3px rgba(255,45,122,.12);
    }
    .req-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fef3c7;
        color: #b45309;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
    }
    .req-count-badge.approved { background: #d1fae5; color: #047857; }
    .req-count-badge.rejected { background: #fee2e2; color: #b91c1c; }
    .req-count-badge.all      { background: #e0e7ff; color: #3730a3; }

    .req-empty-row td {
        text-align: center;
        padding: 40px 16px !important;
        color: #9ca3af;
    }
</style>

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Allowances</h4>
            <p class="text-muted small m-0">Track what you've been given and what you've spent</p>
        </div>
        @if($availableEntries->isNotEmpty())
            <button class="btn-record" data-bs-toggle="modal" data-bs-target="#requestModal">
                <i class="fa-solid fa-paper-plane me-2"></i>Submit Request
            </button>
        @endif
    </div>

    @if(!$staff)

        <div class="alert alert-warning border-0 d-flex align-items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation fs-3"></i>
            <div>
                <div class="fw-bold">No staff record found</div>
                <div class="small">Your user account isn't linked to a staff profile yet. Please contact the administrator.</div>
            </div>
        </div>

    @else

        {{-- SUMMARY CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="alw-summary-card">
                    <div class="alw-icon" style="background:#ecfdf5;color:#10b981;">
                        <i class="fa-solid fa-circle-plus"></i>
                    </div>
                    <div>
                        <div class="alw-label">Total Given</div>
                        <div class="alw-value">Rs {{ number_format($summary['given'], 2) }}</div>
                        <div class="alw-sub">{{ $entries->count() }} allowance{{ $entries->count() === 1 ? '' : 's' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="alw-summary-card">
                    <div class="alw-icon" style="background:#fef2f2;color:#ef4444;">
                        <i class="fa-solid fa-circle-minus"></i>
                    </div>
                    <div>
                        <div class="alw-label">Approved Spent</div>
                        <div class="alw-value">Rs {{ number_format($summary['spent'], 2) }}</div>
                        <div class="alw-sub">Confirmed by admin</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="alw-summary-card">
                    <div class="alw-icon" style="background:#fef3c7;color:#f59e0b;">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="alw-label">Pending</div>
                        <div class="alw-value" style="color:#f59e0b;">Rs {{ number_format($summary['pending'], 2) }}</div>
                        <div class="alw-sub">Awaiting approval</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="alw-summary-card clickable"
                     data-bs-toggle="modal"
                     data-bs-target="#companiesModal">
                    <div class="alw-icon" style="background:#fff5f8;color:#ff2d7a;">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <div class="alw-label">Balance Left</div>
                        <div class="alw-value" style="color:#ff2d7a;">Rs {{ number_format($summary['left'], 2) }}</div>
                        <div class="alw-sub">Tap companies →</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALLOWANCE ENTRIES TABLE --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase small text-muted mb-3">
                <i class="fa-solid fa-list me-2" style="color:#ff2d7a;"></i>
                All Allowance Records
            </h6>
            <div class="alw-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Type</th>
                                <th>Company</th>
                                <th class="text-end">Given</th>
                                <th class="text-end">Spent</th>
                                <th class="text-end">Left</th>
                                <th style="width:140px;">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $e)
                                @php
                                    $pct = $e->amount > 0 ? ($e->spent_computed / $e->amount) * 100 : 0;
                                    $cls = $pct >= 90 ? 'danger' : ($pct >= 60 ? 'warn' : '');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ date('F', mktime(0,0,0,(int)$e->payroll->period_month,1)) }}
                                            {{ $e->payroll->period_year }}
                                        </div>
                                        <div class="text-muted small">
                                            Payroll #{{ $e->payroll->order_number ?? $e->payroll->id }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $e->type?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($e->company)
                                            <i class="fa-solid fa-building me-1 text-muted"></i>
                                            {{ $e->company->name }}
                                        @else
                                            <span class="text-muted small">Direct</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold">Rs {{ number_format($e->amount, 2) }}</td>
                                    <td class="text-end text-danger">- Rs {{ number_format($e->spent_computed, 2) }}</td>
                                    <td class="text-end fw-bold" style="color:#ff2d7a;">
                                        Rs {{ number_format($e->left_computed, 2) }}
                                    </td>
                                    <td>
                                        <div class="alw-progress">
                                            <div class="alw-progress-bar {{ $cls }}" style="width:{{ min(100, $pct) }}%;"></div>
                                        </div>
                                        <div class="text-muted small mt-1" style="font-size:10px;">
                                            {{ number_format($pct, 0) }}% used
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                                        No allowances yet. Your salary slip will show them once payroll is created.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MY REQUESTS --}}
        @php
            $pendingCount  = $requests->where('status', 'pending')->count();
            $approvedCount = $requests->where('status', 'approved')->count();
            $rejectedCount = $requests->where('status', 'rejected')->count();
            $allCount      = $requests->count();
        @endphp

        <div class="mb-4">
            <h6 class="fw-bold text-uppercase small text-muted mb-3">
                <i class="fa-solid fa-paper-plane me-2" style="color:#ff2d7a;"></i>
                My Requests
            </h6>

            @if($requests->isNotEmpty())

                {{-- SHOW RECORDS DROPDOWN --}}
                <div class="req-toolbar">
                    <div class="req-toolbar-left">
                        <label for="reqShowSelect"><i class="fa-solid fa-filter me-1"></i>Show records:</label>
                        <select id="reqShowSelect">
                            <option value="pending" selected>Pending (default)</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="all">All History</option>
                        </select>
                    </div>
                    <div>
                        <span id="reqCountBadge" class="req-count-badge">
                            <i class="fa-solid fa-hourglass-half"></i>
                            <span id="reqCountText">{{ $pendingCount }} pending</span>
                        </span>
                    </div>
                </div>

                <div class="alw-table">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Allowance</th>
                                    <th>Company</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                    <th style="width:60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="requestsTableBody">
                                @foreach($requests as $r)
                                    <tr class="request-row" data-status="{{ $r->status }}">
                                        <td>{{ $r->expense_date?->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $r->allowanceEntry?->type?->name ?? '—' }}
                                            </span>
                                        </td>
                                        <td>{{ $r->allowanceEntry?->company?->name ?? 'Direct' }}</td>
                                        <td class="text-muted small">
                                            {{ \Str::limit($r->description, 50) }}
                                            @if($r->admin_notes)
                                                <div style="font-size:11px;color:#dc2626;margin-top:2px;">
                                                    <i class="fa-solid fa-comment-dots me-1"></i>
                                                    {{ \Str::limit($r->admin_notes, 60) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold">Rs {{ number_format($r->amount, 2) }}</td>
                                        <td>{!! $r->status_badge !!}</td>
                                        <td class="text-center">
                                            @if($r->status === 'pending')
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger cancel-request-btn"
                                                        data-url="{{ route('staff.allowences.request.cancel', $r->id) }}"
                                                        title="Cancel">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- Empty row shown by JS when the current selection has no rows --}}
                                <tr id="reqNoRows" class="req-empty-row" style="display:none;">
                                    <td colspan="7">
                                        <i class="fa-solid fa-inbox fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                                        No <span id="reqNoRowsLabel">pending</span> requests found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                <div class="alw-table">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-paper-plane fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                                        You haven't submitted any allowance requests yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- RECENT EXPENSES --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase small text-muted mb-3">
                <i class="fa-solid fa-receipt me-2" style="color:#ff2d7a;"></i>
                Recent Expenses
            </h6>
            <div class="alw-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Allowance</th>
                                <th>Company</th>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                                <th style="width:60px;"></th>
                            </tr>
                        </thead>
                        <tbody id="expensesTableBody">
                            @forelse($expenses as $x)
                                <tr data-expense-id="{{ $x->id }}">
                                    <td>{{ $x->expense_date?->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $x->allowanceEntry?->type?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $x->allowanceEntry?->company?->name ?? 'Direct' }}</td>
                                    <td class="text-muted small">{{ $x->description ?: '—' }}</td>
                                    <td class="text-end fw-semibold">Rs {{ number_format($x->amount, 2) }}</td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-expense-btn"
                                                data-url="{{ route('staff.allowences.expense.destroy', $x->id) }}"
                                                title="Remove">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noExpensesRow">
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-receipt fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                                        No expenses recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================================
             COMPANIES POPUP
             ============================================================ --}}
        <div class="modal fade" id="companiesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">

                    <div class="modal-header" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#fff;border:none;padding:18px 22px;">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-building me-2"></i>My Allowance Companies
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(2);"></button>
                    </div>

                    <div class="modal-body p-4">

                        <div class="alert alert-light border small mb-3">
                            <i class="fa-solid fa-info-circle me-1 text-primary"></i>
                            Select an allowance category below to see which companies provided it.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Allowance Category</label>
                            <select id="companiesTypeSelect" class="form-select">
                                <option value="">-- Select a category --</option>
                                @foreach($entriesByType as $group)
                                    <option value="{{ $group['type_id'] }}">
                                        {{ $group['type_name'] }}
                                        ({{ count($group['companies']) }} compan{{ count($group['companies']) === 1 ? 'y' : 'ies' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="companiesEmpty" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-hand-pointer fa-2x mb-3 d-block" style="color:#e5e7eb;"></i>
                            <div class="small">Pick a category above to view its companies.</div>
                        </div>

                        <div id="companiesList" style="display:none;"></div>

                    </div>

                    <div class="modal-footer" style="border-top:1px solid #eef2f7;padding:14px 22px;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUBMIT REQUEST MODAL --}}
        @if($availableEntries->isNotEmpty())
        <div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">

                    <div class="modal-header" style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;padding:18px 22px;">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-paper-plane me-2"></i>Submit Allowance Request
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(2);"></button>
                    </div>

                    <form id="requestForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">

                            <div class="alert alert-info border-0 small mb-3">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Your request will be reviewed by admin. Once approved, the amount will be deducted from your allowance.
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Allowance <span class="text-danger">*</span></label>
                                <select name="staff_payroll_allowance_id" id="requestEntrySelect" class="form-select" required>
                                    <option value="">-- Select allowance --</option>
                                    @foreach($availableEntries as $e)
                                        <option value="{{ $e->id }}"
                                                data-available="{{ $e->available_computed }}">
                                            {{ $e->type?->name ?? 'Allowance' }}
                                            — {{ $e->company?->name ?? 'Direct' }}
                                            — {{ date('M Y', mktime(0,0,0,(int)$e->payroll->period_month,1,(int)$e->payroll->period_year)) }}
                                            (Available: Rs {{ number_format($e->available_computed, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text" id="requestBalanceHint">Select an allowance to see available amount.</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Amount (Rs) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="requestAmount" class="form-control"
                                           min="0.01" step="0.01" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Expense Date <span class="text-danger">*</span></label>
                                    <input type="date" name="expense_date" class="form-control"
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" rows="3" required
                                              placeholder="What did you spend it on? (e.g. Hospital bill, medicine, fuel)"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Receipt Image (optional)</label>
                                    <input type="file" name="receipt_image" class="form-control"
                                           accept="image/jpeg,image/png,image/jpg,image/webp">
                                    <div class="form-text">Max 4MB. JPG, PNG, WebP.</div>
                                </div>
                            </div>

                            <div id="requestError" class="alert alert-danger border-0 small mt-3 mb-0" style="display:none;"></div>
                        </div>

                        <div class="modal-footer" style="border-top:1px solid #eef2f7;padding:14px 22px;">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-record" id="requestSubmitBtn">
                                <i class="fa-solid fa-paper-plane me-1"></i>Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
window.STAFF_ALLOWANCE_COMPANIES = @json($entriesByType ?? []);

/* ══════════════════════════════════════════════════════════════
   SHOW RECORDS — dropdown filter (default = Pending only)
   ══════════════════════════════════════════════════════════════ */
(function () {
    'use strict';

    function applyShowFilter(value) {
        var rows    = document.querySelectorAll('#requestsTableBody .request-row');
        var noRows  = document.getElementById('reqNoRows');
        var noRowsL = document.getElementById('reqNoRowsLabel');
        var badge   = document.getElementById('reqCountBadge');
        var countTx = document.getElementById('reqCountText');

        var visible = 0;

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var match = (value === 'all') || (row.getAttribute('data-status') === value);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        }

        if (noRows) {
            if (visible === 0) {
                noRows.style.display = '';
                if (noRowsL) noRowsL.textContent = (value === 'all' ? 'request' : value + ' request');
            } else {
                noRows.style.display = 'none';
            }
        }

        // Update count badge (color + text)
        if (badge && countTx) {
            badge.classList.remove('approved', 'rejected', 'all');
            var icon = badge.querySelector('i');

            if (value === 'approved') {
                badge.classList.add('approved');
                if (icon) icon.className = 'fa-solid fa-circle-check';
            } else if (value === 'rejected') {
                badge.classList.add('rejected');
                if (icon) icon.className = 'fa-solid fa-circle-xmark';
            } else if (value === 'all') {
                badge.classList.add('all');
                if (icon) icon.className = 'fa-solid fa-layer-group';
            } else {
                if (icon) icon.className = 'fa-solid fa-hourglass-half';
            }

            countTx.textContent = visible + ' ' + (value === 'all' ? 'total' : value);
        }
    }

    var select = document.getElementById('reqShowSelect');
    if (select) {
        // Default view = pending
        applyShowFilter(select.value || 'pending');

        select.addEventListener('change', function () {
            applyShowFilter(this.value);
        });
    }

    window.applyShowFilter = applyShowFilter;
})();

/* ══════════════════════════════════════════════════════════════
   EVERYTHING ELSE (companies popup, request modal, etc.)
   ══════════════════════════════════════════════════════════════ */
(function(){
    'use strict';
    const $ = s => document.querySelector(s);
    const $$ = s => Array.from(document.querySelectorAll(s));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    function esc(s){
        return String(s == null ? '' : s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }
    function money(n){
        return 'Rs ' + (Number(n)||0).toLocaleString(undefined,{
            minimumFractionDigits:2, maximumFractionDigits:2
        });
    }

    /* ── Companies popup ── */
    const typeSelect = document.getElementById('companiesTypeSelect');
    const listEl     = document.getElementById('companiesList');
    const emptyEl    = document.getElementById('companiesEmpty');

    if (typeSelect && listEl && emptyEl) {
        typeSelect.addEventListener('change', function(){
            const typeId = this.value;

            if (!typeId) {
                emptyEl.style.display = 'block';
                emptyEl.innerHTML = `
                    <i class="fa-solid fa-hand-pointer fa-2x mb-3 d-block" style="color:#e5e7eb;"></i>
                    <div class="small">Pick a category above to view its companies.</div>`;
                listEl.style.display = 'none';
                listEl.innerHTML = '';
                return;
            }

            const group = (window.STAFF_ALLOWANCE_COMPANIES || [])
                .find(g => String(g.type_id) === String(typeId));

            if (!group || !group.companies || group.companies.length === 0) {
                emptyEl.style.display = 'block';
                emptyEl.innerHTML = `
                    <i class="fa-solid fa-circle-info fa-2x mb-3 d-block" style="color:#e5e7eb;"></i>
                    <div class="small">No companies linked to this allowance yet.</div>`;
                listEl.style.display = 'none';
                listEl.innerHTML = '';
                return;
            }

            let html = '';
            group.companies.forEach(c => {
                const given = Number(c.given) || 0;
                const spent = Number(c.spent) || 0;
                const left  = Number(c.left)  || 0;
                const pct   = given > 0 ? (spent / given) * 100 : 0;
                const cls   = pct >= 90 ? 'danger' : (pct >= 60 ? 'warn' : '');
                const label = c.company_id ? c.company_name : 'Direct / No Company';

                html += `
                    <div class="comp-list-item">
                        <div class="cl-name">
                            <i class="fa-solid fa-circle-check"></i>
                            ${esc(label)}
                            <span class="badge bg-light text-dark ms-auto" style="font-size:10px;">
                                ${c.count} entr${c.count === 1 ? 'y' : 'ies'}
                            </span>
                        </div>
                        <div class="cl-stats">
                            <div class="given">
                                <span class="lbl">Given</span>
                                <strong>${money(given)}</strong>
                            </div>
                            <div class="spent">
                                <span class="lbl">Spent</span>
                                <strong>- ${money(spent)}</strong>
                            </div>
                            <div class="left">
                                <span class="lbl">Balance</span>
                                <strong>${money(left)}</strong>
                            </div>
                        </div>
                        <div class="alw-progress">
                            <div class="alw-progress-bar ${cls}" style="width:${Math.min(100, pct)}%;"></div>
                        </div>
                    </div>`;
            });

            emptyEl.style.display = 'none';
            listEl.innerHTML = html;
            listEl.style.display = 'block';
        });
    }

    /* ── Request modal balance hint ── */
    const reqSelect = document.getElementById('requestEntrySelect');
    const reqAmount = document.getElementById('requestAmount');
    const reqHint   = document.getElementById('requestBalanceHint');

    if (reqSelect && reqHint) {
        reqSelect.addEventListener('change', function(){
            const opt = this.options[this.selectedIndex];
            const bal = parseFloat(opt.dataset.available || 0);
            if (opt.value) {
                reqHint.innerHTML = '<i class="fa-solid fa-info-circle me-1 text-primary"></i>Available: <strong>Rs ' + bal.toLocaleString(undefined,{minimumFractionDigits:2}) + '</strong>';
                reqAmount.max = bal;
            } else {
                reqHint.textContent = 'Select an allowance to see available amount.';
                reqAmount.removeAttribute('max');
            }
        });
    }

    /* ── Submit request ── */
    const requestForm = document.getElementById('requestForm');
    if (requestForm) {
        requestForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const btn = document.getElementById('requestSubmitBtn');
            const errBox = document.getElementById('requestError');
            const orig = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
            errBox.style.display = 'none';

            try {
                const formData = new FormData(requestForm);
                const res = await fetch('{{ route("staff.allowences.request.submit") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    let msg = data.message || 'Please check your input.';
                    if (data.errors) msg = Object.values(data.errors).flat().join(' ');
                    errBox.textContent = msg;
                    errBox.style.display = 'block';
                    btn.disabled = false;
                    btn.innerHTML = orig;
                    return;
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Submitted!',
                    text: data.message || 'Request submitted for approval.',
                    timer: 1400,
                    showConfirmButton: false,
                });

                location.reload();
            } catch (err) {
                errBox.textContent = 'Network error: ' + err.message;
                errBox.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        });
    }

    /* ── Cancel request ── */
    document.querySelectorAll('.cancel-request-btn').forEach(btn => {
        btn.addEventListener('click', async function(){
            const url = this.dataset.url;
            const result = await Swal.fire({
                title: 'Cancel request?',
                text: 'This will remove your pending request.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel',
            });
            if (!result.isConfirmed) return;
            fetch(url, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json'}
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({icon:'success', title:'Cancelled', timer:1000, showConfirmButton:false})
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed.', 'error');
                }
            });
        });
    });

    /* ── Delete expense (backwards compat) ── */
    document.querySelectorAll('.delete-expense-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const url = this.dataset.url;

            Swal.fire({
                title: 'Remove this expense?',
                text: 'This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove',
            }).then(result => {
                if (!result.isConfirmed) return;
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({icon:'success', title:'Removed', timer:1000, showConfirmButton:false})
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Failed.', 'error');
                    }
                });
            });
        });
    });
})();
</script>

@endsection
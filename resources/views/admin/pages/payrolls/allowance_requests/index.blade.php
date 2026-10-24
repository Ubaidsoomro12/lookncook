@extends('admin.layouts.master')

@section('title', 'Allowance Claims')

@section('content')
{{-- ══════════════════════════════════════════════════════════════
     VERSION: v3-live-filters (2026-10-11)
     ══════════════════════════════════════════════════════════════ --}}
<style>
    .arv-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        padding: 0;
        list-style: none;
    }
    .arv-tab {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 18px;
        border-radius: 11px;
        background: #fff;
        border: 1px solid #eef2f7;
        color: #6b7280;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all .2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,.03);
        user-select: none;
    }
    .arv-tab:hover {
        border-color: #ffd6e5;
        color: #ff2d7a;
        transform: translateY(-1px);
    }
    .arv-tab.active {
        background: linear-gradient(135deg, #ff2d7a, #ff4b91);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 8px 18px -8px rgba(255,45,122,.55);
    }
    .arv-tab .arv-count {
        background: rgba(0,0,0,.06);
        padding: 2px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        color: #4b5563;
    }
    .arv-tab.pending-tab  .arv-count { background: #fef3c7; color: #b45309; }
    .arv-tab.approved-tab .arv-count { background: #d1fae5; color: #047857; }
    .arv-tab.rejected-tab .arv-count { background: #fee2e2; color: #b91c1c; }
    .arv-tab.all-tab      .arv-count { background: #e0e7ff; color: #3730a3; }
    .arv-tab.active .arv-count { background: rgba(255,255,255,.28) !important; color: #fff !important; }

    .arv-empty-row td {
        text-align: center;
        padding: 50px 16px !important;
        color: #9ca3af;
    }
    .arv-empty-title {
        font-size: 15px;
        font-weight: 700;
        color: #6b7280;
        margin-top: 10px;
    }
    .arv-empty-sub {
        font-size: 12.5px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .arv-reset-btn {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        font-weight: 700;
        border-radius: 8px;
        height: 38px;
    }
    .arv-reset-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }
</style>

<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Allowance Claims</h4>
            <p class="text-muted small m-0">Review and process allowance requests from staff</p>
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label'=>'Pending', 'value'=>$stats['pending_count'], 'icon'=>'fa-hourglass-half', 'color'=>'#f59e0b'],
                ['label'=>'Pending Amount', 'value'=>'Rs '.number_format($stats['pending_amount'],2), 'icon'=>'fa-money-bill', 'color'=>'#ff2d7a'],
                ['label'=>'Approved Total', 'value'=>$stats['approved_count'], 'icon'=>'fa-check-circle', 'color'=>'#10b981'],
                ['label'=>'This Month', 'value'=>'Rs '.number_format($stats['approved_month'],2), 'icon'=>'fa-calendar-day', 'color'=>'#3b82f6'],
            ];
        @endphp
        @foreach($cards as $c)
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:12px;background:{{ $c['color'] }}1a;color:{{ $c['color'] }};display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid {{ $c['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ $c['label'] }}</div>
                        <div class="fw-bold fs-5">{{ $c['value'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABS (quick status shortcut — stays synced with the status dropdown below) --}}
    <div class="arv-tabs" id="arvTabs">
        <button type="button" class="arv-tab pending-tab active" data-tab="pending" onclick="arvSetTab('pending')">
            <i class="fa-solid fa-hourglass-half"></i>
            <span>Pending</span>
            <span class="arv-count">{{ $stats['pending_count'] }}</span>
        </button>
        <button type="button" class="arv-tab approved-tab" data-tab="approved" onclick="arvSetTab('approved')">
            <i class="fa-solid fa-circle-check"></i>
            <span>Approved</span>
            <span class="arv-count">{{ $stats['approved_count'] }}</span>
        </button>
        <button type="button" class="arv-tab rejected-tab" data-tab="rejected" onclick="arvSetTab('rejected')">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>Rejected</span>
            <span class="arv-count">{{ $stats['rejected_count'] }}</span>
        </button>
        <button type="button" class="arv-tab all-tab" data-tab="all" onclick="arvSetTab('all')">
            <i class="fa-solid fa-layer-group"></i>
            <span>All History</span>
            <span class="arv-count">{{ $stats['all_count'] }}</span>
        </button>
    </div>

    {{-- FILTERS — 100% live, no Filter button --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="arvSearch" class="form-control"
                           placeholder="Search staff name or ID..."
                           autocomplete="off"
                           oninput="arvApplyFilters()"
                           onkeyup="arvApplyFilters()"
                           onchange="arvApplyFilters()">
                </div>
                <div class="col-md-3">
                    <select id="arvStatusFilter" class="form-select" onchange="arvApplyFilters()">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="all">All Statuses</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="arvStaffFilter" class="form-select" onchange="arvApplyFilters()">
                        <option value="">All Staff</option>
                        @foreach($staffList as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->name }} ({{ $s->employee_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn arv-reset-btn w-100" onclick="arvResetFilters()">
                        <i class="fa-solid fa-rotate-left me-1"></i>Reset
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fa-solid fa-info-circle me-1"></i>
                    Filters apply instantly. <span id="arvFilterSummary"></span>
                </small>
            </div>
        </div>
    </div>

    {{-- LIST --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:60px;">#</th>
                            <th>Staff</th>
                            <th>Allowance</th>
                            <th>Company</th>
                            <th>Date</th>
                            <th class="text-end">Amount</th>
                            <th>Receipt</th>
                            <th>Status</th>
                            <th class="text-center" style="width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="arvTableBody">
                        @forelse($requests as $r)
                            @php
                                $rowSearch = strtolower(trim(
                                    ($r->staff?->name ?? '') . ' ' . ($r->staff?->employee_id ?? '')
                                ));
                            @endphp
                            <tr class="arv-row"
                                data-status="{{ $r->status }}"
                                data-staff-id="{{ $r->staff_id }}"
                                data-search="{{ $rowSearch }}">
                                <td class="ps-4">{{ $loop->iteration + ($requests->currentPage()-1) * $requests->perPage() }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $r->staff?->name ?? '—' }}</div>
                                    <div class="text-muted small">{{ $r->staff?->employee_id ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $r->allowanceEntry?->type?->name ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $r->allowanceEntry?->company?->name ?? 'Direct' }}</td>
                                <td>{{ $r->expense_date?->format('d M Y') }}</td>
                                <td class="text-end fw-bold" style="color:#ff2d7a;">
                                    Rs {{ number_format($r->amount, 2) }}
                                </td>
                                <td>
                                    @if($r->receipt_url)
                                        <a href="{{ $r->receipt_url }}" target="_blank">
                                            <img src="{{ $r->receipt_url }}" alt="receipt"
                                                 style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;">
                                        </a>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>{!! $r->status_badge !!}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary view-btn"
                                                data-id="{{ $r->id }}"
                                                data-staff="{{ $r->staff?->name ?? '' }}"
                                                data-type="{{ $r->allowanceEntry?->type?->name ?? '' }}"
                                                data-company="{{ $r->allowanceEntry?->company?->name ?? 'Direct' }}"
                                                data-amount="{{ number_format($r->amount, 2) }}"
                                                data-date="{{ $r->expense_date?->format('d M Y') }}"
                                                data-desc="{{ $r->description }}"
                                                data-admin-notes="{{ $r->admin_notes }}"
                                                data-receipt="{{ $r->receipt_url }}"
                                                data-status="{{ $r->status }}"
                                                title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        @if($r->status === 'pending')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success approve-btn"
                                                    data-url="{{ route('admin.allowance-requests.approve', $r->id) }}"
                                                    data-staff="{{ $r->staff?->name }}"
                                                    data-amount="{{ number_format($r->amount, 2) }}"
                                                    title="Approve">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger reject-btn"
                                                    data-url="{{ route('admin.allowance-requests.reject', $r->id) }}"
                                                    data-staff="{{ $r->staff?->name }}"
                                                    title="Reject">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        {{-- Empty state — JS shows this when zero rows match the current filters --}}
                        <tr id="arvNoRows" class="arv-empty-row" style="display:none;">
                            <td colspan="9">
                                <i class="fa-solid fa-inbox fa-3x d-block" style="color:#e5e7eb;"></i>
                                <div class="arv-empty-title" id="arvNoRowsTitle">No requests match your filters</div>
                                <div class="arv-empty-sub" id="arvNoRowsSub">Try switching tabs or clearing the search.</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($requests->total() > 0)
        <div class="d-flex flex-wrap align-items-center justify-content-between mt-4">
            <p class="text-muted small mb-0">
                Showing <strong>{{ $requests->firstItem() }}</strong> to
                <strong>{{ $requests->lastItem() }}</strong> of
                <strong>{{ $requests->total() }}</strong> entries
                <span class="text-muted">— page {{ $requests->currentPage() }} of {{ $requests->lastPage() }}</span>
            </p>
            <div>{{ $requests->appends(request()->query())->links() }}</div>
        </div>
    @endif
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#fff;border:none;padding:18px 22px;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-invoice me-2"></i>Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(2);"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-borderless mb-0 small">
                    <tr><th class="text-muted" style="width:40%;">Staff</th><td id="v_staff">—</td></tr>
                    <tr><th class="text-muted">Allowance</th><td id="v_type">—</td></tr>
                    <tr><th class="text-muted">Company</th><td id="v_company">—</td></tr>
                    <tr><th class="text-muted">Amount</th><td class="fw-bold" style="color:#ff2d7a;" id="v_amount">—</td></tr>
                    <tr><th class="text-muted">Date</th><td id="v_date">—</td></tr>
                    <tr><th class="text-muted">Description</th><td id="v_desc">—</td></tr>
                    <tr><th class="text-muted">Admin Notes</th><td id="v_notes">—</td></tr>
                    <tr><th class="text-muted">Status</th><td id="v_status">—</td></tr>
                </table>
                <div id="v_receipt_wrap" style="display:none;" class="mt-3 text-center">
                    <div class="small text-muted mb-2">Receipt Image</div>
                    <a id="v_receipt_link" href="#" target="_blank">
                        <img id="v_receipt" src="" alt="receipt"
                             style="max-width:100%;max-height:340px;border-radius:12px;border:1px solid #e5e7eb;">
                    </a>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #eef2f7;padding:14px 22px;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ══════════════════════════════════════════════════════════════
     MAIN SCRIPT — inline, so it ALWAYS runs regardless of layout
     ══════════════════════════════════════════════════════════════ --}}
<script>
/* ── GLOBAL FILTER FUNCTIONS (called by inline handlers) ── */
function arvApplyFilters() {
    var searchEl  = document.getElementById('arvSearch');
    var statusEl  = document.getElementById('arvStatusFilter');
    var staffEl   = document.getElementById('arvStaffFilter');

    var search  = (searchEl ? searchEl.value : '').toLowerCase().trim();
    var status  = (statusEl ? statusEl.value : 'all');
    var staffId = (staffEl ? staffEl.value : '');

    // Sync tab active state to the current status
    var tabs = document.querySelectorAll('.arv-tab');
    for (var i = 0; i < tabs.length; i++) {
        if (tabs[i].getAttribute('data-tab') === status) {
            tabs[i].classList.add('active');
        } else {
            tabs[i].classList.remove('active');
        }
    }

    var rows = document.querySelectorAll('#arvTableBody .arv-row');
    var visible = 0;

    for (var j = 0; j < rows.length; j++) {
        var row       = rows[j];
        var rowStatus = row.getAttribute('data-status') || '';
        var rowStaff  = row.getAttribute('data-staff-id') || '';
        var rowSearch = row.getAttribute('data-search') || '';

        var matchStatus = (status === 'all') || (rowStatus === status);
        var matchSearch = !search || rowSearch.indexOf(search) !== -1;
        var matchStaff  = !staffId || rowStaff === staffId;

        if (matchStatus && matchSearch && matchStaff) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    }

    // Empty state
    var noRows    = document.getElementById('arvNoRows');
    var noRowsTtl = document.getElementById('arvNoRowsTitle');
    var noRowsSub = document.getElementById('arvNoRowsSub');
    var summary   = document.getElementById('arvFilterSummary');

    if (noRows) {
        if (visible === 0 && rows.length > 0) {
            noRows.style.display = '';

            var parts = [];
            if (status !== 'all') {
                parts.push(status.charAt(0).toUpperCase() + status.slice(1));
            } else {
                parts.push('any status');
            }
            if (search)  parts.push('matching "' + search + '"');
            if (staffId) parts.push('for the selected staff');

            if (noRowsTtl) {
                noRowsTtl.textContent = 'No ' + parts.join(' ') + ' requests';
            }
            if (noRowsSub) {
                noRowsSub.textContent = 'Try switching tabs, clearing the search, or choosing a different staff.';
            }
        } else if (visible === 0 && rows.length === 0) {
            noRows.style.display = '';
            if (noRowsTtl) noRowsTtl.textContent = 'No allowance requests yet';
            if (noRowsSub) noRowsSub.textContent = 'Once staff submit claims, they will appear here.';
        } else {
            noRows.style.display = 'none';
        }
    }

    if (summary) {
        var labelParts = [];
        if (status !== 'all') labelParts.push(status);
        if (search)  labelParts.push('search "' + search + '"');
        if (staffId) labelParts.push('staff filter');

        summary.textContent = labelParts.length
            ? '(' + labelParts.join(' + ') + ' — ' + visible + ' shown)'
            : '(' + visible + ' shown)';
    }
}

function arvSetTab(tab) {
    var statusEl = document.getElementById('arvStatusFilter');
    if (statusEl) statusEl.value = tab;
    arvApplyFilters();
}

function arvResetFilters() {
    var searchEl = document.getElementById('arvSearch');
    var staffEl  = document.getElementById('arvStaffFilter');
    var statusEl = document.getElementById('arvStatusFilter');

    if (searchEl) searchEl.value = '';
    if (staffEl)  staffEl.value  = '';
    if (statusEl) statusEl.value = 'all';

    arvApplyFilters();
}

/* ── INIT on load ── */
document.addEventListener('DOMContentLoaded', function () {
    var statusEl = document.getElementById('arvStatusFilter');
    if (statusEl && !statusEl.value) statusEl.value = 'pending';
    arvApplyFilters();
});

// Fallback — script is at end of content, DOM is already ready
if (document.readyState !== 'loading') {
    var s = document.getElementById('arvStatusFilter');
    if (s && !s.value) s.value = 'pending';
    arvApplyFilters();
}

/* ══════════════════════════════════════════════════════════════
   VIEW / APPROVE / REJECT — document-level delegation
   ══════════════════════════════════════════════════════════════ */
document.addEventListener('click', function (e) {
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrf = csrfMeta ? csrfMeta.content : '';

    /* ── VIEW ── */
    var viewBtn = e.target.closest('.view-btn');
    if (viewBtn) {
        var d = viewBtn.dataset;
        document.getElementById('v_staff').textContent   = d.staff;
        document.getElementById('v_type').textContent    = d.type;
        document.getElementById('v_company').textContent = d.company;
        document.getElementById('v_amount').textContent  = 'Rs ' + d.amount;
        document.getElementById('v_date').textContent    = d.date;
        document.getElementById('v_desc').textContent    = d.desc || '—';
        document.getElementById('v_notes').textContent   = d.adminNotes || '—';
        document.getElementById('v_status').innerHTML    = d.status.charAt(0).toUpperCase() + d.status.slice(1);

        if (d.receipt) {
            document.getElementById('v_receipt').src = d.receipt;
            document.getElementById('v_receipt_link').href = d.receipt;
            document.getElementById('v_receipt_wrap').style.display = 'block';
        } else {
            document.getElementById('v_receipt_wrap').style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('viewModal')).show();
        return;
    }

    /* ── APPROVE ── */
    var approveBtn = e.target.closest('.approve-btn');
    if (approveBtn) {
        var url    = approveBtn.dataset.url;
        var staff  = approveBtn.dataset.staff;
        var amount = approveBtn.dataset.amount;

        Swal.fire({
            title: 'Approve Request?',
            html: 'Approve <strong>Rs ' + amount + '</strong> for <strong>' + staff + '</strong>?<br><small class="text-muted">This will be added to their spent amount.</small>',
            input: 'textarea',
            inputLabel: 'Admin Notes (optional)',
            inputPlaceholder: 'Add a note...',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, approve',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ admin_notes: result.value || null })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({icon:'success', title:'Approved!', text: data.message, timer: 1400, showConfirmButton: false})
                        .then(function () { location.reload(); });
                } else {
                    Swal.fire('Error', data.message || 'Failed.', 'error');
                }
            })
            .catch(function (err) { Swal.fire('Error', err.message, 'error'); });
        });
        return;
    }

    /* ── REJECT ── */
    var rejectBtn = e.target.closest('.reject-btn');
    if (rejectBtn) {
        var rUrl   = rejectBtn.dataset.url;
        var rStaff = rejectBtn.dataset.staff;

        Swal.fire({
            title: 'Reject Request?',
            html: 'Reject request from <strong>' + rStaff + '</strong>?',
            input: 'textarea',
            inputLabel: 'Reason for rejection *',
            inputPlaceholder: 'Explain why...',
            inputValidator: function (v) { return (!v || v.length < 3) ? 'Please enter a reason' : null; },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reject',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch(rUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ admin_notes: result.value })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({icon:'success', title:'Rejected', text: data.message, timer: 1400, showConfirmButton: false})
                        .then(function () { location.reload(); });
                } else {
                    Swal.fire('Error', data.message || 'Failed.', 'error');
                }
            })
            .catch(function (err) { Swal.fire('Error', err.message, 'error'); });
        });
        return;
    }
});
</script>
@endsection
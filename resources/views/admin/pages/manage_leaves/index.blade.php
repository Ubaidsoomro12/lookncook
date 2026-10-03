@extends('admin.layouts.master')

@section('content')

<style>
    /* ============================================================
       ✅ RESPONSIVE FIX for 320px screens
    ============================================================ */
    .leaves-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }
    .leaves-page-header h4 {
        margin: 0;
        flex: 1 1 auto;
        min-width: 0;
        word-break: break-word;
    }

    .leaves-toolbar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        width: 100%;
    }
    .leaves-toolbar .toolbar-date {
        width: 100%;
        max-width: 185px;
        flex: 0 1 auto;
        min-width: 0;
    }
    .leaves-toolbar .toolbar-search {
        width: 100%;
        max-width: 380px;
        flex: 1 1 220px;
        min-width: 0;
    }

    /* ============================================================
       ✅ CALENDAR FIX
       The native date picker popup is rendered by the OS and
       cannot be styled. If any ancestor has overflow:hidden,
       the popup gets clipped and looks like it "overflows".
       Force overflow visible on the whole ancestor chain.
    ============================================================ */

    /* The main content area in admin layout may clip — force visible */
    .main-content,
    .content-wrapper,
    .page-content,
    .container-fluid,
    .leaves-card,
    .leaves-card .card-header,
    .leaves-card .card-body,
    .leaves-toolbar,
    .leaves-toolbar .toolbar-date,
    .leaves-toolbar .toolbar-search {
        overflow: visible !important;
    }

    /* Kill transforms on the ancestor chain (they break fixed popups) */
    .main-content,
    .content-wrapper,
    .page-content,
    .container-fluid,
    .leaves-card,
    .leaves-card .card-header {
        transform: none !important;
    }

    /* Give the date input a stacking context so popup floats above tables */
    .leaves-toolbar .toolbar-date {
        position: relative;
        z-index: 1060;
    }
    .leaves-toolbar .toolbar-date input[type="date"] {
        position: relative;
        z-index: 1061;
    }

    /* Small screens: full width, stack */
    @media (max-width: 576px) {
        .leaves-page-header {
            flex-direction: column;
            align-items: stretch;
        }
        .leaves-page-header h4 {
            text-align: center;
        }
        .leaves-page-header .btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .leaves-toolbar .toolbar-date,
        .leaves-toolbar .toolbar-search {
            max-width: 100%;
            flex: 1 1 100%;
        }
        .card-header {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .input-group-text,
        .form-control,
        .btn {
            font-size: 13px;
        }
    }
</style>

<div class="container-fluid">

    {{-- ===== HEADER + ASSIGN BUTTON ===== --}}
    <div class="leaves-page-header">
        <h4><i class="fa fa-calendar-check"></i> Leave Requests</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignLeaveModal">
            <i class="fa fa-plus"></i> Assign Leave
        </button>
    </div>

    {{-- ===== ALERTS ===== --}}
    @if(session('success'))
        <div class="alert alert-success" id="successAlert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- ===== TABLE 1: LEAVES TABLE ===== --}}
    <div class="card leaves-card">
        <div class="card-header bg-white">
            <h5 class="mb-2"><i class="fa fa-list"></i> All Leave Requests</h5>

            <div class="leaves-toolbar">
                {{-- DATE PICKER --}}
                <div class="input-group input-group-sm toolbar-date">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="date"
                           id="leaveDateFilter"
                           class="form-control"
                           style="font-size: 13px;"
                           title="Filter by exact date">
                    <button class="btn btn-outline-secondary" type="button" id="clearLeaveDate" title="Clear date">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                {{-- SEARCH BAR --}}
                <div class="input-group input-group-sm toolbar-search">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text"
                           id="leaveSearch"
                           class="form-control"
                           placeholder="Search employee, ID, type, status...">
                    <button class="btn btn-outline-secondary" type="button" id="clearLeaveSearch">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle" id="leavesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Department</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th style="width: 190px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $key => $leave)
                        <tr data-from="{{ \Carbon\Carbon::parse($leave->from_date)->format('Y-m-d') }}"
                            data-to="{{ \Carbon\Carbon::parse($leave->to_date)->format('Y-m-d') }}">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $leave->staff_name ?? 'N/A' }}</td>
                            <td>{{ $leave->staff_employee_id ?? 'N/A' }}</td>
                            <td>{{ $leave->department ?? '-' }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d-m-Y') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($leave->reason, 40) }}</td>
                            <td>
                                @if($leave->status == 'Pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fa fa-clock"></i> Pending
                                    </span>
                                @elseif($leave->status == 'Approved')
                                    <span class="badge bg-success">
                                        <i class="fa fa-check"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fa fa-times"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($leave->status == 'Pending')
                                    <form action="{{ route('admin.leaves.approve', $leave->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          id="approve-form-{{ $leave->id }}">
                                        @csrf
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                onclick="confirmApprove({{ $leave->id }})">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.leaves.reject', $leave->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          id="reject-form-{{ $leave->id }}">
                                        @csrf
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmReject({{ $leave->id }})">
                                            <i class="fa fa-times"></i> Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">
                                        <i class="fa fa-lock"></i> Action taken
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-3">
                                <i class="fa fa-inbox fa-2x text-muted d-block mb-2"></i>
                                No leave requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="leaveNoResult" class="text-center py-3 d-none">
                <i class="fa fa-search fa-2x text-muted d-block mb-2"></i>
                <span class="text-muted">No matching leave requests found.</span>
            </div>
        </div>
    </div>

    {{-- ===== TABLE 2: LEAVE ASSIGNMENT SUMMARY ===== --}}
    <div class="card mt-4 leaves-card">
        <div class="card-header bg-white">
            <h5 class="mb-2">
                <i class="fa fa-users"></i>
                Leave Assignment Summary
                <small class="text-muted">({{ now()->format('F Y') }})</small>
            </h5>

            <div class="leaves-toolbar">
                {{-- DATE PICKER --}}
                <div class="input-group input-group-sm toolbar-date">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="date"
                           id="assignDateFilter"
                           class="form-control"
                           style="font-size: 13px;"
                           title="Filter by exact date">
                    <button class="btn btn-outline-secondary" type="button" id="clearAssignDate" title="Clear date">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                {{-- SEARCH BAR --}}
                <div class="input-group input-group-sm toolbar-search">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text"
                           id="assignmentSearch"
                           class="form-control"
                           placeholder="Search employee, ID, email...">
                    <button class="btn btn-outline-secondary" type="button" id="clearAssignmentSearch">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle" id="assignmentsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Email</th>
                        <th class="text-center">From Date</th>
                        <th class="text-center">To Date</th>
                        <th class="text-center">Total Assigned</th>
                        <th class="text-center">Approved</th>
                        <th class="text-center">Rejected</th>
                        <th class="text-center">Pending</th>
                        <th class="text-center">Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $key => $row)
                        <tr data-from="{{ $row['from_date'] ? \Carbon\Carbon::parse($row['from_date'])->format('Y-m-d') : '' }}"
                            data-to="{{ $row['to_date'] ? \Carbon\Carbon::parse($row['to_date'])->format('Y-m-d') : '' }}">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $row['user_name'] }}</td>
                            <td>{{ $row['employee_id'] }}</td>
                            <td>{{ $row['user_email'] }}</td>
                            <td class="text-center">
                                @if($row['from_date'])
                                    <span class="badge bg-light text-dark border">
                                        {{ \Carbon\Carbon::parse($row['from_date'])->format('d-m-Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row['to_date'])
                                    <span class="badge bg-light text-dark border">
                                        {{ \Carbon\Carbon::parse($row['to_date'])->format('d-m-Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $row['total_assigned'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $row['approved'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $row['rejected'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $row['pending'] }}</span>
                            </td>
                            <td class="text-center">
                                @if($row['remaining'] > 0)
                                    <span class="badge bg-secondary">{{ $row['remaining'] }}</span>
                                @else
                                    <span class="badge bg-dark">0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-3">
                                <i class="fa fa-inbox fa-2x text-muted d-block mb-2"></i>
                                No leave assignments found for this month.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="assignmentNoResult" class="text-center py-3 d-none">
                <i class="fa fa-search fa-2x text-muted d-block mb-2"></i>
                <span class="text-muted">No matching assignments found.</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== ASSIGN LEAVE MODAL ===== --}}
<div class="modal fade" id="assignLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.leaves.assign') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-user-plus"></i> Assign Leave
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Select User <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number"
                               name="total_leaves"
                               class="form-control"
                               min="1"
                               value="1"
                               required>
                        <small class="text-muted">How many leaves to assign this month</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== SCRIPTS ===== --}}
<script>
    function confirmApprove(id) {
        Swal.fire({
            title: 'Approve this leave?',
            text: 'The leave request will be marked as approved.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes, approve!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: { popup: 'sweet-popup-custom' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-form-' + id).submit();
            }
        });
    }

    function confirmReject(id) {
        Swal.fire({
            title: 'Reject this leave?',
            text: 'The leave request will be marked as rejected.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes, reject!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: { popup: 'sweet-popup-custom' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reject-form-' + id).submit();
            }
        });
    }

    function filterTable(tableSelector, searchValue, dateValue, noResultSelector) {
        const tbody = document.querySelector(tableSelector + ' tbody');
        const noResult = document.querySelector(noResultSelector);
        const rows = tbody.querySelectorAll('tr');
        const query = (searchValue || '').toLowerCase().trim();
        const filterDate = dateValue || '';

        let visibleCount = 0;

        rows.forEach(function (row) {
            let matchesSearch = true;
            let matchesDate = true;

            if (query !== '') {
                const text = row.innerText.toLowerCase();
                matchesSearch = text.includes(query);
            }

            if (filterDate !== '') {
                const fromDate = row.getAttribute('data-from') || '';
                const toDate = row.getAttribute('data-to') || '';
                matchesDate = (fromDate === filterDate || toDate === filterDate);
            }

            if (matchesSearch && matchesDate) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleCount === 0 && (query !== '' || filterDate !== '')) {
            noResult.classList.remove('d-none');
        } else {
            noResult.classList.add('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const leaveSearchInput = document.getElementById('leaveSearch');
        const leaveDateFilter  = document.getElementById('leaveDateFilter');
        const clearLeaveBtn    = document.getElementById('clearLeaveSearch');
        const clearLeaveDate   = document.getElementById('clearLeaveDate');

        function applyLeaveFilter() {
            filterTable('#leavesTable', leaveSearchInput.value, leaveDateFilter.value, '#leaveNoResult');
        }

        if (leaveSearchInput) {
            leaveSearchInput.addEventListener('keyup', applyLeaveFilter);
            leaveDateFilter.addEventListener('change', applyLeaveFilter);

            clearLeaveBtn.addEventListener('click', function () {
                leaveSearchInput.value = '';
                applyLeaveFilter();
            });

            clearLeaveDate.addEventListener('click', function () {
                leaveDateFilter.value = '';
                applyLeaveFilter();
            });
        }

        const assignSearchInput = document.getElementById('assignmentSearch');
        const assignDateFilter  = document.getElementById('assignDateFilter');
        const clearAssignBtn    = document.getElementById('clearAssignmentSearch');
        const clearAssignDate   = document.getElementById('clearAssignDate');

        function applyAssignFilter() {
            filterTable('#assignmentsTable', assignSearchInput.value, assignDateFilter.value, '#assignmentNoResult');
        }

        if (assignSearchInput) {
            assignSearchInput.addEventListener('keyup', applyAssignFilter);
            assignDateFilter.addEventListener('change', applyAssignFilter);

            clearAssignBtn.addEventListener('click', function () {
                assignSearchInput.value = '';
                applyAssignFilter();
            });

            clearAssignDate.addEventListener('click', function () {
                assignDateFilter.value = '';
                applyAssignFilter();
            });
        }

        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(function () {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(function () { successAlert.remove(); }, 500);
            }, 3000);
        }
    });
</script>

<style>
    .sweet-popup-custom {
        border-radius: 18px !important;
        padding: 28px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2) !important;
        font-family: system-ui, -apple-system, sans-serif !important;
        max-width: 420px !important;
    }
    .sweet-popup-custom .swal2-title {
        font-size: 22px !important; font-weight: 700 !important;
        color: #1f2937 !important; margin-top: 10px !important; margin-bottom: 6px !important;
    }
    .sweet-popup-custom .swal2-html-container {
        font-size: 15px !important; color: #6b7280 !important; margin-top: 4px !important;
    }
    .sweet-popup-custom .swal2-icon {
        width: 72px !important; height: 72px !important;
        margin: 0 auto 12px !important; border-width: 3px !important;
    }
    .sweet-popup-custom .swal2-actions {
        gap: 12px !important; margin-top: 22px !important;
        width: 100% !important; justify-content: center !important;
    }
    .sweet-popup-custom .swal2-confirm,
    .sweet-popup-custom .swal2-cancel {
        border-radius: 10px !important; padding: 11px 24px !important;
        font-weight: 600 !important; font-size: 14px !important;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1) !important;
        transition: transform 0.15s ease !important;
    }
    .sweet-popup-custom .swal2-confirm:hover,
    .sweet-popup-custom .swal2-cancel:hover {
        transform: translateY(-1px) !important;
    }
</style>
@endsection
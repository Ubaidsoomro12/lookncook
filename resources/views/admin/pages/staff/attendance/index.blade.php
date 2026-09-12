@extends('admin.layouts.master')
@section('title', 'Staff Attendance')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2">Staff Attendance</h1>
            <p class="text-muted small mb-0">View and manage attendance for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
        </div>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Staff
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Total Staff</h6>
                    <h3 class="fw-bold mb-0">{{ $totalStaff }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Present</h6>
                    <h3 class="fw-bold text-success mb-0">{{ $presentCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Late</h6>
                    <h3 class="fw-bold text-warning mb-0">{{ $lateCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Absent</h6>
                    <h3 class="fw-bold text-danger mb-0">{{ $absentCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.staff.attendance') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control" value="{{ $date }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, ID, email..." value="{{ $search }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.staff.attendance') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Employee ID</th>
                        <th>Role</th>
                        <th>Shift</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Status</th>
                        <th>Late</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $index => $s)
                        @php
                            $attendance = $s->attendances->first();
                            $status = $attendance ? $attendance->status : 'absent';
                            $clockIn = $attendance && $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') : '—';
                            $clockOut = $attendance && $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') : '—';
                            $lateMin = $attendance ? $attendance->late_minutes : 0;
                            $isReopened = $attendance ? $attendance->is_reopened : false;

                            $roleNames = [1=>'Admin',2=>'User',3=>'Manager',4=>'Waiter',5=>'Chef',6=>'Cashier',7=>'Cleaner',8=>'Delivery Rider'];
                            $roleName = $roleNames[$s->user->role_id ?? 0] ?? '—';
                        @endphp
                        <tr>
                            <td>{{ $staff->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($s->image)
                                        <img src="{{ asset($s->image) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $s->name }}</div>
                                        <small class="text-muted">{{ $s->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $s->employee_id ?? '—' }}</td>
                            <td>{{ $roleName }}</td>
                            <td>
                                @if($s->shift_start_time && $s->shift_end_time)
                                    <small>{{ \Carbon\Carbon::parse($s->shift_start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($s->shift_end_time)->format('h:i A') }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $clockIn }}</td>
                            <td>{{ $clockOut }}</td>
                            <td>
                                @if($status == 'present')
                                    <span class="badge bg-success">Present</span>
                                @elseif($status == 'late')
                                    <span class="badge bg-warning text-dark">Late</span>
                                @else
                                    <span class="badge bg-secondary">Absent</span>
                                @endif
                                @if($isReopened)
                                    <span class="badge bg-info text-dark ms-1">Reopened</span>
                                @endif
                            </td>
                            <td>
                                @if($lateMin > 0)
                                    <span class="badge bg-warning text-dark">{{ $lateMin }} min late</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.staff.attendance.detail', $s->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                @if($attendance && $attendance->clock_in && !$isReopened)
                                    <button type="button" class="btn btn-sm btn-outline-warning approve-btn"
                                            data-id="{{ $attendance->id }}"
                                            data-name="{{ $s->name }}"
                                            data-time="{{ $clockIn }}"
                                            title="Approve Re-Clock-In">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No staff found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $staff->appends(['date' => $date, 'search' => $search])->links() }}
        </div>
    </div>
</div>

<!-- ============================================================
     CUSTOM MODAL (BLUE + WHITE THEME)
     ============================================================ -->
<style>
    .custom-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        animation: fadeInBackdrop 0.25s ease;
    }
    .custom-modal-backdrop.active {
        display: flex;
    }
    @keyframes fadeInBackdrop {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .custom-modal-box {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 25px 60px rgba(59, 130, 246, 0.25);
        width: 100%;
        max-width: 460px;
        overflow: hidden;
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .custom-modal-backdrop.active .custom-modal-box {
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    /* BLUE HEADER */
    .custom-modal-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        padding: 30px 24px 24px;
        text-align: center;
        color: #fff;
        position: relative;
    }
    .custom-modal-header .modal-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: 0 0 0 8px rgba(255,255,255,0.1);
        animation: pulse-icon 2s ease-in-out infinite;
    }
    @keyframes pulse-icon {
        0%, 100% { box-shadow: 0 0 0 8px rgba(255,255,255,0.1); }
        50% { box-shadow: 0 0 0 12px rgba(255,255,255,0.15); }
    }
    .custom-modal-header .modal-icon i {
        font-size: 36px;
        color: #fff;
    }
    .custom-modal-header h4 {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 22px;
    }
    .custom-modal-header p {
        font-size: 13px;
        opacity: 0.9;
        margin: 0;
    }

    .custom-modal-body {
        padding: 24px;
        text-align: center;
    }

    /* Light-blue info card */
    .info-card {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .info-card .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2563eb;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .info-card .value {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }

    .custom-modal-footer {
        padding: 0 24px 24px;
        display: flex;
        gap: 12px;
    }
    .custom-modal-footer button {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-modal-cancel {
        background: #f3f4f6;
        color: #374151;
    }
    .btn-modal-cancel:hover {
        background: #e5e7eb;
    }
    .btn-modal-confirm {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }
    .btn-modal-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
    .btn-modal-confirm:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Success modal (green) */
    .custom-modal-header.success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }
    .custom-modal-header.success .modal-icon {
        animation: none;
    }
    /* Error modal (red) */
    .custom-modal-header.error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .custom-modal-header.error .modal-icon {
        animation: none;
    }
</style>

<!-- ============================================================
     APPROVE MODAL
     ============================================================ -->
<div id="approveModal" class="custom-modal-backdrop">
    <div class="custom-modal-box">
        <div class="custom-modal-header">
            <div class="modal-icon">
                <i class="fa-solid fa-rotate-right"></i>
            </div>
            <h4>Approve Re-Clock-In?</h4>
            <p>This will let the staff clock in again today</p>
        </div>
        <div class="custom-modal-body">
            <div class="info-card">
                <div class="label">Employee</div>
                <div class="value" id="modalStaffName">—</div>
                <div class="text-muted small mt-2">
                    <i class="fa-regular fa-clock me-1"></i>
                    Previous clock-in: <strong id="modalClockInTime">—</strong>
                </div>
            </div>
            <p class="text-muted small mb-0">
                <i class="fa-solid fa-circle-info text-primary me-1"></i>
                The staff's previous clock-in/out for today will be <strong>reset</strong>.
                They can clock in again from scratch.
            </p>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-modal-cancel" id="modalCancelBtn">
                <i class="fa-solid fa-xmark me-1"></i> Cancel
            </button>
            <button type="button" class="btn-modal-confirm" id="modalConfirmBtn">
                <span id="confirmBtnText"><i class="fa-solid fa-check me-1"></i> Yes, Approve</span>
                <span id="confirmBtnSpinner" class="d-none">
                    <i class="fa-solid fa-circle-notch fa-spin me-1"></i> Approving...
                </span>
            </button>
        </div>
    </div>
</div>

<!-- ============================================================
     SUCCESS MODAL
     ============================================================ -->
<div id="successModal" class="custom-modal-backdrop">
    <div class="custom-modal-box">
        <div class="custom-modal-header success">
            <div class="modal-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <h4>Approved Successfully!</h4>
            <p id="successMessage">Staff can now clock in again.</p>
        </div>
        <div class="custom-modal-body pb-4">
            <p class="text-muted small mb-0">
                <i class="fa-solid fa-circle-notch fa-spin me-1"></i>
                Reloading page...
            </p>
        </div>
    </div>
</div>

<!-- ============================================================
     ERROR MODAL
     ============================================================ -->
<div id="errorModal" class="custom-modal-backdrop">
    <div class="custom-modal-box">
        <div class="custom-modal-header error">
            <div class="modal-icon">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <h4>Something went wrong</h4>
            <p id="errorMessage">Please try again.</p>
        </div>
        <div class="custom-modal-body pb-4">
            <button type="button" class="btn-modal-cancel" style="width:100%;padding:12px;border-radius:10px;font-weight:600;background:#f3f4f6;color:#374151;border:none;cursor:pointer;" onclick="document.getElementById('errorModal').classList.remove('active')">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveModal = document.getElementById('approveModal');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');

    const staffNameEl = document.getElementById('modalStaffName');
    const clockInTimeEl = document.getElementById('modalClockInTime');
    const cancelBtn = document.getElementById('modalCancelBtn');
    const confirmBtn = document.getElementById('modalConfirmBtn');
    const confirmBtnText = document.getElementById('confirmBtnText');
    const confirmBtnSpinner = document.getElementById('confirmBtnSpinner');

    let pendingAttendanceId = null;

    // ==============================
    // Open modal on approve button click
    // ==============================
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            pendingAttendanceId = this.dataset.id;
            staffNameEl.textContent = this.dataset.name || 'Unknown';
            clockInTimeEl.textContent = this.dataset.time || '—';

            // Reset button state
            confirmBtn.disabled = false;
            confirmBtnText.classList.remove('d-none');
            confirmBtnSpinner.classList.add('d-none');

            approveModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    // ==============================
    // Close modal on cancel
    // ==============================
    function closeApproveModal() {
        approveModal.classList.remove('active');
        document.body.style.overflow = '';
        pendingAttendanceId = null;
    }

    cancelBtn.addEventListener('click', closeApproveModal);

    // Close on backdrop click
    approveModal.addEventListener('click', function(e) {
        if (e.target === approveModal) closeApproveModal();
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && approveModal.classList.contains('active')) {
            closeApproveModal();
        }
    });

    // ==============================
    // Handle confirm approval
    // ==============================
    confirmBtn.addEventListener('click', function() {
        if (!pendingAttendanceId) return;

        // Show loading state
        confirmBtn.disabled = true;
        confirmBtnText.classList.add('d-none');
        confirmBtnSpinner.classList.remove('d-none');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                       || '{{ csrf_token() }}';

        fetch(`{{ url('admin/staff/attendance') }}/${pendingAttendanceId}/approve-reclock`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(d => { throw new Error(d.message || 'Server error'); });
            }
            return res.json();
        })
        .then(data => {
            closeApproveModal();

            if (data.success) {
                document.getElementById('successMessage').textContent = data.message || 'Staff can now clock in again.';
                successModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    location.reload();
                }, 1800);
            } else {
                document.getElementById('errorMessage').textContent = data.message || 'Failed to approve.';
                errorModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        })
        .catch(err => {
            closeApproveModal();
            document.getElementById('errorMessage').textContent = err.message || 'Something went wrong. Please try again.';
            errorModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close error modal on backdrop click
    errorModal.addEventListener('click', function(e) {
        if (e.target === errorModal) {
            errorModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});
</script>
@endsection
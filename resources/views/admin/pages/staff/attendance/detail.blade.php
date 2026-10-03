@extends('admin.layouts.master')
@section('title', 'Attendance Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.staff.attendance') }}" class="btn btn-light rounded-circle" style="width:40px;height:40px;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="h2 mb-0">{{ $staff->name }}</h1>
                <p class="text-muted small mb-0">{{ $staff->employee_id ?? 'No Employee ID' }} • {{ $staff->email }}</p>
            </div>
        </div>
        <a href="{{ route('admin.staff.show', $staff->id) }}" class="btn btn-outline-primary rounded-pill">
            <i class="fa-regular fa-user me-1"></i> View Staff Profile
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Present</h6>
                    <h3 class="fw-bold text-success mb-0">{{ $presentDays }}</h3>
                    <small class="text-muted">out of {{ $workingDays }} days</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Late</h6>
                    <h3 class="fw-bold text-warning mb-0">{{ $lateDays }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Absent</h6>
                    <h3 class="fw-bold text-danger mb-0">{{ $absentDays }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Rate</h6>
                    <h3 class="fw-bold text-primary mb-0">
                        {{ $workingDays > 0 ? round(($presentDays / $workingDays) * 100) : 0 }}%
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Navigation -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.staff.attendance.detail', [$staff->id, 'month' => $prevMonth, 'year' => $prevYear]) }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-chevron-left"></i> Previous
            </a>
            <h5 class="mb-0">{{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</h5>
            <a href="{{ route('admin.staff.attendance.detail', [$staff->id, 'month' => $nextMonth, 'year' => $nextYear]) }}" class="btn btn-outline-secondary">
                Next <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Attendance Records</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Status</th>
                        <th>Late (min)</th>
                        <th>Early (min)</th>
                        <th>Reopened</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($att->date)->format('l') }}</td>
                            <td>{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('h:i A') : '—' }}</td>
                            <td>{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('h:i A') : '—' }}</td>
                            <td>
                                @if($att->status == 'present')
                                    <span class="badge bg-success">Present</span>
                                @elseif($att->status == 'late')
                                    <span class="badge bg-warning text-dark">Late</span>
                                @else
                                    <span class="badge bg-secondary">Absent</span>
                                @endif
                            </td>
                            <td>
                                @if($att->late_minutes > 0)
                                    <span class="badge bg-warning text-dark">{{ $att->late_minutes }} min late</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($att->early_minutes > 0)
                                    <span class="badge bg-info text-dark">{{ $att->early_minutes }} min</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($att->is_reopened)
                                    <span class="badge bg-info text-dark">Yes</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-end">
                                @if($att->clock_in && !$att->is_reopened)
                                    <button type="button" class="btn btn-sm btn-outline-warning approve-btn"
                                            data-id="{{ $att->id }}"
                                            data-name="{{ $staff->name }} ({{ \Carbon\Carbon::parse($att->date)->format('d M Y') }})">
                                        <i class="fa-solid fa-rotate-right me-1"></i> Approve Re-Clock
                                    </button>
                                @elseif($att->is_reopened)
                                    <small class="text-info">Approved at {{ $att->reopened_at ? $att->reopened_at->format('h:i A') : '—' }}</small>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No records for this month.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $attendances->appends(['month' => $month, 'year' => $year])->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            if (!confirm(`Approve "${name}" to clock in again?`)) return;

            fetch(`{{ url('admin/staff/attendance') }}/${id}/approve-reclock`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => alert('Something went wrong.'));
        });
    });
});
</script>
@endsection
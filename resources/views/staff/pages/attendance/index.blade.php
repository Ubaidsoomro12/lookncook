@extends('staff.layouts.master')

@section('title', 'My Attendance')
@section('page-title', 'Attendance History')

@section('content')
<style>
    /* ============================================================
       ✅ FIX: Month navigation — Previous / Month / Next buttons
       - Desktop: teeno ek row me properly aligned
       - Mobile (≤480px): title upar, buttons neeche 50%/50%
    ============================================================ */
    .month-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: nowrap;
    }
    .month-nav .month-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        flex-shrink: 0;
        padding: 8px 14px;
        font-size: 14px;
        line-height: 1.2;
        border-radius: 8px;
    }
    .month-nav .month-title {
        flex: 1 1 auto;
        text-align: center;
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Small screens: stack title + 2 buttons */
    @media (max-width: 480px) {
        .month-nav {
            flex-wrap: wrap;
            gap: 10px;
        }
        .month-nav .month-btn {
            flex: 1 1 calc(50% - 5px);
            justify-content: center;
            padding: 10px 8px;
            font-size: 13px;
        }
        .month-nav .month-title {
            order: -1;
            flex: 1 1 100%;
            padding: 4px 0;
            font-size: 1.15rem;
        }
    }

    /* ✅ FIX: Summary cards 2 per row on small screens */
    @media (max-width: 767px) {
        .summary-col {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>

<div class="row g-4">
    <!-- Summary Cards -->
    <div class="col-md-3 summary-col">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted"><i class="fa-regular fa-calendar-check me-2"></i> Present</h6>
                <h2 class="fw-bold text-success">{{ $presentDays }}</h2>
                <small class="text-muted">out of {{ $workingDays }} working days</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 summary-col">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted"><i class="fa-regular fa-clock me-2"></i> Late</h6>
                <h2 class="fw-bold text-warning">{{ $lateDays }}</h2>
                <small class="text-muted">arrived after grace period</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 summary-col">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted"><i class="fa-regular fa-circle-xmark me-2"></i> Absent</h6>
                <h2 class="fw-bold text-danger">{{ $absentDays }}</h2>
                <small class="text-muted">no clock‑in recorded</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 summary-col">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted"><i class="fa-regular fa-percent me-2"></i> Attendance Rate</h6>
                <h2 class="fw-bold text-primary">
                    @php
                        $rate = $workingDays > 0 ? round(($presentDays / $workingDays) * 100) : 0;
                    @endphp
                    {{ $rate }}%
                </h2>
                <small class="text-muted">{{ $presentDays }} / {{ $workingDays }} days</small>
            </div>
        </div>
    </div>
</div>

<!-- ✅ FIXED Month Navigation -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="month-nav">
                    <a href="{{ route('staff.attendance', ['month' => $prevMonth, 'year' => $prevYear]) }}"
                       class="btn btn-outline-secondary month-btn">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span>Previous</span>
                    </a>
                    <h5 class="month-title">
                        {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
                    </h5>
                    <a href="{{ route('staff.attendance', ['month' => $nextMonth, 'year' => $nextYear]) }}"
                       class="btn btn-outline-secondary month-btn">
                        <span>Next</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Detailed Records</h5>
            </div>
            <div class="card-body">
                @if($attendances->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Status</th>
                                    <th>Late (min)</th>
                                    <th>Early (min)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $att)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($att->date)->format('l') }}</td>
                                        <td>{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('h:i A') : '—' }}</td>
                                        <td>{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('h:i A') : '—' }}</td>
                                        <td>
                                            @if($att->status == 'present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($att->status == 'late')
                                                <span class="badge bg-warning">Late</span>
                                            @else
                                                <span class="badge bg-secondary">Absent</span>
                                            @endif
                                        </td>
                                        <td>{{ $att->late_minutes ?? 0 }}</td>
                                        <td>{{ $att->early_minutes ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $attendances->appends(['month' => $month, 'year' => $year])->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <p class="text-muted text-center py-3">No attendance records for this month.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
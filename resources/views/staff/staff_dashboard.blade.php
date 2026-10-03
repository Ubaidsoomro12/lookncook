@extends('staff.layouts.master')

@section('title', 'Staff Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-regular fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-regular fa-circle-xmark me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $staff = auth()->user()->staff;
        if (!$staff) {
            $staff = \App\Models\Staff::where('user_id', auth()->id())->first();
        }

        $hasStaff = $staff !== null;
        if ($hasStaff) {
            $todayAttendance = $staff->todayAttendance;
            $status = $todayAttendance ? $todayAttendance->status : 'absent';
            $clockIn = $todayAttendance ? $todayAttendance->clock_in : null;
            $clockOut = $todayAttendance ? $todayAttendance->clock_out : null;
            $lateMinutes = $todayAttendance ? $todayAttendance->late_minutes : 0;
            $earlyMinutes = $todayAttendance ? $todayAttendance->early_minutes : 0;
            $isReopened = $todayAttendance ? $todayAttendance->is_reopened : false;

            if ($isReopened) {
                $showClockInButton = true;
                $showClockOutButton = false;
                $showCompleted = false;
            } else {
                $showClockInButton = !$clockIn && !$clockOut;
                $showClockOutButton = $clockIn && !$clockOut;
                $showCompleted = $clockIn && $clockOut;
            }

            // ===== Build exact millisecond timestamp for the clock-in =====
            $clockInTimestamp = null;
            if ($clockIn) {
                $clockInCarbon = \Carbon\Carbon::parse($clockIn);
                $clockInFull = \Carbon\Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    now()->format('Y-m-d') . ' ' . $clockInCarbon->format('H:i:s'),
                    'Asia/Karachi'
                );
                $clockInTimestamp = $clockInFull->getTimestampMs();
            }

            // Total worked seconds if clocked out
            $totalWorkedSeconds = 0;
            if ($clockIn && $clockOut) {
                $totalWorkedSeconds = \Carbon\Carbon::parse($clockIn)->diffInSeconds(\Carbon\Carbon::parse($clockOut));
            }
        }
    @endphp

    @if(!$hasStaff)
        <div class="alert alert-warning" role="alert">
            <i class="fa-regular fa-triangle-exclamation me-2"></i>
            Your account is not linked to a staff record. Please contact the administrator.
        </div>
    @else

        {{-- ====== LIVE CLOCK BAR ====== --}}
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1e1e2d, #2d2d3f);">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(255,45,122,0.15);display:flex;align-items:center;justify-content:center;">
                        <i class="fa-regular fa-clock" style="color:#ff2d7a;font-size:18px;"></i>
                    </div>
                    <div>
                        <div
                            style="color:#9ca3af;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">
                            Current Time (PKT)</div>
                        <div id="liveClock" style="color:#fff;font-size:22px;font-weight:700;font-family:monospace;">--:--:-- --
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <div style="color:#9ca3af;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">
                        Today</div>
                    <div id="liveDate" style="color:#fff;font-size:14px;font-weight:600;">--</div>
                </div>
            </div>
        </div>

        {{-- ====== RE-OPENED INFO BANNER ====== --}}
        @if($isReopened)
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fa-solid fa-rotate-right me-2"></i>
                <div>
                    <strong>Admin has approved you to clock in again today.</strong>
                    <br><small>Your previous clock-in/out has been reset. Please clock in again.</small>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Attendance Status -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted"><i class="fa-solid fa-user-clock me-2"></i> Attendance Status</h6>
                        @if($isReopened)
                            <h2 class="fw-bold text-info">Re-Opened</h2>
                        @elseif($status == 'present')
                            <h2 class="fw-bold text-success">Present</h2>
                        @elseif($status == 'late')
                            <h2 class="fw-bold text-warning">Late</h2>
                        @else
                            <h2 class="fw-bold text-secondary">Absent</h2>
                        @endif
                        <p class="text-muted small mb-2">
                            @if(!$isReopened)
                                @if($clockIn)
                                    <i class="fa-solid fa-right-to-bracket text-success me-1"></i>
                                    In: <strong>{{ \Carbon\Carbon::parse($clockIn)->format('h:i:s A') }}</strong>
                                @else
                                    Not clocked in yet.
                                @endif
                                @if($clockOut)
                                    <br><i class="fa-solid fa-right-from-bracket text-danger me-1"></i>
                                    Out: <strong>{{ \Carbon\Carbon::parse($clockOut)->format('h:i:s A') }}</strong>
                                @endif
                            @else
                                Waiting for you to clock in again.
                            @endif
                        </p>

                        @if(!$isReopened && $status === 'late' && $lateMinutes > 0)
                            <div class="alert alert-warning py-2 px-3 mb-2 small">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                You are <strong>{{ $lateMinutes }} minute(s) late</strong>
                            </div>
                        @endif

                        @if(!$isReopened && $earlyMinutes > 0)
                            <div class="alert alert-info py-2 px-3 mb-0 small">
                                <i class="fa-solid fa-person-running me-1"></i>
                                You left <strong>{{ $earlyMinutes }} minute(s) early</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Shift Info / Working Timer -->
            <div class="col-md-4">
                @if($showClockOutButton)
                    {{-- LIVE WORKING TIMER --}}
                    <div class="card border-0 shadow-sm h-100"
                        style="background: linear-gradient(135deg, #22c55e, #16a34a); color:#fff;">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h6 class="text-white-50 mb-2" style="font-size:12px;text-transform:uppercase;letter-spacing:1px;">
                                <i class="fa-solid fa-stopwatch me-1"></i> Working Time
                            </h6>
                            <h1 class="fw-bold mb-2" id="workingTimer"
                                style="font-family:monospace;font-size:2.5rem;text-shadow:0 2px 4px rgba(0,0,0,0.2);">00:00:00</h1>
                            <div class="text-white-50 small">
                                Started at <strong
                                    style="color:#fff;">{{ \Carbon\Carbon::parse($clockIn)->format('h:i:s A') }}</strong>
                            </div>
                            <div class="mt-2">
                                <span class="badge" style="background:rgba(255,255,255,0.2);font-size:11px;">
                                    <i class="fa-solid fa-circle" style="font-size:6px;vertical-align:middle;color:#fff;"></i> Live
                                </span>
                            </div>
                        </div>
                    </div>
                @elseif($showCompleted)
                    {{-- TOTAL WORKED TIME (frozen) --}}
                    @php
                        $h = floor($totalWorkedSeconds / 3600);
                        $m = floor(($totalWorkedSeconds % 3600) / 60);
                        $s = $totalWorkedSeconds % 60;
                    @endphp
                    <div class="card border-0 shadow-sm h-100"
                        style="background: linear-gradient(135deg, #3b82f6, #2563eb); color:#fff;">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h6 class="text-white-50 mb-2" style="font-size:12px;text-transform:uppercase;letter-spacing:1px;">
                                <i class="fa-solid fa-circle-check me-1"></i> Total Worked
                            </h6>
                            <h1 class="fw-bold mb-2"
                                style="font-family:monospace;font-size:2.5rem;text-shadow:0 2px 4px rgba(0,0,0,0.2);">
                                {{ sprintf('%02d:%02d:%02d', $h, $m, $s) }}
                            </h1>
                            <div class="text-white-50 small">
                                {{ \Carbon\Carbon::parse($clockIn)->format('h:i:s A') }} –
                                {{ \Carbon\Carbon::parse($clockOut)->format('h:i:s A') }}
                            </div>
                        </div>
                    </div>
                @else
                    {{-- SHIFT INFO --}}
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-title text-muted"><i class="fa-regular fa-calendar me-2"></i> Today's Shift</h6>
                            @if($staff->shift_start_time && $staff->shift_end_time)
                                <h4 class="fw-bold mb-1">{{ \Carbon\Carbon::parse($staff->shift_start_time)->format('h:i A') }}</h4>
                                <h4 class="fw-bold text-muted mb-2">to
                                    {{ \Carbon\Carbon::parse($staff->shift_end_time)->format('h:i A') }}</h4>
                            @else
                                <p class="text-muted">No shift assigned.</p>
                            @endif
                            <p class="text-muted small mb-0">{{ now()->format('l, d M Y') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted"><i class="fa-regular fa-hand-pointer me-2"></i> Quick Actions</h6>

                        @if($showClockInButton)
                            <form action="{{ route('staff.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                    <i class="fa-regular fa-circle-check me-2"></i> Clock In
                                </button>
                            </form>
                        @elseif($showClockOutButton)
                            <form action="{{ route('staff.clock-out') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold">
                                    <i class="fa-regular fa-circle-stop me-2"></i> Clock Out
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100 py-2 fw-semibold" disabled>
                                <i class="fa-regular fa-clock me-2"></i> Shift Completed
                            </button>
                        @endif

                        <a href="{{ route('staff.attendance') }}" class="btn btn-outline-primary w-100 mt-2">
                            <i class="fa-regular fa-calendar me-2"></i> View Attendance History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent History -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Your Attendance History (Last 7 Days)</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $attendances = $staff->attendances()->orderBy('date', 'desc')->limit(7)->get();
                        @endphp
                        @if($attendances->count())
                            <ul class="list-group list-group-flush">
                                @foreach($attendances as $att)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <span>
                                            <i class="fa-regular fa-calendar me-2"></i>
                                            <strong>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</strong>
                                            <span class="text-muted small">({{ \Carbon\Carbon::parse($att->date)->format('l') }})</span>
                                        </span>
                                        <span>
                                            @if($att->is_reopened)
                                                <span class="badge bg-info text-dark">Re-Opened</span>
                                            @endif
                                            @if($att->status == 'present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($att->status == 'late')
                                                <span class="badge bg-warning text-dark">Late {{ $att->late_minutes }}m</span>
                                            @else
                                                <span class="badge bg-secondary">Absent</span>
                                            @endif
                                            @if($att->clock_in)
                                                <span class="text-muted small ms-2">
                                                    In: {{ \Carbon\Carbon::parse($att->clock_in)->format('h:i:s A') }}
                                                </span>
                                            @endif
                                            @if($att->clock_out)
                                                <span class="text-muted small ms-2">
                                                    Out: {{ \Carbon\Carbon::parse($att->clock_out)->format('h:i:s A') }}
                                                </span>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted text-center py-3">No attendance records yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                console.log('[Staff Dashboard] Script loaded');

                // ================================================
                // LIVE CLOCK (Pakistan time, updates every second)
                // ================================================
                function updateLiveClock() {
                    try {
                        const now = new Date();
                        const opts = {
                            timeZone: 'Asia/Karachi',
                            hour: '2-digit', minute: '2-digit', second: '2-digit',
                            hour12: true
                        };
                        const dateOpts = {
                            timeZone: 'Asia/Karachi',
                            weekday: 'long', day: 'numeric', month: 'short', year: 'numeric'
                        };
                        const clockEl = document.getElementById('liveClock');
                        const dateEl = document.getElementById('liveDate');
                        if (clockEl) clockEl.textContent = now.toLocaleTimeString('en-US', opts);
                        if (dateEl) dateEl.textContent = now.toLocaleDateString('en-US', dateOpts);
                    } catch (e) { console.error('Live clock error:', e); }
                }
                updateLiveClock();
                setInterval(updateLiveClock, 1000);

                // ================================================
                // LIVE WORKING TIMER
                // ================================================
                @if(isset($showClockOutButton) && $showClockOutButton && $clockInTimestamp)
                    (function () {
                        const clockInMs = {{ (int) $clockInTimestamp }};
                        const timerEl = document.getElementById('workingTimer');
                        console.log('[Timer] Clock-in timestamp:', clockInMs, 'Now:', Date.now());

                        function updateWorkingTimer() {
                            const nowMs = Date.now();
                            const diffSec = Math.max(0, Math.floor((nowMs - clockInMs) / 1000));

                            const h = String(Math.floor(diffSec / 3600)).padStart(2, '0');
                            const m = String(Math.floor((diffSec % 3600) / 60)).padStart(2, '0');
                            const s = String(diffSec % 60).padStart(2, '0');

                            if (timerEl) timerEl.textContent = `${h}:${m}:${s}`;
                        }

                        updateWorkingTimer();
                        setInterval(updateWorkingTimer, 1000);
                    })();
                @endif

                // ================================================
                // AUTO-DISMISS ALERTS (except re-open info banner)
                // ================================================
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('alert-info') && alert.textContent.includes('approved')) return;
                    if (alert.classList.contains('alert-dismissible')) {
                        setTimeout(() => {
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(() => alert.remove(), 500);
                        }, 6000);
                    }
                });

            });
        </script>
    @endpush
@endsection
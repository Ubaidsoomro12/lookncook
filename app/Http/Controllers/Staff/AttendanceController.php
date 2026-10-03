<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!in_array($user->role_id, [4, 5, 6, 7, 8])) {
                return redirect('/')->withErrors(['email' => 'You do not have staff privileges.']);
            }
            return $next($request);
        });
    }

    private function getStaff()
    {
        $user = Auth::user();
        if (!$user) return null;

        $staff = $user->staff;
        if (!$staff) $staff = Staff::where('user_id', $user->id)->first();
        if (!$staff) $staff = Staff::where('email', $user->email)->first();

        return $staff;
    }

    public function index(Request $request)
    {
        $staff = $this->getStaff();

        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Your account is not linked to a staff record. Please contact the administrator.');
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = $staff->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->paginate(15);

        $presentDays = $staff->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $lateDays = $staff->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'late')
            ->count();

        $absentDays = $staff->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'absent')
            ->count();

        $workingDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $prevMonth = $month == 1 ? 12 : $month - 1;
        $prevYear = $month == 1 ? $year - 1 : $year;
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;

        return view('staff.pages.attendance.index', compact(
            'staff', 'attendances', 'month', 'year',
            'presentDays', 'lateDays', 'absentDays',
            'workingDays', 'prevMonth', 'prevYear', 'nextMonth', 'nextYear'
        ));
    }

    public function clockIn(Request $request)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return back()->with('error', 'Your account is not linked to a staff record.');
        }

        $today = now()->toDateString();

        $attendance = Attendance::where('staff_id', $staff->id)
                                ->where('date', $today)
                                ->first();

        if ($attendance && $attendance->clock_in) {
            if (!$attendance->is_reopened) {
                return back()->with('error', 'You already clocked in today. Please ask admin to approve re-clock-in.');
            }
            $attendance->update([
                'clock_in' => null,
                'clock_out' => null,
                'status' => 'absent',
                'late_minutes' => 0,
                'early_minutes' => 0,
                'is_reopened' => false,
                'reopened_at' => null,
            ]);
        }

        $shiftStart = $staff->shift_start_time ? Carbon::parse($staff->shift_start_time) : null;
        $now = Carbon::now();

        $lateMinutes = 0;
        $status = 'present';

        if ($shiftStart && $now->gt($shiftStart)) {
            $lateMinutes = $now->diffInMinutes($shiftStart);
            if ($lateMinutes > 15) {
                $status = 'late';
            } else {
                $lateMinutes = 0;
            }
        }

        Attendance::updateOrCreate(
            ['staff_id' => $staff->id, 'date' => $today],
            [
                'clock_in' => $now->toTimeString(),
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'clock_out' => null,
                'early_minutes' => 0,
            ]
        );

        return back()->with('success', 'Clocked in successfully at ' . $now->format('h:i A') . '.');
    }

    public function clockOut(Request $request)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return back()->with('error', 'Your account is not linked to a staff record.');
        }

        $today = now()->toDateString();
        $attendance = Attendance::where('staff_id', $staff->id)
                                ->where('date', $today)
                                ->first();

        if (!$attendance || !$attendance->clock_in) {
            return back()->with('error', 'You have not clocked in today.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You already clocked out today.');
        }

        $now = Carbon::now();
        $shiftEnd = $staff->shift_end_time ? Carbon::parse($staff->shift_end_time) : null;

        $earlyMinutes = 0;
        if ($shiftEnd && $now->lt($shiftEnd)) {
            $earlyMinutes = $shiftEnd->diffInMinutes($now);
        }

        $attendance->update([
            'clock_out' => $now->toTimeString(),
            'early_minutes' => $earlyMinutes,
        ]);

        return back()->with('success', 'Clocked out successfully at ' . $now->format('h:i A') . '.');
    }
}
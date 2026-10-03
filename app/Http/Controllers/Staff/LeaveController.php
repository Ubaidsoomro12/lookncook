<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Staff;
use App\Models\LeaveAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /* =========================================================
     |  HELPER
     | ========================================================= */

    private function getAuthStaff()
    {
        return Staff::where('user_id', Auth::id())->first();
    }

    /* =========================================================
     |  STAFF METHODS
     | ========================================================= */

    // Staff: list own leaves + cards data
    public function index()
    {
        $staff = $this->getAuthStaff();

        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->withErrors(['error' => 'Staff record not found for this user.']);
        }

        $leaves = Leave::where('staff_id', $staff->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // ---- Cards data (current month only) ----
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $assignment = LeaveAssignment::where('user_id', Auth::id())
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $totalAssigned = $assignment ? $assignment->total_leaves : 0;

        $monthLeaves = Leave::where('staff_id', $staff->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->get();

        $pendingCount = $monthLeaves->where('status', 'Pending')->count();
        $approvedCount = $monthLeaves->where('status', 'Approved')->count();
        $rejectedCount = $monthLeaves->where('status', 'Rejected')->count();

        $usedLeaves = $monthLeaves->whereIn('status', ['Approved', 'Pending'])->count();
        $remaining = max(0, $totalAssigned - $usedLeaves);

        return view('staff.pages.manage_leave.index', compact(
            'leaves',
            'staff',
            'totalAssigned',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'remaining'
        ));
    }

    // Staff: show create form
    public function create()
    {
        $staff = $this->getAuthStaff();

        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->withErrors(['error' => 'Staff record not found for this user.']);
        }

        $assignment = LeaveAssignment::where('user_id', Auth::id())
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        if (!$assignment || $assignment->total_leaves <= 0) {
            return redirect()->route('staff.manage_leave.index')
                ->withErrors(['error' => 'You have no leaves assigned for this month. Please contact admin.']);
        }

        $usedLeaves = Leave::where('staff_id', $staff->id)
            ->whereIn('status', ['Approved', 'Pending'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $remaining = $assignment->total_leaves - $usedLeaves;

        if ($remaining <= 0) {
            return redirect()->route('staff.manage_leave.index')
                ->withErrors(['error' => 'You have used all your assigned leaves for this month.']);
        }

        return view('staff.pages.manage_leave.create', compact('staff', 'remaining', 'assignment'));
    }

    // Staff: store new leave
    public function store(Request $request)
    {
        $staff = $this->getAuthStaff();

        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->withErrors(['error' => 'Staff record not found for this user.']);
        }

        $assignment = LeaveAssignment::where('user_id', Auth::id())
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        if (!$assignment) {
            return back()->withErrors(['error' => 'No leave assigned for this month.']);
        }

        $usedLeaves = Leave::where('staff_id', $staff->id)
            ->whereIn('status', ['Approved', 'Pending'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        if ($usedLeaves >= $assignment->total_leaves) {
            return back()->withErrors(['error' => 'You have no remaining leaves this month.']);
        }

        $request->validate([
            'leave_type' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string',
        ]);

        Leave::create([
            'staff_id' => $staff->id,
            'employee_id' => $staff->employee_id,
            'employee_name' => $staff->name,
            'leave_type' => $request->leave_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->route('staff.manage_leave.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    // Staff: edit form
    public function edit($id)
    {
        $staff = $this->getAuthStaff();

        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->withErrors(['error' => 'Staff record not found for this user.']);
        }

        $leave = Leave::where('id', $id)
            ->where('staff_id', $staff->id)
            ->firstOrFail();

        if ($leave->status !== 'Pending') {
            return redirect()->route('staff.manage_leave.index')
                ->withErrors(['error' => 'Only pending leave requests can be edited.']);
        }

        return view('staff.pages.manage_leave.edit', compact('leave', 'staff'));
    }

    // Staff: update leave
    public function update(Request $request, $id)
    {
        $staff = $this->getAuthStaff();

        if (!$staff) {
            return redirect()->route('staff.dashboard')
                ->withErrors(['error' => 'Staff record not found for this user.']);
        }

        $leave = Leave::where('id', $id)
            ->where('staff_id', $staff->id)
            ->firstOrFail();

        if ($leave->status !== 'Pending') {
            return redirect()->route('staff.manage_leave.index')
                ->withErrors(['error' => 'Only pending leave requests can be updated.']);
        }

        $request->validate([
            'leave_type' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string',
        ]);

        $leave->update([
            'leave_type' => $request->leave_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('staff.manage_leave.index')
            ->with('success', 'Leave request updated successfully!');
    }

    /* =========================================================
     |  ADMIN METHODS
     | ========================================================= */

    // Admin: list all leaves + users for dropdown + assignment summary
    public function adminIndex()
    {
        // ===== Leaves list (existing) =====
        $leaves = DB::table('leaves')
            ->leftJoin('staff', 'staff.id', '=', 'leaves.staff_id')
            ->select(
                'leaves.*',
                'staff.name as staff_name',
                'staff.employee_id as staff_employee_id',
                'staff.department',
                'staff.designation'
            )
            ->orderBy('leaves.created_at', 'desc')
            ->get();

        // ===== All users for assign dropdown =====
        $users = User::orderBy('name')->get();

        // ===== Assignment summary (current month) =====
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        $assignments = LeaveAssignment::with('user')
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get()
            ->map(function ($assignment) {
                // Find staff record for this user
                $staff = Staff::where('user_id', $assignment->user_id)->first();

                // Count this month's leaves for this staff
                if ($staff) {
                    $userLeaves = Leave::where('staff_id', $staff->id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->get();
                } else {
                    $userLeaves = collect();
                }

                $approved = $userLeaves->where('status', 'Approved')->count();
                $rejected = $userLeaves->where('status', 'Rejected')->count();
                $pending  = $userLeaves->where('status', 'Pending')->count();
                $used     = $approved + $pending;

                return [
                    'user_name'      => $assignment->user->name ?? 'N/A',
                    'user_email'     => $assignment->user->email ?? 'N/A',
                    'employee_id'    => $staff->employee_id ?? 'N/A',
                    'total_assigned' => $assignment->total_leaves,
                    'approved'       => $approved,
                    'rejected'       => $rejected,
                    'pending'        => $pending,
                    'used'           => $used,
                    'remaining'      => max(0, $assignment->total_leaves - $used),

                    // 👇 NEW: earliest from_date and latest to_date (this month)
                    'from_date'      => $userLeaves->min('from_date'),
                    'to_date'        => $userLeaves->max('to_date'),
                ];
            });

        return view('admin.pages.manage_leaves.index', compact('leaves', 'users', 'assignments'));
    }

    // Admin: approve leave
    public function approve($id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'Pending') {
            return back()->withErrors(['error' => 'Only pending leaves can be approved.']);
        }

        $leave->update(['status' => 'Approved']);

        return back()->with('success', 'Leave approved successfully!');
    }

    // Admin: reject leave
    public function reject($id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'Pending') {
            return back()->withErrors(['error' => 'Only pending leaves can be rejected.']);
        }

        $leave->update(['status' => 'Rejected']);

        return back()->with('success', 'Leave rejected successfully!');
    }

    // Admin: assign leaves to a user for current month
    public function assignLeave(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_leaves' => 'required|integer|min:1',
        ]);

        LeaveAssignment::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'month' => now()->month,
                'year' => now()->year,
            ],
            [
                'total_leaves' => $request->total_leaves,
            ]
        );

        return back()->with('success', 'Leaves assigned successfully!');
    }
}
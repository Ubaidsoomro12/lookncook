<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use App\Models\Branch;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            if ($request->route()->getName() == 'staff.dashboard') {
                if (!in_array($user->role_id, [4, 5, 6, 7, 8])) {
                    return redirect('/')->withErrors(['email' => 'You do not have staff privileges.']);
                }
                return $next($request);
            }

            if ($user->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges.']);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $staff = Staff::with(['user', 'manager'])->latest()->paginate(10);
        return view('admin.pages.staff.index', compact('staff'));
    }

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));
        $staff = Staff::with(['user', 'manager'])
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('employee_id', 'like', "%{$q}%")
                    ->orWhere('cnic', 'like', "%{$q}%");
            })
            ->latest()->get();

        return response()->json([
            'staff' => $staff->map(function ($s) {
                return [
                    'id' => $s->id,
                    'employee_id' => $s->employee_id,
                    'name' => $s->name,
                    'email' => $s->email,
                    'phone' => $s->phone,
                    'cnic' => $s->cnic,
                    'image' => $s->image ? asset($s->image) : null,
                    'hire_date' => $s->hire_date?->format('Y-m-d'),
                    'salary' => $s->salary,
                    'status' => $s->status,
                    'role_name' => $this->getRoleName($s->user->role_id ?? null),
                    'user_email' => $s->user->email ?? '—',
                    'designation' => $s->designation,
                    'branch' => $s->branch ?? '—',
                    'shift_start_time' => $s->shift_start_time ?? '—',
                    'shift_end_time' => $s->shift_end_time ?? '—',
                    'edit_url' => route('admin.staff.edit', $s->id),
                    'delete_url' => route('admin.staff.destroy', $s->id),
                ];
            })
        ]);
    }

    public function searchUsers(Request $request)
    {
        $q = trim($request->query('q', ''));
        $users = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_name' => $this->getRoleName($user->role_id),
                ];
            });
        return response()->json(['users' => $users]);
    }

    private function getRoleName($role_id)
    {
        $roles = [
            1 => 'Admin',
            2 => 'User',
            3 => 'Manager',
            4 => 'Waiter',
            5 => 'Chef',
            6 => 'Cashier',
            7 => 'Cleaner',
            8 => 'Delivery Rider'
        ];
        return $roles[$role_id] ?? '—';
    }

    public function create()
    {
        $branches = Branch::all();
        $managers = Staff::where('status', 'Active')->get();
        return view('admin.pages.staff.create', compact('branches', 'managers'));
    }

    private function validateShiftTimes($data)
    {
        $errors = [];

        $start = $data['shift_start_time'] ?? null;
        $end = $data['shift_end_time'] ?? null;

        if ($start && $end) {
            if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $start)) {
                $errors['shift_start_time'] = 'Invalid start time. Use HH:MM (24-hour) format.';
            }
            if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $end)) {
                $errors['shift_end_time'] = 'Invalid end time. Use HH:MM (24-hour) format.';
            }

            if (empty($errors)) {
                $startTime = \Carbon\Carbon::createFromFormat('H:i', $start);
                $endTime = \Carbon\Carbon::createFromFormat('H:i', $end);

                if ($endTime <= $startTime) {
                    $endTime->addDay();
                }

                $diffMinutes = $startTime->diffInMinutes($endTime);

                if ($diffMinutes < 60) {
                    $errors['shift_end_time'] = 'Shift must be at least 1 hour long.';
                }
            }
        }

        return $errors;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'employee_id' => 'nullable|string|max:50|unique:staff,employee_id',
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:20',
            'cnic' => 'nullable|string|max:20|unique:staff,cnic',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cv_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'appointment_letter_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:Fixed,Hourly,Commission',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'employee_type' => 'required|in:Full-time,Part-time,Contract',
            'department' => 'nullable|in:Kitchen,Front of House,Delivery,Management',
            'designation' => 'nullable|string|max:50',
            'branch' => 'nullable|string|max:100',
            'work_shift' => 'nullable|in:Morning,Evening,Night',
            'shift_start_time' => 'nullable|date_format:H:i',
            'shift_end_time' => 'nullable|date_format:H:i',
            'reporting_manager_id' => 'nullable|exists:staff,id',
            'status' => 'required|in:Active,On Leave,Terminated',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $shiftErrors = $this->validateShiftTimes($request->all());
        if (!empty($shiftErrors)) {
            return back()->withErrors($shiftErrors)->withInput();
        }

        $data = $request->all();

        foreach (['image', 'cnic_front_image', 'cnic_back_image', 'cv_image', 'appointment_letter_image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->storeImage($request->file($field), $field);
            }
        }

        Staff::create($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $branches = Branch::all();
        $managers = Staff::where('id', '!=', $id)->where('status', 'Active')->get();
        return view('admin.pages.staff.edit', compact('staff', 'branches', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'employee_id' => 'nullable|string|max:50|unique:staff,employee_id,' . $id,
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'phone' => 'required|string|max:20',
            'cnic' => 'nullable|string|max:20|unique:staff,cnic,' . $id,
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cv_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'appointment_letter_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:Fixed,Hourly,Commission',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'employee_type' => 'required|in:Full-time,Part-time,Contract',
            'department' => 'nullable|in:Kitchen,Front of House,Delivery,Management',
            'designation' => 'nullable|string|max:50',
            'branch' => 'nullable|string|max:100',
            'work_shift' => 'nullable|in:Morning,Evening,Night',
            'shift_start_time' => 'nullable|date_format:H:i',
            'shift_end_time' => 'nullable|date_format:H:i',
            'reporting_manager_id' => 'nullable|exists:staff,id',
            'status' => 'required|in:Active,On Leave,Terminated',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $shiftErrors = $this->validateShiftTimes($request->all());
        if (!empty($shiftErrors)) {
            return back()->withErrors($shiftErrors)->withInput();
        }

        $data = $request->except(['_token', '_method']);

        foreach (['image', 'cnic_front_image', 'cnic_back_image', 'cv_image', 'appointment_letter_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($staff->$field) $this->deleteImage($staff->$field);
                $data[$field] = $this->storeImage($request->file($field), $field);
            }
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $staff = Staff::findOrFail($id);
            foreach (['image', 'cnic_front_image', 'cnic_back_image', 'cv_image', 'appointment_letter_image'] as $field) {
                if ($staff->$field) $this->deleteImage($staff->$field);
            }
            $staff->delete();
            return response()->json(['success' => true, 'message' => 'Staff deleted successfully!', 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $staff = Staff::with(['user', 'manager'])->findOrFail($id);
        return view('admin.pages.staff.view', compact('staff'));
    }

    public function dashboard()
    {
        return view('staff.staff_dashboard');
    }

    // =====================================================
    //  ADMIN – ATTENDANCE MANAGEMENT
    // =====================================================

    public function attendance(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $search = $request->input('search', '');

        $query = Staff::with(['user', 'attendances' => function ($q) use ($date) {
            $q->where('date', $date);
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $staff = $query->orderBy('name')->paginate(15);

        $totalStaff = Staff::count();
        $presentCount = Attendance::where('date', $date)->whereIn('status', ['present', 'late'])->count();
        $lateCount = Attendance::where('date', $date)->where('status', 'late')->count();
        $absentCount = $totalStaff - $presentCount;

        return view('admin.pages.staff.attendance.index', compact(
            'staff', 'date', 'search',
            'totalStaff', 'presentCount', 'lateCount', 'absentCount'
        ));
    }

    public function attendanceDetail($id, Request $request)
    {
        $staff = Staff::with('user')->findOrFail($id);

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = $staff->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->paginate(20);

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

        return view('admin.pages.staff.attendance.detail', compact(
            'staff', 'attendances', 'month', 'year',
            'presentDays', 'lateDays', 'absentDays',
            'workingDays', 'prevMonth', 'prevYear', 'nextMonth', 'nextYear'
        ));
    }

    public function approveReclock($attendanceId)
    {
        try {
            $attendance = Attendance::findOrFail($attendanceId);

            $attendance->update([
                'is_reopened' => true,
                'reopened_by' => auth()->id(),
                'reopened_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Staff approved to clock in again. They can now re-clock-in for ' . $attendance->date->format('d M Y') . '.',
                'attendance_id' => $attendance->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ------------------- Helper Methods -------------------

    private function storeImage($file, $field)
    {
        $folder = match ($field) {
            'cnic_front_image', 'cnic_back_image' => 'staff/cnic',
            'cv_image', 'appointment_letter_image' => 'staff/documents',
            default => 'staff/profile',
        };
        $dest = public_path($folder);
        if (!File::exists($dest)) File::makeDirectory($dest, 0755, true);
        $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($dest, $name);
        return $folder . '/' . $name;
    }

    private function deleteImage($path)
    {
        if ($path && File::exists(public_path($path))) File::delete(public_path($path));
    }
}
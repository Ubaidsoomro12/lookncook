<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollAllowanceCompany;
use App\Models\PayrollAllowanceType;
use App\Models\PayrollDeductionType;
use App\Models\PayrollPaymentMethod;
use App\Models\Staff;
use App\Models\StaffAllowanceExpense;
use App\Models\StaffAllowanceRequest;
use App\Models\StaffPayment;
use App\Models\StaffPayroll;
use App\Models\StaffPayrollAllowance;
use App\Models\StaffPayrollDeduction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffPayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $routeName = $request->route() ? $request->route()->getName() : '';

            if (str_starts_with((string) $routeName, 'staff.payroll.')
                || str_starts_with((string) $routeName, 'staff.allowences.')) {
                return $next($request);
            }

            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges.']);
            }

            return $next($request);
        });
    }

    /* ============================================================
       ADMIN — INDEX
    ============================================================ */
    public function index(Request $request)
    {
        $query = StaffPayroll::with(['staff', 'creator']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('staff', function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('employee_id', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }

        if ($request->filled('month'))  $query->where('period_month', $request->month);
        if ($request->filled('year'))   $query->where('period_year', $request->year);
        if ($request->filled('status')) $query->where('payment_status', $request->status);

        $payrolls = $query->latest('period_year')
            ->latest('period_month')
            ->latest('id')
            ->paginate(15)
            ->appends($request->query());

        $stats = [
            'total_pending'    => StaffPayroll::where('payment_status', 'pending')->count(),
            'total_paid'       => StaffPayroll::where('payment_status', 'paid')->count(),
            'this_month'       => StaffPayroll::where('period_month', now()->month)
                                    ->where('period_year', now()->year)->count(),
            'total_allowances' => (float) StaffPayroll::where('period_month', now()->month)
                                    ->where('period_year', now()->year)->sum('total_allowances'),
            'total_deductions' => (float) StaffPayroll::where('period_month', now()->month)
                                    ->where('period_year', now()->year)->sum('total_deductions'),
        ];

        return view('admin.pages.payrolls.index', compact('payrolls', 'stats'));
    }

    /* ============================================================
       ADMIN — CREATE
    ============================================================ */
    public function create(Request $request)
    {
        $staffList = Staff::with('paymentMethod')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get([
                'id', 'name', 'employee_id', 'designation', 'salary',
                'payment_method_id', 'payment_details'
            ]);

        $allowanceTypes = PayrollAllowanceType::active()->orderBy('sort_order')->orderBy('name')->get();
        $deductionTypes = PayrollDeductionType::active()->orderBy('sort_order')->orderBy('name')->get();
        $paymentMethods = PayrollPaymentMethod::active()->orderBy('sort_order')->orderBy('name')->get();
        $companies      = PayrollAllowanceCompany::active()->orderBy('sort_order')->orderBy('name')->get();

        $preselectedStaffId = $request->query('staff_id');

        return view('admin.pages.payrolls.create', compact(
            'staffList', 'preselectedStaffId',
            'allowanceTypes', 'deductionTypes', 'paymentMethods', 'companies'
        ));
    }

    /* ============================================================
       ADMIN — STORE
    ============================================================ */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id'           => 'required|exists:staff,id',
            'period_month'       => 'required|integer|min:1|max:12',
            'period_year'        => 'required|integer|min:2000|max:2100',
            'basic_salary'       => 'required|numeric|min:0',
            'total_working_days' => 'nullable|integer|min:0',
            'present_days'       => 'nullable|integer|min:0',
            'absent_days'        => 'nullable|integer|min:0',
            'overtime_hours'     => 'nullable|numeric|min:0',
            'overtime_rate'      => 'nullable|numeric|min:0',
            'bonus'              => 'nullable|numeric|min:0',
            'payment_status'     => 'nullable|in:pending,paid,cancelled',
            'payment_date'       => 'nullable|date',
            'payment_method'     => 'nullable|string|max:100',
            'notes'              => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $exists = StaffPayroll::where('staff_id', $request->staff_id)
            ->where('period_year', $request->period_year)
            ->where('period_month', $request->period_month)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'period_month' => 'Payroll already exists for this staff and period.',
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            $payroll = new StaffPayroll();
            $payroll->fill([
                'staff_id'           => $request->staff_id,
                'period_month'       => $request->period_month,
                'period_year'        => $request->period_year,
                'basic_salary'       => $request->basic_salary,
                'total_working_days' => $request->total_working_days ?? 0,
                'present_days'       => $request->present_days ?? 0,
                'absent_days'        => $request->absent_days ?? 0,
                'overtime_hours'     => $request->overtime_hours ?? 0,
                'overtime_rate'      => $request->overtime_rate ?? 0,
                'bonus'              => $request->bonus ?? 0,
                'payment_status'     => $request->payment_status ?: 'pending',
                'payment_date'       => $request->payment_date,
                'payment_method'     => $request->payment_method,
                'notes'              => $request->notes,
            ]);
            $payroll->created_by = auth()->id();
            $payroll->save();

            $allowances     = $request->input('allowance_amount', []);
            $enabled        = $request->input('allowance_enabled', []);
            $companiesInput = $request->input('allowance_company', []);

            foreach ($allowances as $typeId => $amount) {
                if (!isset($enabled[$typeId])) continue;
                $amt = (float) $amount;
                if ($amt <= 0) continue;

                $companyId = $companiesInput[$typeId] ?? null;
                if ($companyId === '' || $companyId === '0') $companyId = null;

                StaffPayrollAllowance::create([
                    'staff_payroll_id'  => $payroll->id,
                    'allowance_type_id' => $typeId,
                    'company_id'        => $companyId,
                    'amount'            => $amt,
                ]);
            }

            $deductions = $request->input('deduction_amount', []);
            $enabled    = $request->input('deduction_enabled', []);
            foreach ($deductions as $typeId => $amount) {
                if (!isset($enabled[$typeId])) continue;
                $amt = (float) $amount;
                if ($amt <= 0) continue;

                StaffPayrollDeduction::create([
                    'staff_payroll_id'  => $payroll->id,
                    'deduction_type_id' => $typeId,
                    'amount'            => $amt,
                ]);
            }

            $payroll->recalculate();
            $payroll->save();

            DB::commit();

            return redirect()->route('admin.payrolls.index')
                ->with('success', 'Payroll created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Save failed: ' . $e->getMessage()])->withInput();
        }
    }

    /* ============================================================
       ADMIN — SHOW
    ============================================================ */
    public function show($id)
    {
        $payroll = StaffPayroll::with([
            'staff', 'creator',
            'allowances.type', 'allowances.company',
            'deductions.type'
        ])->findOrFail($id);
        return view('admin.pages.payrolls.show', compact('payroll'));
    }

    /* ============================================================
       ADMIN — EDIT
    ============================================================ */
    public function edit($id)
    {
        $payroll = StaffPayroll::with(['allowances', 'deductions'])->findOrFail($id);

        $staffList = Staff::with('paymentMethod')
            ->orderBy('name')
            ->get([
                'id', 'name', 'employee_id', 'designation', 'salary',
                'payment_method_id', 'payment_details'
            ]);

        $allowanceTypes = PayrollAllowanceType::active()->orderBy('sort_order')->orderBy('name')->get();
        $deductionTypes = PayrollDeductionType::active()->orderBy('sort_order')->orderBy('name')->get();
        $paymentMethods = PayrollPaymentMethod::active()->orderBy('sort_order')->orderBy('name')->get();
        $companies      = PayrollAllowanceCompany::active()->orderBy('sort_order')->orderBy('name')->get();

        $existingAllowances = $payroll->allowances->pluck('amount', 'allowance_type_id')->toArray();
        $existingDeductions = $payroll->deductions->pluck('amount', 'deduction_type_id')->toArray();
        $existingAllowanceCompanies = $payroll->allowances->pluck('company_id', 'allowance_type_id')->toArray();

        return view('admin.pages.payrolls.edit', compact(
            'payroll', 'staffList',
            'allowanceTypes', 'deductionTypes', 'paymentMethods', 'companies',
            'existingAllowances', 'existingDeductions', 'existingAllowanceCompanies'
        ));
    }

    /* ============================================================
       ADMIN — UPDATE
    ============================================================ */
    public function update(Request $request, $id)
    {
        $payroll = StaffPayroll::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'staff_id'           => 'required|exists:staff,id',
            'period_month'       => 'required|integer|min:1|max:12',
            'period_year'        => 'required|integer|min:2000|max:2100',
            'basic_salary'       => 'required|numeric|min:0',
            'total_working_days' => 'nullable|integer|min:0',
            'present_days'       => 'nullable|integer|min:0',
            'absent_days'        => 'nullable|integer|min:0',
            'overtime_hours'     => 'nullable|numeric|min:0',
            'overtime_rate'      => 'nullable|numeric|min:0',
            'bonus'              => 'nullable|numeric|min:0',
            'payment_status'     => 'nullable|in:pending,paid,cancelled',
            'payment_date'       => 'nullable|date',
            'payment_method'     => 'nullable|string|max:100',
            'notes'              => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $exists = StaffPayroll::where('staff_id', $request->staff_id)
            ->where('period_year', $request->period_year)
            ->where('period_month', $request->period_month)
            ->where('id', '!=', $payroll->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'period_month' => 'Payroll already exists for this staff and period.',
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            $payroll->fill([
                'staff_id'           => $request->staff_id,
                'period_month'       => $request->period_month,
                'period_year'        => $request->period_year,
                'basic_salary'       => $request->basic_salary,
                'total_working_days' => $request->total_working_days ?? 0,
                'present_days'       => $request->present_days ?? 0,
                'absent_days'        => $request->absent_days ?? 0,
                'overtime_hours'     => $request->overtime_hours ?? 0,
                'overtime_rate'      => $request->overtime_rate ?? 0,
                'bonus'              => $request->bonus ?? 0,
                'payment_status'     => $request->payment_status ?: $payroll->payment_status,
                'payment_date'       => $request->payment_date,
                'payment_method'     => $request->payment_method,
                'notes'              => $request->notes,
            ]);
            $payroll->save();

            $payroll->allowances()->delete();
            $allowances     = $request->input('allowance_amount', []);
            $enabled        = $request->input('allowance_enabled', []);
            $companiesInput = $request->input('allowance_company', []);

            foreach ($allowances as $typeId => $amount) {
                if (!isset($enabled[$typeId])) continue;
                $amt = (float) $amount;
                if ($amt <= 0) continue;

                $companyId = $companiesInput[$typeId] ?? null;
                if ($companyId === '' || $companyId === '0') $companyId = null;

                StaffPayrollAllowance::create([
                    'staff_payroll_id'  => $payroll->id,
                    'allowance_type_id' => $typeId,
                    'company_id'        => $companyId,
                    'amount'            => $amt,
                ]);
            }

            $payroll->deductions()->delete();
            $deductions = $request->input('deduction_amount', []);
            $enabled    = $request->input('deduction_enabled', []);
            foreach ($deductions as $typeId => $amount) {
                if (!isset($enabled[$typeId])) continue;
                $amt = (float) $amount;
                if ($amt <= 0) continue;

                StaffPayrollDeduction::create([
                    'staff_payroll_id'  => $payroll->id,
                    'deduction_type_id' => $typeId,
                    'amount'            => $amt,
                ]);
            }

            $payroll->recalculate();
            $payroll->save();

            DB::commit();

            return redirect()->route('admin.payrolls.index')
                ->with('success', 'Payroll updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    /* ============================================================
       ADMIN — DESTROY
    ============================================================ */
    public function destroy($id)
    {
        try {
            $payroll = StaffPayroll::findOrFail($id);
            $payroll->delete();
            return response()->json(['success' => true, 'message' => 'Payroll deleted.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       ADMIN — PRINT SLIP
    ============================================================ */
    public function printSlip($id)
    {
        $payroll = StaffPayroll::with([
            'staff', 'creator',
            'allowances.type', 'allowances.company',
            'deductions.type'
        ])->findOrFail($id);
        return view('admin.pages.payrolls.slip', compact('payroll'));
    }

         /* ============================================================
       STAFF — MY PAYROLL (PAYMENT HISTORY)
    ============================================================ */
    public function myPayroll(Request $request)
    {
        $staff = Staff::where('user_id', auth()->id())->first();

        if (!$staff) {
            return view('staff.pages.payroll.index', [
                'staff'    => null,
                'payments' => collect(),
                'summary'  => [
                    'base_salary' => 0,
                    'total_paid'  => 0,
                    'pending'     => 0,
                    'this_month'  => 0,
                ],
                'hireYear' => now()->year,
            ]);
        }

        // Fetch ALL payments (removed pagination so JS can filter everything)
        $payments = StaffPayment::with(['paymentMethod'])
            ->where('staff_id', $staff->id)
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        $baseSalary = (float) $staff->salary;
        $totalPaid  = (float) StaffPayment::where('staff_id', $staff->id)
            ->where('status', 'completed')
            ->sum('amount');
            
        $pending = max(0, $baseSalary - $totalPaid);

        $thisMonth = (float) StaffPayment::where('staff_id', $staff->id)
            ->where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $summary = [
            'base_salary' => $baseSalary,
            'total_paid'  => $totalPaid,
            'pending'     => $pending,
            'this_month'  => $thisMonth,
        ];

        // Calculate the year they joined
        $hireYear = $staff->hire_date ? \Carbon\Carbon::parse($staff->hire_date)->year : now()->year;

        return view('staff.pages.payroll.index', compact('staff', 'payments', 'summary', 'hireYear'));
    }

    /* ============================================================
       STAFF — MY SLIP
    ============================================================ */
    public function mySlip($id)
    {
        $staff = Staff::where('user_id', auth()->id())->firstOrFail();

        $payroll = StaffPayroll::with([
            'staff', 'creator',
            'allowances.type', 'allowances.company',
            'deductions.type'
        ])
            ->where('staff_id', $staff->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('admin.pages.payrolls.slip', compact('payroll'));
    }

    /* ============================================================
       STAFF — AJAX PAYROLL SLIP FOR ALLOWANCES POPUP
    ============================================================ */
    public function getPayrollSlipAjax($id)
    {
        try {
            $staff = Staff::where('user_id', auth()->id())->firstOrFail();
            
            $payroll = StaffPayroll::with([
                'allowances.type', 'allowances.company', 'deductions.type'
            ])
            ->where('staff_id', $staff->id)
            ->where('id', $id)
            ->firstOrFail();

            $html = view('staff.partials.payroll_slip_modal', compact('payroll'))->render();
            
            return response()->json(['success' => true, 'html' => $html]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       STAFF — MY ALLOWANCES DASHBOARD
    ============================================================ */
    public function myAllowances(Request $request)
    {
        $staff = Staff::where('user_id', auth()->id())->first();

        if (!$staff) {
            return view('staff.pages.allowences.index', [
                'staff'            => null,
                'entries'          => collect(),
                'expenses'         => collect(),
                'requests'         => collect(),
                'summary'          => ['given' => 0, 'spent' => 0, 'pending' => 0, 'left' => 0, 'companies' => 0],
                'byCompany'        => collect(),
                'availableEntries' => collect(),
                'entriesByType'    => collect(),
            ]);
        }

        $entries = StaffPayrollAllowance::with([
                'type', 'company', 'payroll', 'expenses', 'requests',
            ])
            ->whereHas('payroll', function ($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })
            ->get()
            ->sortByDesc(function ($e) {
                $year  = $e->payroll->period_year ?? 0;
                $month = $e->payroll->period_month ?? 0;
                return ($year * 100) + $month;
            })
            ->values();

        $entries = $entries->map(function ($e) {
            $spentApproved = (float) $e->expenses->sum('amount');
            $pendingSum    = (float) $e->requests->where('status', 'pending')->sum('amount');

            $e->spent_computed   = $spentApproved;
            $e->pending_computed = $pendingSum;
            $e->left_computed    = max(0, (float) $e->amount - $spentApproved);
            $e->available_computed = max(0, (float) $e->amount - $spentApproved - $pendingSum);
            return $e;
        });

        $entriesByType = $entries
            ->groupBy('allowance_type_id')
            ->map(function ($items) {
                $first = $items->first();

                $companies = $items
                    ->groupBy(fn($i) => $i->company_id ?? 0)
                    ->map(function ($cItems) {
                        $f = $cItems->first();
                        $given = (float) $cItems->sum('amount');
                        $spent = (float) $cItems->sum('spent_computed');
                        $pending = (float) $cItems->sum('pending_computed');

                        return [
                            'company_id'   => $f->company_id,
                            'company_name' => $f->company?->name ?? 'Direct / No Company',
                            'given'        => $given,
                            'spent'        => $spent,
                            'pending'      => $pending,
                            'left'         => max(0, $given - $spent),
                            'count'        => $cItems->count(),
                        ];
                    })
                    ->values()
                    ->toArray();

                return [
                    'type_id'   => $first->allowance_type_id,
                    'type_name' => $first->type?->name ?? 'Unknown Allowance',
                    'companies' => $companies,
                ];
            })
            ->values()
            ->toArray();

        $expenses = StaffAllowanceExpense::with([
                'allowanceEntry.type',
                'allowanceEntry.company',
                'allowanceEntry.payroll',
            ])
            ->where('staff_id', $staff->id)
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        $requests = StaffAllowanceRequest::with([
                'allowanceEntry.type',
                'allowanceEntry.company',
                'allowanceEntry.payroll',
                'reviewer',
            ])
            ->where('staff_id', $staff->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $totalGiven   = (float) $entries->sum('amount');
        $totalSpent   = (float) $entries->sum('spent_computed');
        $totalPending = (float) $entries->sum('pending_computed');

        $byCompany = $entries
            ->groupBy(function ($e) {
                return $e->company?->name ?? 'Direct / No Company';
            })
            ->map(function ($items, $name) {
                $given = (float) $items->sum('amount');
                $spent = (float) $items->sum('spent_computed');
                return [
                    'name'  => $name,
                    'given' => $given,
                    'spent' => $spent,
                    'left'  => max(0, $given - $spent),
                    'count' => $items->count(),
                ];
            })
            ->values();

        $availableEntries = $entries->filter(function ($e) {
            return $e->available_computed > 0;
        })->values();

        return view('staff.pages.allowences.index', [
            'staff'            => $staff,
            'entries'          => $entries,
            'expenses'         => $expenses,
            'requests'         => $requests,
            'summary'          => [
                'given'     => $totalGiven,
                'spent'     => $totalSpent,
                'pending'   => $totalPending,
                'left'      => max(0, $totalGiven - $totalSpent),
                'companies' => $byCompany->count(),
            ],
            'byCompany'        => $byCompany,
            'availableEntries' => $availableEntries,
            'entriesByType'    => $entriesByType,
        ]);
    }

    /* ============================================================
       STAFF — RECORD EXPENSE (kept for backwards compatibility)
    ============================================================ */
    public function storeExpense(Request $request)
    {
        try {
            $staff = Staff::where('user_id', auth()->id())->firstOrFail();

            $v = Validator::make($request->all(), [
                'staff_payroll_allowance_id' => 'required|exists:staff_payroll_allowances,id',
                'amount'                     => 'required|numeric|min:0.01',
                'expense_date'               => 'required|date',
                'description'                => 'nullable|string|max:1000',
            ]);

            if ($v->fails()) {
                return response()->json(['success' => false, 'errors' => $v->errors()], 422);
            }

            $entry = StaffPayrollAllowance::with('payroll')
                ->findOrFail($request->staff_payroll_allowance_id);

            if (!$entry->payroll || $entry->payroll->staff_id !== $staff->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $alreadySpent = (float) $entry->expenses()->sum('amount');
            $available    = (float) $entry->amount - $alreadySpent;
            $requestedAmt = (float) $request->amount;

            if ($requestedAmt > $available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount exceeds available balance of Rs ' . number_format($available, 2),
                ], 422);
            }

            StaffAllowanceExpense::create([
                'staff_id'                   => $staff->id,
                'staff_payroll_allowance_id' => $entry->id,
                'amount'                     => $requestedAmt,
                'expense_date'               => $request->expense_date,
                'description'                => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Expense recorded successfully.']);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       STAFF — DELETE EXPENSE (kept for backwards compatibility)
    ============================================================ */
    public function deleteExpense($id)
    {
        try {
            $staff = Staff::where('user_id', auth()->id())->firstOrFail();

            $expense = StaffAllowanceExpense::where('staff_id', $staff->id)
                ->findOrFail($id);

            $expense->delete();

            return response()->json(['success' => true, 'message' => 'Expense removed.']);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       STAFF — SUBMIT AN ALLOWANCE REQUEST
    ============================================================ */
    public function submitAllowanceRequest(Request $request)
    {
        try {
            $staff = Staff::where('user_id', auth()->id())->firstOrFail();

            $v = Validator::make($request->all(), [
                'staff_payroll_allowance_id' => 'required|exists:staff_payroll_allowances,id',
                'amount'                     => 'required|numeric|min:0.01',
                'expense_date'               => 'required|date',
                'description'                => 'required|string|min:3|max:2000',
                'receipt_image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            ]);

            if ($v->fails()) {
                return response()->json(['success' => false, 'errors' => $v->errors()], 422);
            }

            $entry = StaffPayrollAllowance::with('payroll')
                ->findOrFail($request->staff_payroll_allowance_id);

            if (!$entry->payroll || $entry->payroll->staff_id !== $staff->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $spentApproved = (float) $entry->expenses()->sum('amount');
            $pendingSum    = (float) $entry->pendingRequests()->sum('amount');
            $available     = (float) $entry->amount - $spentApproved - $pendingSum;
            $requestedAmt  = (float) $request->amount;

            if ($requestedAmt > $available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount exceeds available balance (Rs ' . number_format($available, 2) . '). Pending requests are reserved.',
                ], 422);
            }

            $receiptPath = null;
            if ($request->hasFile('receipt_image')) {
                $folder = public_path('staff/allowance-receipts');
                if (!File::exists($folder)) File::makeDirectory($folder, 0755, true);

                $name = time() . '_' . Str::random(8) . '.' . $request->file('receipt_image')->getClientOriginalExtension();
                $request->file('receipt_image')->move($folder, $name);
                $receiptPath = 'staff/allowance-receipts/' . $name;
            }

            StaffAllowanceRequest::create([
                'staff_id'                   => $staff->id,
                'staff_payroll_allowance_id' => $entry->id,
                'amount'                     => $requestedAmt,
                'expense_date'               => $request->expense_date,
                'description'                => $request->description,
                'receipt_image'              => $receiptPath,
                'status'                     => 'pending',
            ]);

            return response()->json(['success' => true, 'message' => 'Request submitted for approval.']);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       STAFF — CANCEL OWN PENDING REQUEST
    ============================================================ */
    public function cancelAllowanceRequest($id)
    {
        try {
            $staff = Staff::where('user_id', auth()->id())->firstOrFail();

            $req = StaffAllowanceRequest::where('staff_id', $staff->id)
                ->where('status', 'pending')
                ->findOrFail($id);

            if ($req->receipt_image && File::exists(public_path($req->receipt_image))) {
                File::delete(public_path($req->receipt_image));
            }
            $req->delete();

            return response()->json(['success' => true, 'message' => 'Request cancelled.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       ADMIN — LIST ALL ALLOWANCE REQUESTS
    ============================================================ */
    public function adminAllowanceRequests(Request $request)
    {
        $query = StaffAllowanceRequest::with([
            'staff', 'allowanceEntry.type', 'allowanceEntry.company',
            'allowanceEntry.payroll', 'reviewer',
        ]);

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('staff', function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('employee_id', 'like', "%$s%");
            });
        }

        $requests = $query->orderByDesc('created_at')->paginate(30)->appends($request->query());

        $stats = [
            'pending_count'   => StaffAllowanceRequest::where('status', 'pending')->count(),
            'pending_amount'  => (float) StaffAllowanceRequest::where('status', 'pending')->sum('amount'),
            'approved_count'  => StaffAllowanceRequest::where('status', 'approved')->count(),
            'approved_amount' => (float) StaffAllowanceRequest::where('status', 'approved')->sum('amount'),
            'rejected_count'  => StaffAllowanceRequest::where('status', 'rejected')->count(),
            'rejected_amount' => (float) StaffAllowanceRequest::where('status', 'rejected')->sum('amount'),
            'all_count'       => StaffAllowanceRequest::count(),
            'approved_month'  => (float) StaffAllowanceRequest::where('status', 'approved')
                                    ->whereMonth('reviewed_at', now()->month)
                                    ->whereYear('reviewed_at', now()->year)
                                    ->sum('amount'),
        ];

        $staffList = Staff::orderBy('name')->get(['id', 'name', 'employee_id']);

        return view('admin.pages.payrolls.allowance_requests.index',
            compact('requests', 'stats', 'staffList'));
    }

    /* ============================================================
       ADMIN — APPROVE A REQUEST
    ============================================================ */
    public function approveAllowanceRequest(Request $request, $id)
    {
        try {
            $req = StaffAllowanceRequest::with('allowanceEntry')->findOrFail($id);

            if ($req->status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'This request is already ' . $req->status . '.'], 422);
            }

            $entry = $req->allowanceEntry;

            $alreadySpent = (float) $entry->expenses()->sum('amount');
            $available    = (float) $entry->amount - $alreadySpent;

            if ((float) $req->amount > $available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot approve: amount exceeds remaining balance (Rs ' . number_format($available, 2) . ').',
                ], 422);
            }

            DB::beginTransaction();

            $req->status      = 'approved';
            $req->admin_notes = $request->input('admin_notes');
            $req->reviewed_by = auth()->id();
            $req->reviewed_at = now();
            $req->save();

            StaffAllowanceExpense::create([
                'staff_id'                   => $req->staff_id,
                'staff_payroll_allowance_id' => $req->staff_payroll_allowance_id,
                'amount'                     => $req->amount,
                'expense_date'               => $req->expense_date,
                'description'                => $req->description,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Request approved. Amount added to expense.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ============================================================
       ADMIN — REJECT A REQUEST
    ============================================================ */
    public function rejectAllowanceRequest(Request $request, $id)
    {
        try {
            $req = StaffAllowanceRequest::findOrFail($id);

            if ($req->status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'This request is already ' . $req->status . '.'], 422);
            }

            $v = Validator::make($request->all(), [
                'admin_notes' => 'required|string|min:3|max:1000',
            ]);

            if ($v->fails()) {
                return response()->json(['success' => false, 'errors' => $v->errors()], 422);
            }

            $req->status      = 'rejected';
            $req->admin_notes = $request->admin_notes;
            $req->reviewed_by = auth()->id();
            $req->reviewed_at = now();
            $req->save();

            return response()->json(['success' => true, 'message' => 'Request rejected.']);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
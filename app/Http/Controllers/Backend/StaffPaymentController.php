<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollPaymentMethod;
use App\Models\Staff;
use App\Models\StaffPayment;
use App\Models\StaffPayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaffPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = StaffPayment::with(['staff', 'paymentMethod']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('staff', function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('employee_id', 'like', "%$s%");
            });
        }

        if ($request->filled('method')) {
            $query->where('payment_method_id', $request->method);
        }

        $payments = $query->orderByDesc('payment_date')->orderByDesc('id')->paginate(20);
        $paymentMethods = PayrollPaymentMethod::active()->orderBy('name')->get();

        return view('admin.pages.staff_payments.index', compact('payments', 'paymentMethods'));
    }

    public function create(Request $request)
    {
        // Load staff with their default salary and payment method
        $staffList = Staff::where('status', 'Active')->orderBy('name')->get();
        $paymentMethods = PayrollPaymentMethod::active()->orderBy('name')->get();

        return view('admin.pages.staff_payments.create', compact('staffList', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id'           => 'required|exists:staff,id',
            'payment_method_id'  => 'required|exists:payroll_payment_methods,id',
            'amount'             => 'required|numeric|min:0.01',
            'payment_date'       => 'required|date',
            'reference_number'   => 'nullable|string|max:100',
            'status'             => 'required|in:pending,completed,failed',
            'notes'              => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            StaffPayment::create([
                'staff_id'           => $request->staff_id,
                'staff_payroll_id'   => null, // No longer linked
                'payment_method_id'  => $request->payment_method_id,
                'amount'             => $request->amount,
                'payment_date'       => $request->payment_date,
                'reference_number'   => $request->reference_number,
                'status'             => $request->status,
                'notes'              => $request->notes,
                'created_by'         => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('admin.staff-payments.index')->with('success', 'Payment recorded successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to save payment: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $payment = StaffPayment::findOrFail($id);
        $staffList = Staff::where('status', 'Active')->orderBy('name')->get();
        $paymentMethods = PayrollPaymentMethod::active()->orderBy('name')->get();

        return view('admin.pages.staff_payments.edit', compact(
            'payment', 'staffList', 'paymentMethods'
        ));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'staff_id'           => 'required|exists:staff,id',
            'payment_method_id'  => 'required|exists:payroll_payment_methods,id',
            'amount'             => 'required|numeric|min:0.01',
            'payment_date'       => 'required|date',
            'reference_number'   => 'nullable|string|max:100',
            'status'             => 'required|in:pending,completed,failed',
            'notes'              => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $payment = StaffPayment::findOrFail($id);
            
            $payment->update([
                'staff_id'           => $request->staff_id,
                'payment_method_id'  => $request->payment_method_id,
                'amount'             => $request->amount,
                'payment_date'       => $request->payment_date,
                'reference_number'   => $request->reference_number,
                'status'             => $request->status,
                'notes'              => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('admin.staff-payments.index')->with('success', 'Payment updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update payment: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $payment = StaffPayment::findOrFail($id);
            $payment->delete();
            return response()->json(['success' => true, 'message' => 'Payment deleted successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
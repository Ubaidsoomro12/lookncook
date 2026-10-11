<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollPaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges.']);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $methods = PayrollPaymentMethod::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.pages.payrolls.payment_methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.pages.payrolls.payment_methods.create');
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'               => 'required|string|max:100|unique:payroll_payment_methods,name',
            'code'               => 'nullable|string|max:50',
            'requires_reference' => 'nullable|boolean',
            'description'        => 'nullable|string|max:255',
            'sort_order'         => 'nullable|integer|min:0',
            'is_active'          => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        PayrollPaymentMethod::create([
            'name'               => $request->name,
            'code'               => $request->code,
            'requires_reference' => $request->has('requires_reference') ? 1 : 0,
            'description'        => $request->description,
            'sort_order'         => $request->sort_order ?? 0,
            'is_active'          => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.payroll-payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function edit($id)
    {
        $method = PayrollPaymentMethod::findOrFail($id);
        return view('admin.pages.payrolls.payment_methods.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $method = PayrollPaymentMethod::findOrFail($id);

        $v = Validator::make($request->all(), [
            'name'               => 'required|string|max:100|unique:payroll_payment_methods,name,' . $id,
            'code'               => 'nullable|string|max:50',
            'requires_reference' => 'nullable|boolean',
            'description'        => 'nullable|string|max:255',
            'sort_order'         => 'nullable|integer|min:0',
            'is_active'          => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $method->update([
            'name'               => $request->name,
            'code'               => $request->code,
            'requires_reference' => $request->has('requires_reference') ? 1 : 0,
            'description'        => $request->description,
            'sort_order'         => $request->sort_order ?? 0,
            'is_active'          => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.payroll-payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $method = PayrollPaymentMethod::findOrFail($id);
            $method->delete();
            return response()->json(['success' => true, 'message' => 'Payment method deleted.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
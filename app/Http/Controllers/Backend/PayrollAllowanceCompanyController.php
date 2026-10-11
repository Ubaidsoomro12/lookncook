<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollAllowanceCompany;
use App\Models\PayrollAllowanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollAllowanceCompanyController extends Controller
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
        $companies = PayrollAllowanceCompany::with('allowanceType')
            ->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.pages.payrolls.allowance_companies.index', compact('companies'));
    }

    public function create()
    {
        $allowanceTypes = PayrollAllowanceType::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.pages.payrolls.allowance_companies.create', compact('allowanceTypes'));
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'allowance_type_id' => 'nullable|exists:payroll_allowance_types,id',
            'name'              => 'required|string|max:150|unique:payroll_allowance_companies,name',
            'contact_person'    => 'nullable|string|max:150',
            'contact_number'    => 'nullable|string|max:50',
            'email'             => 'nullable|email|max:150',
            'notes'             => 'nullable|string',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ]);

        if ($v->fails()) return back()->withErrors($v)->withInput();

        PayrollAllowanceCompany::create([
            'allowance_type_id' => $request->allowance_type_id,
            'name'              => $request->name,
            'contact_person'    => $request->contact_person,
            'contact_number'    => $request->contact_number,
            'email'             => $request->email,
            'notes'             => $request->notes,
            'sort_order'        => $request->sort_order ?? 0,
            'is_active'         => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.payroll-allowance-companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function edit($id)
    {
        $company = PayrollAllowanceCompany::findOrFail($id);
        $allowanceTypes = PayrollAllowanceType::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.pages.payrolls.allowance_companies.edit', compact('company', 'allowanceTypes'));
    }

    public function update(Request $request, $id)
    {
        $company = PayrollAllowanceCompany::findOrFail($id);

        $v = Validator::make($request->all(), [
            'allowance_type_id' => 'nullable|exists:payroll_allowance_types,id',
            'name'              => 'required|string|max:150|unique:payroll_allowance_companies,name,' . $id,
            'contact_person'    => 'nullable|string|max:150',
            'contact_number'    => 'nullable|string|max:50',
            'email'             => 'nullable|email|max:150',
            'notes'             => 'nullable|string',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ]);

        if ($v->fails()) return back()->withErrors($v)->withInput();

        $company->update([
            'allowance_type_id' => $request->allowance_type_id,
            'name'              => $request->name,
            'contact_person'    => $request->contact_person,
            'contact_number'    => $request->contact_number,
            'email'             => $request->email,
            'notes'             => $request->notes,
            'sort_order'        => $request->sort_order ?? 0,
            'is_active'         => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.payroll-allowance-companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $company = PayrollAllowanceCompany::findOrFail($id);
            $company->delete();
            return response()->json(['success' => true, 'message' => 'Company deleted.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
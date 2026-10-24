<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PayrollAllowanceType;
use App\Models\PayrollDeductionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollTypeController extends Controller
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

    /* ============================================================
       Resolve model + view prefix based on route name
       (No reliance on defaults() or route params)
    ============================================================ */
    private function resolve(): array
    {
        $routeName = request()->route() ? request()->route()->getName() : '';
        $kind = str_contains((string) $routeName, 'deduction') ? 'deduction' : 'allowance';

        if ($kind === 'allowance') {
            return [
                'kind'       => 'allowance',
                'model'      => PayrollAllowanceType::class,
                'viewPrefix' => 'admin.pages.payrolls.allowance_types',
                'routeBase'  => 'admin.payroll-allowance-types',
                'label'      => 'Allowance Type',
            ];
        }

        return [
            'kind'       => 'deduction',
            'model'      => PayrollDeductionType::class,
            'viewPrefix' => 'admin.pages.payrolls.deduction_types',
            'routeBase'  => 'admin.payroll-deduction-types',
            'label'      => 'Deduction Type',
        ];
    }

    /* ============================================================
       INDEX  → GET /admin/payroll-allowance-types
    ============================================================ */
    public function index()
    {
        $cfg   = $this->resolve();
        $model = $cfg['model'];

        $types = $model::orderBy('sort_order')->orderBy('name')->get();

        return view($cfg['viewPrefix'] . '.index', [
            'types' => $types,
            'kind'  => $cfg['kind'],
            'cfg'   => $cfg,
        ]);
    }

    /* ============================================================
       CREATE  → GET /admin/payroll-allowance-types/create
    ============================================================ */
    public function create()
    {
        $cfg = $this->resolve();

        return view($cfg['viewPrefix'] . '.create', [
            'kind' => $cfg['kind'],
            'cfg'  => $cfg,
        ]);
    }

    /* ============================================================
       STORE  → POST /admin/payroll-allowance-types/store
    ============================================================ */
    public function store(Request $request)
    {
        $cfg = $this->resolve();

        $v = Validator::make($request->all(), [
            'name'           => 'required|string|max:100|unique:' . (new $cfg['model'])->getTable() . ',name',
            'default_amount' => 'nullable|numeric|min:0',
            'description'    => 'nullable|string|max:255',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $cfg['model']::create([
            'name'           => $request->name,
            'default_amount' => $request->default_amount ?? 0,
            'description'    => $request->description,
            'sort_order'     => $request->sort_order ?? 0,
            'is_active'      => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route($cfg['routeBase'] . '.index')
            ->with('success', $cfg['label'] . ' created successfully.');
    }

    /* ============================================================
       EDIT  → GET /admin/payroll-allowance-types/edit/{id}
       NOTE: Only $id is expected. No Request injection.
    ============================================================ */
    public function edit($id)
    {
        $cfg   = $this->resolve();
        $model = $cfg['model'];
        $type  = $model::findOrFail($id);

        return view($cfg['viewPrefix'] . '.edit', [
            'type' => $type,
            'kind' => $cfg['kind'],
            'cfg'  => $cfg,
        ]);
    }

    /* ============================================================
       UPDATE  → PUT /admin/payroll-allowance-types/update/{id}
       NOTE: Only $id is expected. Request is taken from helper.
    ============================================================ */
    public function update($id)
    {
        $request = request();
        $cfg   = $this->resolve();
        $model = $cfg['model'];
        $type  = $model::findOrFail($id);

        $v = Validator::make($request->all(), [
            'name'           => 'required|string|max:100|unique:' . (new $cfg['model'])->getTable() . ',name,' . $id,
            'default_amount' => 'nullable|numeric|min:0',
            'description'    => 'nullable|string|max:255',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $type->update([
            'name'           => $request->name,
            'default_amount' => $request->default_amount ?? 0,
            'description'    => $request->description,
            'sort_order'     => $request->sort_order ?? 0,
            'is_active'      => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route($cfg['routeBase'] . '.index')
            ->with('success', $cfg['label'] . ' updated successfully.');
    }

    /* ============================================================
       DESTROY  → DELETE /admin/payroll-allowance-types/delete/{id}
    ============================================================ */
    public function destroy($id)
    {
        try {
            $cfg   = $this->resolve();
            $model = $cfg['model'];
            $type  = $model::findOrFail($id);
            $type->delete();

            return response()->json(['success' => true, 'message' => $cfg['label'] . ' deleted.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
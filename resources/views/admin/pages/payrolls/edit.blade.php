{{-- ============================================================
     FILE: resources/views/admin/pages/payrolls/edit.blade.php
     PURPOSE: Edit a monthly payroll — Allowances + Deductions
     LAST UPDATED: 2026-10-11
     ============================================================ --}}
@extends('admin.layouts.master')

@section('title', 'Edit Payroll')

@section('content')

{{-- ── Checkbox styling fix ── --}}
<style>
    .form-check-input {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db !important;
        cursor: pointer;
        transition: all .15s ease;
    }
    .form-check-input:focus {
        border-color: #ff2d7a !important;
        box-shadow: 0 0 0 3px rgba(255,45,122,.15) !important;
    }
    .form-check-input:checked {
        background-color: #ff2d7a !important;
        border-color: #ff2d7a !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
    }
    .form-check-input:hover {
        border-color: #ff2d7a !important;
    }
</style>

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Payroll — {{ $payroll->period_label }}</h4>
            <p class="text-muted small m-0">{{ $payroll->staff?->name ?? '—' }}</p>
        </div>
        <a href="{{ route('admin.payrolls.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Payrolls
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger border-0">
        <ul class="mb-0 small">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.payrolls.update', $payroll->id) }}" method="POST" id="payrollForm">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- TOP: STAFF & PERIOD --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">
                            <i class="fa-solid fa-user-tie me-2" style="color:#ff2d7a;"></i>Staff & Period
                        </h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Staff <span class="text-danger">*</span></label>
                                <select name="staff_id" class="form-select" required>
                                    <option value="">-- Select staff --</option>
                                    @foreach($staffList as $s)
                                        <option value="{{ $s->id }}" @selected(old('staff_id', $payroll->staff_id)==$s->id)>
                                            {{ $s->name }} ({{ $s->employee_id }})
                                            @if($s->designation) — {{ $s->designation }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Month <span class="text-danger">*</span></label>
                                <select name="period_month" class="form-select" required>
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}" @selected(old('period_month', $payroll->period_month)==$m)>
                                            {{ date('F', mktime(0,0,0,$m,1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Year <span class="text-danger">*</span></label>
                                <select name="period_year" class="form-select" required>
                                    @for($y = now()->year + 1; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" @selected(old('period_year', $payroll->period_year)==$y)>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALLOWANCES --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">
                            <i class="fa-solid fa-plus-circle me-2" style="color:#10b981;"></i>Allowances
                        </h6>

                        @if($allowanceTypes->isEmpty())
                            <div class="text-muted small py-3 text-center">No allowance types defined.</div>
                        @else
                            <div class="d-flex flex-column gap-2">
                                @foreach($allowanceTypes as $type)
                                    @php
                                        $has = array_key_exists($type->id, $existingAllowances);
                                        $val = $has ? $existingAllowances[$type->id] : $type->default_amount;
                                        $companyId = $existingAllowanceCompanies[$type->id] ?? null;
                                        $typeCompanies = $companies->where('allowance_type_id', $type->id);
                                    @endphp
                                <div class="p-2 rounded" style="background:#f9fafb;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check m-0 d-flex align-items-center">
                                            <input class="form-check-input allowance-toggle"
                                                   type="checkbox"
                                                   name="allowance_enabled[{{ $type->id }}]"
                                                   value="1"
                                                   id="alw_{{ $type->id }}"
                                                   data-target="alw_input_{{ $type->id }}"
                                                   data-company-wrapper="alw_company_wrap_{{ $type->id }}"
                                                   {{ $has ? 'checked' : '' }}>
                                        </div>
                                        <label for="alw_{{ $type->id }}" class="flex-grow-1 small mb-0 fw-semibold" style="cursor:pointer;">
                                            {{ $type->name }}
                                        </label>
                                        <input type="number"
                                               name="allowance_amount[{{ $type->id }}]"
                                               id="alw_input_{{ $type->id }}"
                                               class="form-control form-control-sm allowance-amount"
                                               value="{{ $val }}"
                                               min="0" step="0.01"
                                               style="width:120px;">
                                    </div>

                                    <div class="mt-2 ms-4"
                                         id="alw_company_wrap_{{ $type->id }}"
                                         style="display:{{ $has ? 'flex' : 'none' }}; align-items:center; gap:8px;">
                                        <label class="small text-muted mb-0"
                                               for="alw_company_{{ $type->id }}"
                                               style="white-space:nowrap;font-size:11px;">
                                            <i class="fa-solid fa-building me-1"></i>Company:
                                        </label>
                                        @if($typeCompanies->isEmpty())
                                            <span class="text-muted small fst-italic">
                                                No companies assigned to "{{ $type->name }}" yet.
                                                <a href="{{ route('admin.payroll-allowance-companies.index') }}" target="_blank">
                                                    Add one →
                                                </a>
                                            </span>
                                        @else
                                            <select name="allowance_company[{{ $type->id }}]"
                                                    id="alw_company_{{ $type->id }}"
                                                    class="form-select form-select-sm"
                                                    style="max-width:280px;">
                                                <option value="">-- None / Direct --</option>
                                                @foreach($typeCompanies as $company)
                                                    <option value="{{ $company->id }}" @selected($companyId == $company->id)>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- DEDUCTIONS --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">
                            <i class="fa-solid fa-minus-circle me-2" style="color:#ef4444;"></i>Deductions
                        </h6>

                        @if($deductionTypes->isEmpty())
                            <div class="text-muted small py-3 text-center">No deduction types defined.</div>
                        @else
                            <div class="d-flex flex-column gap-2">
                                @foreach($deductionTypes as $type)
                                    @php
                                        $has = array_key_exists($type->id, $existingDeductions);
                                        $val = $has ? $existingDeductions[$type->id] : $type->default_amount;
                                    @endphp
                                <div class="d-flex align-items-center gap-2 p-2 rounded" style="background:#f9fafb;">
                                    <div class="form-check m-0 d-flex align-items-center">
                                        <input class="form-check-input deduction-toggle"
                                               type="checkbox"
                                               name="deduction_enabled[{{ $type->id }}]"
                                               value="1"
                                               id="ded_{{ $type->id }}"
                                               data-target="ded_input_{{ $type->id }}"
                                               {{ $has ? 'checked' : '' }}>
                                    </div>
                                    <label for="ded_{{ $type->id }}" class="flex-grow-1 small mb-0 fw-semibold" style="cursor:pointer;">
                                        {{ $type->name }}
                                    </label>
                                    <input type="number"
                                           name="deduction_amount[{{ $type->id }}]"
                                           id="ded_input_{{ $type->id }}"
                                           class="form-control form-control-sm deduction-amount"
                                           value="{{ $val }}"
                                           min="0" step="0.01"
                                           style="width:120px;">
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- LIVE SUMMARY --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background:#111827;color:#f3f4f6;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-uppercase small" style="opacity:.7;letter-spacing:.06em;">
                            <i class="fa-solid fa-calculator me-2"></i>Live Summary
                        </h6>
                        <div class="row g-3 text-center">
                            <div class="col-12 col-md-6">
                                <div style="font-size:11px;opacity:.7;text-transform:uppercase;">Allowances</div>
                                <div id="sumAllow" style="font-size:28px;font-weight:800;color:#10b981;">Rs 0.00</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div style="font-size:11px;opacity:.7;text-transform:uppercase;">Deductions</div>
                                <div id="sumDeduct" style="font-size:28px;font-weight:800;color:#ef4444;">- Rs 0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HIDDEN FIELDS --}}
            <input type="hidden" name="basic_salary"       value="{{ $payroll->basic_salary }}">
            <input type="hidden" name="bonus"              value="{{ $payroll->bonus }}">
            <input type="hidden" name="total_working_days" value="{{ $payroll->total_working_days }}">
            <input type="hidden" name="present_days"       value="{{ $payroll->present_days }}">
            <input type="hidden" name="absent_days"        value="{{ $payroll->absent_days }}">
            <input type="hidden" name="overtime_hours"     value="{{ $payroll->overtime_hours }}">
            <input type="hidden" name="overtime_rate"      value="{{ $payroll->overtime_rate }}">
            <input type="hidden" name="payment_status"     value="{{ $payroll->payment_status }}">
            <input type="hidden" name="payment_date"       value="{{ $payroll->payment_date?->format('Y-m-d') }}">
            <input type="hidden" name="payment_method"     value="{{ $payroll->payment_method }}">
            <input type="hidden" name="notes"              value="{{ $payroll->notes }}">

            {{-- ACTION BUTTONS --}}
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn px-5"
                        style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
                    <i class="fa-solid fa-check me-2"></i>Update Payroll
                </button>
                <a href="{{ route('admin.payrolls.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
(function(){
    'use strict';
    const $ = s => document.querySelector(s);
    const $$ = s => Array.from(document.querySelectorAll(s));

    function money(n){
        return 'Rs ' + (Number(n)||0).toLocaleString(undefined,{
            minimumFractionDigits:2, maximumFractionDigits:2
        });
    }
    function num(el){ return parseFloat(el?.value) || 0; }

    function recalc(){
        let allowanceTotal = 0;
        $$('.allowance-amount').forEach(inp => {
            const toggle = document.getElementById(inp.id.replace('alw_input_', 'alw_'));
            if (toggle && toggle.checked) allowanceTotal += num(inp);
        });

        let deductionTotal = 0;
        $$('.deduction-amount').forEach(inp => {
            const toggle = document.getElementById(inp.id.replace('ded_input_', 'ded_'));
            if (toggle && toggle.checked) deductionTotal += num(inp);
        });

        $('#sumAllow').textContent  = money(allowanceTotal);
        $('#sumDeduct').textContent = '- ' + money(deductionTotal);
    }

    function toggleCompanyWrapper(checkbox){
        const wrapperId = checkbox.dataset.companyWrapper;
        if (!wrapperId) return;
        const wrapper = document.getElementById(wrapperId);
        if (wrapper) wrapper.style.display = checkbox.checked ? 'flex' : 'none';
    }

    $$('.allowance-toggle').forEach(t => {
        const amountInput = document.getElementById(t.dataset.target);
        if (amountInput) amountInput.disabled = !t.checked;

        toggleCompanyWrapper(t);

        t.addEventListener('change', function(){
            const inp = document.getElementById(this.dataset.target);
            if (inp) inp.disabled = !this.checked;
            toggleCompanyWrapper(this);
            recalc();
        });
    });

    $$('.deduction-toggle').forEach(t => {
        const amountInput = document.getElementById(t.dataset.target);
        if (amountInput) amountInput.disabled = !t.checked;

        t.addEventListener('change', function(){
            const inp = document.getElementById(this.dataset.target);
            if (inp) inp.disabled = !this.checked;
            recalc();
        });
    });

    $$('input[type=number]').forEach(el => el.addEventListener('input', recalc));

    recalc();
})();
</script>
@endsection
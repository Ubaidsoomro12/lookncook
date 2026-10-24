@extends('admin.layouts.master')

@section('title', 'Send Payment')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.staff-payments.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Send Payment</h4>
            <p class="text-muted small m-0">Disburse salary or advances to staff</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.staff-payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Staff <span class="text-danger">*</span></label>
                            <select name="staff_id" id="staffSelect" class="form-select" required>
                                <option value="" data-salary="0" data-method="">-- Choose Staff --</option>
                                @foreach($staffList as $s)
                                    <option value="{{ $s->id }}" 
                                            data-salary="{{ $s->salary }}" 
                                            data-method="{{ $s->payment_method_id }}" 
                                            @selected(old('staff_id') == $s->id)>
                                        {{ $s->name }} ({{ $s->employee_id }}) - Salary: Rs {{ number_format($s->salary, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Amount (Rs) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amountInput" class="form-control" step="0.01" min="1" value="{{ old('amount') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method_id" id="paymentMethodSelect" class="form-select" required>
                                    <option value="">-- Select Method --</option>
                                    @foreach($paymentMethods as $m)
                                        <option value="{{ $m->id }}" @selected(old('payment_method_id') == $m->id)>{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Reference / Txn ID</label>
                                <input type="text" name="reference_number" class="form-control" placeholder="e.g. TXN-123456" value="{{ old('reference_number') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="completed">Completed (Paid)</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4" style="background:#ff2d7a; border:none;">
                                <i class="fa-solid fa-paper-plane me-2"></i>Send Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const staffSelect = document.getElementById('staffSelect');
        const amountInput = document.getElementById('amountInput');
        const methodSelect = document.getElementById('paymentMethodSelect');

        staffSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            // Auto-fill salary
            const salary = selectedOption.getAttribute('data-salary');
            if (salary && salary !== '0') {
                amountInput.value = salary;
            } else {
                amountInput.value = '';
            }

            // Auto-fill payment method
            const defaultMethod = selectedOption.getAttribute('data-method');
            if (defaultMethod) {
                methodSelect.value = defaultMethod;
            } else {
                methodSelect.value = '';
            }
        });
    });
</script>
@endsection
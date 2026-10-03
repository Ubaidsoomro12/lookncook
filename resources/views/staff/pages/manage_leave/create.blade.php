@extends('staff.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Apply for Leave</h4>
        <a href="{{ route('staff.manage_leave.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- ===== REMAINING ALERT ===== --}}
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        You have <strong>{{ $remaining }}</strong> leaves remaining this month
        (Total assigned: <strong>{{ $assignment->total_leaves }}</strong>).
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('staff.manage_leave.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Employee Name</label>
                        <input type="text" class="form-control" value="{{ $staff->name }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Employee ID</label>
                        <input type="text" class="form-control" value="{{ $staff->employee_id }}" readonly>
                    </div>
                </div>

                {{-- Leave Type --}}
                <div class="mb-3">
                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                    <input type="text"
                           name="leave_type"
                           id="leave_type"
                           class="form-control"
                           list="leave_type_list"
                           placeholder="Select from list or type your own..."
                           value="{{ old('leave_type') }}"
                           autocomplete="off"
                           required>
                    <datalist id="leave_type_list">
                        <option value="Casual Leave"></option>
                        <option value="Sick Leave"></option>
                        <option value="Annual / Earned Leave"></option>
                        <option value="Unpaid Leave"></option>
                        <option value="Emergency Leave"></option>
                    </datalist>
                    <small class="text-muted">
                        Dropdown se select karo, ya apna khud ka leave type likh do.
                    </small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From Date <span class="text-danger">*</span></label>
                        <input type="date" name="from_date" class="form-control"
                               value="{{ old('from_date') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">To Date <span class="text-danger">*</span></label>
                        <input type="date" name="to_date" class="form-control"
                               value="{{ old('to_date') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason <span class="text-danger">*</span></label>
                    <textarea name="reason" rows="4" class="form-control" required>{{ old('reason') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Submit Leave Request
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
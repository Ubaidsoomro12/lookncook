@extends('admin.layouts.master')

@section('title', 'Edit Staff')
@section('content')
<style>
    /* ✅ Fix 1: Action buttons alignment */


@section('title', 'Edit Staff')

@section('content')


    .staff-form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }
    .staff-form-actions .btn {
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    @media (max-width: 480px) {

        .staff-form-actions {
            flex-direction: column;
        }
        .staff-form-actions .btn {
            width: 100%;
            padding-left: 16px !important;
            padding-right: 16px !important;
        }
    }

    /* ✅ Fix 2: Blue alert box — proper alignment of icon, name, email */
    .staff-linked-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
        word-break: break-word;
    }
    .staff-linked-alert .staff-linked-icon {
        flex-shrink: 0;
        margin-top: 3px;
        font-size: 16px;
    }
    .staff-linked-alert .staff-linked-body {
        flex: 1;
        min-width: 0;   /* very important for text-wrap inside flex */
    }
    .staff-linked-alert .staff-linked-body .name {
        font-weight: 700;
        color: #0c4a6e;
        word-break: break-word;
    }
    .staff-linked-alert .staff-linked-body .email {
        color: #0c4a6e;
        word-break: break-all;
    }
    .staff-linked-alert .staff-linked-hint {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: #6b7280;
    }
    @media (max-width: 576px) {
        .staff-linked-alert {
            align-items: flex-start;
        }
        .staff-linked-alert .staff-linked-hint {
            margin-left: 0;
            margin-top: 6px;
        }

        .staff-form-actions { flex-direction: column; }
        .staff-form-actions .btn { width: 100%; }
    }

    .staff-linked-alert {
        display: flex; align-items: flex-start; gap: 12px;
        flex-wrap: wrap; word-break: break-word;
    }
    .staff-linked-alert .staff-linked-icon {
        flex-shrink: 0; margin-top: 3px; font-size: 16px;
    }
    .staff-linked-alert .staff-linked-body { flex: 1; min-width: 0; }
    .staff-linked-alert .staff-linked-body .name { font-weight: 700; color: #0c4a6e; }
    .staff-linked-alert .staff-linked-body .email { color: #0c4a6e; word-break: break-all; }
    .staff-linked-alert .staff-linked-hint {
        display: block; margin-top: 4px; font-size: 12px; color: #6b7280;

    }
</style>

<div class="container py-4" style="max-width:900px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.staff.index') }}" class="btn btn-light rounded-circle"><i class="fa-solid fa-arrow-left"></i></a>
        <h1 class="h2">Edit Staff</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-4">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <!-- Hidden user_id (cannot be changed here) -->
                <input type="hidden" name="user_id" value="{{ $staff->user_id }}">

                <!-- ✅ FIXED ALERT: proper alignment for icon + name + email + hint -->

                <input type="hidden" name="user_id" value="{{ $staff->user_id }}">


                <div class="alert alert-info staff-linked-alert">
                    <i class="fa-solid fa-user staff-linked-icon"></i>
                    <div class="staff-linked-body">
                        <div>
                            Staff linked to user:
                            <span class="name">{{ $staff->user->name ?? 'Unknown' }}</span>
                            @if($staff->user && $staff->user->email)
                                <span class="email">({{ $staff->user->email }})</span>
                            @endif
                        </div>
                        <span class="staff-linked-hint">To change user, create a new staff record.</span>
                    </div>
                </div>

                <div class="row g-4">

                    <!-- Basic Information -->
                    <div class="col-12"><h5 class="border-bottom pb-2">Basic Information</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Employee ID <span class="text-muted">(optional)</span></label>


                    <div class="col-12"><h5 class="border-bottom pb-2">Basic Information</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Employee ID</label>

                        <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $staff->employee_id) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>

                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $staff->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $staff->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $staff->phone) }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CNIC <span class="text-muted">(optional)</span></label>
                        <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror" value="{{ old('cnic', $staff->cnic) }}">
                        @error('cnic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <input type="text" name="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic" class="form-control" value="{{ old('cnic', $staff->cnic) }}">

                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>

                            <option value="Male" {{ old('gender', $staff->gender)=='Male'?'selected':'' }}>Male</option>
                            <option value="Female" {{ old('gender', $staff->gender)=='Female'?'selected':'' }}>Female</option>
                            <option value="Other" {{ old('gender', $staff->gender)=='Other'?'selected':'' }}>Other</option>

                            @foreach(['Male','Female','Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $staff->gender)==$g?'selected':'' }}>{{ $g }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $staff->date_of_birth?->format('Y-m-d')) }}">
                    </div>



                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Emergency Contact</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $staff->emergency_contact_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="emergency_contact_number" class="form-control" value="{{ old('emergency_contact_number', $staff->emergency_contact_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $staff->blood_group)==$bg?'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Images & Documents</h5></div>
                    @foreach(['image'=>'Profile Image','cnic_front_image'=>'CNIC Front','cnic_back_image'=>'CNIC Back','cv_image'=>'CV/Resume','appointment_letter_image'=>'Appointment Letter'] as $field=>$label)
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }}</label>
                            @if($staff->$field)
                                <div class="d-flex align-items-center gap-3 p-2 bg-light border rounded-3 mb-2">
                                    @if(str_contains($field, 'image') || str_contains($field, 'cnic'))
                                        <img src="{{ asset($staff->$field) }}" alt="Current" class="rounded object-fit-cover border" style="width:50px;height:50px;">
                                    @else
                                        <i class="fa-solid fa-file-pdf text-danger fs-4"></i>
                                    @endif
                                    <span class="small text-muted text-truncate" style="max-width:150px;">{{ basename($staff->$field) }}</span>
                                </div>
                            @endif

                            <input type="file" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" accept="{{ $field=='cv_image'||$field=='appointment_letter_image' ? '.pdf,.png,.jpeg,.jpg,.jfif' : 'image/*' }}">
                            <div class="form-text">Leave empty to keep current file.</div>
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach

                    <!-- Address -->
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $staff->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Employment -->
                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Employment Details</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                        <select name="employee_type" class="form-select @error('employee_type') is-invalid @enderror" required>
                            <option value="Full-time" {{ old('employee_type', $staff->employee_type)=='Full-time'?'selected':'' }}>Full-time</option>
                            <option value="Part-time" {{ old('employee_type', $staff->employee_type)=='Part-time'?'selected':'' }}>Part-time</option>
                            <option value="Contract" {{ old('employee_type', $staff->employee_type)=='Contract'?'selected':'' }}>Contract</option>
                        </select>
                        @error('employee_type')<div class="invalid-feedback">{{ $message }}</div>@enderror

                            <input type="file" name="{{ $field }}" class="form-control" accept="{{ in_array($field,['cv_image','appointment_letter_image']) ? '.pdf,.png,.jpeg,.jpg,.jfif' : 'image/*' }}">
                            <div class="form-text">Leave empty to keep current file.</div>
                        </div>
                    @endforeach

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $staff->address) }}</textarea>
                    </div>

                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Employment Details</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                        <select name="employee_type" class="form-select" required>
                            @foreach(['Full-time','Part-time','Contract'] as $t)
                                <option value="{{ $t }}" {{ old('employee_type', $staff->employee_type)==$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select">
                            <option value="">Select</option>
                            @foreach(['Kitchen','Front of House','Delivery','Management'] as $dept)
                                <option value="{{ $dept }}" {{ old('department', $staff->department)==$dept?'selected':'' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">

                        <label class="form-label">Designation (Job Title)</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $staff->designation) }}" placeholder="e.g. Senior Waiter">

                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $staff->designation) }}">

                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Branch</label>
                        <select name="branch" class="form-select">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->branch_name }}" {{ old('branch', $staff->branch)==$branch->branch_name?'selected':'' }}>{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Work Shift</label>
                        <select name="work_shift" class="form-select">
                            <option value="">Select</option>

                            <option value="Morning" {{ old('work_shift', $staff->work_shift)=='Morning'?'selected':'' }}>Morning</option>
                            <option value="Evening" {{ old('work_shift', $staff->work_shift)=='Evening'?'selected':'' }}>Evening</option>
                            <option value="Night" {{ old('work_shift', $staff->work_shift)=='Night'?'selected':'' }}>Night</option>
                        </select>
                    </div>

                    <!-- ===== NEW SHIFT TIME FIELDS ===== -->
                    <div class="col-md-6">
                        <label class="form-label">Shift Start Time <span class="text-muted">(optional)</span></label>
                        <input type="time" name="shift_start_time" class="form-control" value="{{ old('shift_start_time', $staff->shift_start_time) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shift End Time <span class="text-muted">(optional)</span></label>
                        <input type="time" name="shift_end_time" class="form-control" value="{{ old('shift_end_time', $staff->shift_end_time) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Reporting Manager <span class="text-muted">(optional)</span></label>

                            @foreach(['Morning','Evening','Night'] as $ws)
                                <option value="{{ $ws }}" {{ old('work_shift', $staff->work_shift)==$ws?'selected':'' }}>{{ $ws }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shift Start Time</label>
                        <input type="time" name="shift_start_time" class="form-control" value="{{ old('shift_start_time', $staff->shift_start_time) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shift End Time</label>
                        <input type="time" name="shift_end_time" class="form-control" value="{{ old('shift_end_time', $staff->shift_end_time) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reporting Manager</label>

                        <select name="reporting_manager_id" class="form-select">
                            <option value="">None</option>
                            @foreach($managers as $m)
                                <option value="{{ $m->id }}" {{ old('reporting_manager_id', $staff->reporting_manager_id)==$m->id?'selected':'' }}>{{ $m->name }} ({{ $m->employee_id ?? 'ID:'.$m->id }})</option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Salary -->
                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Salary & Payroll</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" name="hire_date" class="form-control @error('hire_date') is-invalid @enderror" value="{{ old('hire_date', $staff->hire_date?->format('Y-m-d')) }}" required>
                        @error('hire_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Monthly Salary (PKR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary', $staff->salary) }}" required>
                        @error('salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Salary Type <span class="text-danger">*</span></label>
                        <select name="salary_type" class="form-select @error('salary_type') is-invalid @enderror" required>
                            <option value="Fixed" {{ old('salary_type', $staff->salary_type)=='Fixed'?'selected':'' }}>Fixed</option>
                            <option value="Hourly" {{ old('salary_type', $staff->salary_type)=='Hourly'?'selected':'' }}>Hourly</option>
                            <option value="Commission" {{ old('salary_type', $staff->salary_type)=='Commission'?'selected':'' }}>Commission</option>
                        </select>
                        @error('salary_type')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Salary & Payroll</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', $staff->hire_date?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Monthly Salary (PKR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary', $staff->salary) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Salary Type <span class="text-danger">*</span></label>
                        <select name="salary_type" class="form-select" required>
                            @foreach(['Fixed','Hourly','Commission'] as $st)
                                <option value="{{ $st }}" {{ old('salary_type', $staff->salary_type)==$st?'selected':'' }}>{{ $st }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hourly Rate (if applicable)</label>
                        <input type="number" step="0.01" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $staff->hourly_rate) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Account No.</label>
                        <input type="text" name="bank_account_no" class="form-control" value="{{ old('bank_account_no', $staff->bank_account_no) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $staff->bank_name) }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status', $staff->status)=='Active'?'selected':'' }}>Active</option>
                            <option value="On Leave" {{ old('status', $staff->status)=='On Leave'?'selected':'' }}>On Leave</option>
                            <option value="Terminated" {{ old('status', $staff->status)=='Terminated'?'selected':'' }}>Terminated</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- ✅ FIXED Submit Buttons -->


                    {{-- ⭐ PAYMENT METHOD --}}
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label m-0">Payment Method</label>
                            <a href="{{ route('admin.payroll-payment-methods.index') }}" target="_blank"
                               class="small text-decoration-none">
                                <i class="fa-solid fa-gear"></i> Manage
                            </a>
                        </div>
                        <select name="payment_method_id" id="paymentMethodSelect" class="form-select">
                            <option value="">-- Select payment method --</option>
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->id }}"
                                        data-code="{{ strtoupper($pm->code ?? '') }}"
                                        @selected(old('payment_method_id', $staff->payment_method_id)==$pm->id)>
                                    {{ $pm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6"></div>

                    <div class="col-12">
                        <div id="methodFieldsWrapper" style="display:none;">
                            <div class="card border-0" style="background:#f9fafb;border-radius:12px;">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3 small text-uppercase text-muted" id="methodFieldsTitle">
                                        <i class="fa-solid fa-credit-card me-2" style="color:#ff2d7a;"></i>
                                        Payment Method Details
                                    </h6>
                                    <div class="row g-3" id="methodFieldsContainer"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            @foreach(['Active','On Leave','Terminated'] as $s)
                                <option value="{{ $s }}" {{ old('status', $staff->status)==$s?'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-12">
                        <div class="staff-form-actions">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Update Staff</button>
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
(function(){
    'use strict';

    const METHOD_FIELDS = {
        'CASH': [],
        'BANK': [
            { key: 'bank_name',      label: 'Bank Name',       type: 'text',     required: true  },
            { key: 'account_title',  label: 'Account Title',   type: 'text',     required: true  },
            { key: 'account_number', label: 'Account Number',  type: 'text',     required: true  },
            { key: 'iban',           label: 'IBAN (optional)', type: 'text',     required: false }
        ],
        'CHEQUE': [
            { key: 'bank_name',      label: 'Bank Name',            type: 'text', required: true  },
            { key: 'cheque_number',  label: 'Cheque No. (optional)', type: 'text', required: false }
        ],
        'EASYPAISA': [
            { key: 'mobile_number',  label: 'Easypaisa Mobile Number', type: 'text', required: true },
            { key: 'account_title',  label: 'Account Title',           type: 'text', required: true }
        ],
        'JAZZCASH': [
            { key: 'mobile_number',  label: 'JazzCash Mobile Number', type: 'text', required: true },
            { key: 'account_title',  label: 'Account Title',           type: 'text', required: true }
        ],
        'OTHER': [
            { key: 'custom_label',   label: 'Label / Field Name', type: 'text',     required: true },
            { key: 'custom_value',   label: 'Details',            type: 'textarea', required: true }
        ]
    };

    const methodSelect = document.getElementById('paymentMethodSelect');
    const wrapper = document.getElementById('methodFieldsWrapper');
    const container = document.getElementById('methodFieldsContainer');
    const titleEl = document.getElementById('methodFieldsTitle');

    // Saved details (or old input if validation failed)
    const OLD_DETAILS = @json(old('payment_details', $staff->payment_details ?? []));

    function renderFields(code){
        container.innerHTML = '';

        if (!code){
            wrapper.style.display = 'none';
            return;
        }

        const fields = METHOD_FIELDS[code] || [];

        if (fields.length === 0){
            wrapper.style.display = 'block';
            titleEl.innerHTML = '<i class="fa-solid fa-credit-card me-2" style="color:#ff2d7a;"></i> Payment Method Details';
            container.innerHTML = `
                <div class="col-12">
                    <div class="text-muted small">
                        <i class="fa-solid fa-info-circle me-1"></i>
                        This method requires no additional details.
                    </div>
                </div>`;
            return;
        }

        wrapper.style.display = 'block';
        const methodName = methodSelect.options[methodSelect.selectedIndex].textContent.trim();
        titleEl.innerHTML = `<i class="fa-solid fa-credit-card me-2" style="color:#ff2d7a;"></i> ${methodName} — Details`;

        fields.forEach(f => {
            const val = OLD_DETAILS && OLD_DETAILS[f.key] ? String(OLD_DETAILS[f.key]) : '';
            const req = f.required ? 'required' : '';
            const star = f.required ? ' <span class="text-danger">*</span>' : '';

            if (f.type === 'textarea'){
                container.innerHTML += `
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">${f.label}${star}</label>
                        <textarea name="payment_details[${f.key}]" class="form-control" rows="2" ${req}>${val}</textarea>
                    </div>`;
            } else {
                container.innerHTML += `
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">${f.label}${star}</label>
                        <input type="text" name="payment_details[${f.key}]" class="form-control" value="${val}" ${req}>
                    </div>`;
            }
        });
    }

    methodSelect?.addEventListener('change', function(){
        const opt = this.options[this.selectedIndex];
        const code = (opt.dataset.code || '').toUpperCase();
        renderFields(code);
    });

    // Initial render
    if (methodSelect && methodSelect.value){
        const opt = methodSelect.options[methodSelect.selectedIndex];
        renderFields((opt.dataset.code || '').toUpperCase());
    }
})();
</script>
>>>>>>> muzamil_dev
@endsection
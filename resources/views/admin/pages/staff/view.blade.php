@extends('admin.layouts.master')

@section('title', 'View Staff')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h2">{{ $staff->name }}</h1>
        <div>
            <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    @php
        $roles = [1=>'Admin',2=>'User',3=>'Manager',4=>'Waiter',5=>'Chef',6=>'Cashier',7=>'Cleaner',8=>'Delivery Rider'];
        $paymentDetails = $staff->payment_details ?? [];
    @endphp

    <div class="row g-4">

        {{-- PERSONAL --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Personal Details</h5>
                    <table class="table table-borderless mb-0">
                        <tr><th>User Account</th><td>{{ $staff->user->name ?? '—' }} ({{ $staff->user->email ?? '—' }})</td></tr>
                        <tr><th>Role</th><td>{{ $roles[$staff->user->role_id ?? 0] ?? '—' }}</td></tr>
                        <tr><th>Employee ID</th><td>{{ $staff->employee_id ?? '—' }}</td></tr>
                        <tr><th>Name</th><td>{{ $staff->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $staff->email }}</td></tr>
                        <tr><th>Phone</th><td>{{ $staff->phone }}</td></tr>
                        <tr><th>Gender</th><td>{{ $staff->gender ?? '—' }}</td></tr>
                        <tr><th>Date of Birth</th><td>{{ $staff->date_of_birth?->format('d M Y') ?? '—' }}</td></tr>
                        <tr><th>CNIC</th><td>{{ $staff->cnic ?? '—' }}</td></tr>
                        <tr><th>Blood Group</th><td>{{ $staff->blood_group ?? '—' }}</td></tr>
                        <tr><th>Address</th><td>{{ $staff->address ?? '—' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- EMPLOYMENT --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Employment</h5>
                    <table class="table table-borderless mb-0">
                        <tr><th>Employee Type</th><td>{{ $staff->employee_type }}</td></tr>
                        <tr><th>Department</th><td>{{ $staff->department ?? '—' }}</td></tr>
                        <tr><th>Designation</th><td>{{ $staff->designation ?? '—' }}</td></tr>
                        <tr><th>Branch</th><td>{{ $staff->branch ?? '—' }}</td></tr>
                        <tr><th>Work Shift</th><td>{{ $staff->work_shift ?? '—' }}</td></tr>
                        <tr><th>Shift Start</th><td>{{ $staff->shift_start_time ?? '—' }}</td></tr>
                        <tr><th>Shift End</th><td>{{ $staff->shift_end_time ?? '—' }}</td></tr>
                        <tr><th>Reporting Manager</th><td>{{ $staff->manager->name ?? '—' }}</td></tr>
                        <tr><th>Hire Date</th><td>{{ $staff->hire_date?->format('d M Y') }}</td></tr>
                        <tr><th>Salary (PKR)</th><td>{{ number_format($staff->salary, 2) }}</td></tr>
                        <tr><th>Salary Type</th><td>{{ $staff->salary_type }}</td></tr>
                        <tr><th>Hourly Rate</th><td>{{ $staff->hourly_rate ?? '—' }}</td></tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $staff->status=='Active'?'success':($staff->status=='On Leave'?'warning text-dark':'secondary') }}">
                                    {{ $staff->status }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- PAYMENT METHOD --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title m-0">
                            <i class="fa-solid fa-credit-card me-2" style="color:#ff2d7a;"></i>
                            Payment Details
                        </h5>
                        <a href="{{ route('admin.payroll-payment-methods.index') }}" target="_blank"
                           class="small text-decoration-none">
                            <i class="fa-solid fa-gear"></i> Manage Methods
                        </a>
                    </div>

                    @if(!$staff->paymentMethod)
                        <div class="alert alert-warning border-0 mb-0">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            No payment method assigned. <a href="{{ route('admin.staff.edit', $staff->id) }}">Assign one now</a>.
                        </div>
                    @else
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100" style="background:#fff5f8;">
                                    <div class="small text-uppercase text-muted fw-bold mb-1">Method</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-circle-check" style="color:#ff2d7a;"></i>
                                        <span class="fw-bold" style="font-size:16px;">{{ $staff->paymentMethod->name }}</span>
                                    </div>
                                    @if($staff->paymentMethod->code)
                                        <div class="small text-muted mt-1">
                                            Code: <code>{{ $staff->paymentMethod->code }}</code>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($paymentDetails))
                                @foreach($paymentDetails as $key => $value)
                                    @if(!is_null($value) && $value !== '')
                                        <div class="col-md-4">
                                            <div class="border rounded-3 p-3 h-100" style="background:#f9fafb;">
                                                <div class="small text-uppercase text-muted fw-bold mb-1">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </div>
                                                <div class="fw-semibold" style="word-break:break-word;">
                                                    {{ $value }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-md-8">
                                    <div class="border rounded-3 p-3 h-100" style="background:#f9fafb;">
                                        <div class="small text-muted">
                                            <i class="fa-solid fa-info-circle me-1"></i>
                                            This payment method has no additional details saved.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- DOCUMENTS --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Documents</h5>
                    <div class="row g-3">
                        @php
                            $hasDoc = false;
                        @endphp
                        @foreach(['image'=>'Profile','cnic_front_image'=>'CNIC Front','cnic_back_image'=>'CNIC Back','cv_image'=>'CV','appointment_letter_image'=>'Appointment Letter'] as $field=>$label)
                            @if($staff->$field)
                                @php $hasDoc = true; @endphp
                                <div class="col-6 col-md-3 col-lg-2">
                                    <a href="{{ asset($staff->$field) }}" target="_blank" class="d-block text-center text-decoration-none">
                                        @if(str_contains($field, 'image') || str_contains($field, 'cnic'))
                                            <img src="{{ asset($staff->$field) }}" class="img-thumbnail" style="height:90px;width:100%;object-fit:cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center border rounded" style="height:90px;background:#fff5f8;">
                                                <i class="fa-solid fa-file-pdf fs-2 text-danger"></i>
                                            </div>
                                        @endif
                                        <span class="small d-block mt-1 text-dark fw-semibold">{{ $label }}</span>
                                    </a>
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasDoc)
                            <div class="col-12">
                                <div class="text-muted small py-3 text-center">
                                    <i class="fa-solid fa-folder-open me-1"></i>
                                    No documents uploaded yet.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
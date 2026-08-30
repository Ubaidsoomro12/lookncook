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
    @php $roles = [1=>'Admin',2=>'User',3=>'Manager',4=>'Waiter',5=>'Chef',6=>'Cashier',7=>'Cleaner',8=>'Delivery Rider']; @endphp
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Personal Details</h5>
                    <table class="table table-borderless">
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
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Employment</h5>
                    <table class="table table-borderless">
                        <tr><th>Employee Type</th><td>{{ $staff->employee_type }}</td></tr>
                        <tr><th>Department</th><td>{{ $staff->department ?? '—' }}</td></tr>
                        <tr><th>Designation</th><td>{{ $staff->designation ?? '—' }}</td></tr>
                        <tr><th>Branch</th><td>{{ $staff->branch ?? '—' }}</td></tr>
                        <tr><th>Work Shift</th><td>{{ $staff->work_shift ?? '—' }}</td></tr>
                        <tr><th>Reporting Manager</th><td>{{ $staff->manager->name ?? '—' }}</td></tr>
                        <tr><th>Hire Date</th><td>{{ $staff->hire_date?->format('d M Y') }}</td></tr>
                        <tr><th>Salary (PKR)</th><td>{{ number_format($staff->salary, 2) }}</td></tr>
                        <tr><th>Salary Type</th><td>{{ $staff->salary_type }}</td></tr>
                        <tr><th>Hourly Rate</th><td>{{ $staff->hourly_rate ?? '—' }}</td></tr>
                        <tr><th>Bank</th><td>{{ $staff->bank_name }} ({{ $staff->bank_account_no }})</td></tr>
                        <tr><th>Status</th><td><span class="badge bg-{{ $staff->status=='Active'?'success':($staff->status=='On Leave'?'warning':'secondary') }}">{{ $staff->status }}</span></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- Documents -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Documents</h5>
                    <div class="row g-3">
                        @foreach(['image'=>'Profile','cnic_front_image'=>'CNIC Front','cnic_back_image'=>'CNIC Back','cv_image'=>'CV','appointment_letter_image'=>'Appointment Letter'] as $field=>$label)
                            @if($staff->$field)
                                <div class="col-md-2">
                                    <a href="{{ asset($staff->$field) }}" target="_blank" class="d-block text-center">
                                        @if(str_contains($field, 'image') || str_contains($field, 'cnic'))
                                            <img src="{{ asset($staff->$field) }}" class="img-thumbnail" style="height:80px;object-fit:cover;">
                                        @else
                                            <i class="fa-solid fa-file-pdf fs-1 text-danger"></i>
                                        @endif
                                        <span class="small d-block">{{ $label }}</span>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
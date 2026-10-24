@extends('admin.layouts.master')
@section('title', 'Add Staff')
@section('content')
<div class="container py-4" style="max-width:900px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.staff.index') }}" class="btn btn-light rounded-circle"><i class="fa-solid fa-arrow-left"></i></a>
        <h1 class="h2">Add New Staff</h1>
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
            <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">

                    <!-- User Selection (AJAX Search) -->
                    <div class="col-12">
                        <h5 class="border-bottom pb-2">User Account</h5>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Select User <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="userSearchInput" class="form-control" placeholder="Search user by name or email..." autocomplete="off">
                            <input type="hidden" name="user_id" id="selectedUserId" value="{{ old('user_id') }}">
                            <button class="btn btn-outline-secondary" type="button" id="clearUserBtn"><i class="fa-solid fa-times"></i></button>
                        </div>
                        <div id="userSearchResults" class="mt-2" style="max-height:200px; overflow-y:auto; display:none; border:1px solid #e5e7eb; border-radius:8px;"></div>
                        <div id="selectedUserDisplay" class="mt-2">
                            @if(old('user_id'))
                                @php $selectedUser = \App\Models\User::find(old('user_id')); @endphp
                                @if($selectedUser)
                                    <span class="badge bg-primary p-2">Selected: {{ $selectedUser->name }} ({{ $selectedUser->email }})</span>
                                @endif
                            @endif
                        </div>
                        @error('user_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Basic Information -->
                    <div class="col-12 mt-3"><h5 class="border-bottom pb-2">Basic Information</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Employee ID <span class="text-muted">(optional)</span></label>
                        <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CNIC <span class="text-muted">(optional)</span></label>
                        <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror" value="{{ old('cnic') }}">
                        @error('cnic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                            <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                            <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                    </div>

                    <!-- Emergency Contact -->
                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Emergency Contact</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="emergency_contact_number" class="form-control" value="{{ old('emergency_contact_number') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Images -->
                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Images & Documents</h5></div>
                    @foreach(['image'=>'Profile Image','cnic_front_image'=>'CNIC Front','cnic_back_image'=>'CNIC Back','cv_image'=>'CV/Resume','appointment_letter_image'=>'Appointment Letter'] as $field=>$label)
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" accept="{{ $field=='cv_image'||$field=='appointment_letter_image' ? '.pdf,.png,.jpeg,.jpg,.jfif' : 'image/*' }}">
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Max 2MB (images) / 5MB (PDF).</div>
                        </div>
                    @endforeach

                    <!-- Address -->
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Employment -->
                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Employment Details</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                        <select name="employee_type" class="form-select @error('employee_type') is-invalid @enderror" required>
                            <option value="Full-time" {{ old('employee_type')=='Full-time'?'selected':'' }}>Full-time</option>
                            <option value="Part-time" {{ old('employee_type')=='Part-time'?'selected':'' }}>Part-time</option>
                            <option value="Contract" {{ old('employee_type')=='Contract'?'selected':'' }}>Contract</option>
                        </select>
                        @error('employee_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select">
                            <option value="">Select</option>
                            @foreach(['Kitchen','Front of House','Delivery','Management'] as $dept)
                                <option value="{{ $dept }}" {{ old('department')==$dept?'selected':'' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Designation (Job Title)</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation') }}" placeholder="e.g. Senior Waiter">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Branch</label>
                        <select name="branch" class="form-select">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->branch_name }}" {{ old('branch')==$branch->branch_name?'selected':'' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Work Shift</label>
                        <select name="work_shift" class="form-select">
                            <option value="">Select</option>
                            <option value="Morning" {{ old('work_shift')=='Morning'?'selected':'' }}>Morning</option>
                            <option value="Evening" {{ old('work_shift')=='Evening'?'selected':'' }}>Evening</option>
                            <option value="Night" {{ old('work_shift')=='Night'?'selected':'' }}>Night</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reporting Manager <span class="text-muted">(optional)</span></label>
                        <select name="reporting_manager_id" class="form-select">
                            <option value="">None</option>
                            @foreach($managers as $m)
                                <option value="{{ $m->id }}" {{ old('reporting_manager_id')==$m->id?'selected':'' }}>{{ $m->name }} ({{ $m->employee_id ?? 'ID:'.$m->id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Salary -->
                    <div class="col-12 mt-2"><h5 class="border-bottom pb-2">Salary & Payroll</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" name="hire_date" class="form-control @error('hire_date') is-invalid @enderror" value="{{ old('hire_date') }}" required>
                        @error('hire_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Monthly Salary (PKR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}" required>
                        @error('salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Salary Type <span class="text-danger">*</span></label>
                        <select name="salary_type" class="form-select @error('salary_type') is-invalid @enderror" required>
                            <option value="Fixed" {{ old('salary_type')=='Fixed'?'selected':'' }}>Fixed</option>
                            <option value="Hourly" {{ old('salary_type')=='Hourly'?'selected':'' }}>Hourly</option>
                            <option value="Commission" {{ old('salary_type')=='Commission'?'selected':'' }}>Commission</option>
                        </select>
                        @error('salary_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hourly Rate (if applicable)</label>
                        <input type="number" step="0.01" name="hourly_rate" class="form-control" value="{{ old('hourly_rate') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Account No.</label>
                        <input type="text" name="bank_account_no" class="form-control" value="{{ old('bank_account_no') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status')=='Active'?'selected':'' }}>Active</option>
                            <option value="On Leave" {{ old('status')=='On Leave'?'selected':'' }}>On Leave</option>
                            <option value="Terminated" {{ old('status')=='Terminated'?'selected':'' }}>Terminated</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Submit -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill">Save Staff</button>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary btn-lg px-5 rounded-pill">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearchInput');
    const resultsDiv = document.getElementById('userSearchResults');
    const selectedUserId = document.getElementById('selectedUserId');
    const selectedDisplay = document.getElementById('selectedUserDisplay');
    const clearBtn = document.getElementById('clearUserBtn');
    let timer;

    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }
        timer = setTimeout(() => {
            fetch("{{ route('admin.staff.search-users') }}?q=" + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    if (data.users.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-2 text-muted">No users found.</div>';
                        resultsDiv.style.display = 'block';
                        return;
                    }
                    let html = '<ul class="list-group list-group-flush">';
                    data.users.forEach(user => {
                        html += `<li class="list-group-item list-group-item-action" style="cursor:pointer;" data-id="${user.id}" data-name="${user.name}" data-email="${user.email}">
                                    <strong>${user.name}</strong> (${user.email}) - <span class="badge bg-secondary">${user.role_name}</span>
                                </li>`;
                    });
                    html += '</ul>';
                    resultsDiv.innerHTML = html;
                    resultsDiv.style.display = 'block';

                    resultsDiv.querySelectorAll('li').forEach(li => {
                        li.addEventListener('click', function() {
                            const id = this.dataset.id;
                            const name = this.dataset.name;
                            const email = this.dataset.email;
                            selectedUserId.value = id;
                            searchInput.value = name + ' (' + email + ')';
                            selectedDisplay.innerHTML = `<span class="badge bg-primary p-2">Selected: ${name} (${email})</span>`;
                            resultsDiv.style.display = 'none';
                        });
                    });
                })
                .catch(() => {});
        }, 300);
    });

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        selectedUserId.value = '';
        selectedDisplay.innerHTML = '';
        resultsDiv.style.display = 'none';
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#userSearchInput') && !e.target.closest('#userSearchResults')) {
            resultsDiv.style.display = 'none';
        }
    });
});
</script>
@endsection
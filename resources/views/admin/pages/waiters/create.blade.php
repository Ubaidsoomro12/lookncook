@extends('admin.layouts.master')
@section('title', 'Create | Waiter')

@section('content')
<div class="container py-4" style="max-width: 900px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.waiter.index') }}"
            class="btn btn-light rounded-circle d-flex align-items-center justify-content-center"
            style="width: 40px; height: 40px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="h2 fw-bold text-dark mb-0">Add New Waiter</h1>
    </div>

    @if($errors->any())
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
        <p class="fw-semibold mb-1">Please fix the following:</p>
        <ul class="list-unstyled mb-0">
            @foreach($errors->all() as $error)
            <li><i class="fa-solid fa-circle-exclamation me-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.waiter.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-12">
                        <h5 class="fw-bold text-primary border-bottom pb-2">Basic Information</h5>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('name') ? 'border-danger' : '' }}"
                            placeholder="e.g. John Doe">
                        @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('email') ? 'border-danger' : '' }}"
                            placeholder="waiter@example.com">
                        @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            Phone Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('phone') ? 'border-danger' : '' }}"
                            placeholder="+92 300 1234567">
                        @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            CNIC <span class="text-muted small">(Optional)</span>
                        </label>
                        <input type="text" name="cnic" value="{{ old('cnic') }}"
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('cnic') ? 'border-danger' : '' }}"
                            placeholder="e.g. 12345-1234567-1">
                        @error('cnic')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Images Section -->
                    <div class="col-12 mt-3">
                        <h5 class="fw-bold text-primary border-bottom pb-2">Images & Documents</h5>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            Profile Image <span class="text-muted small">(Optional)</span>
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('image') ? 'border-danger' : '' }}">
                        <div class="text-muted small mt-1">JPG, PNG, GIF or WEBP. Max 2MB.</div>
                        @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            CNIC Front Image <span class="text-muted small">(Optional)</span>
                        </label>
                        <input type="file" name="cnic_front_image" accept="image/*"
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('cnic_front_image') ? 'border-danger' : '' }}">
                        <div class="text-muted small mt-1">JPG, PNG, GIF or WEBP. Max 2MB.</div>
                        @error('cnic_front_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            CNIC Back Image <span class="text-muted small">(Optional)</span>
                        </label>
                        <input type="file" name="cnic_back_image" accept="image/*"
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('cnic_back_image') ? 'border-danger' : '' }}">
                        <div class="text-muted small mt-1">JPG, PNG, GIF or WEBP. Max 2MB.</div>
                        @error('cnic_back_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            CV / Resume <span class="text-muted small">(Optional)</span>
                        </label>
                        <input type="file" name="cv_image" accept=".pdf,.png,.jpeg,.jpg,.jfif"
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('cv_image') ? 'border-danger' : '' }}">
                        <div class="text-muted small mt-1">PDF, PNG, JPEG, JPG, or JFIF. Max 5MB.</div>
                        @error('cv_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            Appointment Letter <span class="text-muted small">(Optional)</span>
                        </label>
                        <input type="file" name="appointment_letter_image" accept=".pdf,.png,.jpeg,.jpg,.jfif"
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('appointment_letter_image') ? 'border-danger' : '' }}">
                        <div class="text-muted small mt-1">PDF, PNG, JPEG, JPG, or JFIF. Max 5MB.</div>
                        @error('appointment_letter_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                  

                    <!-- Address & Other Details -->
                    <div class="col-12 mt-3">
                        <h5 class="fw-bold text-primary border-bottom pb-2">Other Details</h5>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-dark">Address</label>
                        <textarea name="address" rows="2"
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('address') ? 'border-danger' : '' }}"
                            placeholder="Waiter's residential address">{{ old('address') }}</textarea>
                        @error('address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            Hire Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="hire_date" value="{{ old('hire_date') }}" required
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('hire_date') ? 'border-danger' : '' }}">
                        @error('hire_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">
                            Salary (PKR) <span class="text-danger">*</span>
                        </label>
                        <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" required
                            class="form-control form-control-lg rounded-3 border {{ $errors->has('salary') ? 'border-danger' : '' }}"
                            placeholder="e.g. 25000">
                        @error('salary')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium text-dark">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" required
                            class="form-select form-select-lg rounded-3 border {{ $errors->has('status') ? 'border-danger' : '' }}">
                            <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select status</option>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="d-flex gap-3 pt-2">
                            <button type="submit"
                                class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm"
                                style="background: linear-gradient(135deg, #ff2d7a, #ff4b91); border: none;">
                                <i class="fa-solid fa-check me-2"></i> Save Waiter
                            </button>
                            <a href="{{ route('admin.waiter.index') }}"
                                class="btn btn-outline-secondary btn-lg px-5 rounded-pill">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
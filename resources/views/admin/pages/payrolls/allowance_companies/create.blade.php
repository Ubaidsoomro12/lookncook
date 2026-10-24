@extends('admin.layouts.master')

@section('title', 'Add Allowance Company')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add Allowance Company</h4>
            <p class="text-muted small m-0">Company that provides an allowance</p>
        </div>
        <a href="{{ route('admin.payroll-allowance-companies.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger border-0">
        <ul class="mb-0 small">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.payroll-allowance-companies.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">For Allowance Type <span class="text-danger">*</span></label>
                        <select name="allowance_type_id" class="form-select" required>
                            <option value="">-- Select allowance type --</option>
                            @foreach($allowanceTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('allowance_type_id')==$type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">This company will only appear for the selected allowance type.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn px-5"
                            style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
                        <i class="fa-solid fa-check me-2"></i>Save
                    </button>
                    <a href="{{ route('admin.payroll-allowance-companies.index') }}"
                       class="btn btn-outline-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
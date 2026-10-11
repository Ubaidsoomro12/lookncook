@extends('admin.layouts.master')

@section('title', 'Edit Allowance Type')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Allowance Type</h4>
            <p class="text-muted small m-0">{{ $type->name }}</p>
        </div>
        <a href="{{ route('admin.payroll-allowance-types.index') }}" class="btn btn-outline-secondary">
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
            <form action="{{ route('admin.payroll-allowance-types.update', $type->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $type->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Default Amount</label>
                        <input type="number" name="default_amount" class="form-control"
                               value="{{ old('default_amount', $type->default_amount) }}" min="0" step="0.01">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $type->sort_order) }}" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="is_active" {{ old('is_active', $type->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $type->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn px-5"
                            style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
                        <i class="fa-solid fa-check me-2"></i>Update
                    </button>
                    <a href="{{ route('admin.payroll-allowance-types.index') }}"
                       class="btn btn-outline-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
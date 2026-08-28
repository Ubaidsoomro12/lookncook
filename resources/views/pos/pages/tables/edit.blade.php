@extends('pos.layouts.pos_master')

@section('title', 'Edit Table')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Table #{{ $table->table_number }}</h4>
        <p class="text-muted small m-0">Update table details</p>
    </div>
    <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Back to Tables
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Table Number --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Table Number <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="table_number" 
                           class="form-control @error('table_number') is-invalid @enderror" 
                           value="{{ old('table_number', $table->table_number) }}" required>
                    @error('table_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Table Name --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Table Name</label>
                    <input type="text" 
                           name="table_name" 
                           class="form-control" 
                           value="{{ old('table_name', $table->table_name) }}">
                </div>

                {{-- Capacity --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Capacity <span class="text-danger">*</span></label>
                    <select name="capacity" class="form-select @error('capacity') is-invalid @enderror" required>
                        @for($i = 1; $i <= 20; $i++)
                            <option value="{{ $i }}" {{ old('capacity', $table->capacity) == $i ? 'selected' : '' }}>
                                {{ $i }} {{ $i == 1 ? 'Person' : 'People' }}
                            </option>
                        @endfor
                    </select>
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Table Type --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Table Type</label>
                    <select name="table_type" class="form-select">
                        @foreach(['dining','bar','lounge','private','booth'] as $type)
                            <option value="{{ $type }}" {{ old('table_type', $table->table_type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Zone --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Zone</label>
                    <select name="zone" class="form-select">
                        @foreach(['male area','family area','indoor','booth','outdoor'] as $zone)
                            <option value="{{ $zone }}" {{ old('zone', $table->zone) == $zone ? 'selected' : '' }}>
                                {{ ucfirst($zone) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Floor --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Floor</label>
                    <input type="text" name="floor" class="form-control" 
                           value="{{ old('floor', $table->floor) }}">
                </div>

                {{-- Status --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(['available','occupied','reserved','maintenance'] as $status)
                            <option value="{{ $status }}" {{ old('status', $table->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Branch Name --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Branch Name</label>
                    <input type="text" name="branch_name" class="form-control" 
                           value="{{ old('branch_name', $table->branch_name) }}">
                </div>

                {{-- QR Code --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">QR Code</label>
                    <input type="text" name="qr_code" class="form-control" 
                           value="{{ old('qr_code', $table->qr_code) }}">
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $table->description) }}</textarea>
                </div>

                {{-- Is Active --}}
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                               id="is_active" {{ old('is_active', $table->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-danger px-4">
                    <i class="fa-solid fa-save me-2"></i>Update Table
                </button>
                <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection 
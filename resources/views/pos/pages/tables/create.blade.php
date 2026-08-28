@extends('pos.layouts.pos_master')

@section('title', 'Add New Table')

@section('content')
<div class="container-fluid px-4">
    <!-- ========================================== -->
    <!-- PAGE HEADER -->
    <!-- ========================================== -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add New Table</h4>
            <p class="text-muted small m-0">Create a new restaurant table</p>
        </div>
        <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Tables
        </a>
    </div>

    <!-- ========================================== -->
    <!-- FORM CARD -->
    <!-- ========================================== -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf

                <!-- ================================ -->
                <!-- TWO COLUMN LAYOUT -->
                <!-- ================================ -->
                <div class="row g-4">

                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <!-- Table Number -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Table Number <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-hashtag text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="table_number" 
                                       class="form-control border-start-0 @error('table_number') is-invalid @enderror" 
                                       value="{{ old('table_number') }}" 
                                       placeholder="e.g. T-01, Table 5" 
                                       required>
                            </div>
                            @error('table_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Table Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Table Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-tag text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="table_name" 
                                       class="form-control border-start-0" 
                                       value="{{ old('table_name') }}" 
                                       placeholder="e.g. Window Side, VIP">
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Capacity <span class="text-danger">*</span>
                            </label>
                            <select name="capacity" 
                                    class="form-select @error('capacity') is-invalid @enderror" 
                                    required>
                                <option value="">Select Capacity</option>
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" {{ old('capacity') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'Person' : 'People' }}
                                    </option>
                                @endfor
                            </select>
                            @error('capacity')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Table Type -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Table Type</label>
                            <select name="table_type" class="form-select">
                                <option value="dining" {{ old('table_type') == 'dining' ? 'selected' : '' }}>🍽️ Dining</option>
                                <option value="bar" {{ old('table_type') == 'bar' ? 'selected' : '' }}>🍸 Bar</option>
                                <option value="lounge" {{ old('table_type') == 'lounge' ? 'selected' : '' }}>🛋️ Lounge</option>
                                <option value="private" {{ old('table_type') == 'private' ? 'selected' : '' }}>🔒 Private</option>
                                <option value="booth" {{ old('table_type') == 'booth' ? 'selected' : '' }}>🪑 Booth</option>
                                <!-- <option value="outdoor" {{ old('table_type') == 'outdoor' ? 'selected' : '' }}>🌿 Outdoor</option>
                                <option value="indoor" {{ old('table_type') == 'indoor' ? 'selected' : '' }}>🏠 Indoor</option> -->
                            </select>
                        </div>

                        <!-- Zone -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Zone</label>
                            <select name="zone" class="form-select">
                                <!-- <option value="dining" {{ old('zone') == 'dining' ? 'selected' : '' }}>🍽️ Dining</option> -->
                                <option value="male area" {{ old('zone') == 'male area' ? 'selected' : '' }}>👨 Male Area</option>
                                <option value="family area" {{ old('zone') == 'family area' ? 'selected' : '' }}>👨‍👩‍👧‍👦 Family Area</option>
                                <option value="indoor" {{ old('zone') == 'indoor' ? 'selected' : '' }}>🏠 Indoor</option>
                                <option value="booth" {{ old('zone') == 'booth' ? 'selected' : '' }}>🪑 Booth</option>
                                <option value="outdoor" {{ old('zone') == 'outdoor' ? 'selected' : '' }}>🌿 Outdoor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <!-- Floor -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Floor</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-building text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="floor" 
                                       class="form-control border-start-0" 
                                       value="{{ old('floor') }}" 
                                       placeholder="e.g. Ground, 1st Floor">
                            </div>
                        </div>

                        <!-- Branch Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Branch Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-store text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="branch_name" 
                                       class="form-control border-start-0" 
                                       value="{{ old('branch_name') }}" 
                                       placeholder="e.g. Main Branch">
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">QR Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-qrcode text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="qr_code" 
                                       class="form-control border-start-0" 
                                       value="{{ old('qr_code') }}" 
                                       placeholder="QR code text or URL">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" 
                                    class="form-select @error('status') is-invalid @enderror" 
                                    required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>🟢 Available</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>🟡 Occupied</option>
                                <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>🔴 Reserved</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       id="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       style="width: 3rem; height: 1.5rem;">
                                <label class="form-check-label fw-semibold ms-2" for="is_active">
                                    Active
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">If active, table will be visible and available for booking.</small>
                        </div>
                    </div>

                    <!-- Full Width - Description -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Any notes about this table...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ================================ -->
                <!-- FORM ACTIONS -->
                <!-- ================================ -->
                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-danger px-5">
                        <i class="fa-solid fa-save me-2"></i>Save Table
                    </button>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fa-solid fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('admin.layouts.master')

@section('title', 'Edit Table')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-white">Edit Table #{{ $id ?? '' }}</h1>
                <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to Tables
                </a>
            </div>
        </div>
    </div>

    <div class="card bg-dark text-white border-gray-800">
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="table_number" class="form-label">Table Number</label>
                        <input type="text" class="form-control bg-dark text-white border-gray-700" id="table_number" 
                               value="Table {{ $id ?? '1' }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <select class="form-select bg-dark text-white border-gray-700" id="capacity" required>
                            <option value="2">2 People</option>
                            <option value="4" selected>4 People</option>
                            <option value="6">6 People</option>
                            <option value="8">8 People</option>
                            <option value="10">10 People</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select bg-dark text-white border-gray-700" id="status" required>
                            <option value="available" selected>Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="reserved">Reserved</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control bg-dark text-white border-gray-700" id="location" 
                               value="Ground Floor" required>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control bg-dark text-white border-gray-700" id="description" 
                                  rows="3" placeholder="Enter table description">This table is near the window with a great view.</textarea>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-save me-2"></i>Update Table
                    </button>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
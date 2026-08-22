@extends('admin.layouts.master')

@section('title', 'Table Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="text-white">Table Management</h1>
            <a href="{{ route('admin.tables.create') }}" class="btn btn-danger">
                <i class="fa-solid fa-plus me-2"></i>Add New Table
            </a>
        </div>
    </div>

    <!-- Bootstrap Table -->
    <div class="table-responsive">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Table Number</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Table 1</td>
                    <td>4 People</td>
                    <td><span class="badge bg-success">Available</span></td>
                    <td>Ground Floor</td>
                    <td>
                        <a href="{{ route('admin.tables.edit', 1) }}" class="btn btn-sm btn-primary me-1">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Table 2</td>
                    <td>6 People</td>
                    <td><span class="badge bg-warning">Occupied</span></td>
                    <td>Ground Floor</td>
                    <td>
                        <a href="{{ route('admin.tables.edit', 2) }}" class="btn btn-sm btn-primary me-1">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Table 3</td>
                    <td>2 People</td>
                    <td><span class="badge bg-danger">Reserved</span></td>
                    <td>First Floor</td>
                    <td>
                        <a href="{{ route('admin.tables.edit', 3) }}" class="btn btn-sm btn-primary me-1">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Table 4</td>
                    <td>8 People</td>
                    <td><span class="badge bg-success">Available</span></td>
                    <td>First Floor</td>
                    <td>
                        <a href="{{ route('admin.tables.edit', 4) }}" class="btn btn-sm btn-primary me-1">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Table 5</td>
                    <td>4 People</td>
                    <td><span class="badge bg-warning">Occupied</span></td>
                    <td>Outdoor</td>
                    <td>
                        <a href="{{ route('admin.tables.edit', 5) }}" class="btn btn-sm btn-primary me-1">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
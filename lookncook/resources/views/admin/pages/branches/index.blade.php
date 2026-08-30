@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-white mb-0">
                <i class="fa-solid fa-store me-2" style="color: #ff2d7a;"></i>
                Branches Management
            </h4>
            <p class="text-muted small mb-0">Manage all your restaurant branches</p>
        </div>
        <a href="{{ route('admin.branches.create') }}" class="btn" style="background: linear-gradient(135deg, #ff2d7a, #ff6b9d); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 500; transition: all 0.3s;">
            <i class="fa-solid fa-plus me-2"></i> Add New Branch
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71; border-left: 4px solid #2ecc71; border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-check-circle me-2" style="color: #2ecc71;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="error-alert" class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="background: rgba(231, 76, 60, 0.15); color: #e74c3c; border-left: 4px solid #e74c3c; border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-exclamation-circle me-2" style="color: #e74c3c;"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
        </div>
    @endif

    <!-- Table Card -->
    <div class="card" style="background: #1e1e2d; border: 1px solid #2d2d3f; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: #e0e0e0;">
                    <thead style="background: #151521; border-bottom: 2px solid #2d2d3f;">
                        <tr>
                            <th style="padding: 16px 20px; color: #9ca3af; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-hashtag me-1" style="color: #ff2d7a;"></i> ID
                            </th>
                            <th style="padding: 16px 20px; color: #9ca3af; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-store me-1" style="color: #ff2d7a;"></i> Branch Name
                            </th>
                            <th style="padding: 16px 20px; color: #9ca3af; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-location-dot me-1" style="color: #ff2d7a;"></i> Address
                            </th>
                            <th style="padding: 16px 20px; color: #9ca3af; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-phone me-1" style="color: #ff2d7a;"></i> Phone
                            </th>
                            <th style="padding: 16px 20px; color: #9ca3af; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-circle me-1" style="color: #ff2d7a;"></i> Status
                            </th>
                            <th style="padding: 16px 20px; color: #9ca3af; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">
                                <i class="fa-solid fa-sliders me-1" style="color: #ff2d7a;"></i> Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr style="border-bottom: 1px solid #2d2d3f; transition: all 0.3s;" 
                                onmouseover="this.style.background='rgba(255,45,122,0.05)'" 
                                onmouseout="this.style.background='transparent'">
                                <td style="padding: 14px 20px; font-weight: 600; color: #ff2d7a;">#{{ $branch->branch_id }}</td>
                                <td style="padding: 14px 20px;">
                                    <span style="font-weight: 500;">{{ $branch->branch_name }}</span>
                                </td>
                                <td style="padding: 14px 20px; color: #b0b0b0;">{{ $branch->address }}</td>
                                <td style="padding: 14px 20px; color: #b0b0b0;">{{ $branch->phone ?? 'N/A' }}</td>
                                <td style="padding: 14px 20px;">
                                    <span class="badge" style="
                                        background: {{ $branch->status == 'active' ? 'rgba(46, 204, 113, 0.15)' : 'rgba(231, 76, 60, 0.15)' }};
                                        color: {{ $branch->status == 'active' ? '#2ecc71' : '#e74c3c' }};
                                        padding: 6px 14px;
                                        border-radius: 20px;
                                        font-weight: 500;
                                        font-size: 11px;
                                        border: 1px solid {{ $branch->status == 'active' ? 'rgba(46, 204, 113, 0.3)' : 'rgba(231, 76, 60, 0.3)' }};
                                    ">
                                        <i class="fa-solid fa-circle me-1" style="font-size: 6px; vertical-align: middle;"></i>
                                        {{ ucfirst($branch->status) }}
                                    </span>
                                </td>
                                <td style="padding: 14px 20px;">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.branches.edit', $branch->branch_id) }}" 
                                           class="btn btn-sm" 
                                           style="background: rgba(52, 152, 219, 0.15); color: #3498db; border: 1px solid rgba(52, 152, 219, 0.2); border-radius: 8px; padding: 6px 14px; transition: all 0.3s;"
                                           onmouseover="this.style.background='rgba(52, 152, 219, 0.25)'" 
                                           onmouseout="this.style.background='rgba(52, 152, 219, 0.15)'">
                                            <i class="fa-solid fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.branches.destroy', $branch->branch_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm" 
                                                    style="background: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.2); border-radius: 8px; padding: 6px 14px; transition: all 0.3s;"
                                                    onmouseover="this.style.background='rgba(231, 76, 60, 0.25)'" 
                                                    onmouseout="this.style.background='rgba(231, 76, 60, 0.15)'"
                                                    onclick="return confirm('Are you sure you want to delete this branch?')">
                                                <i class="fa-solid fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 60px 20px; text-align: center;">
                                    <div style="color: #555;">
                                        <i class="fa-solid fa-store fa-4x d-block mb-3" style="color: #2d2d3f;"></i>
                                        <h5 style="color: #9ca3af;">No Branches Found</h5>
                                        <p class="text-muted small">Click the "Add New Branch" button to create your first branch.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success message after 5 seconds
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    successAlert.remove();
                }, 500);
            }, 5000);
        }

        // Auto-hide error message after 5 seconds
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                errorAlert.style.opacity = '0';
                errorAlert.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    errorAlert.remove();
                }, 500);
            }, 5000);
        }
    });
</script>
@endsection
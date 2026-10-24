@extends('pos.layouts.pos_master')

@section('title', 'Table Management')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Table Management</h4>
        <p class="text-muted small m-0">Manage all restaurant tables from here</p>
    </div>
    <div>
        <a href="{{ route('admin.tables.create') }}" class="btn btn-danger px-4">
            <i class="fa-solid fa-plus me-2"></i>Add New Table
        </a>
    </div>
</div>

{{-- Search Bar --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.tables.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa-solid fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-start-0" 
                           placeholder="Search tables by name, number or branch..." 
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="table_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="dining" {{ request('table_type') == 'dining' ? 'selected' : '' }}>Dining</option>
                    <option value="bar" {{ request('table_type') == 'bar' ? 'selected' : '' }}>Bar</option>
                    <option value="lounge" {{ request('table_type') == 'lounge' ? 'selected' : '' }}>Lounge</option>
                    <option value="private" {{ request('table_type') == 'private' ? 'selected' : '' }}>Private</option>
                    <option value="booth" {{ request('table_type') == 'booth' ? 'selected' : '' }}>Booth</option>
                    <option value="outdoor" {{ request('table_type') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                    <option value="indoor" {{ request('table_type') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="zone" class="form-select">
                    <option value="">All Zones</option>
                    <option value="dining" {{ request('zone') == 'dining' ? 'selected' : '' }}>Dining</option>
                    <option value="male area" {{ request('zone') == 'male area' ? 'selected' : '' }}>Male Area</option>
                    <option value="family area" {{ request('zone') == 'family area' ? 'selected' : '' }}>Family Area</option>
                    <option value="indoor" {{ request('zone') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                    <option value="booth" {{ request('zone') == 'booth' ? 'selected' : '' }}>Booth</option>
                    <option value="outdoor" {{ request('zone') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                </select>
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">#</th>
                        <th>Table No.</th>
                        <th>Table Name</th>
                        <th>Capacity</th>
                        <th>Type</th>
                        <th>Zone</th>
                        <th>Floor</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 130px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tablesTableBody">
                    @forelse($tables as $table)
                    <tr id="table-row-{{ $table->id }}">
                        <td class="ps-4">{{ $loop->iteration + ($tables->currentPage() - 1) * $tables->perPage() }}</td>
                        <td>
                            <span class="fw-semibold">{{ $table->table_number }}</span>
                        </td>
                        <td>{{ $table->table_name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2">
                                <i class="fa-regular fa-user me-1"></i>{{ $table->capacity }}
                            </span>
                        </td>
                        <td>
                            @php
                                $typeColors = [
                                    'dining' => 'primary',
                                    'bar' => 'info',
                                    'lounge' => 'warning',
                                    'private' => 'danger',
                                    'booth' => 'secondary',
                                    'outdoor' => 'success',
                                    'indoor' => 'dark',
                                ];
                                $color = $typeColors[$table->table_type] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($table->table_type ?? '—') }}</span>
                        </td>
                        <td>{{ ucfirst($table->zone ?? '—') }}</td>
                        <td>{{ $table->floor ?? '—' }}</td>
                        <td id="status-cell-{{ $table->id }}">
                            @if($table->status == 'available')
                                <span class="badge bg-success px-3 py-2">Available</span>
                            @elseif($table->status == 'occupied')
                                <span class="badge bg-warning text-dark px-3 py-2">Occupied</span>
                            @elseif($table->status == 'reserved')
                                <span class="badge bg-danger px-3 py-2">Reserved</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">Maintenance</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.tables.edit', $table->id) }}" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger delete-table-btn"
                                        data-id="{{ $table->id }}"
                                        data-url="{{ route('admin.tables.destroy', $table->id) }}"
                                        data-name="{{ $table->table_number }}"
                                        title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="9" class="text-center py-5">
                            <div class="py-4">
                                <i class="fa-solid fa-table-cells fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Tables Found</h5>
                                <p class="text-muted small mb-0">Click <strong>"Add New Table"</strong> to create your first table.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($tables->total() > 0)
<div class="d-flex flex-wrap align-items-center justify-content-between mt-4" id="paginationWrapper">
    <p class="text-muted small mb-0" id="paginationInfo">
        Showing <strong>{{ $tables->firstItem() }}</strong> 
        to <strong>{{ $tables->lastItem() }}</strong> 
        of <strong>{{ $tables->total() }}</strong> entries
    </p>
    <div>
        {{ $tables->appends(request()->query())->links() }}
    </div>
</div>
@endif

<!-- Include SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Add Animate.css for smooth animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // ==================================================================
        // SWEETALERT2 DELETE CONFIRMATION WITH AJAX
        // ==================================================================
        function handleDelete(button) {
            const id = button.dataset.id;
            const url = button.dataset.url;
            const name = button.dataset.name || 'Table #' + id;
            
            Swal.fire({
                title: 'Are you sure?',
                html: `You are about to delete <strong>"Table #${name}"</strong><br>This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                backdrop: 'rgba(0,0,0,0.6)',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                customClass: {
                    popup: 'rounded-4 shadow-lg',
                    title: 'fw-bold text-dark',
                    htmlContainer: 'text-muted',
                    confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-semibold',
                    cancelButton: 'btn btn-secondary px-4 py-2 rounded-pill fw-semibold',
                    actions: 'gap-3'
                },
                iconHtml: `
                    <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-trash-can text-danger fs-1"></i>
                    </div>
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Deleting...',
                        html: 'Please wait while the table is being deleted.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-4 shadow-lg',
                        }
                    });

                    // Send AJAX DELETE request
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close SweetAlert
                            Swal.close();
                            
                            // Remove the row with animation
                            const row = document.getElementById('table-row-' + id);
                            if (row) {
                                row.style.transition = 'all 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';
                                setTimeout(() => {
                                    row.remove();
                                    
                                    // Check if table is empty
                                    const tbody = document.getElementById('tablesTableBody');
                                    if (tbody && tbody.children.length === 0) {
                                        tbody.innerHTML = `
                                            <tr id="emptyRow">
                                                <td colspan="9" class="text-center py-5">
                                                    <div class="py-4">
                                                        <i class="fa-solid fa-table-cells fa-3x text-muted mb-3 d-block"></i>
                                                        <h5 class="text-muted">No Tables Found</h5>
                                                        <p class="text-muted small mb-0">Click <strong>"Add New Table"</strong> to create your first table.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
                                    }
                                    
                                    // Update pagination info
                                    updatePaginationInfo();
                                }, 300);
                            }
                            
                            // Show success toast
                            showToast(data.message || 'Table deleted successfully!', 'success');
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to delete table.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'rounded-4 shadow-lg',
                                    confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-semibold'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'rounded-4 shadow-lg',
                                confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-semibold'
                            }
                        });
                    });
                }
            });
        }

        // Attach event listeners to delete buttons
        document.querySelectorAll('.delete-table-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleDelete(this);
            });
        });

        // ==================================================================
        // UPDATE PAGINATION INFO
        // ==================================================================
        function updatePaginationInfo() {
            const tbody = document.getElementById('tablesTableBody');
            const paginationWrapper = document.getElementById('paginationWrapper');
            
            if (tbody) {
                const rows = tbody.querySelectorAll('tr:not(#emptyRow)');
                const total = rows.length;
                const emptyRow = tbody.querySelector('#emptyRow');
                
                if (paginationWrapper) {
                    if (total === 0 || emptyRow) {
                        paginationWrapper.style.display = 'none';
                    } else {
                        const infoSpan = document.getElementById('paginationInfo');
                        if (infoSpan) {
                            const start = 1;
                            const end = total;
                            infoSpan.innerHTML = `
                                Showing <strong>${start}</strong> 
                                to <strong>${end}</strong> 
                                of <strong>${total}</strong> entries
                            `;
                        }
                        paginationWrapper.style.display = 'flex';
                    }
                }
            }
        }

        // ==================================================================
        // TOAST FUNCTION (for success/error messages)
        // ==================================================================
        function showToast(message, type = 'success') {
            let container = document.getElementById('toastContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toastContainer';
                container.style.cssText = 'position:fixed; top:20px; right:20px; z-index:99999; display:flex; flex-direction:column; gap:10px;';
                document.body.appendChild(container);
            }
            
            const toast = document.createElement('div');
            const isSuccess = type === 'success';
            
            toast.style.cssText = `
                background: #fff;
                padding: 14px 20px;
                border-radius: 12px;
                border-left: 4px solid ${isSuccess ? '#22c55e' : '#ef4444'};
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                display: flex;
                align-items: center;
                gap: 12px;
                transform: translateX(120%);
                opacity: 0;
                transition: all 0.4s ease;
                min-width: 280px;
                max-width: 100%;
            `;
            
            toast.innerHTML = `
                <div style="width:32px; height:32px; border-radius:50%; background:${isSuccess ? '#ecfdf5' : '#fef2f2'}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa-solid ${isSuccess ? 'fa-check' : 'fa-xmark'}" style="color:${isSuccess ? '#22c55e' : '#ef4444'}; font-size:14px;"></i>
                </div>
                <div style="flex:1;">
                    <p style="font-weight:600; color:#1f2937; margin:0; font-size:14px;">${isSuccess ? 'Success' : 'Error'}</p>
                    <p style="color:#6b7280; margin:0; font-size:13px;">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:#9ca3af; cursor:pointer; font-size:18px;">×</button>
            `;
            
            container.appendChild(toast);
            
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            });
            
            setTimeout(() => {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        }

        // Show session messages as toasts
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if($errors->any())
            showToast(@json($errors->first()), 'error');
        @endif
    });
</script>
@endsection
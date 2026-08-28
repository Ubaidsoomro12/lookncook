@extends('admin.layouts.master')
@section('title', 'Waiter Management')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header Row -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h2 fw-bold text-dark">Waiter Management</h1>
                <p class="text-muted small mt-1">Manage all waiters from here.</p>
            </div>
            <a href="{{ route('admin.waiter.create') }}"
                class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill shadow-sm"
                style="background: linear-gradient(135deg, #ff2d7a, #ff4b91); border: none;">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Waiter</span>
            </a>
        </div>

        <!-- Search + Table Card -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">

            <!-- Search Bar -->
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="position-relative w-100" style="max-width: 320px;">
                        <i
                            class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted small"></i>
                        <input type="text" id="waiterSearchInput"
                            placeholder="Search waiters by name, email, CNIC or phone..."
                            class="form-control form-control-sm ps-5 pe-4 py-2 rounded-pill border" autocomplete="off">
                        <i id="searchLoadingIcon"
                            class="fa-solid fa-circle-notch fa-spin position-absolute top-50 end-0 translate-middle-y me-3 text-muted d-none"></i>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-3 py-3">Image</th>
                            <th class="px-3 py-3">#</th>
                            <th class="px-3 py-3">Name</th>
                            <th class="px-3 py-3">Email</th>
                            <th class="px-3 py-3">Phone</th>
                            <th class="px-3 py-3">CNIC</th>
                            <th class="px-3 py-3">Hire Date</th>
                            <th class="px-3 py-3">Salary</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="waitersTableBody">
                        @forelse($waiters as $index => $waiter)
                            <tr id="waiter-row-{{ $waiter->id }}" data-row-id="{{ $waiter->id }}">
                                <td class="px-3 py-3">
                                    @if($waiter->image)
                                        <img src="{{ asset($waiter->image) }}" alt="{{ $waiter->name }}"
                                            class="rounded-circle object-fit-cover border" style="width: 48px; height: 48px;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                            style="width: 48px; height: 48px; color: #ccc;">
                                            <i class="fa-solid fa-user fs-4"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-muted">{{ $waiters->firstItem() + $index }}</td>
                                <td class="px-3 py-3 fw-semibold text-dark">{{ $waiter->name }}</td>
                                <td class="px-3 py-3 text-muted">{{ $waiter->email }}</td>
                                <td class="px-3 py-3 text-muted">{{ $waiter->phone }}</td>
                                <td class="px-3 py-3 text-muted">{{ $waiter->cnic ?? '—' }}</td>
                                <td class="px-3 py-3 text-muted">
                                    {{ $waiter->hire_date ? $waiter->hire_date->format('d M, Y') : '—' }}</td>
                                <td class="px-3 py-3 fw-semibold text-dark">Rs. {{ number_format($waiter->salary, 0) }}</td>
                                <td class="px-3 py-3">
                                    @if($waiter->status === 'active')
                                        <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.waiter.show', $waiter->id) }}"
                                            class="btn btn-sm btn-outline-info rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="View">
                                            <i class="fa-solid fa-eye fa-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.waiter.edit', $waiter->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Edit">
                                            <i class="fa-solid fa-pen fa-xs"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center delete-waiter-btn"
                                            style="width: 32px; height: 32px;" 
                                            title="Delete"
                                            data-id="{{ $waiter->id }}"
                                            data-url="{{ route('admin.waiter.destroy', $waiter->id) }}"
                                            data-name="{{ $waiter->name }}">
                                            <i class="fa-solid fa-trash fa-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users-slash fs-1 d-block mb-2"></i>
                                    No waiters found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($waiters->hasPages())
                <div class="card-footer bg-white border-top py-3" id="paginationWrapper">
                    {{ $waiters->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Include SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Add Animate.css for smooth animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('waiterSearchInput');
            const tableBody = document.getElementById('waitersTableBody');
            const loadingIcon = document.getElementById('searchLoadingIcon');
            const paginationBox = document.getElementById('paginationWrapper');
            const searchUrl = "{{ route('admin.waiter.search') }}";
            
            // Get CSRF token from meta tag (now available in head.blade.php)
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Fallback token if meta tag not found
            const token = csrfToken || '{{ csrf_token() }}';

            // ==================================================================
            // SWEETALERT2 DELETE CONFIRMATION WITH AJAX
            // ==================================================================
            function handleDelete(button) {
                const id = button.dataset.id;
                const url = button.dataset.url;
                const name = button.dataset.name || 'this waiter';

                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete <strong>"${name}"</strong><br>This action cannot be undone!`,
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
                            html: 'Please wait while the waiter is being deleted.',
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

                        // Send AJAX DELETE request with CSRF token
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            // Check if response is OK
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message || 'Server error');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Close SweetAlert
                                Swal.close();
                                
                                // Remove the row with animation
                                const row = document.getElementById('waiter-row-' + id);
                                if (row) {
                                    row.style.transition = 'all 0.3s ease';
                                    row.style.opacity = '0';
                                    row.style.transform = 'translateX(-20px)';
                                    setTimeout(() => {
                                        row.remove();
                                        
                                        // Check if table is empty
                                        const tbody = document.getElementById('waitersTableBody');
                                        if (tbody && tbody.children.length === 0) {
                                            tbody.innerHTML = `
                                                <tr id="emptyRow">
                                                    <td colspan="10" class="text-center py-5 text-muted">
                                                        <i class="fa-solid fa-users-slash fs-1 d-block mb-2"></i>
                                                        No waiters found.
                                                    </td>
                                                </tr>
                                            `;
                                        }
                                        
                                        // Update pagination info
                                        updatePaginationInfo();
                                    }, 300);
                                }
                                
                                // Show success toast
                                showToast(data.message || 'Waiter deleted successfully!', 'success');
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'Failed to delete waiter.',
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
                            
                            // Check if it's a CSRF error
                            if (error.message && error.message.toLowerCase().includes('csrf')) {
                                Swal.fire({
                                    title: 'Session Expired',
                                    text: 'Your session has expired. Please refresh the page and try again.',
                                    icon: 'warning',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Refresh Page',
                                    customClass: {
                                        popup: 'rounded-4 shadow-lg',
                                        confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-semibold'
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.message || 'Something went wrong. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'rounded-4 shadow-lg',
                                        confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-semibold'
                                    }
                                });
                            }
                        });
                    }
                });
            }

            // Attach event listeners to delete buttons
            document.querySelectorAll('.delete-waiter-btn').forEach(function(button) {
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
                const tbody = document.getElementById('waitersTableBody');
                const paginationWrapper = document.getElementById('paginationWrapper');
                
                if (tbody) {
                    const rows = tbody.querySelectorAll('tr:not(#emptyRow)');
                    const total = rows.length;
                    const emptyRow = tbody.querySelector('#emptyRow');
                    
                    if (paginationWrapper) {
                        if (total === 0 || emptyRow) {
                            paginationWrapper.style.display = 'none';
                        } else {
                            paginationWrapper.style.display = 'block';
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

            // ==================================================================
            // INSTANT / DYNAMIC SEARCH
            // ==================================================================
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                debounceTimer = setTimeout(() => {
                    loadingIcon.classList.remove('d-none');

                    fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        renderRows(data.waiters);
                        if (paginationBox) paginationBox.style.display = query ? 'none' : '';
                    })
                    .catch(() => {
                        tableBody.innerHTML = `<tr><td colspan="10" class="text-center py-5 text-danger">Something went wrong. Try again.</td></tr>`;
                    })
                    .finally(() => loadingIcon.classList.add('d-none'));
                }, 300);
            });

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.textContent = str ?? '';
                return div.innerHTML;
            }

            function renderRows(waiters) {
                if (!waiters.length) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fs-1 d-block mb-2"></i>
                                No waiters found.
                            </td>
                        </tr>`;
                    return;
                }

                tableBody.innerHTML = waiters.map((w, index) => `
                    <tr id="waiter-row-${w.id}" data-row-id="${w.id}">
                        <td class="px-3 py-3">
                            ${w.image
                                ? `<img src="${w.image}" alt="${escapeHtml(w.name)}" class="rounded-circle object-fit-cover border" style="width: 48px; height: 48px;">`
                                : `<div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 48px; height: 48px; color: #ccc;"><i class="fa-solid fa-user fs-4"></i></div>`
                            }
                        </td>
                        <td class="px-3 py-3 text-muted">${index + 1}</td>
                        <td class="px-3 py-3 fw-semibold text-dark">${escapeHtml(w.name)}</td>
                        <td class="px-3 py-3 text-muted">${escapeHtml(w.email)}</td>
                        <td class="px-3 py-3 text-muted">${escapeHtml(w.phone)}</td>
                        <td class="px-3 py-3 text-muted">${escapeHtml(w.cnic) || '—'}</td>
                        <td class="px-3 py-3 text-muted">${w.hire_date ? new Date(w.hire_date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }) : '—'}</td>
                        <td class="px-3 py-3 fw-semibold text-dark">Rs. ${Number(w.salary).toLocaleString()}</td>
                        <td class="px-3 py-3">
                            ${w.status === 'active'
                                ? `<span class="badge bg-success rounded-pill px-3 py-2">Active</span>`
                                : `<span class="badge bg-secondary rounded-pill px-3 py-2">Inactive</span>`
                            }
                        </td>
                        <td class="px-3 py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="${w.edit_url}" class="btn btn-sm btn-outline-info rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                                    <i class="fa-solid fa-eye fa-xs"></i>
                                </a>
                                <a href="${w.edit_url}" class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                    <i class="fa-solid fa-pen fa-xs"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center delete-waiter-btn" style="width: 32px; height: 32px;" title="Delete" data-id="${w.id}" data-url="${w.delete_url}" data-name="${escapeHtml(w.name)}">
                                    <i class="fa-solid fa-trash fa-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');

                // Re-attach event listeners to dynamically created delete buttons
                document.querySelectorAll('.delete-waiter-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleDelete(this);
                    });
                });
            }
        });
    </script>
@endsection
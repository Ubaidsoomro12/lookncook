@extends('admin.layouts.master')
@section('title', 'Staff Management')
@section('content')
<style>
    /* ✅ FIX: Header row (heading + Add Staff button) */
    .staff-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }
    .staff-header h1 {
        margin: 0;
        word-break: break-word;
        flex: 1 1 auto;
        min-width: 0;
    }
    .staff-header .staff-add-btn {
        white-space: nowrap;
        flex-shrink: 0;
    }
    /* ✅ Chhoti screens pe button heading ke neeche full-width */
    @media (max-width: 480px) {
        .staff-header {
            flex-direction: column;
            align-items: stretch;
        }
        .staff-header h1 {
            font-size: 1.5rem;
            text-align: center;
        }
        .staff-header .staff-add-btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    }

    /* ✅ Custom Delete Modal Styles */
    .delete-modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }
    .delete-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: #fee2e2;
        color: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        animation: pulseIcon 2s infinite;
    }
    @keyframes pulseIcon {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.2); }
        70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .delete-modal-title {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }
    .delete-modal-text {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 24px;
    }
    .delete-modal-text strong {
        color: #111827;
    }
</style>

<div class="container-fluid py-4">
    <!-- ✅ FIXED HEADER -->
    <div class="staff-header">
        <h1 class="h2">Staff Management</h1>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary rounded-pill px-4 staff-add-btn">
            <i class="fa-solid fa-plus me-1"></i> Add Staff
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-search"></i></span>
                        <input type="text" id="staffSearch" class="form-control border-start-0" placeholder="Search by name, email, ID...">
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Image</th><th>Employee ID</th><th>Name</th>
                        <th>User (Email)</th><th>Role</th><th>Designation</th><th>Branch</th>
                        <th>Shift Start</th><th>Shift End</th>
                        <th>Phone</th><th>Salary</th><th>Status</th><th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTableBody">
                    @forelse($staff as $index => $s)
                    @php
                        $roles = [1=>'Admin',2=>'User',3=>'Manager',4=>'Waiter',5=>'Chef',6=>'Cashier',7=>'Cleaner',8=>'Delivery Rider'];
                        $roleName = $roles[$s->user->role_id ?? 0] ?? '—';
                    @endphp
                    <tr id="staff-row-{{ $s->id }}">
                        <td>{{ $staff->firstItem() + $index }}</td>
                        <td>
                            @if($s->image)
                                <img src="{{ asset($s->image) }}" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                            @else
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                    <i class="fa-solid fa-user text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $s->employee_id ?? '—' }}</td>
                        <td class="fw-semibold">{{ $s->name }}</td>
                        <td>{{ $s->user->email ?? '—' }}</td>
                        <td>{{ $roleName }}</td>
                        <td>{{ $s->designation ?? '—' }}</td>
                        <td>{{ $s->branch ?? '—' }}</td>
                        <td>{{ $s->shift_start_time ?? '—' }}</td>
                        <td>{{ $s->shift_end_time ?? '—' }}</td>
                        <td>{{ $s->phone }}</td>
                        <td>Rs. {{ number_format($s->salary, 0) }}</td>
                        <td>
                            @if($s->status == 'Active')
                                <span class="badge bg-success">Active</span>
                            @elseif($s->status == 'On Leave')
                                <span class="badge bg-warning">On Leave</span>
                            @else
                                <span class="badge bg-secondary">Terminated</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.staff.show', $s->id) }}" class="btn btn-sm btn-outline-info"><i class="fa-regular fa-eye"></i></a>
                            <a href="{{ route('admin.staff.edit', $s->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $s->id }}" data-url="{{ route('admin.staff.destroy', $s->id) }}" data-name="{{ $s->name }}"><i class="fa-regular fa-trash-can"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="14" class="text-center text-muted py-4">No staff found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $staff->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- ✅ Custom Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-modal-content">
            <div class="modal-body text-center p-5">
                <div class="delete-icon-wrapper">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h5 class="delete-modal-title">Delete Staff Member?</h5>
                <p class="delete-modal-text">
                    Are you sure you want to delete <strong id="deleteStaffName">this staff member</strong>? <br>
                    This action cannot be undone.
                </p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" id="confirmDeleteBtn">
                        <i class="fa-solid fa-trash-can me-1"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('staffSearch');
    const tbody = document.getElementById('staffTableBody');
    
    // Delete Modal Variables
    const deleteModalElement = document.getElementById('deleteModal');
    const deleteModal = new bootstrap.Modal(deleteModalElement);
    const deleteStaffName = document.getElementById('deleteStaffName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    let deleteUrl = '';
    let deleteRowId = '';
    let timer;

    // --- Search Logic ---
    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        timer = setTimeout(() => {
            fetch("{{ route('admin.staff.search') }}?q=" + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    if (data.staff.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="14" class="text-center text-muted py-4">No staff found.</td></tr>`;
                        return;
                    }
                    tbody.innerHTML = data.staff.map(s => `
                        <tr id="staff-row-${s.id}">
                            <td></td>
                            <td>${s.image ? `<img src="${s.image}" class="rounded-circle" width="40" height="40" style="object-fit:cover;">` : `<div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="fa-solid fa-user text-muted"></i></div>`}</td>
                            <td>${s.employee_id || '—'}</td>
                            <td class="fw-semibold">${s.name}</td>
                            <td>${s.user_email || '—'}</td>
                            <td>${s.role_name || '—'}</td>
                            <td>${s.designation || '—'}</td>
                            <td>${s.branch || '—'}</td>
                            <td>${s.shift_start_time || '—'}</td>
                            <td>${s.shift_end_time || '—'}</td>
                            <td>${s.phone}</td>
                            <td>Rs. ${Number(s.salary).toLocaleString()}</td>
                            <td>${s.status === 'Active' ? '<span class="badge bg-success">Active</span>' : s.status === 'On Leave' ? '<span class="badge bg-warning">On Leave</span>' : '<span class="badge bg-secondary">Terminated</span>'}</td>
                            <td class="text-end">
                                <a href="${s.edit_url}" class="btn btn-sm btn-outline-info"><i class="fa-regular fa-eye"></i></a>
                                <a href="${s.edit_url}" class="btn btn-sm btn-outline-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${s.id}" data-url="${s.delete_url}" data-name="${s.name}"><i class="fa-regular fa-trash-can"></i></button>
                            </td>
                        </tr>
                    `).join('');
                    // Reattach listeners after ajax render
                    attachDeleteListeners();
                })
                .catch(() => {});
        }, 300);
    });

    // --- Delete Logic ---
    function attachDeleteListeners() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteUrl = this.dataset.url;
                deleteRowId = this.dataset.id;
                const staffName = this.dataset.name || 'this staff member';
                
                // Set the name in the modal
                deleteStaffName.textContent = staffName;
                
                // Show modal
                deleteModal.show();
            });
        });
    }

    // Confirm Delete Action
    confirmDeleteBtn.addEventListener('click', function() {
        // Add loading state
        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Deleting...';
        this.disabled = true;

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove row
                const row = document.getElementById('staff-row-' + deleteRowId);
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    setTimeout(() => row.remove(), 300);
                }
                
                // Close modal
                deleteModal.hide();
                
                // Reset button (for next time)
                this.innerHTML = originalHtml;
                this.disabled = false;
                
                // Show success toast (or just alert)
                setTimeout(() => {
                    // Optional: show a small toast instead of alert
                    const alertBox = document.createElement('div');
                    alertBox.className = 'position-fixed top-0 end-0 m-3 alert alert-success shadow-sm rounded-3';
                    alertBox.style.zIndex = '9999';
                    alertBox.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Staff deleted successfully!';
                    document.body.appendChild(alertBox);
                    setTimeout(() => alertBox.remove(), 3000);
                }, 300);
            } else {
                alert('Error: ' + (data.message || 'Failed to delete.'));
                this.innerHTML = originalHtml;
                this.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong.');
            this.innerHTML = originalHtml;
            this.disabled = false;
        });
    });

    // Initial listener attachment
    attachDeleteListeners();
});
</script>
@endsection
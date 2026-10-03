@extends('admin.layouts.master')
@section('title', 'Staff Management')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Staff Management</h1>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary rounded-pill px-4">
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
                        <th>User (Email)</th><th>Role</th><th>Designation</th><th>Branch</th><th>Phone</th><th>Salary</th><th>Status</th><th class="text-end">Actions</th>
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
                    <tr><td colspan="12" class="text-center text-muted py-4">No staff found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $staff->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('staffSearch');
    const tbody = document.getElementById('staffTableBody');
    let timer;
    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        timer = setTimeout(() => {
            fetch("{{ route('admin.staff.search') }}?q=" + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    if (data.staff.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="12" class="text-center text-muted py-4">No staff found.</td></tr>`;
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
                    document.querySelectorAll('.delete-btn').forEach(btn => btn.addEventListener('click', handleDelete));
                })
                .catch(() => {});
        }, 300);
    });

    function handleDelete(e) {
        const btn = e.currentTarget;
        const id = btn.dataset.id;
        const url = btn.dataset.url;
        const name = btn.dataset.name || 'this staff';
        if (confirm(`Are you sure you want to delete "${name}"?`)) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('staff-row-' + id);
                    if (row) row.remove();
                    alert('Deleted successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => alert('Something went wrong.'));
        }
    }
    document.querySelectorAll('.delete-btn').forEach(btn => btn.addEventListener('click', handleDelete));
});
</script>
@endsection
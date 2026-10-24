@extends('admin.layouts.master')

@section('title', 'Payroll Payment Methods')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Payroll Payment Methods</h4>
            <p class="text-muted small m-0">Manage payment methods available for salary disbursement</p>
        </div>
        <a href="{{ route('admin.payroll-payment-methods.create') }}" class="btn px-4"
           style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
            <i class="fa-solid fa-plus me-2"></i>Add Payment Method
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:60px;">#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Ref Required</th>
                            <th>Sort</th>
                            <th>Status</th>
                            <th class="text-center" style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($methods as $m)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $m->name }}</div>
                                @if($m->description)
                                    <div class="text-muted small">{{ $m->description }}</div>
                                @endif
                            </td>
                            <td><code>{{ $m->code ?? '—' }}</code></td>
                            <td>
                                @if($m->requires_reference)
                                    <span class="badge bg-warning text-dark">Yes</span>
                                @else
                                    <span class="badge bg-light text-dark">No</span>
                                @endif
                            </td>
                            <td>{{ $m->sort_order }}</td>
                            <td>
                                @if($m->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.payroll-payment-methods.edit', $m->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger delete-btn"
                                            data-name="{{ $m->name }}"
                                            data-url="{{ route('admin.payroll-payment-methods.destroy', $m->id) }}"
                                            title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Payment Methods Yet</h5>
                                <p class="text-muted small mb-0">Click <strong>"Add Payment Method"</strong> to create one.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const url  = this.dataset.url;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Delete Method?',
                html: `Delete <strong>${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!',
            }).then(result => {
                if (!result.isConfirmed) return;
                fetch(url, {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json'}
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({icon: 'success', title: 'Deleted', timer: 1200, showConfirmButton: false})
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Failed.', 'error');
                    }
                });
            });
        });
    });
});
</script>
@endsection
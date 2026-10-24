@extends('admin.layouts.master')

@section('title', 'Allowance Types')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Allowance Types</h4>
            <p class="text-muted small m-0">Manage all salary allowance heads</p>
        </div>
        <a href="{{ route('admin.payroll-allowance-types.create') }}" class="btn px-4"
           style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
            <i class="fa-solid fa-plus me-2"></i>Add Allowance Type
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
                            <th>Default Amount</th>
                            <th>Sort</th>
                            <th>Status</th>
                            <th class="text-center" style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $t)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $t->name }}</div>
                                @if($t->description)
                                    <div class="text-muted small">{{ $t->description }}</div>
                                @endif
                            </td>
                            <td>Rs {{ number_format($t->default_amount, 2) }}</td>
                            <td>{{ $t->sort_order }}</td>
                            <td>
                                @if($t->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.payroll-allowance-types.edit', $t->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger delete-type-btn"
                                            data-id="{{ $t->id }}"
                                            data-name="{{ $t->name }}"
                                            data-url="{{ route('admin.payroll-allowance-types.destroy', $t->id) }}"
                                            title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Allowance Types Yet</h5>
                                <p class="text-muted small mb-0">Click <strong>"Add Allowance Type"</strong> to create one.</p>
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

    document.querySelectorAll('.delete-type-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const url  = this.dataset.url;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Delete Type?',
                html: `Delete <strong>${name}</strong>?<br><small class="text-danger">This also deletes all related payroll entries.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!',
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Failed to delete.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Something went wrong.', 'error'));
            });
        });
    });
});
</script>
@endsection
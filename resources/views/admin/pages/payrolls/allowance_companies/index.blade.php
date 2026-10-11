@extends('admin.layouts.master')

@section('title', 'Allowance Companies')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Allowance Companies</h4>
            <p class="text-muted small m-0">Companies that provide allowances (insurance, fuel, etc.)</p>
        </div>
        <a href="{{ route('admin.payroll-allowance-companies.create') }}" class="btn px-4"
           style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
            <i class="fa-solid fa-plus me-2"></i>Add Company
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
                            <th>Company</th>
                            <th>For Allowance Type</th>
                            <th>Contact</th>
                            <th>Sort</th>
                            <th>Status</th>
                            <th class="text-center" style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $c)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $c->name }}</div>
                                @if($c->email)
                                    <div class="text-muted small">{{ $c->email }}</div>
                                @endif
                            </td>
                            <td>
                                @if($c->allowanceType)
                                    <span class="badge bg-light text-dark">{{ $c->allowanceType->name }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($c->contact_person || $c->contact_number)
                                    <div class="small">{{ $c->contact_person }}</div>
                                    <div class="text-muted small">{{ $c->contact_number }}</div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ $c->sort_order }}</td>
                            <td>
                                @if($c->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.payroll-allowance-companies.edit', $c->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger delete-btn"
                                            data-name="{{ $c->name }}"
                                            data-url="{{ route('admin.payroll-allowance-companies.destroy', $c->id) }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Companies Yet</h5>
                                <p class="text-muted small mb-0">Click <strong>"Add Company"</strong> to create one.</p>
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
                title: 'Delete Company?',
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
@extends('admin.layouts.master')

@section('title', 'Staff Payments')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Staff Payments</h4>
            <p class="text-muted small m-0">Track and manage salary disbursements</p>
        </div>
        <a href="{{ route('admin.staff-payments.create') }}" class="btn btn-primary" style="background:#ff2d7a; border:none;">
            <i class="fa-solid fa-plus me-2"></i>Send Payment
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search staff..." value="{{ request('search') }}">
                </div>
                <div class="col-md-5">
                    <select name="method" class="form-select">
                        <option value="">All Payment Methods</option>
                        @foreach($paymentMethods as $m)
                            <option value="{{ $m->id }}" @selected(request('method') == $m->id)>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn w-100" style="background:#ff2d7a;color:#fff;border:none;"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Date</th>
                            <th>Staff</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th class="text-center" style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $p)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration + ($payments->currentPage()-1) * $payments->perPage() }}</td>
                            <td>{{ $p->payment_date->format('d M Y') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->staff?->name ?? '—' }}</div>
                                <div class="text-muted small">{{ $p->staff?->employee_id ?? '' }}</div>
                            </td>
                            <td>{{ $p->paymentMethod?->name ?? '—' }}</td>
                            <td>{{ $p->reference_number ?? '—' }}</td>
                            <td class="text-end fw-bold" style="color:#ff2d7a;">Rs {{ number_format($p->amount, 2) }}</td>
                            <td>
                                @if($p->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($p->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ Str::limit($p->notes, 30) ?? '—' }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.staff-payments.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                            data-url="{{ route('admin.staff-payments.destroy', $p->id) }}" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                                No payments recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            
            Swal.fire({
                title: 'Delete Payment?',
                text: 'This will remove the payment record.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({icon:'success', title:'Deleted', timer:1000, showConfirmButton:false})
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        });
    });
});
</script>
@endsection
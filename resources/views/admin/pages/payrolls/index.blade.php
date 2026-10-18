@extends('admin.layouts.master')

@section('title', 'Staff Payrolls (Allowances & Deductions)')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Allowances & Deductions</h4>
            <p class="text-muted small m-0">Distribute monthly allowances and deductions to staff</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.payroll-allowance-types.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-list me-2"></i>Allowance Types
            </a>
            <a href="{{ route('admin.payroll-deduction-types.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-list me-2"></i>Deduction Types
            </a>
            <a href="{{ route('admin.payrolls.create') }}" class="btn px-4"
               style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
                <i class="fa-solid fa-plus me-2"></i>Send Allowance/Deduction
            </a>
        </div>
    </div>

    @php
        $stats = $stats ?? ['total_pending'=>0,'total_paid'=>0,'this_month'=>0,'total_allowances'=>0,'total_deductions'=>0];
        $statCards = [
            ['label'=>'Pending Records',  'value'=>$stats['total_pending'], 'icon'=>'fa-hourglass-half', 'color'=>'#f59e0b'],
            ['label'=>'This Month',       'value'=>$stats['this_month'].' Entries', 'icon'=>'fa-calendar-day',   'color'=>'#3b82f6'],
            ['label'=>'Allowances Given (Month)', 'value'=>'Rs '.number_format($stats['total_allowances'], 2), 'icon'=>'fa-circle-plus', 'color'=>'#10b981'],
            ['label'=>'Deductions (Month)','value'=>'Rs '.number_format($stats['total_deductions'], 2), 'icon'=>'fa-circle-minus', 'color'=>'#ef4444'],
        ];
    @endphp
    <div class="row g-3 mb-4">
        @foreach($statCards as $s)
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:12px;background:{{ $s['color'] }}1a;color:{{ $s['color'] }};display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid {{ $s['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ $s['label'] }}</div>
                        <div class="fw-bold fs-5">{{ $s['value'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form class="row g-2 align-items-center" method="GET">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search staff name, ID, email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" @selected(request('month')==$m)>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @for($y = now()->year + 1; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" @selected(request('year')==$y)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending"   @selected(request('status')=='pending')>Pending</option>
                        <option value="paid"      @selected(request('status')=='paid')>Processed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn w-100" style="background:#ff2d7a;color:#fff;border:none;">
                        <i class="fa-solid fa-filter me-1"></i>Filter
                    </button>
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
                            <th class="ps-4" style="width:60px;">#</th>
                            <th>Staff</th>
                            <th>Period</th>
                            <th class="text-success">Allowances</th>
                            <th class="text-danger">Deductions</th>
                            <th>Status</th>
                            <th class="text-center" style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $p)
                        <tr>
                            <td class="ps-4">
                                {{ $loop->iteration + ($payrolls->currentPage() - 1) * $payrolls->perPage() }}
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $p->staff?->name ?? '—' }}</div>
                                <div class="text-muted small">
                                    {{ $p->staff?->employee_id ?? '' }}
                                    @if($p->staff?->designation) • {{ $p->staff->designation }} @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $p->month_name }} {{ $p->period_year }}
                                </span>
                            </td>
                            <td class="text-success fw-semibold">
                                + Rs {{ number_format($p->total_allowances + $p->overtime_amount, 2) }}
                            </td>
                            <td class="text-danger fw-semibold">
                                - Rs {{ number_format($p->total_deductions, 2) }}
                            </td>
                            <td>{!! $p->status_badge !!}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.payrolls.show', $p->id) }}"
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.payrolls.edit', $p->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit Allowances/Deductions">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-payroll-btn"
                                            data-id="{{ $p->id }}" data-url="{{ route('admin.payrolls.destroy', $p->id) }}"
                                            data-name="{{ $p->staff?->name ?? 'Payroll' }} ({{ $p->period_label }})"
                                            title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-solid fa-money-check-dollar fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Records Found</h5>
                                <p class="text-muted small mb-0">Click <strong>"Send Allowance/Deduction"</strong> to create one.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($payrolls->total() > 0)
        <div class="d-flex flex-wrap align-items-center justify-content-between mt-4">
            <p class="text-muted small mb-0">
                Showing <strong>{{ $payrolls->firstItem() }}</strong> to
                <strong>{{ $payrolls->lastItem() }}</strong> of
                <strong>{{ $payrolls->total() }}</strong> entries
            </p>
            <div>{{ $payrolls->appends(request()->query())->links() }}</div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('.delete-payroll-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.dataset.url;
            const name = this.dataset.name;
            
            Swal.fire({
                title: 'Delete Record?',
                html: `Delete allowance/deduction record for <strong>${name}</strong>?<br>This cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
            }).then(r => {
                if (!r.isConfirmed) return;
                fetch(url, {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json'}
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        Swal.fire({icon: 'success', title: 'Deleted', timer: 1200, showConfirmButton: false})
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', d.message, 'error');
                    }
                });
            });
        });
    });
});
</script>
@endsection
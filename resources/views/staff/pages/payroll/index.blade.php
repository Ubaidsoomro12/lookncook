@extends('staff.layouts.master')

@section('title', 'My Payroll')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Payroll</h4>
            <p class="text-muted small m-0">View your salary history and download payslips</p>
        </div>
        @if($staff)
            <div class="text-end">
                <div class="fw-bold">{{ $staff->name }}</div>
                <div class="text-muted small">
                    {{ $staff->employee_id ?? '—' }}
                    @if($staff->designation) • {{ $staff->designation }} @endif
                </div>
            </div>
        @endif
    </div>

    @if(!$staff)
        <div class="alert alert-warning border-0 d-flex align-items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation fs-3"></i>
            <div>
                <div class="fw-bold">No staff record found</div>
                <div class="small">Your user account isn't linked to a staff profile yet. Please contact the administrator.</div>
            </div>
        </div>
    @else
        @php
            $cards = [
                ['label'=>'This Month',     'value'=>'Rs '.number_format($summary['this_month'], 2),    'icon'=>'fa-calendar-day',    'color'=>'#ff2d7a'],
                ['label'=>'Total Earned',   'value'=>'Rs '.number_format($summary['total_earned'], 2),  'icon'=>'fa-sack-dollar',     'color'=>'#3b82f6'],
                ['label'=>'Total Paid',     'value'=>'Rs '.number_format($summary['total_paid'], 2),    'icon'=>'fa-circle-check',    'color'=>'#10b981'],
                ['label'=>'Pending Payout', 'value'=>'Rs '.number_format($summary['total_pending'], 2), 'icon'=>'fa-hourglass-half',  'color'=>'#f59e0b'],
            ];
        @endphp
        <div class="row g-3 mb-4">
            @foreach($cards as $c)
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:12px;background:{{ $c['color'] }}1a;color:{{ $c['color'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid {{ $c['icon'] }}"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-muted small">{{ $c['label'] }}</div>
                            <div class="fw-bold" style="font-size:17px;">{{ $c['value'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form class="row g-2 align-items-center" method="GET">
                    <div class="col-md-3">
                        <select name="year" class="form-select">
                            <option value="">All Years</option>
                            @for($y = now()->year + 1; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" @selected(request('year')==$y)>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending"   @selected(request('status')=='pending')>Pending</option>
                            <option value="paid"      @selected(request('status')=='paid')>Paid</option>
                            <option value="cancelled" @selected(request('status')=='cancelled')>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn w-100" style="background:#ff2d7a;color:#fff;border:none;">
                            <i class="fa-solid fa-filter me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('staff.payroll.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                                <th>Period</th>
                                <th>Basic</th>
                                <th>Allowances</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th class="text-center" style="width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $p)
                            <tr>
                                <td class="ps-4">{{ $loop->iteration + ($payrolls->currentPage() - 1) * $payrolls->perPage() }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $p->month_name }} {{ $p->period_year }}</div>
                                    <div class="text-muted small">{{ $p->present_days }}P / {{ $p->absent_days }}A</div>
                                </td>
                                <td>Rs {{ number_format($p->basic_salary, 2) }}</td>
                                <td class="text-success">+ Rs {{ number_format($p->total_allowances + $p->overtime_amount, 2) }}</td>
                                <td class="text-danger">- Rs {{ number_format($p->total_deductions, 2) }}</td>
                                <td class="fw-bold" style="color:#ff2d7a;">Rs {{ number_format($p->net_salary, 2) }}</td>
                                <td>{!! $p->status_badge !!}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('staff.payroll.slip', $p->id) }}" target="_blank"
                                           class="btn btn-sm btn-outline-dark" title="View Slip">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fa-solid fa-money-check-dollar fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No Payrolls Yet</h5>
                                    <p class="text-muted small mb-0">Your salary records will appear here once admin creates them.</p>
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
    @endif
</div>
@endsection
@extends('staff.layouts.master')

@section('content')
<div class="container-fluid">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>My Leaves</h4>
        <a href="{{ route('staff.manage_leave.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Apply for Leave
        </a>
    </div>

    {{-- ===== 4 STAT CARDS ===== --}}
    <div class="row mb-3">
        {{-- 👇 CHANGED: Total → Remaining --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg,#4e73df,#224abe);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size: 13px; opacity: .9;">Remaining</div>
                        <div style="font-size: 28px; font-weight: 700;">{{ $remaining }}</div>
                    </div>
                    <i class="fa fa-calendar-check" style="font-size: 32px; opacity: .5;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg,#f6c23e,#dda20a);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size: 13px; opacity: .9;">Pending</div>
                        <div style="font-size: 28px; font-weight: 700;">{{ $pendingCount }}</div>
                    </div>
                    <i class="fa fa-clock" style="font-size: 32px; opacity: .5;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg,#1cc88a,#13855c);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size: 13px; opacity: .9;">Approved</div>
                        <div style="font-size: 28px; font-weight: 700;">{{ $approvedCount }}</div>
                    </div>
                    <i class="fa fa-check-circle" style="font-size: 32px; opacity: .5;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg,#e74a3b,#be2617);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size: 13px; opacity: .9;">Rejected</div>
                        <div style="font-size: 28px; font-weight: 700;">{{ $rejectedCount }}</div>
                    </div>
                    <i class="fa fa-times-circle" style="font-size: 32px; opacity: .5;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ALERTS ===== --}}
    @if(session('success'))
        <div class="alert alert-success" id="successAlert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- ===== LEAVES TABLE ===== --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Employee ID</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th style="width: 130px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $key => $leave)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $leave->employee_name }}</td>
                            <td>{{ $leave->employee_id }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ $leave->from_date->format('d-m-Y') }}</td>
                            <td>{{ $leave->to_date->format('d-m-Y') }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td>
                                @if($leave->status == 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($leave->status == 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if($leave->status == 'Pending')
                                    <a href="{{ route('staff.manage_leave.edit', $leave->id) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary" disabled
                                            title="Only pending leaves can be edited">
                                        <i class="fa fa-lock"></i> Locked
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-3">
                                <i class="fa fa-inbox fa-2x text-muted d-block mb-2"></i>
                                No leave records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(function () {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(function () { successAlert.remove(); }, 500);
            }, 3000);
        }
    });
</script>
@endsection
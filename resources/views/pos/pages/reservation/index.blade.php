@extends('pos.layouts.pos_master')

@section('title', 'Reservations')

@section('content')
<style>
    .res-card { background:#fff; border:1.5px solid #ffd6e5; border-radius:16px; box-shadow:0 2px 8px rgba(255,45,122,.05); }
    .table-res th { background:#f9fafb; font-size:12px; text-transform:uppercase; color:#6b7280; font-weight:700; }
    .table-res td { font-size:13px; vertical-align:middle; }
    
    /* Badges */
    .badge-pending { background:#fef3c7; color:#b45309; border:1px solid #fde68a; }
    .badge-confirmed { background:#d1fae5; color:#047857; border:1px solid #a7f3d0; }
    .badge-completed { background:#e0e7ff; color:#3730a3; border:1px solid #c7d2fe; }
    .badge-cancelled { background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; }
</style>

<div class="container-fluid px-4 py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Reservations</h4>
            <p class="text-muted small m-0">Manage external hall bookings and events</p>
        </div>
        <a href="{{ route('pos.reservations.create') }}" class="btn btn-pink px-4" style="background:linear-gradient(135deg,#ff2d7a,#ff4b91); color:#fff; border:none; padding:10px 20px; border-radius:8px; text-decoration:none;">
            <i class="fa-solid fa-plus me-2"></i>New Reservation
        </a>
    </div>

    <div class="res-card mb-4 p-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" id="searchCustomer" class="form-control" placeholder="Search customer name..." oninput="filterTable()">
            </div>
            <div class="col-md-4">
                <select id="filterStatus" class="form-select" onchange="filterTable()">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" id="filterDate" class="form-control" onchange="filterTable()">
            </div>
        </div>
    </div>

    <div class="res-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 table-res">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Customer</th>
                        <th>Event Date</th>
                        <th>Hall</th>
                        <th>Guests</th>
                        <th>Waiters</th>
                        <th class="text-end">Grand Total</th>
                        <th>Status</th>
                        <th class="text-center" style="width:150px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="reservationTable">
                    @forelse($reservations as $r)
                    <tr class="res-row" data-name="{{ strtolower($r->customer_name) }}" data-status="{{ $r->status }}" data-date="{{ $r->event_date->format('Y-m-d') }}">
                        <td class="ps-4">
                            #{{ $r->id }}
                            @if($r->updater)
                                <i class="fa-solid fa-pen text-muted ms-1" style="font-size:10px;" title="Edited by {{ $r->updater->name }}"></i>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $r->customer_name }}</div>
                            <div class="text-muted small">{{ $r->customer_phone }}</div>
                        </td>
                        <td>{{ $r->event_date->format('d M Y') }}</td>
                        <td>{{ $r->hall_name }}</td>
                        <td>{{ $r->total_guests }} <div class="text-muted small">(Rs {{ number_format($r->per_head_amount, 2) }}/head)</div></td>
                        <td>
                            @foreach($r->waiters as $w)
                                <span class="badge bg-light text-dark border mb-1">{{ $w->name }}</span>
                            @endforeach
                        </td>
                        <td class="text-end fw-bold" style="color:#ff2d7a;">Rs {{ number_format($r->grand_total, 2) }}</td>
                        <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('pos.reservations.edit', $r->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="{{ route('pos.reservations.print', $r->id) }}" target="_blank" class="btn btn-sm btn-outline-dark" title="Print/PDF">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-url="{{ route('pos.reservations.destroy', $r->id) }}" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noServerData">
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-calendar-xmark fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                            No reservations found. Click "New Reservation" to create one.
                        </td>
                    </tr>
                    @endforelse
                    
                    <tr id="noFilterData" style="display:none;">
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-magnifying-glass fa-3x mb-3 d-block" style="color:#e5e7eb;"></i>
                            No reservations match your filters.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function filterTable() {
        const name = document.getElementById('searchCustomer').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const date = document.getElementById('filterDate').value;
        
        const rows = document.querySelectorAll('.res-row');
        let visible = 0;

        rows.forEach(row => {
            const matchName = !name || row.dataset.name.includes(name);
            const matchStatus = !status || row.dataset.status === status;
            const matchDate = !date || row.dataset.date === date;

            if (matchName && matchStatus && matchDate) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noFilterData').style.display = (visible === 0 && rows.length > 0) ? '' : 'none';
    }

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Delete Reservation?',
                text: 'This will permanently delete the reservation and its items.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(this.dataset.url, {
                        method: 'DELETE',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
                    }).then(r => r.json()).then(d => {
                        if(d.success) { location.reload(); }
                        else { Swal.fire('Error', d.message, 'error'); }
                    });
                }
            });
        });
    });
</script>
@endsection
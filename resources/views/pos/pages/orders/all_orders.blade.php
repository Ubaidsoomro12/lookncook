@extends('pos.layouts.pos_master')

@section('title', 'All Orders')

@section('content')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h4 { font-weight: 800; color: #111827; margin-bottom: 4px; }
    .page-header p { color: #6b7280; font-size: 13px; margin: 0; }

    /* Stats Cards */
    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .stat-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .stat-icon.green { background: #ecfdf5; color: #10b981; }
    .stat-icon.red { background: #fef2f2; color: #ef4444; }
    .stat-icon.blue { background: #eff6ff; color: #3b82f6; }
    .stat-value { font-size: 20px; font-weight: 800; color: #111827; margin: 0; line-height: 1.2; }
    .stat-label { font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin: 0; }

    /* Filters Bar */
    .filters-bar {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    /* Table */
    .table-custom {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        min-height: 200px;
    }
    .table-custom thead th {
        background: #f9fafb;
        color: #4b5563;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .table-custom tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .table-custom tbody tr:hover { background: #f9fafb; }
    
    .status-badge {
        padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;
    }
    .status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .status-preparing { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .status-served { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .status-completed { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
    .status-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .amount-text { font-weight: 700; color: #ff2d7a; }

    /* Pagination */
    .pagination-wrapper { padding: 16px; background: #fff; border-top: 1px solid #e5e7eb; }
    
    /* Loading State */
    #tableContainer.loading {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<div class="container-fluid px-4 py-4">
    
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3">
        <div>
            <h4>Order History</h4>
            <p>View all past, completed, and cancelled orders</p>
        </div>
        <a href="{{ route('pos.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Active Orders
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-calendar-day"></i></div>
                <div>
                    <p class="stat-label">Total Today</p>
                    <h3 class="stat-value">{{ number_format($stats['total_today']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <p class="stat-label">Completed</p>
                    <h3 class="stat-value">{{ number_format($stats['completed']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
                <div>
                    <p class="stat-label">Cancelled</p>
                    <h3 class="stat-value">{{ number_format($stats['cancelled']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX Filters Bar -->
    <div class="filters-bar">
        <div class="flex-grow-1" style="min-width: 250px;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                <input type="text" id="ajaxSearch" class="form-control border-start-0" placeholder="Search Order # or Customer Name..." value="{{ request('search') }}">
            </div>
        </div>
        
        <div style="width: 180px;">
            <select id="ajaxStatus" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                <option value="preparing" @selected(request('status') == 'preparing')>Preparing</option>
                <option value="served" @selected(request('status') == 'served')>Served</option>
                <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
            </select>
        </div>

        <div style="width: 180px;">
            <input type="date" id="ajaxDate" class="form-control" value="{{ request('date') }}">
        </div>

        <button type="button" id="ajaxReset" class="btn btn-light border px-3" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></button>
    </div>

    <!-- Orders Table Container -->
    <div class="table-custom" id="tableContainer">
        @include('pos.pages.orders.partials.all_orders_table', ['orders' => $orders])
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('ajaxSearch');
        const statusSelect = document.getElementById('ajaxStatus');
        const dateInput = document.getElementById('ajaxDate');
        const resetBtn = document.getElementById('ajaxReset');
        const tableContainer = document.getElementById('tableContainer');
        
        let debounceTimer;

        // AJAX Request Function
        function fetchOrders() {
            tableContainer.classList.add('loading');
            
            const search = searchInput.value.trim();
            const status = statusSelect.value;
            const date = dateInput.value;
            
            // Build the URL with query parameters
            const url = new URL("{{ route('pos.orders.all') }}", window.location.origin);
            if (search) url.searchParams.append('search', search);
            if (status) url.searchParams.append('status', status);
            if (date) url.searchParams.append('date', date);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                tableContainer.innerHTML = data.html;
                tableContainer.classList.remove('loading');
            })
            .catch(error => {
                console.error('Error fetching orders:', error);
                tableContainer.classList.remove('loading');
            });
        }

        // Debounce for Search Input
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchOrders, 400); // 400ms delay
        });

        // Change events for Filters
        statusSelect.addEventListener('change', fetchOrders);
        dateInput.addEventListener('change', fetchOrders);

        // Reset Button
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusSelect.value = '';
            dateInput.value = '';
            fetchOrders();
        });
    });
</script>
@endsection
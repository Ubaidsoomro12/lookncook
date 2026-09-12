@extends('admin.layouts.master')
@section('title', 'View | Orders')

@section('content')
    <style>
        .ord-page * {
            box-sizing: border-box;
        }

        .ord-full-width {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 16px;
        }

        @media (min-width: 1280px) {
            .ord-full-width {
                padding: 0;
            }
        }

        .ord-header h1 {
            font-size: 24px !important;
            font-weight: 700 !important;
            color: #1f2937;
            margin: 0;
        }

        .ord-header p {
            font-size: 14px;
            color: #6b7280;
            margin: 4px 0 0 0;
        }

        .ord-add-btn {
            background: linear-gradient(to right, #ff2d7a, #ff4b91);
            color: #fff;
            font-weight: 500;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(255, 45, 122, 0.25);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            white-space: nowrap;
            transition: all .2s;
        }

        .ord-add-btn:hover {
            opacity: .9;
            color: #fff;
            text-decoration: none;
        }

        .ord-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .ord-filter-bar {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }

        .ord-search-wrap {
            position: relative;
            width: 100%;
            max-width: 260px;
        }

        .ord-search-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 13px;
        }

        .ord-search {
            width: 100%;
            padding: 10px 16px 10px 36px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            outline: none;
            transition: all .2s;
        }

        .ord-search:focus {
            border-color: #ff2d7a;
            box-shadow: 0 0 0 3px rgba(255, 45, 122, 0.15);
        }

        .ord-select {
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
            outline: none;
            transition: all .2s;
        }

        .ord-select:focus {
            border-color: #ff2d7a;
            box-shadow: 0 0 0 3px rgba(255, 45, 122, 0.15);
        }

        .ord-table-responsive {
            width: 100%;
            overflow-x: scroll !important;
            overflow-y: hidden;
            border-radius: 0 0 16px 16px;
        }

        .ord-table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .ord-table-responsive::-webkit-scrollbar-track {
            background: linear-gradient(to right, #ff2d7a, #ff6fa5);
            border-radius: 0 0 16px 16px;
        }

        .ord-table-responsive::-webkit-scrollbar-thumb {
            background: #111827;
            border-radius: 9999px;
        }

        .ord-table-responsive::-webkit-scrollbar-thumb:hover {
            background: #000;
        }

        .ord-table-responsive::-webkit-scrollbar-button {
            display: none;
            width: 0;
            height: 0;
        }

        .ord-table-responsive {
            scrollbar-width: thin;
            scrollbar-color: #111827 #ff2d7a;
        }

        .ord-page table.ord-table {
            width: 100%;
            min-width: 1180px;
            font-size: 14px;
            text-align: left;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .ord-page .ord-table thead {
            background: #f9fafb;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .05em;
        }

        .ord-page .ord-table thead th {
            padding: 12px 24px;
            font-weight: 600;
            white-space: nowrap;
            border-bottom: 1px solid #e5e7eb;
        }

        .ord-page .ord-table tbody tr {
            border-top: 1px solid #f3f4f6;
        }

        .ord-page .ord-table tbody tr:first-child {
            border-top: none;
        }

        .ord-page .ord-table tbody td {
            padding: 12px 24px;
            vertical-align: middle;
            color: #374151;
        }

        .ord-order-number {
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
        }

        .ord-customer-name {
            font-weight: 500;
            color: #1f2937;
        }

        .ord-customer-email {
            font-size: 11px;
            color: #9ca3af;
        }

        .ord-total {
            font-weight: 700;
            color: #ff2d7a;
            white-space: nowrap;
        }

        .ord-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: opacity .2s;
        }

        .ord-thumb:hover {
            opacity: .8;
        }

        .ord-date-cell {
            color: #9ca3af;
            white-space: nowrap;
            font-size: 12px;
        }

        .ord-badge {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid;
            display: inline-block;
            white-space: nowrap;
        }

        .ord-badge-green {
            background: #ecfdf5;
            color: #16a34a;
            border-color: #bbf7d0;
        }

        .ord-badge-amber {
            background: #fffbeb;
            color: #d97706;
            border-color: #fde68a;
        }

        .ord-badge-red {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .ord-badge-blue {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .ord-badge-gray {
            background: #f3f4f6;
            color: #6b7280;
            border-color: #e5e7eb;
        }

        .ord-badge-greendark {
            background: #f0fdf4;
            color: #15803d;
            border-color: #86efac;
        }

        .ord-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #fbcfe8;
        }

        .ord-avatar-fallback {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #fdf2f8;
            border: 1px solid #fbcfe8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff2d7a;
            font-size: 11px;
            font-weight: 700;
        }

        .ord-rider-name {
            font-size: 12px;
            font-weight: 500;
            color: #1f2937;
            margin: 0;
        }

        .ord-rider-eta {
            font-size: 10px;
            color: #9ca3af;
        }

        .ord-rider-unassigned {
            color: #d1d5db;
            font-size: 12px;
        }

        .ord-actions-group {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .ord-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
        }

        .ord-action-assign {
            background: #fdf2f8;
            color: #ff2d7a;
        }

        .ord-action-assign:hover {
            background: #fbcfe8;
        }

        .ord-action-view {
            background: #eef2ff;
            color: #4f46e5;
        }

        .ord-action-view:hover {
            background: #e0e7ff;
        }

        .ord-action-approve {
            background: #ecfdf5;
            color: #059669;
        }

        .ord-action-approve:hover {
            background: #d1fae5;
        }

        .ord-action-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .ord-action-delete:hover {
            background: #fee2e2;
        }

        /* Toast Styles */
        #toastContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
            max-width: 380px;
            padding: 0 16px;
        }

        @media (min-width: 640px) {
            #toastContainer {
                padding: 0;
            }
        }

        .toast-item {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.3s ease-out;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .toast-item.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-item.success {
            border-color: #86efac;
        }

        .toast-item.error {
            border-color: #fca5a5;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }

        .toast-icon.success {
            background: #ecfdf5;
        }

        .toast-icon.error {
            background: #fef2f2;
        }

        .toast-icon i.success {
            color: #22c55e;
        }

        .toast-icon i.error {
            color: #dc2626;
        }

        .toast-content {
            flex: 1;
        }

        .toast-content .toast-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .toast-content .toast-message {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 0;
        }

        .toast-close {
            background: none;
            border: none;
            color: #d1d5db;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: #6b7280;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: linear-gradient(to right, #ff2d7a, #ff4b91);
            transition: width 5s linear;
        }

        .toast-item.error .toast-progress {
            background: #dc2626;
        }

        /* Bootstrap 5 Custom Overrides */
        .bg-pink-soft {
            background-color: #fdf2f8;
        }

        .text-pink {
            color: #ff2d7a;
        }

        .border-pink-soft {
            border-color: #fbcfe8;
        }

        .btn-pink {
            background: linear-gradient(to right, #ff2d7a, #ff4b91);
            color: #fff;
            font-weight: 500;
            border: none;
            box-shadow: 0 4px 12px rgba(255, 45, 122, 0.25);
            transition: all .2s;
        }

        .btn-pink:hover {
            color: #fff;
            opacity: .9;
        }

        .bg-pink-light {
            background-color: #fdf2f8;
        }

        .w-16 {
            width: 64px;
        }

        .h-16 {
            height: 64px;
        }

        /* Modal z-index fixes */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }

        /* Export Status Styles */
        #exportStatus {
            font-size: 12px;
            font-weight: 500;
        }
        #exportStatus.text-success {
            color: #16a34a !important;
        }
        #exportStatus.text-danger {
            color: #dc2626 !important;
        }
        #exportStatus.text-info {
            color: #2563eb !important;
        }
    </style>

    <div class="ord-page">
        <div class="ord-full-width">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                <div class="ord-header">
                    <h1>Manage Orders</h1>
                    <p>Manage all customer orders and payment statuses.</p>
                </div>
                <a href="{{ route('admin.payment-methods.index') }}" class="ord-add-btn">
                    <i class="fa-solid fa-credit-card me-2"></i> Manage Payment Methods
                </a>
            </div>

            <div class="ord-card">
                <div class="ord-filter-bar">
                    <div class="ord-search-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="orderSearchInput" placeholder="Search Order #, Customer..."
                            class="ord-search" autocomplete="off">
                    </div>
                    <select id="filterMethod" class="ord-select">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                    <select id="filterStatus" class="ord-select">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="failed">Failed</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="ord-table-responsive">
                    <table class="ord-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Total</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Screenshot</th>
                                <th>Rider Status</th>
                                <th>Rider</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            @php
                                $assignStatusColors = [
                                    'review' => 'ord-badge-amber',
                                    'preparing' => 'ord-badge-blue',
                                    'completed' => 'ord-badge-green',
                                    'delivered' => 'ord-badge-greendark',
                                ];
                            @endphp
                            @forelse($orders as $order)
                                <tr data-row-id="{{ $order->id }}" data-method="{{ $order->payment_method_id }}"
                                    data-status="{{ $order->payment_status }}">
                                    <td class="ord-order-number">#{{ $order->order_number }}</td>
                                    <td>
                                        <p class="ord-customer-name mb-0">{{ $order->customer_name }}</p>
                                        <p class="ord-customer-email mb-0">{{ $order->customer_email }}</p>
                                    </td>
                                    <td class="text-secondary">{{ $order->customer_phone ?? '—' }}</td>
                                    <td class="ord-total">Rs. {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-secondary">{{ $order->paymentMethod?->name ?? '—' }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match ($order->payment_status) {
                                                'approved' => 'ord-badge-green',
                                                'pending' => 'ord-badge-amber',
                                                'failed' => 'ord-badge-red',
                                                'completed' => 'ord-badge-blue',
                                                default => 'ord-badge-gray'
                                            };
                                        @endphp
                                        <span
                                            class="ord-badge payment-status-badge {{ $badgeClass }}">{{ ucfirst($order->payment_status) }}</span>
                                    </td>
                                    <td>
                                        @if($order->payment_screenshot)
                                            <a href="{{ asset($order->payment_screenshot) }}" target="_blank">
                                                <img src="{{ asset($order->payment_screenshot) }}" class="ord-thumb">
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="ord-badge rider-status-badge {{ $assignStatusColors[$order->status] ?? 'ord-badge-gray' }}">
                                            {{ ucfirst($order->status ?? 'review') }}
                                        </span>
                                    </td>
                                    <td class="rider-cell">
                                        @if($order->rider)
                                            <div class="d-flex align-items-center gap-2">
                                                @if($order->rider->image_url)
                                                    <img src="{{ $order->rider->image_url }}" alt="{{ $order->rider->name }}"
                                                        class="ord-avatar">
                                                @else
                                                    <div class="ord-avatar-fallback">{{ strtoupper(substr($order->rider->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="ord-rider-name">{{ $order->rider->name }}</p>
                                                    @if($order->estimated_time)
                                                        <span class="ord-rider-eta"><i class="fa-regular fa-clock"></i>
                                                            {{ $order->estimated_time }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="ord-rider-unassigned">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="ord-date-cell">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td class="text-end">
                                        <div class="ord-actions-group">
                                            @if($order->payment_status === 'approved')
                                                <button type="button" class="assign-rider-btn ord-action-btn ord-action-assign"
                                                    data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}"
                                                    data-rider-id="{{ $order->rider_assigned }}"
                                                    data-estimated-time="{{ $order->estimated_time }}"
                                                    data-status="{{ $order->status ?? 'review' }}"
                                                    data-url="{{ route('admin.orders.assign', $order->id) }}" title="Assign Rider">
                                                    <i class="fa-solid fa-motorcycle" style="font-size:11px;"></i>
                                                </button>
                                            @endif
                                            @if(in_array($order->payment_status, ['pending', 'failed']))
                                                <button type="button" class="approve-order-btn ord-action-btn ord-action-approve"
                                                    data-url="{{ route('admin.payments.approve', $order->id) }}"
                                                    title="Approve Payment">
                                                    <i class="fa-solid fa-check" style="font-size:11px;"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="view-order-btn ord-action-btn ord-action-view"
                                                data-url="{{ route('admin.payments.show', $order->id) }}" title="View Details">
                                                <i class="fa-solid fa-eye" style="font-size:11px;"></i>
                                            </button>
                                            <button type="button" class="delete-order-btn ord-action-btn ord-action-delete"
                                                data-id="{{ $order->id }}" data-name="Order #{{ $order->order_number }}"
                                                data-url="{{ route('admin.payments.destroy', $order->id) }}" title="Delete">
                                                <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="11" class="text-center text-secondary py-5">
                                        <i class="fa-solid fa-receipt d-block mb-2" style="font-size:24px;"></i>
                                        No orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background: #fdf2f8; font-weight: 700; border-top: 2px solid #ff2d7a;">
                                <td colspan="3" class="text-end fw-bold text-pink">TOTALS</td>
                                <td class="fw-bold text-pink">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</td>
                                <td colspan="7"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small">
                            Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of
                            {{ $orders->total() }} orders
                        </div>
                        <div>
                            {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-receipt text-pink fs-3"></i>
                                <h5 class="fw-bold mt-2 mb-0">{{ $totalOrders }}</h5>
                                <small class="text-muted">Total Orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-users text-primary fs-3"></i>
                                <h5 class="fw-bold mt-2 mb-0">{{ $totalCustomers }}</h5>
                                <small class="text-muted">Total Customers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-dollar-sign text-success fs-3"></i>
                                <h5 class="fw-bold mt-2 mb-0 text-pink">Rs. {{ number_format($totalAmount, 0) }}</h5>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-check-circle text-success fs-3"></i>
                                <h5 class="fw-bold mt-2 mb-0">{{ $totalApproved }}</h5>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-clock text-warning fs-3"></i>
                                <h5 class="fw-bold mt-2 mb-0">{{ $totalPending }}</h5>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fa-solid fa-motorcycle text-pink fs-3"></i>
                                <h5 class="fw-bold mt-2 mb-0">{{ $totalRiderAssigned }}</h5>
                                <small class="text-muted">Riders Assigned</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export Controls -->
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-8">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <label class="fw-medium text-nowrap">Export:</label>
                            <select id="exportFilterType" class="form-select form-select-sm" style="width:auto;">
                                <option value="all">All Records</option>
                                <option value="day">Today</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                            <input type="date" id="exportFilterDate" class="form-control form-control-sm"
                                style="width:auto;" value="{{ date('Y-m-d') }}">
                            <button id="exportBtn" class="btn btn-pink btn-sm">
                                <i class="fa-solid fa-file-excel me-1"></i> Export to Excel
                            </button>
                            <span id="exportStatus" class="text-muted small"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex justify-content-end gap-2 align-items-center">
                            <label class="fw-medium text-nowrap text-muted small">Show:</label>
                            <select id="perPageSelect" class="form-select form-select-sm" style="width:auto;">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ORDER DETAILS MODAL -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h3 class="modal-title fw-bold text-dark" id="orderDetailsModalLabel">Order Details</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div id="orderDetailsContent" class="text-center py-3 text-secondary">
                        <i class="fa-solid fa-circle-notch fa-spin fs-2"></i> Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ASSIGN RIDER MODAL -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h3 class="modal-title fw-bold text-dark" id="assignModalLabel">Assign Rider — <span
                            id="assignModalOrderNumber" class="text-pink"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assignForm">
                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Select Rider <span
                                    class="text-danger">*</span></label>
                            <select id="assignRiderSelect" name="rider_id" required class="form-select rounded-3">
                                <option value="">-- Choose an available rider --</option>
                                @foreach($riders as $rider)
                                    <option value="{{ $rider->id }}">
                                        {{ $rider->name }}
                                        ({{ ucfirst($rider->vehicle_type) }}{{ $rider->vehicle_number ? ' - ' . $rider->vehicle_number : '' }})
                                    </option>
                                @endforeach
                            </select>
                            @if($riders->isEmpty())
                                <p class="text-warning small mt-1">No active riders found. Add one from Rider Management first.
                                </p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Estimated Delivery Time <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="assignEstimatedTime" name="estimated_time" required
                                placeholder="e.g. 30-40 mins" class="form-control rounded-3">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark">Order Status <span
                                    class="text-danger">*</span></label>
                            <select id="assignStatusSelect" name="status" required class="form-select rounded-3">
                                <option value="review">Review</option>
                                <option value="preparing">Preparing</option>
                                <option value="completed">Completed</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>

                        <div class="d-flex gap-3 pt-2">
                            <button type="submit" id="assignSubmitBtn" class="btn btn-pink flex-grow-1 rounded-3">
                                <span id="assignSubmitText">Assign Rider</span>
                                <i id="assignSubmitSpinner" class="fa-solid fa-circle-notch fa-spin d-none"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-3 flex-grow-1"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- APPROVE MODAL -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow-lg text-center">
                <div class="modal-body p-4">
                    <div class="w-16 h-16 rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="box-shadow: 0 0 0 8px rgba(16,185,129,0.2);">
                        <i class="fa-solid fa-check text-success fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark" id="approveModalLabel">Approve Payment?</h3>
                    <p class="text-secondary mt-2">
                        Are you sure you want to approve
                        <span id="approveModalItemName" class="fw-semibold text-dark">this order</span>?
                        This will mark the payment as <strong class="text-success">Approved</strong>.
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <button type="button" class="btn btn-outline-secondary flex-grow-1 rounded-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="approveModalConfirmBtn" class="btn btn-success flex-grow-1 rounded-3">
                            <span id="approveModalConfirmText">Yes, Approve</span>
                            <i id="approveModalSpinner" class="fa-solid fa-circle-notch fa-spin d-none"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow-lg text-center">
                <div class="modal-body p-4">
                    <div class="w-16 h-16 rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="box-shadow: 0 0 0 8px rgba(239,68,68,0.2);">
                        <i class="fa-solid fa-trash-can text-danger fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark" id="deleteModalLabel">Delete Order?</h3>
                    <p class="text-secondary mt-2">
                        Are you sure you want to delete
                        <span id="deleteModalItemName" class="fw-semibold text-dark">this order</span>?
                        This action cannot be undone.
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <button type="button" class="btn btn-outline-secondary flex-grow-1 rounded-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="deleteModalConfirmBtn" class="btn btn-danger flex-grow-1 rounded-3">
                            <span id="deleteModalConfirmText">Yes, Delete</span>
                            <i id="deleteModalSpinner" class="fa-solid fa-circle-notch fa-spin d-none"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST CONTAINER -->
    <div id="toastContainer"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ==================================================================
            // TOAST NOTIFICATION SYSTEM
            // ==================================================================
            const toastContainer = document.getElementById('toastContainer');
            
            function showToast(message, type = 'success', duration = 5000) {
                const isSuccess = type === 'success';
                const toast = document.createElement('div');
                toast.className = `toast-item show ${isSuccess ? 'success' : 'error'}`;
                toast.innerHTML = `
                    <div class="toast-icon ${isSuccess ? 'success' : 'error'}">
                        <i class="fa-solid ${isSuccess ? 'fa-check success' : 'fa-xmark error'}"></i>
                    </div>
                    <div class="toast-content">
                        <p class="toast-title">${isSuccess ? 'Success' : 'Error'}</p>
                        <p class="toast-message">${message}</p>
                    </div>
                    <button class="toast-close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="toast-progress"></div>
                `;
                toastContainer.appendChild(toast);
                requestAnimationFrame(() => toast.classList.remove('translate-x-[120%]', 'opacity-0'));
                const progressBar = toast.querySelector('.toast-progress');
                progressBar.style.transition = `width ${duration}ms linear`;
                requestAnimationFrame(() => requestAnimationFrame(() => { progressBar.style.width = '0%'; }));
                toast.querySelector('.toast-close').addEventListener('click', () => removeToast(toast));
                const timer = setTimeout(() => removeToast(toast), duration);
                toast.addEventListener('mouseenter', () => { clearTimeout(timer); progressBar.style.transition = 'none'; });
                
                function removeToast(el) { 
                    el.classList.add('translate-x-[120%]', 'opacity-0'); 
                    setTimeout(() => el.remove(), 300); 
                }
            }

            @if(session('success')) showToast(@json(session('success')), 'success'); @endif
            @if(session('error')) showToast(@json(session('error')), 'error'); @endif

            // ==================================================================
            // VARIABLES
            // ==================================================================
            const assignStatusColors = {
                review: 'ord-badge-amber',
                preparing: 'ord-badge-blue',
                completed: 'ord-badge-green',
                delivered: 'ord-badge-greendark',
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";
            const tableBody = document.getElementById('ordersTableBody');
            const searchInput = document.getElementById('orderSearchInput');
            const filterMethod = document.getElementById('filterMethod');
            const filterStatus = document.getElementById('filterStatus');

            // ==================================================================
            // FILTER & SEARCH FUNCTIONS
            // ==================================================================
            function filterTable() {
                const searchVal = searchInput.value.toLowerCase();
                const methodVal = filterMethod.value;
                const statusVal = filterStatus.value.toLowerCase();

                tableBody.querySelectorAll('tr').forEach(row => {
                    if (row.id === 'emptyRow') return;
                    const text = row.innerText.toLowerCase();
                    const rowMethod = row.dataset.method || '';
                    const rowStatus = row.dataset.status || '';

                    const matchesSearch = text.includes(searchVal);
                    const matchesMethod = methodVal === '' || rowMethod === methodVal;
                    const matchesStatus = statusVal === '' || rowStatus === statusVal;

                    row.style.display = (matchesSearch && matchesMethod && matchesStatus) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterTable);
            filterMethod.addEventListener('change', filterTable);
            filterStatus.addEventListener('change', filterTable);

            // ==================================================================
            // ORDER DETAILS MODAL
            // ==================================================================
            const detailsModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

            function openDetailsModal(url) {
                const detailsContent = document.getElementById('orderDetailsContent');
                detailsContent.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin fs-2"></i> Loading...`;
                detailsModal.show();

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.html) {
                        detailsContent.innerHTML = data.html;
                    } else {
                        detailsContent.innerHTML = `<p class="text-danger">Failed to load order details.</p>`;
                    }
                })
                .catch(() => {
                    detailsContent.innerHTML = `<p class="text-danger">An error occurred.</p>`;
                });
            }

            tableBody.addEventListener('click', function (e) {
                const btn = e.target.closest('.view-order-btn');
                if (btn) openDetailsModal(btn.dataset.url);
            });

            // ==================================================================
            // ASSIGN RIDER MODAL
            // ==================================================================
            const assignModal = new bootstrap.Modal(document.getElementById('assignModal'));
            let assignFormUrl = null;
            const assignForm = document.getElementById('assignForm');
            const assignRiderSelect = document.getElementById('assignRiderSelect');
            const assignEstimatedTime = document.getElementById('assignEstimatedTime');
            const assignStatusSelect = document.getElementById('assignStatusSelect');
            const assignSubmitBtn = document.getElementById('assignSubmitBtn');
            const assignSubmitText = document.getElementById('assignSubmitText');
            const assignSubmitSpinner = document.getElementById('assignSubmitSpinner');

            function openAssignModal(btn) {
                assignFormUrl = btn.dataset.url;
                document.getElementById('assignModalOrderNumber').textContent = '#' + btn.dataset.orderNumber;
                assignRiderSelect.value = btn.dataset.riderId && btn.dataset.riderId !== '' ? btn.dataset.riderId : '';
                assignEstimatedTime.value = btn.dataset.estimatedTime && btn.dataset.estimatedTime !== 'null' ? btn.dataset.estimatedTime : '';
                assignStatusSelect.value = btn.dataset.status || 'review';
                assignSubmitText.textContent = (btn.dataset.riderId && btn.dataset.riderId !== '') ? 'Update Assignment' : 'Assign Rider';
                assignModal.show();
            }

            tableBody.addEventListener('click', function (e) {
                const btn = e.target.closest('.assign-rider-btn');
                if (btn) openAssignModal(btn);
            });

            function updateRiderCell(order) {
                const row = tableBody.querySelector(`tr[data-row-id="${order.id}"]`);
                if (!row) return;

                const riderCell = row.querySelector('.rider-cell');
                const initial = order.rider_name ? order.rider_name.charAt(0).toUpperCase() : '?';
                const photoHtml = order.rider_image
                    ? `<img src="${order.rider_image}" alt="${order.rider_name}" class="ord-avatar">`
                    : `<div class="ord-avatar-fallback">${initial}</div>`;
                const statusClass = assignStatusColors[order.status] || 'ord-badge-gray';
                const statusLabel = order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1) : 'Review';

                if (riderCell) {
                    riderCell.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            ${photoHtml}
                            <div>
                                <p class="ord-rider-name">${order.rider_name ?? '—'}</p>
                                <span class="ord-badge ${statusClass}">${statusLabel}</span>
                            </div>
                        </div>`;
                }

                const statusCell = row.querySelector('td:nth-child(8) .ord-badge');
                if (statusCell) {
                    statusCell.className = `ord-badge ${statusClass}`;
                    statusCell.textContent = statusLabel;
                }

                const assignBtn = row.querySelector('.assign-rider-btn');
                if (assignBtn) {
                    assignBtn.dataset.riderId = order.rider_id ?? '';
                    assignBtn.dataset.estimatedTime = order.estimated_time ?? '';
                    assignBtn.dataset.status = order.status ?? 'review';
                }
            }

            assignForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!assignFormUrl) return;

                const riderId = assignRiderSelect.value;
                if (!riderId || riderId === '') {
                    alert('Please select a rider from the dropdown list.');
                    return;
                }

                assignSubmitBtn.disabled = true;
                assignSubmitSpinner.classList.remove('d-none');

                fetch(assignFormUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        rider_id: riderId,
                        estimated_time: assignEstimatedTime.value,
                        status: assignStatusSelect.value,
                    })
                })
                .then(async res => {
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || errorData.errors || 'Server error');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        updateRiderCell(data.order);
                        showToast(data.message, 'success');
                        assignModal.hide();
                    } else {
                        showToast(data.message || 'Failed to assign rider.', 'error');
                    }
                })
                .catch(err => {
                    showToast(err.message || 'Something went wrong while assigning the rider.', 'error');
                })
                .finally(() => {
                    assignSubmitBtn.disabled = false;
                    assignSubmitSpinner.classList.add('d-none');
                });
            });

            // ==================================================================
            // APPROVE MODAL
            // ==================================================================
            const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
            let pendingApproveUrl = null;
            let pendingApproveRow = null;

            tableBody.addEventListener('click', function (e) {
                const btn = e.target.closest('.approve-order-btn');
                if (btn) {
                    pendingApproveUrl = btn.dataset.url;
                    pendingApproveRow = btn.closest('tr');
                    const orderNumberCol = btn.closest('tr').querySelector('td.ord-order-number');
                    const orderName = orderNumberCol ? orderNumberCol.innerText.trim() : 'Order';
                    document.getElementById('approveModalItemName').textContent = orderName ? `"${orderName}"` : 'this order';
                    approveModal.show();
                }
            });

            document.getElementById('approveModalConfirmBtn').addEventListener('click', function () {
                if (!pendingApproveUrl) return;
                const confirmBtn = this;
                const confirmText = document.getElementById('approveModalConfirmText');
                const spinner = document.getElementById('approveModalSpinner');

                confirmBtn.disabled = true;
                confirmText.textContent = 'Approving...';
                spinner.classList.remove('d-none');

                fetch(pendingApproveUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Failed to approve');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        if (pendingApproveRow) {
                            const statusCell = pendingApproveRow.querySelector('td .ord-badge.payment-status-badge');
                            if (statusCell) {
                                statusCell.className = 'ord-badge payment-status-badge ord-badge-green';
                                statusCell.innerText = 'Approved';
                            }
                            pendingApproveRow.dataset.status = 'approved';
                            const approveBtn = pendingApproveRow.querySelector('.approve-order-btn');
                            if (approveBtn) approveBtn.remove();

                            const actionsCell = pendingApproveRow.querySelector('td .ord-actions-group');
                            if (actionsCell && !actionsCell.querySelector('.assign-rider-btn')) {
                                const orderId = pendingApproveRow.dataset.rowId;
                                const orderNumberEl = pendingApproveRow.querySelector('td.ord-order-number');
                                const orderNumber = orderNumberEl ? orderNumberEl.innerText.replace('#', '') : '';
                                const assignBtnHtml = `
                                    <button type="button" class="assign-rider-btn ord-action-btn ord-action-assign"
                                            data-order-id="${orderId}"
                                            data-order-number="${orderNumber}"
                                            data-rider-id=""
                                            data-estimated-time=""
                                            data-status="review"
                                            data-url="/admin/orders/${orderId}/assign"
                                            title="Assign Rider">
                                        <i class="fa-solid fa-motorcycle" style="font-size:11px;"></i>
                                    </button>
                                `;
                                actionsCell.insertAdjacentHTML('afterbegin', assignBtnHtml);
                            }
                        }
                        showToast(data.message, 'success');
                        approveModal.hide();
                    } else {
                        showToast(data.message || 'Failed to approve payment.', 'error');
                        approveModal.hide();
                    }
                })
                .catch(err => {
                    showToast(err.message, 'error');
                    approveModal.hide();
                })
                .finally(() => {
                    confirmBtn.disabled = false;
                    confirmText.textContent = 'Yes, Approve';
                    spinner.classList.add('d-none');
                });
            });

            // ==================================================================
            // DELETE MODAL
            // ==================================================================
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let pendingDeleteUrl = null;
            let pendingDeleteRow = null;

            tableBody.addEventListener('click', function (e) {
                const btn = e.target.closest('.delete-order-btn');
                if (btn) {
                    pendingDeleteUrl = btn.dataset.url;
                    pendingDeleteRow = btn.closest('tr');
                    document.getElementById('deleteModalItemName').textContent = btn.dataset.name || 'this order';
                    deleteModal.show();
                }
            });

            document.getElementById('deleteModalConfirmBtn').addEventListener('click', function () {
                if (!pendingDeleteUrl) return;
                const confirmBtn = this;
                const confirmText = document.getElementById('deleteModalConfirmText');
                const spinner = document.getElementById('deleteModalSpinner');

                confirmBtn.disabled = true;
                confirmText.textContent = 'Deleting...';
                spinner.classList.remove('d-none');

                fetch(pendingDeleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Failed to delete');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        pendingDeleteRow?.remove();
                        if (!tableBody.querySelector('tr:not(#emptyRow)')) {
                            tableBody.innerHTML = `<tr id="emptyRow"><td colspan="11" class="text-center text-secondary py-5"><i class="fa-solid fa-receipt d-block mb-2" style="font-size:24px;"></i>No orders found.</td></tr>`;
                        }
                        showToast(data.message, 'success');
                        deleteModal.hide();
                    } else {
                        showToast(data.message || 'Failed to delete order.', 'error');
                        deleteModal.hide();
                    }
                })
                .catch(err => {
                    showToast(err.message, 'error');
                    deleteModal.hide();
                })
                .finally(() => {
                    confirmBtn.disabled = false;
                    confirmText.textContent = 'Yes, Delete';
                    spinner.classList.add('d-none');
                });
            });

            // ==================================================================
            // EXPORT TO CSV
            // ==================================================================
            const exportBtn = document.getElementById('exportBtn');
            const exportFilterType = document.getElementById('exportFilterType');
            const exportFilterDate = document.getElementById('exportFilterDate');
            const exportStatus = document.getElementById('exportStatus');

            if (exportBtn) {
                // Update date input visibility based on filter type
                exportFilterType.addEventListener('change', function() {
                    if (this.value === 'all') {
                        exportFilterDate.style.display = 'none';
                        exportFilterDate.value = '';
                    } else {
                        exportFilterDate.style.display = 'inline-block';
                        if (this.value === 'day') {
                            exportFilterDate.type = 'date';
                            const today = new Date();
                            exportFilterDate.value = today.toISOString().split('T')[0];
                        } else if (this.value === 'month') {
                            exportFilterDate.type = 'month';
                            const today = new Date();
                            exportFilterDate.value = today.toISOString().slice(0, 7);
                        } else if (this.value === 'year') {
                            exportFilterDate.type = 'number';
                            exportFilterDate.placeholder = 'YYYY';
                            exportFilterDate.min = '2020';
                            exportFilterDate.max = new Date().getFullYear();
                            exportFilterDate.value = new Date().getFullYear();
                            exportFilterDate.style.width = '100px';
                        }
                    }
                });
                // Trigger initial
                exportFilterType.dispatchEvent(new Event('change'));

                exportBtn.addEventListener('click', function() {
                    const filterType = exportFilterType.value;
                    let filterDate = exportFilterDate.value;
                    
                    if (filterType === 'all') {
                        filterDate = '';
                    } else if (!filterDate) {
                        showToast('Please select a date.', 'error');
                        return;
                    }
                    
                    // Disable button and show loading
                    exportBtn.disabled = true;
                    exportBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Exporting...';
                    exportStatus.textContent = 'Processing...';
                    exportStatus.className = 'text-info small';
                    
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    
                    fetch('{{ route('admin.payments.export') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            filter_type: filterType,
                            filter_date: filterDate
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Server error');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            exportStatus.className = 'text-success small';
                            exportStatus.textContent = '✅ ' + data.count + ' orders exported and deleted.';
                            showToast(data.message, 'success');
                            // Download the file
                            const link = document.createElement('a');
                            link.href = data.file;
                            link.download = data.filename;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            // Reload page after 3 seconds
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            exportStatus.className = 'text-danger small';
                            exportStatus.textContent = '❌ ' + data.message;
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error('Export error:', err);
                        exportStatus.className = 'text-danger small';
                        exportStatus.textContent = '❌ Error: ' + err.message;
                        showToast('Export error: ' + err.message, 'error');
                    })
                    .finally(() => {
                        exportBtn.disabled = false;
                        exportBtn.innerHTML = '<i class="fa-solid fa-file-excel me-1"></i> Export to Excel';
                    });
                });
            }

            // ==================================================================
            // PER PAGE CHANGE
            // ==================================================================
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', this.value);
                    window.location.href = url.toString();
                });
            }
        });
    </script>

    @stack('scripts')
@endsection
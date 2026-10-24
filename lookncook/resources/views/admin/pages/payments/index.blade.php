@extends('admin.layouts.master')
@section('title', 'View | Orders')

@section('content')
<style>
  .ord-page * { box-sizing: border-box; }
  .ord-full-width { max-width: 1280px; margin: 0 auto; padding: 0 16px; }
  @media (min-width: 1280px) { .ord-full-width { padding: 0; } }

  .ord-header h1 { font-size: 24px !important; font-weight: 700 !important; color: #1f2937; margin: 0; }
  .ord-header p { font-size: 14px; color: #6b7280; margin: 4px 0 0 0; }

  .ord-add-btn {
    background: linear-gradient(to right, #ff2d7a, #ff4b91);
    color: #fff; font-weight: 500; font-size: 14px;
    padding: 10px 20px; border-radius: 12px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.25);
    border: none; display: inline-flex; align-items: center; gap: 8px;
    text-decoration: none; white-space: nowrap; transition: all .2s;
  }
  .ord-add-btn:hover { opacity: .9; color: #fff; text-decoration: none; }

  .ord-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }

  .ord-filter-bar { padding: 16px; border-bottom: 1px solid #f3f4f6; display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }

  .ord-search-wrap { position: relative; width: 100%; max-width: 260px; }
  .ord-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }
  .ord-search {
    width: 100%; padding: 10px 16px 10px 36px; border-radius: 12px;
    border: 1px solid #e5e7eb; font-size: 14px; outline: none; transition: all .2s;
  }
  .ord-search:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.15); }

  .ord-select {
    padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; background: #fff; outline: none; transition: all .2s;
  }
  .ord-select:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.15); }

  .ord-table-responsive {
    width: 100%;
    overflow-x: scroll !important;
    overflow-y: hidden;
    border-radius: 0 0 16px 16px;
  }
  .ord-table-responsive::-webkit-scrollbar { height: 8px; }
  .ord-table-responsive::-webkit-scrollbar-track {
    background: linear-gradient(to right, #ff2d7a, #ff6fa5);
    border-radius: 0 0 16px 16px;
  }
  .ord-table-responsive::-webkit-scrollbar-thumb { background: #111827; border-radius: 9999px; }
  .ord-table-responsive::-webkit-scrollbar-thumb:hover { background: #000; }
  .ord-table-responsive::-webkit-scrollbar-button { display: none; width: 0; height: 0; }
  .ord-table-responsive { scrollbar-width: thin; scrollbar-color: #111827 #ff2d7a; }

  .ord-page table.ord-table { width: 100%; min-width: 1180px; font-size: 14px; text-align: left; border-collapse: collapse; margin-bottom: 0; }
  .ord-page .ord-table thead { background: #f9fafb; color: #6b7280; text-transform: uppercase; font-size: 11px; letter-spacing: .05em; }
  .ord-page .ord-table thead th { padding: 12px 24px; font-weight: 600; white-space: nowrap; border-bottom: 1px solid #e5e7eb; }
  .ord-page .ord-table tbody tr { border-top: 1px solid #f3f4f6; }
  .ord-page .ord-table tbody tr:first-child { border-top: none; }
  .ord-page .ord-table tbody td { padding: 12px 24px; vertical-align: middle; color: #374151; }

  .ord-order-number { font-weight: 600; color: #1f2937; white-space: nowrap; }
  .ord-customer-name { font-weight: 500; color: #1f2937; }
  .ord-customer-email { font-size: 11px; color: #9ca3af; }
  .ord-total { font-weight: 700; color: #ff2d7a; white-space: nowrap; }
  .ord-thumb { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; transition: opacity .2s; }
  .ord-thumb:hover { opacity: .8; }
  .ord-date-cell { color: #9ca3af; white-space: nowrap; font-size: 12px; }

  .ord-badge { padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; border: 1px solid; display: inline-block; white-space: nowrap; }
  .ord-badge-green  { background:#ecfdf5; color:#16a34a; border-color:#bbf7d0; }
  .ord-badge-amber  { background:#fffbeb; color:#d97706; border-color:#fde68a; }
  .ord-badge-red    { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
  .ord-badge-blue   { background:#eff6ff; color:#2563eb; border-color:#bfdbfe; }
  .ord-badge-gray   { background:#f3f4f6; color:#6b7280; border-color:#e5e7eb; }
  .ord-badge-greendark { background:#f0fdf4; color:#15803d; border-color:#86efac; }

  .ord-avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 1px solid #fbcfe8; }
  .ord-avatar-fallback {
    width: 28px; height: 28px; border-radius: 50%; background: #fdf2f8; border: 1px solid #fbcfe8;
    display: flex; align-items: center; justify-content: center; color: #ff2d7a; font-size: 11px; font-weight: 700;
  }
  .ord-rider-name { font-size: 12px; font-weight: 500; color: #1f2937; margin: 0; }
  .ord-rider-eta { font-size: 10px; color: #9ca3af; }
  .ord-rider-unassigned { color: #d1d5db; font-size: 12px; }

  .ord-actions-group { display: flex; justify-content: flex-end; gap: 8px; }
  .ord-action-btn { width: 32px; height: 32px; border-radius: 8px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: all .2s; }
  .ord-action-assign  { background:#fdf2f8; color:#ff2d7a; }
  .ord-action-assign:hover  { background:#fbcfe8; }
  .ord-action-view    { background:#eef2ff; color:#4f46e5; }
  .ord-action-view:hover    { background:#e0e7ff; }
  .ord-action-approve { background:#ecfdf5; color:#059669; }
  .ord-action-approve:hover { background:#d1fae5; }
  .ord-action-delete  { background:#fef2f2; color:#dc2626; }
  .ord-action-delete:hover  { background:#fee2e2; }

  .ord-modal-content { border: none; border-radius: 16px; }
  .ord-modal-icon-circle {
    width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px auto;
  }
  .ord-icon-green { background:#ecfdf5; box-shadow: 0 0 0 8px rgba(209,250,229,.5); color:#10b981; }
  .ord-icon-red   { background:#fef2f2; box-shadow: 0 0 0 8px rgba(254,226,226,.5); color:#ef4444; }
  .ord-form-label { font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
  .ord-form-control, .ord-form-select {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; outline: none; transition: all .2s;
  }
  .ord-form-control:focus, .ord-form-select:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.15); }
  .ord-submit-btn {
    background: linear-gradient(to right, #ff2d7a, #ff4b91); color: #fff; font-weight: 500; border: none;
    border-radius: 12px; padding: 10px 20px; box-shadow: 0 4px 12px rgba(255,45,122,0.2);
    display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s;
  }
  .ord-submit-btn:hover { opacity: .9; color: #fff; }
  .ord-approve-btn {
    background: linear-gradient(to right, #10b981, #059669); color: #fff; font-weight: 500; border: none;
    border-radius: 12px; padding: 10px 20px; box-shadow: 0 4px 12px rgba(16,185,129,0.2);
    display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s;
  }
  .ord-approve-btn:hover { opacity: .9; color: #fff; }
  .ord-danger-btn {
    background: linear-gradient(to right, #ef4444, #dc2626); color: #fff; font-weight: 500; border: none;
    border-radius: 12px; padding: 10px 20px; box-shadow: 0 4px 12px rgba(239,68,68,0.2);
    display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s;
  }
  .ord-danger-btn:hover { opacity: .9; color: #fff; }
  .ord-cancel-btn {
    border: 1px solid #e5e7eb; color: #6b7280; font-weight: 500; background: #fff;
    border-radius: 12px; padding: 10px 20px; transition: all .2s;
  }
  .ord-cancel-btn:hover { background: #f9fafb; }

  .ord-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  @media (max-width: 480px) { .ord-detail-grid { grid-template-columns: 1fr; } }
  .ord-detail-label { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; margin-bottom: 2px; }
  .ord-detail-value { font-weight: 500; color: #1f2937; margin: 0; }
  .ord-detail-sub { font-size: 12px; color: #6b7280; margin: 0; }

  /* TOAST */
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
  @media (min-width: 640px) { #toastContainer { padding: 0; } }
  .toast-item {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
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
  .toast-item.show { transform: translateX(0); opacity: 1; }
  .toast-item.success { border-color: #86efac; }
  .toast-item.error { border-color: #fca5a5; }
  .toast-icon {
    width: 36px; height: 36px; min-width: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; margin-top: 2px;
  }
  .toast-icon.success { background: #ecfdf5; }
  .toast-icon.error { background: #fef2f2; }
  .toast-icon i.success { color: #22c55e; }
  .toast-icon i.error { color: #dc2626; }
  .toast-content { flex: 1; }
  .toast-content .toast-title { font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 2px; }
  .toast-content .toast-message { font-size: 14px; color: #6b7280; margin-bottom: 0; }
  .toast-close {
    background: none; border: none; color: #d1d5db; cursor: pointer; padding: 4px; transition: color 0.2s; flex-shrink: 0;
  }
  .toast-close:hover { color: #6b7280; }
  .toast-progress {
    position: absolute; bottom: 0; left: 0; height: 3px; width: 100%;
    background: linear-gradient(to right, #ff2d7a, #ff4b91);
    transition: width 5s linear;
  }
  .toast-item.error .toast-progress { background: #dc2626; }
</style>

<div class="ord-page">
  <div class="ord-full-width">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div class="ord-header">
        <h1>Manage Orders</h1>
        <p>Manage all customer orders and payment statuses.</p>
      </div>
      <a href="{{ route('admin.payment-methods.index') }}" class="ord-add-btn">
        <i class="fa-solid fa-credit-card"></i> Manage Payment Methods
      </a>
    </div>

    <div class="ord-card">
      <div class="ord-filter-bar">
        <div class="ord-search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="orderSearchInput" placeholder="Search Order #, Customer..." class="ord-search" autocomplete="off">
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
                  'review'    => 'ord-badge-amber',
                  'preparing' => 'ord-badge-blue',
                  'completed' => 'ord-badge-green',
                  'delivered' => 'ord-badge-greendark',
              ];
            @endphp
            @forelse($orders as $order)
              <tr data-row-id="{{ $order->id }}" data-method="{{ $order->payment_method_id }}" data-status="{{ $order->payment_status }}">
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
                    $badgeClass = match($order->payment_status) {
                        'approved' => 'ord-badge-green',
                        'pending' => 'ord-badge-amber',
                        'failed' => 'ord-badge-red',
                        'completed' => 'ord-badge-blue',
                        default => 'ord-badge-gray'
                    };
                  @endphp
                  <span class="ord-badge payment-status-badge {{ $badgeClass }}">{{ ucfirst($order->payment_status) }}</span>
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
                  <span class="ord-badge rider-status-badge {{ $assignStatusColors[$order->status] ?? 'ord-badge-gray' }}">
                    {{ ucfirst($order->status ?? 'review') }}
                  </span>
                </td>
                <td class="rider-cell">
                  @if($order->rider)
                    <div class="d-flex align-items-center gap-2">
                      @if($order->rider->image_url)
                        <img src="{{ $order->rider->image_url }}" alt="{{ $order->rider->name }}" class="ord-avatar">
                      @else
                        <div class="ord-avatar-fallback">{{ strtoupper(substr($order->rider->name, 0, 1)) }}</div>
                      @endif
                      <div>
                        <p class="ord-rider-name">{{ $order->rider->name }}</p>
                        @if($order->estimated_time)
                          <span class="ord-rider-eta"><i class="fa-regular fa-clock"></i> {{ $order->estimated_time }}</span>
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
                            data-order-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_number }}"
                            data-rider-id="{{ $order->rider_assigned }}"
                            data-estimated-time="{{ $order->estimated_time }}"
                            data-status="{{ $order->status ?? 'review' }}"
                            data-url="{{ route('admin.orders.assign', $order->id) }}"
                            title="Assign Rider">
                      <i class="fa-solid fa-motorcycle" style="font-size:11px;"></i>
                    </button>
                    @endif

                    <button type="button" class="view-order-btn ord-action-btn ord-action-view"
                            data-url="{{ route('admin.payments.show', $order->id) }}" title="View Details">
                      <i class="fa-solid fa-eye" style="font-size:11px;"></i>
                    </button>

                    @if(in_array($order->payment_status, ['pending', 'failed']))
                    <button type="button" class="approve-order-btn ord-action-btn ord-action-approve"
                            data-url="{{ route('admin.payments.approve', $order->id) }}" title="Approve Payment">
                      <i class="fa-solid fa-check" style="font-size:11px;"></i>
                    </button>
                    @endif

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
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ======================= ORDER DETAILS MODAL ======================= -->
<div id="orderDetailsModal" class="fixed inset-0 z-[100] hidden">
    <div id="orderDetailsBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="orderDetailsBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-lg font-bold text-gray-800">Order Details</h3>
                <button type="button" id="orderDetailsCloseBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div id="orderDetailsContent" class="text-center py-6 text-gray-500">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl"></i> Loading...
            </div>
        </div>
    </div>
</div>

<!-- ======================= ASSIGN RIDER MODAL ======================= -->
<div id="assignModal" class="fixed inset-0 z-[100] hidden">
    <div id="assignModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="assignModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-lg font-bold text-gray-800">Assign Rider — <span id="assignModalOrderNumber" class="text-[#ff2d7a]"></span></h3>
                <button type="button" id="assignModalCloseBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form id="assignForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Rider <span class="text-red-500">*</span></label>
                    <select id="assignRiderSelect" name="rider_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all">
                        <option value="">-- Choose an available rider --</option>
                        @foreach($riders as $rider)
                            <option value="{{ $rider->id }}">
                                {{ $rider->name }} ({{ ucfirst($rider->vehicle_type) }}{{ $rider->vehicle_number ? ' - '.$rider->vehicle_number : '' }})
                            </option>
                        @endforeach
                    </select>
                    @if($riders->isEmpty())
                        <p class="text-xs text-amber-500 mt-1">No active riders found. Add one from Rider Management first.</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Estimated Delivery Time <span class="text-red-500">*</span></label>
                    <input type="text" id="assignEstimatedTime" name="estimated_time" required placeholder="e.g. 30-40 mins"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Order Status <span class="text-red-500">*</span></label>
                    <select id="assignStatusSelect" name="status" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all">
                        <option value="review">Review</option>
                        <option value="preparing">Preparing</option>
                        <option value="completed">Completed</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" id="assignSubmitBtn"
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91] text-white font-medium px-6 py-2.5 rounded-xl shadow-md shadow-[#ff2d7a]/20 hover:opacity-90 transition-all">
                        <span id="assignSubmitText">Assign Rider</span>
                        <i id="assignSubmitSpinner" class="fa-solid fa-circle-notch fa-spin hidden"></i>
                    </button>
                    <button type="button" id="assignModalCancelBtn" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================= STYLISH & DYNAMIC APPROVE CONFIRMATION MODAL ======================= -->
<div id="approveModal" class="fixed inset-0 z-[100] hidden">
    <div id="approveModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="approveModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4 ring-8 ring-emerald-50/50">
                <i class="fa-solid fa-check text-emerald-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 text-center">Approve Payment?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to approve
                <span id="approveModalItemName" class="font-semibold text-gray-700">this order</span>?
                This will mark the payment as <strong class="text-green-600">Approved</strong>.
            </p>
            <div class="flex items-center gap-3 mt-6">
                <button type="button" id="approveModalCancelBtn" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">Cancel</button>
                <button type="button" id="approveModalConfirmBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium shadow-md shadow-emerald-500/20 hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span id="approveModalConfirmText">Yes, Approve</span>
                    <i id="approveModalSpinner" class="fa-solid fa-circle-notch fa-spin hidden"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================= DELETE CONFIRMATION MODAL ======================= -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden">
    <div id="deleteModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="deleteModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4 ring-8 ring-red-50/50">
                <i class="fa-solid fa-trash-can text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 text-center">Delete Order?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to delete
                <span id="deleteModalItemName" class="font-semibold text-gray-700">this order</span>?
                This action cannot be undone.
            </p>
            <div class="flex items-center gap-3 mt-6">
                <button type="button" id="deleteModalCancelBtn" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">Cancel</button>
                <button type="button" id="deleteModalConfirmBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-red-600 text-white font-medium shadow-md shadow-red-500/20 hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span id="deleteModalConfirmText">Yes, Delete</span>
                    <i id="deleteModalSpinner" class="fa-solid fa-circle-notch fa-spin hidden"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================= TOAST NOTIFICATION CONTAINER ======================= -->
<div id="toastContainer" class="fixed top-5 right-5 z-[200] flex flex-col gap-3 w-full max-w-sm px-4 sm:px-0"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const assignStatusColors = {
        review:    'bg-amber-50 text-amber-600 border-amber-200',
        preparing: 'bg-blue-50 text-blue-600 border-blue-200',
        completed: 'bg-emerald-50 text-emerald-600 border-emerald-200',
        delivered: 'bg-green-50 text-green-700 border-green-300',
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";
    const tableBody = document.getElementById('ordersTableBody');
    const searchInput = document.getElementById('orderSearchInput');
    const filterMethod = document.getElementById('filterMethod');
    const filterStatus = document.getElementById('filterStatus');

    // ==================================================================
    // TOAST NOTIFICATION SYSTEM
    // ==================================================================
    const toastContainer = document.getElementById('toastContainer');
    function showToast(message, type = 'success', duration = 5000) {
        const isSuccess = type === 'success';
        const toast = document.createElement('div');
        toast.className = `relative overflow-hidden bg-white border ${isSuccess ? 'border-green-200' : 'border-red-200'} rounded-2xl shadow-xl p-4 flex items-start gap-3 translate-x-[120%] opacity-0 transition-all duration-300 ease-out`;
        toast.innerHTML = `
            <div class="w-9 h-9 rounded-full ${isSuccess ? 'bg-green-50' : 'bg-red-50'} flex items-center justify-center shrink-0 mt-0.5">
                <i class="fa-solid ${isSuccess ? 'fa-check text-green-500' : 'fa-xmark text-red-500'} text-sm"></i>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-semibold text-gray-800">${isSuccess ? 'Success' : 'Error'}</p>
                <p class="text-sm text-gray-500 mt-0.5">${message}</p>
            </div>
            <button class="toast-close-btn text-gray-300 hover:text-gray-500 transition-colors shrink-0">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div class="absolute bottom-0 left-0 h-1 ${isSuccess ? 'bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91]' : 'bg-red-400'} toast-progress" style="width:100%;"></div>
        `;
        toastContainer.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-x-[120%]', 'opacity-0'));
        const progressBar = toast.querySelector('.toast-progress');
        progressBar.style.transition = `width ${duration}ms linear`;
        requestAnimationFrame(() => requestAnimationFrame(() => { progressBar.style.width = '0%'; }));
        toast.querySelector('.toast-close-btn').addEventListener('click', () => removeToast(toast));
        const timer = setTimeout(() => removeToast(toast), duration);
        toast.addEventListener('mouseenter', () => { clearTimeout(timer); progressBar.style.transition = 'none'; });
        function removeToast(el) { el.classList.add('translate-x-[120%]', 'opacity-0'); setTimeout(() => el.remove(), 300); }
    }

    @if(session('success')) showToast(@json(session('success')), 'success'); @endif
    @if(session('error')) showToast(@json(session('error')), 'error'); @endif

    // ==================================================================
    // FILTER & SEARCH FUNCTIONS
    // ==================================================================
    function filterTable() {
        const searchVal = searchInput.value.toLowerCase();
        const methodVal = filterMethod.value;
        const statusVal = filterStatus.value.toLowerCase();

        tableBody.querySelectorAll('tr').forEach(row => {
            if(row.id === 'emptyRow') return;
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
    // ORDER DETAILS MODAL (AJAX)
    // ==================================================================
    const detailsModal = document.getElementById('orderDetailsModal');
    const detailsBackdrop = document.getElementById('orderDetailsBackdrop');
    const detailsBox = document.getElementById('orderDetailsBox');
    const detailsContent = document.getElementById('orderDetailsContent');
    const detailsCloseBtn = document.getElementById('orderDetailsCloseBtn');

    function openDetailsModal(url) {
        detailsModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            detailsBackdrop.classList.remove('opacity-0');
            detailsBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
        detailsContent.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-2xl"></i> Loading...`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            if(data.html) {
                detailsContent.innerHTML = data.html;
            } else {
                detailsContent.innerHTML = `<p class="text-red-500">Failed to load order details.</p>`;
            }
        })
        .catch(() => {
            detailsContent.innerHTML = `<p class="text-red-500">An error occurred.</p>`;
        });
    }

    function closeDetailsModal() {
        detailsBackdrop.classList.add('opacity-0');
        detailsBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { detailsModal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
    }

    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-order-btn');
        if (btn) openDetailsModal(btn.dataset.url);
    });

    detailsCloseBtn.addEventListener('click', closeDetailsModal);
    detailsBackdrop.addEventListener('click', closeDetailsModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !detailsModal.classList.contains('hidden')) closeDetailsModal(); });

    // ==================================================================
    // ASSIGN RIDER MODAL
    // ==================================================================
    const assignModal = document.getElementById('assignModal');
    const assignBackdrop = document.getElementById('assignModalBackdrop');
    const assignBox = document.getElementById('assignModalBox');
    const assignModalOrderNumber = document.getElementById('assignModalOrderNumber');
    const assignRiderSelect = document.getElementById('assignRiderSelect');
    const assignEstimatedTime = document.getElementById('assignEstimatedTime');
    const assignStatusSelect = document.getElementById('assignStatusSelect');
    const assignForm = document.getElementById('assignForm');
    const assignModalCloseBtn = document.getElementById('assignModalCloseBtn');
    const assignModalCancelBtn = document.getElementById('assignModalCancelBtn');
    const assignSubmitBtn = document.getElementById('assignSubmitBtn');
    const assignSubmitText = document.getElementById('assignSubmitText');
    const assignSubmitSpinner = document.getElementById('assignSubmitSpinner');

    let assignFormUrl = null;

    function openAssignModal(btn) {
        assignFormUrl = btn.dataset.url;
        assignModalOrderNumber.textContent = '#' + btn.dataset.orderNumber;
        assignRiderSelect.value = btn.dataset.riderId && btn.dataset.riderId !== '' ? btn.dataset.riderId : '';
        assignEstimatedTime.value = btn.dataset.estimatedTime && btn.dataset.estimatedTime !== 'null' ? btn.dataset.estimatedTime : '';
        assignStatusSelect.value = btn.dataset.status || 'review';
        assignSubmitText.textContent = (btn.dataset.riderId && btn.dataset.riderId !== '') ? 'Update Assignment' : 'Assign Rider';

        assignModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            assignBackdrop.classList.remove('opacity-0');
            assignBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeAssignModal() {
        assignBackdrop.classList.add('opacity-0');
        assignBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { assignModal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
        assignFormUrl = null;
        assignForm.reset();
    }

    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.assign-rider-btn');
        if (btn) openAssignModal(btn);
    });

    assignModalCloseBtn.addEventListener('click', closeAssignModal);
    assignModalCancelBtn.addEventListener('click', closeAssignModal);
    assignBackdrop.addEventListener('click', closeAssignModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !assignModal.classList.contains('hidden')) closeAssignModal(); });

    function updateRiderCell(order) {
        const row = tableBody.querySelector(`tr[data-row-id="${order.id}"]`);
        if (!row) return;

        const riderCell = row.querySelector('.rider-cell');
        const initial = order.rider_name ? order.rider_name.charAt(0).toUpperCase() : '?';
        const photoHtml = order.rider_image
            ? `<img src="${order.rider_image}" alt="${order.rider_name}" class="w-7 h-7 rounded-full object-cover border border-pink-100">`
            : `<div class="w-7 h-7 rounded-full bg-pink-50 border border-pink-100 flex items-center justify-center text-[#ff2d7a] text-xs font-bold">${initial}</div>`;
        const statusClass = assignStatusColors[order.status] || 'bg-gray-100 text-gray-500 border-gray-200';
        const statusLabel = order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1) : 'Review';

        if (riderCell) {
            riderCell.innerHTML = `
                <div class="flex items-center gap-2">
                    ${photoHtml}
                    <div>
                        <p class="text-xs font-medium text-gray-800">${order.rider_name ?? '—'}</p>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border ${statusClass}">${statusLabel}</span>
                    </div>
                </div>`;
        }

        const statusCell = row.querySelector('td:nth-child(8) .rounded-full');
        if (statusCell) {
            statusCell.className = `px-2.5 py-1 rounded-full text-xs font-semibold border ${statusClass}`;
            statusCell.textContent = statusLabel;
        }

        const assignBtn = row.querySelector('.assign-rider-btn');
        if (assignBtn) {
            assignBtn.dataset.riderId = order.rider_id ?? '';
            assignBtn.dataset.estimatedTime = order.estimated_time ?? '';
            assignBtn.dataset.status = order.status ?? 'review';
        }
    }

    assignForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!assignFormUrl) return;

        const riderId = assignRiderSelect.value;
        if (!riderId || riderId === '') {
            alert('Please select a rider from the dropdown list.');
            return;
        }

        assignSubmitBtn.disabled = true;
        assignSubmitSpinner.classList.remove('hidden');

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
                closeAssignModal();
            } else {
                showToast(data.message || 'Failed to assign rider.', 'error');
            }
        })
        .catch(err => {
            showToast(err.message || 'Something went wrong while assigning the rider.', 'error');
        })
        .finally(() => {
            assignSubmitBtn.disabled = false;
            assignSubmitSpinner.classList.add('hidden');
        });
    });

    // ==================================================================
    // STYLISH & DYNAMIC APPROVE MODAL LOGIC
    // ==================================================================
    const approveModal = document.getElementById('approveModal');
    const approveBackdrop = document.getElementById('approveModalBackdrop');
    const approveBox = document.getElementById('approveModalBox');
    const approveItemName = document.getElementById('approveModalItemName');
    const approveCancelBtn = document.getElementById('approveModalCancelBtn');
    const approveConfirmBtn = document.getElementById('approveModalConfirmBtn');
    const approveConfirmText = document.getElementById('approveModalConfirmText');
    const approveSpinner = document.getElementById('approveModalSpinner');

    let pendingApproveUrl = null;
    let pendingApproveRow = null;

    function openApproveModal(url, row, name) {
        pendingApproveUrl = url; pendingApproveRow = row;
        approveItemName.textContent = name ? `"${name}"` : 'this order';
        approveModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            approveBackdrop.classList.remove('opacity-0');
            approveBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
        approveBackdrop.classList.add('opacity-0');
        approveBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { approveModal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
        pendingApproveUrl = null; pendingApproveRow = null;
    }

    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.approve-order-btn');
        if (btn) {
            const orderNumberCol = btn.closest('tr').querySelector('td.font-medium.text-gray-800');
            const orderName = orderNumberCol ? orderNumberCol.innerText.trim() : 'Order';
            openApproveModal(btn.dataset.url, btn.closest('tr'), orderName);
        }
    });

    approveCancelBtn.addEventListener('click', closeApproveModal);
    approveBackdrop.addEventListener('click', closeApproveModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !approveModal.classList.contains('hidden')) closeApproveModal(); });

    approveConfirmBtn.addEventListener('click', function () {
        if (!pendingApproveUrl) return;
        approveConfirmBtn.disabled = true;
        approveConfirmText.textContent = 'Approving...';
        approveSpinner.classList.remove('hidden');

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
                    const statusCell = pendingApproveRow.querySelector('td .rounded-full');
                    if (statusCell) {
                        statusCell.className = 'px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-50 text-green-600 border-green-200';
                        statusCell.innerText = 'Approved';
                    }
                    pendingApproveRow.dataset.status = 'approved';
                    const approveBtn = pendingApproveRow.querySelector('.approve-order-btn');
                    if (approveBtn) approveBtn.remove();

                    const actionsCell = pendingApproveRow.querySelector('td.text-right .flex.justify-end.gap-2');
                    if (actionsCell && !actionsCell.querySelector('.assign-rider-btn')) {
                        const orderId = pendingApproveRow.dataset.rowId;
                        const orderNumberEl = pendingApproveRow.querySelector('td.font-medium.text-gray-800');
                        const orderNumber = orderNumberEl ? orderNumberEl.innerText.replace('#', '') : '';
                        const assignBtnHtml = `
                            <button type="button" class="assign-rider-btn w-8 h-8 flex items-center justify-center rounded-lg bg-pink-50 text-[#ff2d7a] hover:bg-pink-100 transition-all"
                                    data-order-id="${orderId}"
                                    data-order-number="${orderNumber}"
                                    data-rider-id=""
                                    data-estimated-time=""
                                    data-status="review"
                                    data-url="/admin/orders/${orderId}/assign"
                                    title="Assign Rider">
                                <i class="fa-solid fa-motorcycle text-xs"></i>
                            </button>
                        `;
                        actionsCell.insertAdjacentHTML('afterbegin', assignBtnHtml);
                    }
                }
                showToast(data.message, 'success');
                closeApproveModal();
            } else {
                showToast(data.message || 'Failed to approve payment.', 'error');
                closeApproveModal();
            }
        })
        .catch(err => {
            showToast(err.message, 'error');
            closeApproveModal();
        })
        .finally(() => {
            approveConfirmBtn.disabled = false;
            approveConfirmText.textContent = 'Yes, Approve';
            approveSpinner.classList.add('hidden');
        });
    });

    // ==================================================================
    // DELETE ORDER MODAL
    // ==================================================================
    const deleteModal = document.getElementById('deleteModal');
    const deleteBackdrop = document.getElementById('deleteModalBackdrop');
    const deleteBox = document.getElementById('deleteModalBox');
    const deleteItemName = document.getElementById('deleteModalItemName');
    const deleteCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteConfirmBtn = document.getElementById('deleteModalConfirmBtn');
    const deleteConfirmText = document.getElementById('deleteModalConfirmText');
    const deleteSpinner = document.getElementById('deleteModalSpinner');

    let pendingDeleteUrl = null;
    let pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url; pendingDeleteRow = row;
        deleteItemName.textContent = name ? `"${name}"` : 'this order';
        deleteModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            deleteBackdrop.classList.remove('opacity-0');
            deleteBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteBackdrop.classList.add('opacity-0');
        deleteBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { deleteModal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
        pendingDeleteUrl = null; pendingDeleteRow = null;
    }

    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-order-btn');
        if (btn) openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
    });

    deleteCancelBtn.addEventListener('click', closeDeleteModal);
    deleteBackdrop.addEventListener('click', closeDeleteModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) closeDeleteModal(); });

    deleteConfirmBtn.addEventListener('click', function () {
        if (!pendingDeleteUrl) return;
        deleteConfirmBtn.disabled = true;
        deleteConfirmText.textContent = 'Deleting...';
        deleteSpinner.classList.remove('hidden');

        fetch(pendingDeleteUrl, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
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
                    tableBody.innerHTML = `<tr id="emptyRow"><td colspan="11" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-receipt text-2xl mb-2 block"></i>No orders found.</td></tr>`;
                }
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Failed to delete order.', 'error');
            }
        })
        .catch(err => showToast(err.message, 'error'))
        .finally(() => {
            deleteConfirmBtn.disabled = false;
            deleteConfirmText.textContent = 'Yes, Delete';
            deleteSpinner.classList.add('hidden');
            closeDeleteModal();
        });
    });
});
</script>
@endsection
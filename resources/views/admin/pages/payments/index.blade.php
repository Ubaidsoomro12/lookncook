@extends('admin.layouts.master')
@section('title', 'View | Orders')
@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Order Managements</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all customer orders and payment statuses.</p>
        </div>
        <div>
            <a href="{{ route('admin.payment-methods.index') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91] text-white font-medium px-5 py-2.5 rounded-xl shadow-md shadow-[#ff2d7a]/20 hover:opacity-90 transition-all">
                <i class="fa-solid fa-credit-card"></i> Manage Payment Methods
            </a>
        </div>
    </div>

    <!-- Table + Filter Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        <!-- Search & Filter Bar -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap items-center gap-3">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="orderSearchInput" placeholder="Search Order #, Customer..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all">
            </div>
            
            <select id="filterMethod" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all bg-white">
                <option value="">All Methods</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                @endforeach
            </select>
            
            <select id="filterStatus" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all bg-white">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="failed">Failed</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Order #</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Payment Method</th>
                        <th class="px-6 py-3">Payment Status</th>
                        <th class="px-6 py-3">Screenshot</th>
                        <th class="px-6 py-3">Order Status</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody" class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr data-row-id="{{ $order->id }}" data-method="{{ $order->payment_method_id }}" data-status="{{ $order->payment_status }}">
                            <td class="px-6 py-3 font-medium text-gray-800">#{{ $order->order_number }}</td>
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-800">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-400">{{ $order->customer_email }}</div>
                            </td>
                            <td class="px-6 py-3 text-gray-600 text-sm">{{ $order->customer_phone ?? '—' }}</td>
                            <td class="px-6 py-3 font-bold text-[#ff2d7a]">Rs. {{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $order->paymentMethod?->name ?? '—' }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $badgeClass = match($order->payment_status) {
                                        'approved' => 'bg-green-50 text-green-600 border-green-200',
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'failed' => 'bg-red-50 text-red-600 border-red-200',
                                        'completed' => 'bg-blue-50 text-blue-600 border-blue-200',
                                        default => 'bg-gray-100 text-gray-500 border-gray-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if($order->payment_screenshot)
                                    <a href="{{ asset($order->payment_screenshot) }}" target="_blank">
                                        <img src="{{ asset($order->payment_screenshot) }}" class="w-10 h-10 object-cover rounded-md border border-gray-200 hover:opacity-80 transition">
                                    </a>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ ucfirst($order->order_status) }}</td>
                            <td class="px-6 py-3 text-gray-400 whitespace-nowrap text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <!-- View Button -->
                                    <button type="button" class="view-order-btn w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all"
                                            data-url="{{ route('admin.payments.show', $order->id) }}" title="View Details">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    
                                    <!-- Approve Button (Only show for Pending/Failed) -->
                                    @if(in_array($order->payment_status, ['pending', 'failed']))
                                    <button type="button" class="approve-order-btn w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all"
                                            data-url="{{ route('admin.payments.approve', $order->id) }}" title="Approve Payment">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <button type="button" class="delete-order-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all"
                                            data-id="{{ $order->id }}" data-name="Order #{{ $order->order_number }}" 
                                            data-url="{{ route('admin.payments.destroy', $order->id) }}" title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="10" class="px-6 py-10 text-center text-gray-400">
                                <i class="fa-solid fa-receipt text-2xl mb-2 block"></i>
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100" id="paginationWrapper">
                {{ $orders->links() }}
            </div>
        @endif
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

    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif

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

    // Trigger modal on button click
    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.approve-order-btn');
        if (btn) {
            // Find the Order # column in the same row to show in the modal
            const orderNumberCol = btn.closest('tr').querySelector('td.font-medium.text-gray-800');
            const orderName = orderNumberCol ? orderNumberCol.innerText.trim() : 'Order';
            openApproveModal(btn.dataset.url, btn.closest('tr'), orderName);
        }
    });

    approveCancelBtn.addEventListener('click', closeApproveModal);
    approveBackdrop.addEventListener('click', closeApproveModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !approveModal.classList.contains('hidden')) closeApproveModal(); });

    // Handle AJAX approval dynamically without refreshing the page
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
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // DYNAMICALLY UPDATE THE ROW
                if (pendingApproveRow) {
                    // 1. Update Status Badge
                    const statusCell = pendingApproveRow.querySelector('td .rounded-full');
                    if (statusCell) {
                        statusCell.className = 'px-2.5 py-1 rounded-full text-xs font-semibold border bg-green-50 text-green-600 border-green-200';
                        statusCell.innerText = 'Approved';
                    }
                    // 2. Update the data-status attribute for filters
                    pendingApproveRow.dataset.status = 'approved';
                    // 3. Remove the approve button
                    const approveBtn = pendingApproveRow.querySelector('.approve-order-btn');
                    if (approveBtn) approveBtn.remove();
                }
                showToast(data.message, 'success');
                closeApproveModal();
            } else {
                showToast(data.message || 'Failed to approve payment.', 'error');
                closeApproveModal();
            }
        })
        .catch(() => {
            showToast('Something went wrong while approving.', 'error');
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
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                pendingDeleteRow?.remove();
                if (!tableBody.querySelector('tr:not(#emptyRow)')) {
                    tableBody.innerHTML = `<tr id="emptyRow"><td colspan="10" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-receipt text-2xl mb-2 block"></i>No orders found.</td></tr>`;
                }
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Failed to delete order.', 'error');
            }
        })
        .catch(() => showToast('Something went wrong while deleting.', 'error'))
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
@extends('admin.layouts.master')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Payment Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all payments and orders.</p>
        </div>
        <!-- No "Add New" button here -->
    </div>

    <!-- Search + Table Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        <!-- Search Bar -->
        <div class="p-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    id="paymentSearchInput"
                    placeholder="Search by order #, customer, transaction..."
                    class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all"
                    autocomplete="off"
                >
                <i id="searchLoadingIcon" class="fa-solid fa-circle-notch fa-spin absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm hidden"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Order #</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Payment Method</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Transaction Ref</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="paymentsTableBody" class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr data-row-id="{{ $order->id }}">
                            <td class="px-6 py-3 font-medium text-gray-800">
                                #{{ $order->order_number ?? $order->id }}
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800">{{ $order->customer_name ?? $order->user?->name ?? 'Guest' }}</span>
                                    <span class="text-xs text-gray-400">{{ $order->customer_email ?? $order->user?->email ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 font-semibold text-gray-800">
                                Rs. {{ number_format((float) $order->total_amount, 0) }}
                            </td>
                            <td class="px-6 py-3 text-gray-500">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    {{ $order->paymentMethod?->name ?? $order->payment_method_slug ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @php
                                    $status = $order->payment_status ?? 'pending';
                                @endphp
                                @if($status === 'approved' || $status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-200">Approved</span>
                                @elseif($status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-600 border border-yellow-200">Pending</span>
                                @elseif($status === 'failed')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">Failed</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs max-w-[120px] truncate">
                                {{ $order->transaction_reference ?? $order->stripe_payment_intent_id ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs whitespace-nowrap">
                                {{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <!-- View Details Button -->
                                    <button type="button"
                                        class="view-payment-btn w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition-all"
                                        data-id="{{ $order->id }}"
                                        data-url="{{ route('admin.payments.show', $order->id) }}"
                                        title="View Details">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>

                                    <!-- Approve Button (only for pending) -->
                                    @if(($order->payment_status ?? 'pending') === 'pending')
                                        <button type="button"
                                            class="approve-payment-btn w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all"
                                            data-id="{{ $order->id }}"
                                            data-url="{{ route('admin.payments.approve', $order->id) }}"
                                            data-name="#{{ $order->order_number ?? $order->id }}"
                                            title="Approve Payment">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <button type="button"
                                        class="delete-payment-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all"
                                        data-id="{{ $order->id }}"
                                        data-name="#{{ $order->order_number ?? $order->id }}"
                                        data-url="{{ route('admin.payments.destroy', $order->id) }}"
                                        title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                <i class="fa-solid fa-receipt text-2xl mb-2 block"></i>
                                No payments found.
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

<!-- ======================= VIEW DETAILS MODAL ======================= -->
<div id="viewModal" class="fixed inset-0 z-[100] hidden">
    <div id="viewModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="viewModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-lg font-bold text-gray-800">Payment Details</h3>
                <button type="button" id="viewModalCloseBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div id="viewModalContent" class="space-y-4 text-sm">
                <!-- Dynamic content will be injected -->
                <div class="animate-pulse flex space-x-4">
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-gray-200 rounded"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                        <div class="h-4 bg-gray-200 rounded w-4/6"></div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" id="viewModalCloseBtn2" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-all">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ======================= APPROVE CONFIRMATION MODAL ======================= -->
<div id="approveModal" class="fixed inset-0 z-[100] hidden">
    <div id="approveModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="approveModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4 ring-8 ring-emerald-50/50">
                <i class="fa-solid fa-check-circle text-emerald-500 text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 text-center">Approve Payment?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to approve payment
                <span id="approveModalItemName" class="font-semibold text-gray-700">this record</span>?
                This will mark the payment as <span class="font-semibold text-emerald-600">Approved</span>.
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
            <h3 class="text-lg font-bold text-gray-800 text-center">Delete Payment?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to delete payment
                <span id="deleteModalItemName" class="font-semibold text-gray-700">this record</span>?
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
    const searchInput   = document.getElementById('paymentSearchInput');
    const tableBody     = document.getElementById('paymentsTableBody');
    const loadingIcon   = document.getElementById('searchLoadingIcon');
    const paginationBox = document.getElementById('paginationWrapper');
    const searchUrl     = "{{ route('admin.payments.search') }}";
    const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

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
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-[120%]', 'opacity-0');
        });
        const progressBar = toast.querySelector('.toast-progress');
        progressBar.style.transition = `width ${duration}ms linear`;
        requestAnimationFrame(() => {
            requestAnimationFrame(() => { progressBar.style.width = '0%'; });
        });
        toast.querySelector('.toast-close-btn').addEventListener('click', () => removeToast(toast));
        const timer = setTimeout(() => removeToast(toast), duration);
        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            progressBar.style.transition = 'none';
        });
        function removeToast(el) {
            el.classList.add('translate-x-[120%]', 'opacity-0');
            setTimeout(() => el.remove(), 300);
        }
    }

    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif
    @if($errors->any())
        showToast(@json($errors->first()), 'error');
    @endif

    // ==================================================================
    // SEARCH (AJAX)
    // ==================================================================
    let debounceTimer;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        debounceTimer = setTimeout(() => {
            loadingIcon.classList.remove('hidden');

            fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderRows(data.orders);
                if (paginationBox) paginationBox.style.display = query ? 'none' : '';
            })
            .catch(() => {
                tableBody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-red-400">Something went wrong. Try again.</td></tr>`;
            })
            .finally(() => loadingIcon.classList.add('hidden'));
        }, 300);
    });

    function renderRows(orders) {
        if (!orders.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                        <i class="fa-solid fa-receipt text-2xl mb-2 block"></i>
                        No payments found.
                    </td>
                </tr>`;
            return;
        }

        tableBody.innerHTML = orders.map(o => {
            const status = o.payment_status || 'pending';
            let statusBadge = '';
            if (status === 'approved' || status === 'completed')
                statusBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-200">Approved</span>`;
            else if (status === 'pending')
                statusBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-600 border border-yellow-200">Pending</span>`;
            else if (status === 'failed')
                statusBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">Failed</span>`;
            else
                statusBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">${status}</span>`;

            const approveBtn = (status === 'pending')
                ? `<button type="button" class="approve-payment-btn w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all" data-id="${o.id}" data-url="${o.approve_url}" data-name="#${o.order_number || o.id}" title="Approve Payment"><i class="fa-solid fa-check text-xs"></i></button>`
                : '';

            return `
            <tr data-row-id="${o.id}">
                <td class="px-6 py-3 font-medium text-gray-800">#${o.order_number || o.id}</td>
                <td class="px-6 py-3">
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-800">${o.customer_name || (o.user ? o.user.name : 'Guest')}</span>
                        <span class="text-xs text-gray-400">${o.customer_email || (o.user ? o.user.email : '')}</span>
                    </div>
                </td>
                <td class="px-6 py-3 font-semibold text-gray-800">Rs. ${Number(o.total_amount).toLocaleString()}</td>
                <td class="px-6 py-3 text-gray-500">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">${o.payment_method_name || o.payment_method_slug || '—'}</span>
                </td>
                <td class="px-6 py-3">${statusBadge}</td>
                <td class="px-6 py-3 text-gray-500 text-xs max-w-[120px] truncate">${o.transaction_reference || o.stripe_payment_intent_id || '—'}</td>
                <td class="px-6 py-3 text-gray-500 text-xs whitespace-nowrap">${o.created_at ? new Date(o.created_at).toLocaleString('en-IN', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '—'}</td>
                <td class="px-6 py-3 text-right">
                    <div class="flex justify-end gap-2">
                        <button type="button" class="view-payment-btn w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition-all" data-id="${o.id}" data-url="${o.show_url}" title="View Details"><i class="fa-solid fa-eye text-xs"></i></button>
                        ${approveBtn}
                        <button type="button" class="delete-payment-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all" data-id="${o.id}" data-name="#${o.order_number || o.id}" data-url="${o.delete_url}" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                    </div>
                </td>
            </tr>
        `}).join('');
    }

    // ==================================================================
    // VIEW DETAILS MODAL
    // ==================================================================
    const viewModal = document.getElementById('viewModal');
    const viewModalBackdrop = document.getElementById('viewModalBackdrop');
    const viewModalBox = document.getElementById('viewModalBox');
    const viewModalContent = document.getElementById('viewModalContent');
    const viewModalCloseBtn = document.getElementById('viewModalCloseBtn');
    const viewModalCloseBtn2 = document.getElementById('viewModalCloseBtn2');

    function openViewModal(url) {
        viewModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            viewModalBackdrop.classList.remove('opacity-0');
            viewModalBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
        viewModalContent.innerHTML = `
            <div class="animate-pulse flex space-x-4">
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                    <div class="h-4 bg-gray-200 rounded w-4/6"></div>
                </div>
            </div>
        `;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.html) {
                viewModalContent.innerHTML = data.html;
            } else {
                viewModalContent.innerHTML = `<p class="text-red-500">Failed to load details.</p>`;
            }
        })
        .catch(() => {
            viewModalContent.innerHTML = `<p class="text-red-500">Error loading details.</p>`;
        });
    }

    function closeViewModal() {
        viewModalBackdrop.classList.add('opacity-0');
        viewModalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            viewModal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    tableBody.addEventListener('click', function (e) {
        const viewBtn = e.target.closest('.view-payment-btn');
        if (viewBtn) {
            e.preventDefault();
            openViewModal(viewBtn.dataset.url);
        }
    });

    viewModalCloseBtn.addEventListener('click', closeViewModal);
    viewModalCloseBtn2.addEventListener('click', closeViewModal);
    viewModalBackdrop.addEventListener('click', closeViewModal);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !viewModal.classList.contains('hidden')) closeViewModal();
    });

    // ==================================================================
    // APPROVE PAYMENT – CUSTOM MODAL
    // ==================================================================
    const approveModal = document.getElementById('approveModal');
    const approveModalBackdrop = document.getElementById('approveModalBackdrop');
    const approveModalBox = document.getElementById('approveModalBox');
    const approveModalItemName = document.getElementById('approveModalItemName');
    const approveModalCancelBtn = document.getElementById('approveModalCancelBtn');
    const approveModalConfirmBtn = document.getElementById('approveModalConfirmBtn');
    const approveModalConfirmText = document.getElementById('approveModalConfirmText');
    const approveModalSpinner = document.getElementById('approveModalSpinner');

    let pendingApproveUrl = null;
    let pendingApproveRow = null;

    function openApproveModal(url, row, name) {
        pendingApproveUrl = url;
        pendingApproveRow = row;
        approveModalItemName.textContent = name || 'this record';
        approveModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            approveModalBackdrop.classList.remove('opacity-0');
            approveModalBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
        approveModalBackdrop.classList.add('opacity-0');
        approveModalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            approveModal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
        pendingApproveUrl = null;
        pendingApproveRow = null;
    }

    // Approve button click – open modal
    tableBody.addEventListener('click', function (e) {
        const approveBtn = e.target.closest('.approve-payment-btn');
        if (!approveBtn) return;
        openApproveModal(approveBtn.dataset.url, approveBtn.closest('tr'), approveBtn.dataset.name);
    });

    approveModalCancelBtn.addEventListener('click', closeApproveModal);
    approveModalBackdrop.addEventListener('click', closeApproveModal);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !approveModal.classList.contains('hidden')) closeApproveModal();
    });

    // Confirm approve
    approveModalConfirmBtn.addEventListener('click', function () {
        if (!pendingApproveUrl) return;
        approveModalConfirmBtn.disabled = true;
        approveModalConfirmText.textContent = 'Approving...';
        approveModalSpinner.classList.remove('hidden');

        fetch(pendingApproveUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Request failed');
            return res.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Payment approved successfully.', 'success');
                // Update row status
                const row = pendingApproveRow;
                if (row) {
                    const statusCell = row.querySelector('td:nth-child(5)');
                    statusCell.innerHTML = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-200">Approved</span>`;
                    // Remove approve button
                    const actionsCell = row.querySelector('td:last-child');
                    const approveBtnOld = actionsCell.querySelector('.approve-payment-btn');
                    if (approveBtnOld) approveBtnOld.remove();
                }
            } else {
                showToast(data.message || 'Failed to approve payment.', 'error');
            }
        })
        .catch(() => {
            showToast('Something went wrong while approving.', 'error');
        })
        .finally(() => {
            approveModalConfirmBtn.disabled = false;
            approveModalConfirmText.textContent = 'Yes, Approve';
            approveModalSpinner.classList.add('hidden');
            closeApproveModal();
        });
    });

    // ==================================================================
    // DELETE CONFIRMATION MODAL
    // ==================================================================
    const deleteModal          = document.getElementById('deleteModal');
    const deleteModalBackdrop  = document.getElementById('deleteModalBackdrop');
    const deleteModalBox       = document.getElementById('deleteModalBox');
    const deleteModalItemName  = document.getElementById('deleteModalItemName');
    const deleteModalCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteModalConfirmBtn= document.getElementById('deleteModalConfirmBtn');
    const deleteModalConfirmText = document.getElementById('deleteModalConfirmText');
    const deleteModalSpinner   = document.getElementById('deleteModalSpinner');

    let pendingDeleteUrl = null;
    let pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url;
        pendingDeleteRow = row;
        deleteModalItemName.textContent = name || 'this record';
        deleteModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            deleteModalBackdrop.classList.remove('opacity-0');
            deleteModalBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModalBackdrop.classList.add('opacity-0');
        deleteModalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
        pendingDeleteUrl = null;
        pendingDeleteRow = null;
    }

    tableBody.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-payment-btn');
        if (!btn) return;
        openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
    });

    deleteModalCancelBtn.addEventListener('click', closeDeleteModal);
    deleteModalBackdrop.addEventListener('click', closeDeleteModal);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) closeDeleteModal();
    });

    deleteModalConfirmBtn.addEventListener('click', function () {
        if (!pendingDeleteUrl) return;
        deleteModalConfirmBtn.disabled = true;
        deleteModalConfirmText.textContent = 'Deleting...';
        deleteModalSpinner.classList.remove('hidden');

        fetch(pendingDeleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Request failed');
            return res.json();
        })
        .then(data => {
            if (data.success) {
                pendingDeleteRow?.remove();
                if (!tableBody.querySelector('tr')) {
                    tableBody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-receipt text-2xl mb-2 block"></i>No payments found.</td></tr>`;
                }
                showToast('Payment deleted successfully.', 'success');
            } else {
                showToast('Failed to delete payment.', 'error');
            }
        })
        .catch(() => {
            showToast('Something went wrong while deleting.', 'error');
        })
        .finally(() => {
            deleteModalConfirmBtn.disabled = false;
            deleteModalConfirmText.textContent = 'Yes, Delete';
            deleteModalSpinner.classList.add('hidden');
            closeDeleteModal();
        });
    });
});
</script>
@endsection
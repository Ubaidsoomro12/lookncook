@extends('admin.layouts.master')
@section('title', 'View | Payments')

@section('content')
<div class="max-w-7xl mx-auto">

<!-- Gradient Banner Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 bg-gradient-to-r from-[#ff2d7a] to-[#ff6b9d] rounded-2xl px-6 py-6 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center text-xl text-white">💳</div>
        <div>
            <h1 class="text-xl font-bold text-white">Payment Methods</h1>
            <p class="text-sm text-white/80 mt-0.5">Manage your payment methods</p>
        </div>
    </div>
    <a href="{{ route('admin.payment-methods.create') }}"
       class="inline-flex items-center gap-2 bg-white text-[#ff2d7a] font-semibold px-5 py-2.5 rounded-xl shadow-md hover:bg-pink-50 hover:shadow-lg transition-all">
        <i class="fa-solid fa-plus text-xs"></i>
        <span>Add New</span>
    </a>
</div>

    <!-- Search + Table Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        <div class="p-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    id="methodSearchInput"
                    placeholder="Search by name, bank..."
                    class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                    autocomplete="off"
                >
                <i id="searchLoadingIcon" class="fa-solid fa-circle-notch fa-spin absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm hidden"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Logo</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Account Holder</th>
                        <th class="px-6 py-3">Account #</th>
                        <th class="px-6 py-3">Deep Link</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="methodsTableBody" class="divide-y divide-gray-100">
                    @forelse($methods as $method)
                        <tr data-row-id="{{ $method->id }}">
                            <td class="px-6 py-3 text-gray-500">{{ $method->id }}</td>
                            <td class="px-6 py-3">
                                @if($method->logo)
                                    <img src="{{ asset($method->logo) }}" alt="{{ $method->name }} logo" class="w-9 h-9 rounded-lg object-contain border border-gray-100 bg-gray-50">
                                @else
                                    <div class="w-9 h-9 rounded-lg border border-gray-100 bg-gray-50 flex items-center justify-center text-gray-300">
                                        <i class="fa-solid fa-image text-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-medium text-gray-800">
                                <span class="mr-1">{{ $method->icon ?? '💳' }}</span>{{ $method->name }}
                            </td>
                            <td class="px-6 py-3">
                                @php
                                    $typeLabels = ['cod' => 'Cash on Delivery', 'mobile_wallet' => 'Mobile Wallet', 'bank' => 'Bank'];
                                    $typeColors = [
                                        'cod' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'mobile_wallet' => 'bg-sky-50 text-sky-600 border-sky-200',
                                        'bank' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $typeColors[$method->type] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                    {{ $typeLabels[$method->type] ?? ucfirst($method->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $method->account_title ?? '—' }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs truncate max-w-[140px]">{{ $method->account_number ?? $method->iban ?? '—' }}</td>
                            <td class="px-6 py-3 text-gray-400 text-xs truncate max-w-[120px]">{{ $method->deep_link ?? '—' }}</td>
                            <td class="px-6 py-3">
                                @if($method->is_active)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-200">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $method->sort_order }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.payment-methods.edit', $method->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <button type="button"
                                        class="delete-method-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all"
                                        data-id="{{ $method->id }}"
                                        data-name="{{ $method->name }}"
                                        data-url="{{ route('admin.payment-methods.destroy', $method->id) }}"
                                        title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-400">
                                <i class="fa-solid fa-credit-card text-2xl mb-2 block"></i>
                                No payment methods found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($methods->hasPages())
            <div class="px-6 py-4 border-t border-gray-100" id="paginationWrapper">
                {{ $methods->links() }}
            </div>
        @endif
    </div>
</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden">
    <div id="deleteModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="deleteModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4 ring-8 ring-red-50/50">
                <i class="fa-solid fa-trash-can text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 text-center">Delete Method?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to delete
                <span id="deleteModalItemName" class="font-semibold text-gray-700">this method</span>?
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

<!-- TOAST CONTAINER -->
<div id="toastContainer" class="fixed top-5 right-5 z-[200] flex flex-col gap-3 w-full max-w-sm px-4 sm:px-0"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput   = document.getElementById('methodSearchInput');
    const tableBody     = document.getElementById('methodsTableBody');
    const loadingIcon   = document.getElementById('searchLoadingIcon');
    const paginationBox = document.getElementById('paginationWrapper');
    const searchUrl     = "{{ route('admin.payment-methods.search') }}";
    const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

    const typeLabels = { cod: 'Cash on Delivery', mobile_wallet: 'Mobile Wallet', bank: 'Bank' };
    const typeColors = {
        cod: 'bg-amber-50 text-amber-600 border-amber-200',
        mobile_wallet: 'bg-sky-50 text-sky-600 border-sky-200',
        bank: 'bg-indigo-50 text-indigo-600 border-indigo-100',
    };

    // Toast
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
            <div class="absolute bottom-0 left-0 h-1 ${isSuccess ? 'bg-gradient-to-r from-[#334155] to-[#1e293b]' : 'bg-red-400'} toast-progress" style="width:100%;"></div>
        `;
        toastContainer.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-x-[120%]', 'opacity-0'));
        const progressBar = toast.querySelector('.toast-progress');
        progressBar.style.transition = `width ${duration}ms linear`;
        requestAnimationFrame(() => requestAnimationFrame(() => progressBar.style.width = '0%'));
        toast.querySelector('.toast-close-btn').addEventListener('click', () => removeToast(toast));
        const timer = setTimeout(() => removeToast(toast), duration);
        toast.addEventListener('mouseenter', () => { clearTimeout(timer); progressBar.style.transition = 'none'; });
        function removeToast(el) { el.classList.add('translate-x-[120%]', 'opacity-0'); setTimeout(() => el.remove(), 300); }
    }

    @if(session('success')) showToast(@json(session('success')), 'success'); @endif
    @if(session('error')) showToast(@json(session('error')), 'error'); @endif
    @if($errors->any()) showToast(@json($errors->first()), 'error'); @endif

    // Search
    let debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        debounceTimer = setTimeout(() => {
            loadingIcon.classList.remove('hidden');
            fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                renderRows(data.methods);
                if (paginationBox) paginationBox.style.display = query ? 'none' : '';
            })
            .catch(() => {
                tableBody.innerHTML = `<tr><td colspan="10" class="px-6 py-10 text-center text-red-400">Something went wrong.</td></tr>`;
            })
            .finally(() => loadingIcon.classList.add('hidden'));
        }, 300);
    });

    function esc(str) { const d = document.createElement('div'); d.textContent = str ?? ''; return d.innerHTML; }

    function renderRows(methods) {
        if (!methods.length) {
            tableBody.innerHTML = `<tr><td colspan="10" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-credit-card text-2xl mb-2 block"></i>No methods found.</td></tr>`;
            return;
        }
        tableBody.innerHTML = methods.map(m => {
            const badgeClass = typeColors[m.type] || 'bg-gray-100 text-gray-500 border-gray-200';
            const badgeLabel = typeLabels[m.type] || m.type;
            const statusBadge = m.is_active
                ? `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-200">Active</span>`
                : `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>`;
            const logoCell = m.logo_url
                ? `<img src="${m.logo_url}" alt="${esc(m.name)} logo" class="w-9 h-9 rounded-lg object-contain border border-gray-100 bg-gray-50">`
                : `<div class="w-9 h-9 rounded-lg border border-gray-100 bg-gray-50 flex items-center justify-center text-gray-300"><i class="fa-solid fa-image text-xs"></i></div>`;
            return `
            <tr data-row-id="${m.id}">
                <td class="px-6 py-3 text-gray-500">${m.id}</td>
                <td class="px-6 py-3">${logoCell}</td>
                <td class="px-6 py-3 font-medium text-gray-800"><span class="mr-1">${esc(m.icon) || '💳'}</span>${esc(m.name)}</td>
                <td class="px-6 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold border ${badgeClass}">${badgeLabel}</span></td>
                <td class="px-6 py-3 text-gray-500">${esc(m.account_title) || '—'}</td>
                <td class="px-6 py-3 text-gray-500 text-xs truncate max-w-[140px]">${esc(m.account_number) || esc(m.iban) || '—'}</td>
                <td class="px-6 py-3 text-gray-400 text-xs truncate max-w-[120px]">${esc(m.deep_link) || '—'}</td>
                <td class="px-6 py-3">${statusBadge}</td>
                <td class="px-6 py-3 text-gray-500">${m.sort_order ?? 0}</td>
                <td class="px-6 py-3 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="${m.edit_url}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all" title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                        <button type="button" class="delete-method-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all" data-id="${m.id}" data-name="${esc(m.name)}" data-url="${m.delete_url}" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                    </div>
                </td>
            </tr>
        `}).join('');
    }

    // Delete modal
    const deleteModal          = document.getElementById('deleteModal');
    const deleteModalBackdrop  = document.getElementById('deleteModalBackdrop');
    const deleteModalBox       = document.getElementById('deleteModalBox');
    const deleteModalItemName  = document.getElementById('deleteModalItemName');
    const deleteModalCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteModalConfirmBtn= document.getElementById('deleteModalConfirmBtn');
    const deleteModalConfirmText = document.getElementById('deleteModalConfirmText');
    const deleteModalSpinner   = document.getElementById('deleteModalSpinner');

    let pendingDeleteUrl = null, pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url; pendingDeleteRow = row;
        deleteModalItemName.textContent = name || 'this method';
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
        setTimeout(() => { deleteModal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
        pendingDeleteUrl = null; pendingDeleteRow = null;
    }

    tableBody.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-method-btn');
        if (btn) openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
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
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                pendingDeleteRow?.remove();
                if (!tableBody.querySelector('tr')) {
                    tableBody.innerHTML = `<tr><td colspan="10" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-credit-card text-2xl mb-2 block"></i>No methods found.</td></tr>`;
                }
                showToast('Payment method deleted.', 'success');
            } else {
                showToast(data.message || 'Failed to delete.', 'error');
            }
        })
        .catch(() => showToast('Something went wrong.', 'error'))
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
{{-- FILE: resources/views/admin/pages/riders/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'View | Riders')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Pink Gradient Banner Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 bg-gradient-to-r from-[#ff2d7a] to-[#ff6fa5] rounded-2xl px-6 py-6 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center text-xl">🛵</div>
            <div>
                <h1 class="text-xl font-bold text-white">Rider Management</h1>
                <p class="text-sm text-pink-50/90 mt-0.5">Manage your delivery riders</p>
            </div>
        </div>
        <a href="{{ route('admin.riders.create') }}"
           class="inline-flex items-center gap-2 bg-white text-[#ff2d7a] font-semibold px-5 py-2.5 rounded-xl shadow-md hover:bg-pink-50 transition-all">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add Rider</span>
        </a>
    </div>

    <!-- Search + Table Card -->
    <div class="bg-white border border-pink-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="p-4 border-b border-pink-50 flex items-center justify-between gap-4">
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    id="riderSearchInput"
                    placeholder="Search by name, phone, vehicle #..."
                    class="w-full pl-9 pr-9 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                    autocomplete="off"
                >
                <i id="searchLoadingIcon" class="fa-solid fa-circle-notch fa-spin absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm hidden"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-pink-50/50 text-[#ff2d7a] uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Photo</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Contact</th>
                        <th class="px-6 py-3">Address</th>
                        <th class="px-6 py-3">Vehicle</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="ridersTableBody" class="divide-y divide-pink-50">
                    @forelse($riders as $rider)
                        <tr data-row-id="{{ $rider->id }}">
                            <td class="px-6 py-3 text-gray-500">{{ $rider->id }}</td>
                            <td class="px-6 py-3">
                                @if($rider->image)
                                    <img src="{{ asset($rider->image) }}" alt="{{ $rider->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-pink-100">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-pink-50 border-2 border-pink-100 flex items-center justify-center text-[#ff2d7a] font-bold text-sm">
                                        {{ strtoupper(substr($rider->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-medium text-gray-800">
                                {{ $rider->name }}
                                @if($rider->email)
                                    <p class="text-xs text-gray-400 font-normal">{{ $rider->email }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $rider->phone }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs truncate max-w-[160px]">
                                {{ $rider->address }}
                                @if($rider->city) <br><span class="text-gray-400">{{ $rider->city }}</span> @endif
                            </td>
                            <td class="px-6 py-3">
                                @php
                                    $vehicleLabels = ['bike' => '🏍️ Bike', 'car' => '🚗 Car', 'van' => '🚐 Van', 'bicycle' => '🚲 Bicycle'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-pink-50 text-[#ff2d7a] border-pink-200">
                                    {{ $vehicleLabels[$rider->vehicle_type] ?? ucfirst($rider->vehicle_type) }}
                                </span>
                                @if($rider->vehicle_number)
                                    <p class="text-xs text-gray-400 mt-1">{{ $rider->vehicle_number }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <button type="button"
                                    class="toggle-status-btn px-2.5 py-1 rounded-full text-xs font-semibold border transition-all {{ $rider->is_active ? 'bg-green-50 text-green-600 border-green-200' : 'bg-gray-100 text-gray-500 border-gray-200' }}"
                                    data-id="{{ $rider->id }}"
                                    data-url="{{ route('admin.riders.toggle-status', $rider->id) }}">
                                    {{ $rider->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.riders.edit', $rider->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <button type="button"
                                        class="delete-rider-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all"
                                        data-id="{{ $rider->id }}"
                                        data-name="{{ $rider->name }}"
                                        data-url="{{ route('admin.riders.destroy', $rider->id) }}"
                                        title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                <i class="fa-solid fa-motorcycle text-2xl mb-2 block"></i>
                                No riders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riders->hasPages())
            <div class="px-6 py-4 border-t border-pink-50" id="paginationWrapper">
                {{ $riders->links() }}
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
            <h3 class="text-lg font-bold text-gray-800 text-center">Delete Rider?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to delete
                <span id="deleteModalItemName" class="font-semibold text-gray-700">this rider</span>?
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
    const searchInput   = document.getElementById('riderSearchInput');
    const tableBody     = document.getElementById('ridersTableBody');
    const loadingIcon   = document.getElementById('searchLoadingIcon');
    const paginationBox = document.getElementById('paginationWrapper');
    const searchUrl     = "{{ route('admin.riders.search') }}";
    const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

    const vehicleLabels = { bike: '🏍️ Bike', car: '🚗 Car', van: '🚐 Van', bicycle: '🚲 Bicycle' };

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
            <div class="absolute bottom-0 left-0 h-1 ${isSuccess ? 'bg-gradient-to-r from-[#ff2d7a] to-[#ff6fa5]' : 'bg-red-400'} toast-progress" style="width:100%;"></div>
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
                renderRows(data.riders);
                if (paginationBox) paginationBox.style.display = query ? 'none' : '';
            })
            .catch(() => {
                tableBody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-red-400">Something went wrong.</td></tr>`;
            })
            .finally(() => loadingIcon.classList.add('hidden'));
        }, 300);
    });

    function esc(str) { const d = document.createElement('div'); d.textContent = str ?? ''; return d.innerHTML; }

    function renderRows(riders) {
        if (!riders.length) {
            tableBody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-motorcycle text-2xl mb-2 block"></i>No riders found.</td></tr>`;
            return;
        }
        tableBody.innerHTML = riders.map(r => {
            const initial = esc(r.name).charAt(0).toUpperCase();
            const photoCell = r.image_url
                ? `<img src="${r.image_url}" alt="${esc(r.name)}" class="w-10 h-10 rounded-full object-cover border-2 border-pink-100">`
                : `<div class="w-10 h-10 rounded-full bg-pink-50 border-2 border-pink-100 flex items-center justify-center text-[#ff2d7a] font-bold text-sm">${initial}</div>`;
            const statusBadge = r.is_active
                ? `bg-green-50 text-green-600 border-green-200`
                : `bg-gray-100 text-gray-500 border-gray-200`;
            const vehicleLabel = vehicleLabels[r.vehicle_type] || r.vehicle_type;

            return `
            <tr data-row-id="${r.id}">
                <td class="px-6 py-3 text-gray-500">${r.id}</td>
                <td class="px-6 py-3">${photoCell}</td>
                <td class="px-6 py-3 font-medium text-gray-800">${esc(r.name)}${r.email ? `<p class="text-xs text-gray-400 font-normal">${esc(r.email)}</p>` : ''}</td>
                <td class="px-6 py-3 text-gray-600">${esc(r.phone)}</td>
                <td class="px-6 py-3 text-gray-500 text-xs truncate max-w-[160px]">${esc(r.address)}${r.city ? `<br><span class="text-gray-400">${esc(r.city)}</span>` : ''}</td>
                <td class="px-6 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-pink-50 text-[#ff2d7a] border-pink-200">${vehicleLabel}</span>
                    ${r.vehicle_number ? `<p class="text-xs text-gray-400 mt-1">${esc(r.vehicle_number)}</p>` : ''}
                </td>
                <td class="px-6 py-3">
                    <button type="button" class="toggle-status-btn px-2.5 py-1 rounded-full text-xs font-semibold border transition-all ${statusBadge}" data-id="${r.id}" data-url="${r.toggle_url}">
                        ${r.is_active ? 'Active' : 'Inactive'}
                    </button>
                </td>
                <td class="px-6 py-3 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="${r.edit_url}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all" title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                        <button type="button" class="delete-rider-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all" data-id="${r.id}" data-name="${esc(r.name)}" data-url="${r.delete_url}" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                    </div>
                </td>
            </tr>
        `}).join('');
    }

    // Status toggle (delegated, works for both server-rendered and AJAX rows)
    tableBody.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('.toggle-status-btn');
        if (toggleBtn) {
            toggleBtn.disabled = true;
            fetch(toggleBtn.dataset.url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.is_active) {
                        toggleBtn.textContent = 'Active';
                        toggleBtn.className = 'toggle-status-btn px-2.5 py-1 rounded-full text-xs font-semibold border transition-all bg-green-50 text-green-600 border-green-200';
                    } else {
                        toggleBtn.textContent = 'Inactive';
                        toggleBtn.className = 'toggle-status-btn px-2.5 py-1 rounded-full text-xs font-semibold border transition-all bg-gray-100 text-gray-500 border-gray-200';
                    }
                    showToast('Rider status updated.', 'success', 2500);
                } else {
                    showToast('Failed to update status.', 'error');
                }
            })
            .catch(() => showToast('Something went wrong.', 'error'))
            .finally(() => { toggleBtn.disabled = false; });
            return;
        }

        const delBtn = e.target.closest('.delete-rider-btn');
        if (delBtn) openDeleteModal(delBtn.dataset.url, delBtn.closest('tr'), delBtn.dataset.name);
    });

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
        deleteModalItemName.textContent = name || 'this rider';
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
                    tableBody.innerHTML = `<tr><td colspan="8" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-motorcycle text-2xl mb-2 block"></i>No riders found.</td></tr>`;
                }
                showToast('Rider deleted.', 'success');
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
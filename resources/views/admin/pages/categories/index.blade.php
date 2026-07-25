@extends('admin.layouts.master')

@section('admin_content')
<!-- FIXED: Added CSRF meta tag here so AJAX delete works regardless of your master layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-7xl mx-auto">

    <!-- Header Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Categories Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all product categories from here.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91] text-white font-medium px-5 py-2.5 rounded-xl shadow-md shadow-[#ff2d7a]/20 hover:opacity-90 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Category</span>
        </a>
    </div>

    <!-- Search + Table Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        <!-- Search Bar (top-left, instant/dynamic — no submit button) -->
        <div class="p-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    id="categorySearchInput"
                    placeholder="Search categories by name, slug or description..."
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
                        <th class="px-6 py-3">Image</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Description</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody" class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr data-row-id="{{ $category->id }}">
                            <td class="px-6 py-3">
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                         class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                            <td class="px-6 py-3 text-gray-500 max-w-xs truncate">
                                {{ $category->description ? Str::limit($category->description, 45) : '—' }}
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $category->slug }}</td>
                            <td class="px-6 py-3">
                                @if($category->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-200">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <button type="button"
                                        class="delete-category-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-url="{{ route('admin.categories.destroy', $category->id) }}"
                                        title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                <i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100" id="paginationWrapper">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ======================= DELETE CONFIRMATION MODAL ======================= -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop -->
    <div id="deleteModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

    <!-- Modal Box -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="deleteModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform transition-all duration-300 scale-95 opacity-0">

            <!-- Icon -->
            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4 ring-8 ring-red-50/50">
                <i class="fa-solid fa-trash-can text-red-500 text-2xl"></i>
            </div>

            <h3 class="text-lg font-bold text-gray-800 text-center">Delete Category?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to delete
                <span id="deleteModalCategoryName" class="font-semibold text-gray-700">this category</span>?
                This action cannot be undone.
            </p>

            <div class="flex items-center gap-3 mt-6">
                <button type="button" id="deleteModalCancelBtn"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="button" id="deleteModalConfirmBtn"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-red-600 text-white font-medium shadow-md shadow-red-500/20 hover:opacity-90 transition-all flex items-center justify-center gap-2">
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
    const searchInput   = document.getElementById('categorySearchInput');
    const tableBody     = document.getElementById('categoriesTableBody');
    const loadingIcon   = document.getElementById('searchLoadingIcon');
    const paginationBox = document.getElementById('paginationWrapper');
    const searchUrl     = "{{ route('admin.categories.search') }}";
    const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

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

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-[120%]', 'opacity-0');
        });

        // Progress bar countdown
        const progressBar = toast.querySelector('.toast-progress');
        progressBar.style.transition = `width ${duration}ms linear`;
        requestAnimationFrame(() => {
            requestAnimationFrame(() => { progressBar.style.width = '0%'; });
        });

        // Manual close
        toast.querySelector('.toast-close-btn').addEventListener('click', () => removeToast(toast));

        // Auto dismiss
        const timer = setTimeout(() => removeToast(toast), duration);

        // Pause on hover
        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            progressBar.style.transition = 'none';
        });

        function removeToast(el) {
            el.classList.add('translate-x-[120%]', 'opacity-0');
            setTimeout(() => el.remove(), 300);
        }
    }

    // Show flash messages from server (after create/update/delete redirects)
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
    // INSTANT / DYNAMIC SEARCH (JSON, no submit button)
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
                renderRows(data.categories);
                if (paginationBox) paginationBox.style.display = query ? 'none' : '';
            })
            .catch(() => {
                tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-10 text-center text-red-400">Something went wrong. Try again.</td></tr>`;
            })
            .finally(() => loadingIcon.classList.add('hidden'));
        }, 300);
    });

    function truncate(str, len) {
        if (!str) return '—';
        return str.length > len ? str.substring(0, len) + '...' : str;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    function renderRows(categories) {
        if (!categories.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        <i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>
                        No categories found.
                    </td>
                </tr>`;
            return;
        }

        tableBody.innerHTML = categories.map(cat => `
            <tr data-row-id="${cat.id}">
                <td class="px-6 py-3">
                    ${cat.image
                        ? `<img src="${cat.image}" alt="${escapeHtml(cat.name)}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">`
                        : `<div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-image"></i></div>`
                    }
                </td>
                <td class="px-6 py-3 font-medium text-gray-800">${escapeHtml(cat.name)}</td>
                <td class="px-6 py-3 text-gray-500 max-w-xs truncate">${escapeHtml(truncate(cat.description, 45))}</td>
                <td class="px-6 py-3 text-gray-500">${escapeHtml(cat.slug)}</td>
                <td class="px-6 py-3">
                    ${cat.status === 'active'
                        ? `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-200">Active</span>`
                        : `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>`
                    }
                </td>
                <td class="px-6 py-3 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="${cat.edit_url}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all" title="Edit">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <button type="button" class="delete-category-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all" data-id="${cat.id}" data-name="${escapeHtml(cat.name)}" data-url="${cat.delete_url}" title="Delete">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // ==================================================================
    // STYLISH DELETE CONFIRMATION MODAL
    // ==================================================================
    const deleteModal          = document.getElementById('deleteModal');
    const deleteModalBackdrop  = document.getElementById('deleteModalBackdrop');
    const deleteModalBox       = document.getElementById('deleteModalBox');
    const deleteModalNameEl    = document.getElementById('deleteModalCategoryName');
    const deleteModalCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteModalConfirmBtn= document.getElementById('deleteModalConfirmBtn');
    const deleteModalConfirmText = document.getElementById('deleteModalConfirmText');
    const deleteModalSpinner   = document.getElementById('deleteModalSpinner');

    let pendingDeleteUrl = null;
    let pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url;
        pendingDeleteRow = row;
        deleteModalNameEl.textContent = name ? `"${name}"` : 'this category';

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

    // Open modal when any delete button is clicked (event delegation — works for search-rendered rows too)
    tableBody.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-category-btn');
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
            if (!res.ok) throw new Error('Request failed with status ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                pendingDeleteRow?.remove();
                if (!tableBody.querySelector('tr')) {
                    tableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>No categories found.</td></tr>`;
                }
                showToast('Category deleted successfully.', 'success');
            } else {
                showToast('Failed to delete category. Please try again.', 'error');
            }
        })
        .catch(() => {
            showToast('Something went wrong while deleting. Please try again.', 'error');
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
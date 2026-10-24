@extends('admin.layouts.master')
@section('title', 'View | Categories')

@section('content')
<style>
  .cat-page * { box-sizing: border-box; }
  .cat-page .cat-header h1 {
    font-size: 24px !important;
    font-weight: 700 !important;
    color: #1f2937;
    margin: 0;
    line-height: 1.3;
  }
  .cat-page .cat-header p {
    font-size: 14px;
    color: #6b7280;
    margin: 4px 0 0 0;
  }
  .cat-add-btn {
    background: linear-gradient(to right, #ff2d7a, #ff4b91);
    color: #fff;
    font-weight: 500;
    font-size: 14px;
    padding: 10px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.25);
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    text-decoration: none;
    white-space: nowrap;
  }
  .cat-add-btn:hover { opacity: 0.9; color: #fff; text-decoration: none; }

  .cat-card {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    background: #fff;
  }

  .cat-search-wrap { position: relative; width: 100%; max-width: 320px; }
  .cat-search {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 12px 10px 36px;
    font-size: 14px;
    width: 100%;
    outline: none;
    transition: all 0.2s;
  }
  .cat-search:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.15);
  }
  .cat-search-wrap i.fa-magnifying-glass {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%); color: #9ca3af; font-size: 13px;
  }
  .cat-search-spinner {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%); color: #9ca3af; font-size: 13px; display: none;
  }

  .cat-page table.cat-table { width: 100%; margin-bottom: 0; border-collapse: collapse; }
  .cat-page .cat-table thead th {
    background: #f9fafb;
    color: #6b7280;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    font-weight: 600;
    padding: 12px 24px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    white-space: nowrap;
  }
  .cat-page .cat-table tbody td {
    padding: 12px 24px;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
    color: #374151;
  }
  .cat-page .cat-table tbody tr:last-child td { border-bottom: none; }

  .cat-img { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb; display: block; }
  .cat-img-placeholder {
    width: 48px; height: 48px; border-radius: 8px; background: #f3f4f6;
    display: flex; align-items: center; justify-content: center; color: #d1d5db;
  }

  .cat-status-active {
    background: #ecfdf5; color: #16a34a; border: 1px solid #bbf7d0;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .cat-status-inactive {
    background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }

  .cat-edit-btn, .cat-delete-btn {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; transition: all 0.2s;
  }
  .cat-edit-btn { background: #eff6ff; color: #2563eb; }
  .cat-edit-btn:hover { background: #dbeafe; color: #2563eb; }
  .cat-delete-btn { background: #fef2f2; color: #dc2626; }
  .cat-delete-btn:hover { background: #fee2e2; color: #dc2626; }

  .cat-full-width { max-width: 1280px; margin: 0 auto; padding: 0 16px; }
  @media (min-width: 1280px) { .cat-full-width { padding: 0; } }

  .cat-pagination-bar {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
  }
  .cat-pagination-info { font-size: 13px; color: #6b7280; }
  .cat-pagination-nav { display: flex; align-items: center; gap: 6px; }
  .cat-page-link {
    min-width: 32px; height: 32px; padding: 0 8px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: 1px solid #e5e7eb; background: #fff;
    color: #374151; font-size: 13px; text-decoration: none; transition: all .15s;
  }
  .cat-page-link:hover { background: #f9fafb; color: #374151; text-decoration: none; }
  .cat-page-link.active { background: #1f2937; border-color: #1f2937; color: #fff; }
  .cat-page-link.disabled { color: #d1d5db; pointer-events: none; }

  /* ===== DELETE MODAL - EXACT TAILWIND STYLE ===== */
  .delete-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(17, 24, 39, 0.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  .delete-modal-overlay.active {
    display: flex;
  }
  .delete-modal-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    width: 100%;
    max-width: 400px;
    padding: 24px;
    transform: scale(0.95);
    opacity: 0;
    transition: all 0.3s ease;
  }
  .delete-modal-overlay.active .delete-modal-box {
    transform: scale(1);
    opacity: 1;
  }
  .delete-modal-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #fef2f2;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px auto;
    box-shadow: 0 0 0 8px rgba(254, 226, 226, 0.5);
  }
  .delete-modal-icon i {
    color: #dc2626;
    font-size: 24px;
  }
  .delete-modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-align: center;
    margin-bottom: 8px;
  }
  .delete-modal-text {
    font-size: 14px;
    color: #6b7280;
    text-align: center;
    line-height: 1.6;
    margin-bottom: 0;
  }
  .delete-modal-text .highlight-name {
    font-weight: 600;
    color: #1f2937;
  }
  .delete-modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
  }
  .delete-modal-actions .btn-cancel {
    flex: 1;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.2s;
  }
  .delete-modal-actions .btn-cancel:hover {
    background: #f9fafb;
  }
  .delete-modal-actions .btn-delete {
    flex: 1;
    padding: 10px 16px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(to right, #ef4444, #dc2626);
    color: #fff;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
  }
  .delete-modal-actions .btn-delete:hover {
    opacity: 0.9;
  }
  .delete-modal-actions .btn-delete:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }
  .delete-spinner {
    display: none;
    animation: spin 1s linear infinite;
  }
  .delete-spinner.show {
    display: inline-block;
  }
  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  /* ===== TOAST NOTIFICATIONS ===== */
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
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="cat-page">
  <div class="cat-full-width">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div class="cat-header">
        <h1>Categories Management</h1>
        <p>Manage all product categories from here.</p>
      </div>
      <a href="{{ route('admin.categories.create') }}" class="cat-add-btn">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Category</span>
      </a>
    </div>

    <div class="cat-card">

      <div class="p-3 p-md-4" style="border-bottom:1px solid #f3f4f6;">
        <div class="cat-search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="categorySearchInput" placeholder="Search categories by name, slug or description..." class="cat-search" autocomplete="off">
          <i id="searchLoadingIcon" class="fa-solid fa-circle-notch fa-spin cat-search-spinner"></i>
        </div>
      </div>

      <div class="table-responsive">
        <table class="cat-table">
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Description</th>
              <th>Slug</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="categoriesTableBody">
            @forelse($categories as $category)
              <tr data-row-id="{{ $category->id }}">
                <td>
                  @if($category->image)
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="cat-img">
                  @else
                    <div class="cat-img-placeholder"><i class="fa-solid fa-image"></i></div>
                  @endif
                </td>
                <td class="fw-medium text-dark">{{ $category->name }}</td>
                <td class="text-secondary" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ $category->description ? Str::limit($category->description, 45) : '—' }}
                </td>
                <td class="text-secondary">{{ $category->slug }}</td>
                <td>
                  @if($category->status === 'active')
                    <span class="cat-status-active">Active</span>
                  @else
                    <span class="cat-status-inactive">Inactive</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="cat-edit-btn" title="Edit">
                      <i class="fa-solid fa-pen" style="font-size:11px;"></i>
                    </a>
                    <button type="button"
                      class="delete-category-btn cat-delete-btn"
                      data-id="{{ $category->id }}"
                      data-name="{{ $category->name }}"
                      data-url="{{ route('admin.categories.destroy', $category->id) }}"
                      title="Delete">
                      <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr id="emptyRow">
                <td colspan="6" class="text-center text-secondary py-5">
                  <i class="fa-solid fa-folder-open d-block mb-2" style="font-size:24px;"></i>
                  No categories found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($categories->hasPages())
        <div class="p-3 p-md-4" style="border-top:1px solid #f3f4f6;" id="paginationWrapper">
          <div class="cat-pagination-bar">
            <div class="cat-pagination-info">
              Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
            </div>
            <div class="cat-pagination-nav">
              <a href="{{ $categories->previousPageUrl() }}" class="cat-page-link {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                <i class="fa-solid fa-chevron-left" style="font-size:11px;"></i>
              </a>
              @for($p = 1; $p <= $categories->lastPage(); $p++)
                <a href="{{ $categories->url($p) }}" class="cat-page-link {{ $p == $categories->currentPage() ? 'active' : '' }}">{{ $p }}</a>
              @endfor
              <a href="{{ $categories->nextPageUrl() }}" class="cat-page-link {{ $categories->hasMorePages() ? '' : 'disabled' }}">
                <i class="fa-solid fa-chevron-right" style="font-size:11px;"></i>
              </a>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- ===== DELETE MODAL - EXACT TAILWIND STYLE ===== -->
<div id="deleteModal" class="delete-modal-overlay">
  <div class="delete-modal-box">
    <div class="delete-modal-icon">
      <i class="fa-solid fa-trash-can"></i>
    </div>
    <h3 class="delete-modal-title">Delete Category?</h3>
    <p class="delete-modal-text">
      Are you sure you want to delete <span id="deleteModalCategoryName" class="highlight-name">this category</span>? This action cannot be undone.
    </p>
    <div class="delete-modal-actions">
      <button type="button" id="deleteModalCancelBtn" class="btn-cancel">Cancel</button>
      <button type="button" id="deleteModalConfirmBtn" class="btn-delete">
        <span id="deleteModalConfirmText">Yes, Delete</span>
        <i id="deleteModalSpinner" class="fa-solid fa-circle-notch delete-spinner"></i>
      </button>
    </div>
  </div>
</div>

<!-- ===== TOAST CONTAINER ===== -->
<div id="toastContainer"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('categorySearchInput');
  const tableBody = document.getElementById('categoriesTableBody');
  const loadingIcon = document.getElementById('searchLoadingIcon');
  const paginationBox = document.getElementById('paginationWrapper');
  const searchUrl = "{{ route('admin.categories.search') }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // ============================================================
  // TOAST NOTIFICATION SYSTEM - TOP RIGHT
  // ============================================================
  function showToast(message, type = 'success', duration = 5000) {
    const container = document.getElementById('toastContainer');
    const isSuccess = type === 'success';
    
    const toast = document.createElement('div');
    toast.className = `toast-item ${isSuccess ? 'success' : 'error'}`;
    
    toast.innerHTML = `
      <div class="toast-icon ${isSuccess ? 'success' : 'error'}">
        <i class="fa-solid ${isSuccess ? 'fa-check' : 'fa-xmark'} ${isSuccess ? 'success' : 'error'}" style="font-size:14px;"></i>
      </div>
      <div class="toast-content">
        <p class="toast-title">${isSuccess ? 'Success' : 'Error'}</p>
        <p class="toast-message">${message}</p>
      </div>
      <button class="toast-close">
        <i class="fa-solid fa-xmark" style="font-size:14px;"></i>
      </button>
      <div class="toast-progress"></div>
    `;
    
    container.appendChild(toast);
    
    requestAnimationFrame(() => {
      toast.classList.add('show');
    });
    
    const progress = toast.querySelector('.toast-progress');
    progress.style.transition = `width ${duration}ms linear`;
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        progress.style.width = '0%';
      });
    });
    
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => removeToast(toast));
    
    const timer = setTimeout(() => removeToast(toast), duration);
    
    toast.addEventListener('mouseenter', () => {
      clearTimeout(timer);
      progress.style.transition = 'none';
    });
    
    function removeToast(el) {
      el.classList.remove('show');
      setTimeout(() => el.remove(), 300);
    }
  }

  // ============================================================
  // FLASH MESSAGES
  // ============================================================
  @if(session('success'))
    showToast(@json(session('success')), 'success');
  @endif
  @if(session('error'))
    showToast(@json(session('error')), 'error');
  @endif
  @if($errors->any())
    showToast(@json($errors->first()), 'error');
  @endif

  // ============================================================
  // SEARCH FUNCTIONALITY
  // ============================================================
  let debounceTimer;
  searchInput.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const query = this.value.trim();
    debounceTimer = setTimeout(() => {
      loadingIcon.style.display = 'block';
      fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json())
      .then(data => {
        renderRows(data.categories);
        if (paginationBox) paginationBox.style.display = query ? 'none' : '';
      })
      .catch(() => {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">Something went wrong. Try again.</td></tr>`;
      })
      .finally(() => loadingIcon.style.display = 'none');
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
        <tr><td colspan="6" class="text-center text-secondary py-5">
          <i class="fa-solid fa-folder-open d-block mb-2" style="font-size:24px;"></i>
          No categories found.
        </td></tr>`;
      return;
    }
    tableBody.innerHTML = categories.map(cat => `
      <tr data-row-id="${cat.id}">
        <td>
          ${cat.image
            ? `<img src="${cat.image}" alt="${escapeHtml(cat.name)}" class="cat-img">`
            : `<div class="cat-img-placeholder"><i class="fa-solid fa-image"></i></div>`
          }
        </td>
        <td class="fw-medium text-dark">${escapeHtml(cat.name)}</td>
        <td class="text-secondary" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(truncate(cat.description, 45))}</td>
        <td class="text-secondary">${escapeHtml(cat.slug)}</td>
        <td>
          ${cat.status === 'active'
            ? `<span class="cat-status-active">Active</span>`
            : `<span class="cat-status-inactive">Inactive</span>`
          }
        </td>
        <td class="text-end">
          <div class="d-flex justify-content-end gap-2">
            <a href="${cat.edit_url}" class="cat-edit-btn"><i class="fa-solid fa-pen" style="font-size:11px;"></i></a>
            <button type="button" class="delete-category-btn cat-delete-btn" data-id="${cat.id}" data-name="${escapeHtml(cat.name)}" data-url="${cat.delete_url}"><i class="fa-solid fa-trash" style="font-size:11px;"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
  }

  // ============================================================
  // DELETE MODAL - EXACT TAILWIND STYLE
  // ============================================================
  const deleteModal = document.getElementById('deleteModal');
  const deleteModalNameEl = document.getElementById('deleteModalCategoryName');
  const deleteModalCancelBtn = document.getElementById('deleteModalCancelBtn');
  const deleteModalConfirmBtn = document.getElementById('deleteModalConfirmBtn');
  const deleteModalConfirmText = document.getElementById('deleteModalConfirmText');
  const deleteModalSpinner = document.getElementById('deleteModalSpinner');

  let pendingDeleteUrl = null;
  let pendingDeleteRow = null;

  function openDeleteModal(url, row, name) {
    pendingDeleteUrl = url;
    pendingDeleteRow = row;
    deleteModalNameEl.textContent = name ? `"${name}"` : 'this category';
    deleteModal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeDeleteModal() {
    deleteModal.classList.remove('active');
    document.body.style.overflow = '';
    pendingDeleteUrl = null;
    pendingDeleteRow = null;
    deleteModalConfirmBtn.disabled = false;
    deleteModalConfirmText.textContent = 'Yes, Delete';
    deleteModalSpinner.classList.remove('show');
  }

  // Event delegation for delete buttons
  tableBody.addEventListener('click', function (e) {
    const btn = e.target.closest('.delete-category-btn');
    if (!btn) return;
    e.preventDefault();
    openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
  });

  // Cancel button
  deleteModalCancelBtn.addEventListener('click', function(e) {
    e.preventDefault();
    closeDeleteModal();
  });

  // Click outside to close
  deleteModal.addEventListener('click', function(e) {
    if (e.target === this) {
      closeDeleteModal();
    }
  });

  // ESC key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
      closeDeleteModal();
    }
  });

  // Confirm delete
  deleteModalConfirmBtn.addEventListener('click', function () {
    if (!pendingDeleteUrl) return;

    deleteModalConfirmBtn.disabled = true;
    deleteModalConfirmText.textContent = 'Deleting...';
    deleteModalSpinner.classList.add('show');

    fetch(pendingDeleteUrl, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
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
        if (pendingDeleteRow) {
          pendingDeleteRow.remove();
        }
        if (!tableBody.querySelector('tr')) {
          tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-secondary py-5"><i class="fa-solid fa-folder-open d-block mb-2" style="font-size:24px;"></i>No categories found.</td></tr>`;
        }
        showToast('Category deleted successfully.', 'success');
        closeDeleteModal();
      } else {
        showToast(data.message || 'Failed to delete category.', 'error');
        closeDeleteModal();
      }
    })
    .catch(err => {
      showToast(err.message || 'Something went wrong while deleting.', 'error');
      closeDeleteModal();
    })
    .finally(() => {
      deleteModalConfirmBtn.disabled = false;
      deleteModalConfirmText.textContent = 'Yes, Delete';
      deleteModalSpinner.classList.remove('show');
    });
  });
});
</script>
@endsection
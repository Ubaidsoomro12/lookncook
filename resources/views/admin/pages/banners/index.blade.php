@extends('admin.layouts.master')

@section('content')
<style>
  .banner-page * { box-sizing: border-box; }
  .banner-header {
    background: linear-gradient(to right, #ff2d7a, #ff6fa5);
    border-radius: 16px;
    padding: 24px 30px;
  }
  .banner-header h1 { color: #fff; font-size: 20px; font-weight: 700; margin: 0; }
  .banner-header p { color: rgba(255,255,255,0.9); font-size: 14px; margin: 0; }

  .banner-add-btn {
    background: #fff;
    color: #ff2d7a;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    text-decoration: none;
    white-space: nowrap;
  }
  .banner-add-btn:hover { background: #fdf2f8; color: #ff2d7a; text-decoration: none; }

  .banner-card {
    border: 1px solid #fce7f3;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    background: #fff;
  }

  .banner-search-wrap { position: relative; width: 100%; max-width: 320px; }
  .banner-search {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 12px 10px 36px;
    font-size: 14px;
    width: 100%;
    outline: none;
    transition: all 0.2s;
  }
  .banner-search:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.15); }
  .banner-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }

  .banner-page table.banner-table { width: 100%; margin-bottom: 0; border-collapse: collapse; }
  .banner-page .banner-table thead th {
    background: #fdf2f8;
    color: #ff2d7a;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    font-weight: 600;
    padding: 12px 24px;
    border-bottom: 1px solid #fce7f3;
    text-align: left;
    white-space: nowrap;
  }
  .banner-page .banner-table tbody td {
    padding: 12px 24px;
    vertical-align: middle;
    border-bottom: 1px solid #fdf2f8;
    font-size: 14px;
    color: #374151;
  }
  .banner-page .banner-table tbody tr:last-child td { border-bottom: none; }

  .banner-img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #fce7e8; display: block; }

  .banner-section-badge {
    background: #fdf2f8; color: #ff2d7a; border: 1px solid #fbcfe8;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .banner-status-active {
    background: #ecfdf5; color: #16a34a; border: 1px solid #bbf7d0;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .banner-status-inactive {
    background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }

  .banner-edit-btn, .banner-delete-btn {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; transition: all 0.2s;
  }
  .banner-edit-btn { background: #fffbeb; color: #d97706; }
  .banner-edit-btn:hover { background: #fef3c7; color: #d97706; }
  .banner-delete-btn { background: #fef2f2; color: #dc2626; }
  .banner-delete-btn:hover { background: #fee2e2; color: #dc2626; }

  .banner-full-width { max-width: 1280px; margin: 0 auto; padding: 0 16px; }
  @media (min-width: 1280px) { .banner-full-width { padding: 0; } }

  .banner-pagination-bar {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
  }
  .banner-pagination-info { font-size: 13px; color: #6b7280; }
  .banner-pagination-nav { display: flex; align-items: center; gap: 6px; }
  .banner-page-link {
    min-width: 32px; height: 32px; padding: 0 8px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: 1px solid #fce7f3; background: #fff;
    color: #374151; font-size: 13px; text-decoration: none; transition: all .15s;
  }
  .banner-page-link:hover { background: #fdf2f8; color: #374151; text-decoration: none; }
  .banner-page-link.active { background: #ff2d7a; border-color: #ff2d7a; color: #fff; }
  .banner-page-link.disabled { color: #d1d5db; pointer-events: none; }

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

<div class="banner-page">
  <div class="banner-full-width">

    <div class="banner-header mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="d-flex align-items-center justify-content-center text-white" style="width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,0.15); font-size:20px;">🖼️</div>
          <div>
            <h1>Banners</h1>
            <p>Manage your website banners</p>
          </div>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="banner-add-btn">
          <i class="fa-solid fa-plus" style="font-size:12px;"></i>
          <span>Add Banner</span>
        </a>
      </div>
    </div>

    <div class="banner-card">
      <div class="p-3 p-md-4" style="border-bottom:1px solid #fdf2f8;">
        <div class="banner-search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="bannerSearchInput" placeholder="Search by title or section..." class="banner-search">
        </div>
      </div>

      <div class="table-responsive">
        <table class="banner-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Title</th>
              <th>Section</th>
              <th>Status</th>
              <th>Order</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="bannersTableBody">
            @forelse($banners as $banner)
              <tr data-row-id="{{ $banner->id }}">
                <td class="text-secondary">{{ $banner->id }}</td>
                <td><img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="banner-img"></td>
                <td class="fw-medium text-dark">{{ strip_tags($banner->title) ?? '—' }}</td>
                <td><span class="banner-section-badge">{{ ucfirst($banner->section) }}</span></td>
                <td>
                  @if($banner->status)
                    <span class="banner-status-active">Active</span>
                  @else
                    <span class="banner-status-inactive">Inactive</span>
                  @endif
                </td>
                <td class="text-secondary">{{ $banner->sort_order }}</td>
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="banner-edit-btn" title="Edit">
                      <i class="fa-solid fa-pen" style="font-size:11px;"></i>
                    </a>
                    <button type="button"
                      class="delete-banner-btn banner-delete-btn"
                      data-id="{{ $banner->id }}"
                      data-name="{{ $banner->title ?? 'Banner' }}"
                      data-url="{{ route('admin.banners.destroy', $banner->id) }}"
                      title="Delete">
                      <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-secondary py-5">
                  <i class="fa-solid fa-image d-block mb-2" style="font-size:24px;"></i>
                  No banners found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($banners->hasPages())
        <div class="p-3 p-md-4" style="border-top:1px solid #fdf2f8;" id="paginationWrapper">
          <div class="banner-pagination-bar">
            <div class="banner-pagination-info">
              Showing {{ $banners->firstItem() }} to {{ $banners->lastItem() }} of {{ $banners->total() }} results
            </div>
            <div class="banner-pagination-nav">
              <a href="{{ $banners->previousPageUrl() }}" class="banner-page-link {{ $banners->onFirstPage() ? 'disabled' : '' }}">
                <i class="fa-solid fa-chevron-left" style="font-size:11px;"></i>
              </a>
              @for($p = 1; $p <= $banners->lastPage(); $p++)
                <a href="{{ $banners->url($p) }}" class="banner-page-link {{ $p == $banners->currentPage() ? 'active' : '' }}">{{ $p }}</a>
              @endfor
              <a href="{{ $banners->nextPageUrl() }}" class="banner-page-link {{ $banners->hasMorePages() ? '' : 'disabled' }}">
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
    <h3 class="delete-modal-title">Delete Banner?</h3>
    <p class="delete-modal-text">
      Are you sure you want to delete <span id="deleteModalItemName" class="highlight-name">this banner</span>?
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
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";
  const tableBody = document.getElementById('bannersTableBody');
  const searchInput = document.getElementById('bannerSearchInput');
  const paginationWrapper = document.getElementById('paginationWrapper');
  const searchUrl = "{{ route('admin.banners.search') }}";

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
    
    // Show with animation
    requestAnimationFrame(() => {
      toast.classList.add('show');
    });
    
    // Progress bar
    const progress = toast.querySelector('.toast-progress');
    progress.style.transition = `width ${duration}ms linear`;
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        progress.style.width = '0%';
      });
    });
    
    // Close button
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => removeToast(toast));
    
    // Auto dismiss
    const timer = setTimeout(() => removeToast(toast), duration);
    
    // Pause on hover
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
      fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' } 
      })
      .then(res => res.json())
      .then(data => renderRows(data.banners))
      .catch(() => {
        tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">Something went wrong.</td></tr>`;
      });
    }, 300);
  });

  function renderRows(banners) {
    if (!banners.length) {
      tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-secondary py-5"><i class="fa-solid fa-image d-block mb-2" style="font-size:24px;"></i>No banners found.</td></tr>`;
      if (paginationWrapper) paginationWrapper.style.display = 'none';
      return;
    }
    tableBody.innerHTML = banners.map(b => `
      <tr data-row-id="${b.id}">
        <td class="text-secondary">${b.id}</td>
        <td><img src="${b.image_url}" class="banner-img"></td>
        <td class="fw-medium text-dark">${b.title || '—'}</td>
        <td><span class="banner-section-badge">${b.section.charAt(0).toUpperCase() + b.section.slice(1)}</span></td>
        <td>${b.status ? '<span class="banner-status-active">Active</span>' : '<span class="banner-status-inactive">Inactive</span>'}</td>
        <td class="text-secondary">${b.sort_order}</td>
        <td class="text-end">
          <div class="d-flex justify-content-end gap-2">
            <a href="${b.edit_url}" class="banner-edit-btn"><i class="fa-solid fa-pen" style="font-size:11px;"></i></a>
            <button class="delete-banner-btn banner-delete-btn" data-id="${b.id}" data-name="${b.title || 'Banner'}" data-url="${b.delete_url}"><i class="fa-solid fa-trash" style="font-size:11px;"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
    if (paginationWrapper) paginationWrapper.style.display = 'none';
  }

  // ============================================================
  // DELETE MODAL - EXACT TAILWIND STYLE
  // ============================================================
  const deleteModal = document.getElementById('deleteModal');
  const deleteItemName = document.getElementById('deleteModalItemName');
  const deleteCancelBtn = document.getElementById('deleteModalCancelBtn');
  const deleteConfirmBtn = document.getElementById('deleteModalConfirmBtn');
  const deleteConfirmText = document.getElementById('deleteModalConfirmText');
  const deleteSpinner = document.getElementById('deleteModalSpinner');

  let pendingDeleteUrl = null;
  let pendingDeleteRow = null;

  function openDeleteModal(url, row, name) {
    pendingDeleteUrl = url;
    pendingDeleteRow = row;
    deleteItemName.textContent = name || 'this banner';
    deleteModal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeDeleteModal() {
    deleteModal.classList.remove('active');
    document.body.style.overflow = '';
    pendingDeleteUrl = null;
    pendingDeleteRow = null;
    deleteConfirmBtn.disabled = false;
    deleteConfirmText.textContent = 'Yes, Delete';
    deleteSpinner.classList.remove('show');
  }

  // Event delegation for delete buttons
  tableBody.addEventListener('click', function (e) {
    const btn = e.target.closest('.delete-banner-btn');
    if (btn) {
      e.preventDefault();
      openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
    }
  });

  // Cancel button
  deleteCancelBtn.addEventListener('click', function(e) {
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
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
      closeDeleteModal();
    }
  });

  // Confirm delete
  deleteConfirmBtn.addEventListener('click', function(e) {
    e.preventDefault();
    if (!pendingDeleteUrl) return;

    deleteConfirmBtn.disabled = true;
    deleteConfirmText.textContent = 'Deleting...';
    deleteSpinner.classList.add('show');

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
          tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-secondary py-5"><i class="fa-solid fa-image d-block mb-2" style="font-size:24px;"></i>No banners found.</td></tr>`;
        }
        showToast(data.message || 'Banner deleted successfully.', 'success');
        closeDeleteModal();
      } else {
        showToast(data.message || 'Failed to delete banner.', 'error');
        closeDeleteModal();
      }
    })
    .catch(err => {
      showToast(err.message || 'Something went wrong.', 'error');
      closeDeleteModal();
    })
    .finally(() => {
      deleteConfirmBtn.disabled = false;
      deleteConfirmText.textContent = 'Yes, Delete';
      deleteSpinner.classList.remove('show');
    });
  });
});
</script>
@endsection
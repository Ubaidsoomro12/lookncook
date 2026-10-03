{{-- FILE: resources/views/admin/pages/riders/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'View | Riders')

@section('content')
<style>
  .rider-page * { box-sizing: border-box; }
  
  /* ===== FULL WIDTH CONTAINER - EXACTLY LIKE TAILWIND ===== */
  .rider-full-width {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
  }
  @media (min-width: 1280px) {
    .rider-full-width {
      padding: 0;
    }
  }

  .rider-gradient {
    background: linear-gradient(to right, #ff2d7a, #ff6fa5);
    border-radius: 16px;
    padding: 24px 30px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  }
  .rider-gradient h1 { color: #fff; font-size: 20px; font-weight: 700; margin: 0; }
  .rider-gradient p { color: rgba(255,255,255,0.9); font-size: 14px; margin: 0; }

  .rider-add-btn {
    background: #fff; color: #ff2d7a; font-weight: 600; font-size: 14px;
    padding: 10px 20px; border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    border: none; display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.2s; text-decoration: none; white-space: nowrap;
  }
  .rider-add-btn:hover { background: #fdf2f8; color: #ff2d7a; text-decoration: none; }

  .rider-card {
    border: 1px solid #fce7f3; border-radius: 16px; overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05); background: #fff;
  }

  .rider-search-wrap { position: relative; width: 100%; max-width: 320px; }
  .rider-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }
  .rider-search {
    border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 10px 12px 10px 36px; font-size: 14px; width: 100%;
    outline: none; transition: all 0.2s;
  }
  .rider-search:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .rider-search-spinner {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #9ca3af; font-size: 13px; display: none;
  }

  .rider-table thead th {
    background: #fdf2f8; color: #ff2d7a; text-transform: uppercase;
    font-size: 11px; letter-spacing: 0.5px; font-weight: 600;
    padding: 12px 24px; border-bottom: 1px solid #fce7f3;
    white-space: nowrap;
  }
  .rider-table tbody td {
    padding: 12px 24px; vertical-align: middle;
    border-bottom: 1px solid #fdf2f8; font-size: 14px;
  }
  .rider-table tbody tr:last-child td { border-bottom: none; }

  .rider-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #fbcfe8; }
  .rider-avatar-fallback {
    width: 40px; height: 40px; border-radius: 50%; background: #fdf2f8;
    border: 2px solid #fbcfe8; display: flex; align-items: center; justify-content: center;
    color: #ff2d7a; font-weight: 700; font-size: 14px;
  }

  .rider-vehicle-badge {
    background: #fdf2f8; color: #ff2d7a; border: 1px solid #fbcfe8;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .rider-status-active {
    background: #ecfdf5; color: #16a34a; border: 1px solid #bbf7d0;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
    border: none; cursor: pointer; transition: all 0.2s;
  }
  .rider-status-active:hover { background: #d1fae5; }
  .rider-status-inactive {
    background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
    border: none; cursor: pointer; transition: all 0.2s;
  }
  .rider-status-inactive:hover { background: #e5e7eb; }

  .rider-btn-edit {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; background: #fffbeb; color: #d97706; transition: all 0.2s;
  }
  .rider-btn-edit:hover { background: #fef3c7; color: #d97706; }
  .rider-btn-delete {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; background: #fef2f2; color: #dc2626; transition: all 0.2s;
  }
  .rider-btn-delete:hover { background: #fee2e2; color: #dc2626; }

  /* ===== DELETE MODAL ===== */
  .delete-modal-overlay {
    position: fixed; inset: 0; background: rgba(17,24,39,0.6);
    backdrop-filter: blur(4px); z-index: 9999; display: none;
    align-items: center; justify-content: center; padding: 16px;
  }
  .delete-modal-overlay.active { display: flex; }
  .delete-modal-box {
    background: #fff; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    width: 100%; max-width: 400px; padding: 24px;
    transform: scale(0.95); opacity: 0; transition: all 0.3s ease;
  }
  .delete-modal-overlay.active .delete-modal-box { transform: scale(1); opacity: 1; }
  .delete-modal-icon {
    width: 64px; height: 64px; border-radius: 50%; background: #fef2f2;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px auto; box-shadow: 0 0 0 8px rgba(254,226,226,0.5);
  }
  .delete-modal-icon i { color: #dc2626; font-size: 24px; }
  .delete-modal-title { font-size: 18px; font-weight: 700; color: #1f2937; text-align: center; margin-bottom: 8px; }
  .delete-modal-text { font-size: 14px; color: #6b7280; text-align: center; line-height: 1.6; margin-bottom: 0; }
  .delete-modal-text .highlight-name { font-weight: 600; color: #1f2937; }
  .delete-modal-actions { display: flex; gap: 12px; margin-top: 24px; }
  .delete-modal-actions .btn-cancel {
    flex: 1; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    background: #fff; color: #6b7280; font-weight: 500; transition: all .2s;
  }
  .delete-modal-actions .btn-cancel:hover { background: #f9fafb; }
  .delete-modal-actions .btn-delete {
    flex: 1; padding: 10px 16px; border-radius: 12px; border: none;
    background: linear-gradient(to right, #ef4444, #dc2626);
    color: #fff; font-weight: 500; box-shadow: 0 4px 12px rgba(239,68,68,0.2);
    display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s;
  }
  .delete-modal-actions .btn-delete:hover { opacity: .9; }
  .delete-modal-actions .btn-delete:disabled { opacity: .7; cursor: not-allowed; }
  .delete-spinner { display: none; animation: spin 1s linear infinite; }
  .delete-spinner.show { display: inline-block; }
  @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

  /* ===== TOAST ===== */
  #toastContainer {
    position: fixed; top: 20px; right: 20px; z-index: 99999;
    display: flex; flex-direction: column; gap: 12px;
    width: 100%; max-width: 380px; padding: 0 16px;
  }
  @media (min-width: 640px) { #toastContainer { padding: 0; } }
  .toast-item {
    background: #fff; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
    padding: 16px 20px; display: flex; align-items: flex-start; gap: 12px;
    transform: translateX(120%); opacity: 0; transition: all 0.3s ease-out;
    border: 1px solid #e5e7eb; position: relative; overflow: hidden;
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
    background: linear-gradient(to right, #ff2d7a, #ff6fa5); transition: width 5s linear;
  }
  .toast-item.error .toast-progress { background: #dc2626; }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="rider-page">
  <div class="rider-full-width">

    <!-- Gradient Header -->
    <div class="rider-gradient mb-4">
      <div class="row align-items-center">
        <div class="col-12 col-sm-8">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center text-white" style="width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,0.15); font-size:20px;">🛵</div>
            <div>
              <h1>Rider Management</h1>
              <p>Manage your delivery riders</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-4 mt-3 mt-sm-0 text-sm-end">
          <a href="{{ route('admin.riders.create') }}" class="rider-add-btn">
            <i class="fa-solid fa-plus" style="font-size:12px;"></i>
            <span>Add Rider</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Card -->
    <div class="rider-card">
      <!-- Search -->
      <div class="p-3 p-md-4" style="border-bottom:1px solid #fdf2f8;">
        <div class="rider-search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="riderSearchInput" placeholder="Search by name, phone, vehicle #..." class="rider-search" autocomplete="off">
          <i id="searchLoadingIcon" class="fa-solid fa-circle-notch fa-spin rider-search-spinner"></i>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="rider-table table mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Photo</th>
              <th>Name</th>
              <th>Contact</th>
              <th>Address</th>
              <th>Vehicle</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="ridersTableBody">
            @forelse($riders as $rider)
              <tr data-row-id="{{ $rider->id }}">
                <td class="text-secondary">{{ $rider->id }}</td>
                <td>
                  @if($rider->image)
                    <img src="{{ asset($rider->image) }}" alt="{{ $rider->name }}" class="rider-avatar">
                  @else
                    <div class="rider-avatar-fallback">{{ strtoupper(substr($rider->name, 0, 1)) }}</div>
                  @endif
                </td>
                <td class="fw-medium text-dark">
                  {{ $rider->name }}
                  @if($rider->email)
                    <p class="text-xs text-secondary mb-0" style="font-weight:400;">{{ $rider->email }}</p>
                  @endif
                </td>
                <td class="text-secondary">{{ $rider->phone }}</td>
                <td class="text-secondary text-xs" style="max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                  {{ $rider->address }}
                  @if($rider->city) <br><span class="text-secondary" style="color:#9ca3af;">{{ $rider->city }}</span> @endif
                </td>
                <td>
                  @php
                    $vehicleLabels = ['bike' => '🏍️ Bike', 'car' => '🚗 Car', 'van' => '🚐 Van', 'bicycle' => '🚲 Bicycle'];
                  @endphp
                  <span class="rider-vehicle-badge">{{ $vehicleLabels[$rider->vehicle_type] ?? ucfirst($rider->vehicle_type) }}</span>
                  @if($rider->vehicle_number)
                    <p class="text-xs text-secondary mt-1" style="color:#9ca3af;">{{ $rider->vehicle_number }}</p>
                  @endif
                </td>
                <td>
                  <button type="button"
                    class="toggle-status-btn {{ $rider->is_active ? 'rider-status-active' : 'rider-status-inactive' }}"
                    data-id="{{ $rider->id }}"
                    data-url="{{ route('admin.riders.toggle-status', $rider->id) }}">
                    {{ $rider->is_active ? 'Active' : 'Inactive' }}
                  </button>
                </td>
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.riders.edit', $rider->id) }}" class="rider-btn-edit" title="Edit">
                      <i class="fa-solid fa-pen" style="font-size:11px;"></i>
                    </a>
                    <button type="button"
                      class="delete-rider-btn rider-btn-delete"
                      data-id="{{ $rider->id }}"
                      data-name="{{ $rider->name }}"
                      data-url="{{ route('admin.riders.destroy', $rider->id) }}"
                      title="Delete">
                      <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-secondary py-5">
                  <i class="fa-solid fa-motorcycle d-block mb-2" style="font-size:24px;"></i>
                  No riders found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($riders->hasPages())
        <div class="p-3 p-md-4" style="border-top:1px solid #fdf2f8;" id="paginationWrapper">
          {{ $riders->links() }}
        </div>
      @endif
    </div>
  </div>
</div>

<!-- ===== DELETE MODAL ===== -->
<div id="deleteModal" class="delete-modal-overlay">
  <div class="delete-modal-box">
    <div class="delete-modal-icon">
      <i class="fa-solid fa-trash-can"></i>
    </div>
    <h3 class="delete-modal-title">Delete Rider?</h3>
    <p class="delete-modal-text">
      Are you sure you want to delete <span id="deleteModalItemName" class="highlight-name">this rider</span>? This action cannot be undone.
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
    const searchInput = document.getElementById('riderSearchInput');
    const tableBody = document.getElementById('ridersTableBody');
    const loadingIcon = document.getElementById('searchLoadingIcon');
    const paginationBox = document.getElementById('paginationWrapper');
    const searchUrl = "{{ route('admin.riders.search') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

    const vehicleLabels = { bike: '🏍️ Bike', car: '🚗 Car', van: '🚐 Van', bicycle: '🚲 Bicycle' };

    // ============================================================
    // TOAST NOTIFICATION SYSTEM
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
            <button class="toast-close"><i class="fa-solid fa-xmark" style="font-size:14px;"></i></button>
            <div class="toast-progress"></div>
        `;
        container.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('show'));
        const progress = toast.querySelector('.toast-progress');
        progress.style.transition = `width ${duration}ms linear`;
        requestAnimationFrame(() => requestAnimationFrame(() => progress.style.width = '0%'));
        toast.querySelector('.toast-close').addEventListener('click', () => removeToast(toast));
        const timer = setTimeout(() => removeToast(toast), duration);
        toast.addEventListener('mouseenter', () => { clearTimeout(timer); progress.style.transition = 'none'; });
        function removeToast(el) { el.classList.remove('show'); setTimeout(() => el.remove(), 300); }
    }

    @if(session('success')) showToast(@json(session('success')), 'success'); @endif
    @if(session('error')) showToast(@json(session('error')), 'error'); @endif
    @if($errors->any()) showToast(@json($errors->first()), 'error'); @endif

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
                renderRows(data.riders);
                if (paginationBox) paginationBox.style.display = query ? 'none' : '';
            })
            .catch(() => {
                tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">Something went wrong.</td></tr>`;
            })
            .finally(() => loadingIcon.style.display = 'none');
        }, 300);
    });

    function esc(str) { const d = document.createElement('div'); d.textContent = str ?? ''; return d.innerHTML; }

    function renderRows(riders) {
        if (!riders.length) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-secondary py-5"><i class="fa-solid fa-motorcycle d-block mb-2" style="font-size:24px;"></i>No riders found.</td></tr>`;
            return;
        }
        tableBody.innerHTML = riders.map(r => {
            const initial = esc(r.name).charAt(0).toUpperCase();
            const photoCell = r.image_url
                ? `<img src="${r.image_url}" alt="${esc(r.name)}" class="rider-avatar">`
                : `<div class="rider-avatar-fallback">${initial}</div>`;
            const statusClass = r.is_active ? 'rider-status-active' : 'rider-status-inactive';
            const vehicleLabel = vehicleLabels[r.vehicle_type] || r.vehicle_type;

            return `
            <tr data-row-id="${r.id}">
                <td class="text-secondary">${r.id}</td>
                <td>${photoCell}</td>
                <td class="fw-medium text-dark">${esc(r.name)}${r.email ? `<p class="text-xs text-secondary mb-0" style="font-weight:400;">${esc(r.email)}</p>` : ''}</td>
                <td class="text-secondary">${esc(r.phone)}</td>
                <td class="text-secondary text-xs" style="max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${esc(r.address)}${r.city ? `<br><span class="text-secondary" style="color:#9ca3af;">${esc(r.city)}</span>` : ''}</td>
                <td>
                    <span class="rider-vehicle-badge">${vehicleLabel}</span>
                    ${r.vehicle_number ? `<p class="text-xs text-secondary mt-1" style="color:#9ca3af;">${esc(r.vehicle_number)}</p>` : ''}
                </td>
                <td>
                    <button type="button" class="toggle-status-btn ${statusClass}" data-id="${r.id}" data-url="${r.toggle_url}">
                        ${r.is_active ? 'Active' : 'Inactive'}
                    </button>
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="${r.edit_url}" class="rider-btn-edit"><i class="fa-solid fa-pen" style="font-size:11px;"></i></a>
                        <button type="button" class="delete-rider-btn rider-btn-delete" data-id="${r.id}" data-name="${esc(r.name)}" data-url="${r.delete_url}"><i class="fa-solid fa-trash" style="font-size:11px;"></i></button>
                    </div>
                </td>
            </tr>
        `}).join('');
    }

    // ============================================================
    // STATUS TOGGLE
    // ============================================================
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
                        toggleBtn.className = 'toggle-status-btn rider-status-active';
                    } else {
                        toggleBtn.textContent = 'Inactive';
                        toggleBtn.className = 'toggle-status-btn rider-status-inactive';
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

    // ============================================================
    // DELETE MODAL
    // ============================================================
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalItemName = document.getElementById('deleteModalItemName');
    const deleteModalCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteModalConfirmBtn = document.getElementById('deleteModalConfirmBtn');
    const deleteModalConfirmText = document.getElementById('deleteModalConfirmText');
    const deleteModalSpinner = document.getElementById('deleteModalSpinner');

    let pendingDeleteUrl = null;
    let pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url;
        pendingDeleteRow = row;
        deleteModalItemName.textContent = name || 'this rider';
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

    deleteModalCancelBtn.addEventListener('click', function(e) {
        e.preventDefault();
        closeDeleteModal();
    });

    deleteModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
            closeDeleteModal();
        }
    });

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
                    tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-secondary py-5"><i class="fa-solid fa-motorcycle d-block mb-2" style="font-size:24px;"></i>No riders found.</td></tr>`;
                }
                showToast('Rider deleted successfully.', 'success');
                closeDeleteModal();
            } else {
                showToast(data.message || 'Failed to delete rider.', 'error');
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
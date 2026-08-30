@extends('admin.layouts.master')
@section('title', 'View | Payments')

@section('content')
<style>
  /* ====== EXACT TAILWIND STYLING ====== */
  .tw-container {
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1rem;
    padding-right: 1rem;
  }
  @media (min-width: 640px) {
    .tw-container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }

  .payment-gradient {
    background: linear-gradient(to right, #ff2d7a, #ff6b9d);
    border-radius: 1rem;
    padding: 1.5rem 1.5rem;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  }
  @media (min-width: 640px) {
    .payment-gradient {
      padding: 1.5rem 1.875rem;
    }
  }

  .payment-gradient h1 {
    color: #fff;
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
  }
  .payment-gradient p {
    color: rgba(255,255,255,0.8);
    font-size: 0.875rem;
    margin: 0;
  }

  .payment-add-btn {
    background: #fff;
    color: #ff2d7a;
    font-weight: 600;
    padding: 0.625rem 1.25rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
    text-decoration: none;
    font-size: 0.875rem;
  }
  .payment-add-btn:hover {
    background: #fdf2f8;
    color: #ff2d7a;
    text-decoration: none;
  }

  .payment-card {
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    background: #fff;
  }

  .payment-search-wrap {
    position: relative;
    width: 100%;
    max-width: 20rem;
  }
  .payment-search-wrap i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 0.875rem;
  }
  .payment-search {
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 0.625rem 0.75rem 0.625rem 2.25rem;
    font-size: 0.875rem;
    width: 100%;
    transition: all 0.2s;
    outline: none;
  }
  .payment-search:focus {
    border-color: #334155;
    box-shadow: 0 0 0 3px rgba(51,65,85,0.2);
  }
  .payment-search-spinner {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 0.875rem;
    display: none;
  }

  .payment-table thead th {
    background: #f9fafb;
    color: #6b7280;
    text-transform: uppercase;
    font-size: 0.6875rem;
    letter-spacing: 0.05em;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
  }
  .payment-table tbody td {
    padding: 0.75rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.875rem;
  }
  .payment-table tbody tr:last-child td {
    border-bottom: none;
  }

  .payment-logo {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
    object-fit: contain;
    border: 1px solid #f3f4f6;
    background: #f9fafb;
  }
  .payment-logo-placeholder {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
    border: 1px solid #f3f4f6;
    background: #f9fafb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d1d5db;
  }

  .payment-badge-cod {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
    border-radius: 9999px;
    padding: 0.25rem 0.625rem;
    font-size: 0.6875rem;
    font-weight: 600;
    display: inline-block;
  }
  .payment-badge-wallet {
    background: #f0f9ff;
    color: #0284c7;
    border: 1px solid #bae6fd;
    border-radius: 9999px;
    padding: 0.25rem 0.625rem;
    font-size: 0.6875rem;
    font-weight: 600;
    display: inline-block;
  }
  .payment-badge-bank {
    background: #eef2ff;
    color: #4f46e5;
    border: 1px solid #c7d2fe;
    border-radius: 9999px;
    padding: 0.25rem 0.625rem;
    font-size: 0.6875rem;
    font-weight: 600;
    display: inline-block;
  }
  .payment-badge-default {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    border-radius: 9999px;
    padding: 0.25rem 0.625rem;
    font-size: 0.6875rem;
    font-weight: 600;
    display: inline-block;
  }

  .payment-status-active {
    background: #ecfdf5;
    color: #16a34a;
    border: 1px solid #bbf7d0;
    border-radius: 9999px;
    padding: 0.25rem 0.625rem;
    font-size: 0.6875rem;
    font-weight: 600;
    display: inline-block;
  }
  .payment-status-inactive {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    border-radius: 9999px;
    padding: 0.25rem 0.625rem;
    font-size: 0.6875rem;
    font-weight: 600;
    display: inline-block;
  }

  .payment-btn-edit {
    width: 2rem;
    height: 2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    background: #fffbeb;
    color: #d97706;
    border: none;
    transition: all 0.2s;
  }
  .payment-btn-edit:hover {
    background: #fef3c7;
    color: #d97706;
  }
  .payment-btn-delete {
    width: 2rem;
    height: 2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    background: #fef2f2;
    color: #dc2626;
    border: none;
    transition: all 0.2s;
  }
  .payment-btn-delete:hover {
    background: #fee2e2;
    color: #dc2626;
  }

  .tw-flex { display: flex; }
  .tw-items-center { align-items: center; }
  .tw-gap-2 { gap: 0.5rem; }
  .tw-gap-3 { gap: 0.75rem; }
  .tw-justify-end { justify-content: flex-end; }
  .tw-mb-4 { margin-bottom: 1rem; }
  .tw-p-3 { padding: 0.75rem; }
  .tw-p-4 { padding: 1rem; }
  .tw-border-b { border-bottom: 1px solid #f3f4f6; }
  .tw-text-secondary { color: #6b7280; }
  .tw-text-dark { color: #1f2937; }
  .tw-font-medium { font-weight: 500; }
  .tw-text-center { text-align: center; }
  .tw-py-5 { padding-top: 1.25rem; padding-bottom: 1.25rem; }
  .tw-text-end { text-align: right; }
  .tw-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .tw-max-w-140 { max-width: 140px; }
  .tw-max-w-120 { max-width: 120px; }

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

<div class="tw-container">

  <!-- Gradient Header -->
  <div class="payment-gradient tw-mb-4">
    <div class="row align-items-center">
      <div class="col-12 col-sm-8">
        <div class="tw-flex tw-items-center tw-gap-3">
          <div class="d-flex align-items-center justify-content-center text-white" style="width:2.75rem; height:2.75rem; border-radius:0.75rem; background:rgba(255,255,255,0.2); font-size:1.25rem;">💳</div>
          <div>
            <h1>Payment Methods</h1>
            <p>Manage your payment methods</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-4 mt-3 mt-sm-0 text-sm-end">
        <a href="{{ route('admin.payment-methods.create') }}" class="payment-add-btn">
          <i class="fa-solid fa-plus" style="font-size:0.75rem;"></i>
          <span>Add New</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Card -->
  <div class="payment-card">
    <!-- Search -->
    <div class="tw-p-3 tw-p-4 tw-border-b">
      <div class="payment-search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="methodSearchInput" placeholder="Search by name, bank..." class="payment-search" autocomplete="off">
        <i id="searchLoadingIcon" class="fa-solid fa-circle-notch fa-spin payment-search-spinner"></i>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="payment-table table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Logo</th>
            <th>Name</th>
            <th>Type</th>
            <th>Account Holder</th>
            <th>Account #</th>
            <th>Deep Link</th>
            <th>Status</th>
            <th>Order</th>
            <th class="tw-text-end">Actions</th>
          </tr>
        </thead>
        <tbody id="methodsTableBody">
          @forelse($methods as $method)
            <tr data-row-id="{{ $method->id }}">
              <td class="tw-text-secondary">{{ $method->id }}</td>
              <td>
                @if($method->logo)
                  <img src="{{ asset($method->logo) }}" alt="{{ $method->name }} logo" class="payment-logo">
                @else
                  <div class="payment-logo-placeholder">
                    <i class="fa-solid fa-image" style="font-size:0.75rem;"></i>
                  </div>
                @endif
              </td>
              <td class="tw-font-medium tw-text-dark">
                <span class="mr-1">{{ $method->icon ?? '💳' }}</span>{{ $method->name }}
              </td>
              <td>
                @php
                  $typeLabels = ['cod' => 'Cash on Delivery', 'mobile_wallet' => 'Mobile Wallet', 'bank' => 'Bank'];
                  $typeClasses = [
                    'cod' => 'payment-badge-cod',
                    'mobile_wallet' => 'payment-badge-wallet',
                    'bank' => 'payment-badge-bank',
                  ];
                @endphp
                <span class="{{ $typeClasses[$method->type] ?? 'payment-badge-default' }}">
                  {{ $typeLabels[$method->type] ?? ucfirst($method->type) }}
                </span>
              </td>
              <td class="tw-text-secondary">{{ $method->account_title ?? '—' }}</td>
              <td class="tw-text-secondary tw-text-xs tw-truncate tw-max-w-140">{{ $method->account_number ?? $method->iban ?? '—' }}</td>
              <td class="tw-text-secondary tw-text-xs tw-truncate tw-max-w-120" style="color:#9ca3af;">{{ $method->deep_link ?? '—' }}</td>
              <td>
                @if($method->is_active)
                  <span class="payment-status-active">Active</span>
                @else
                  <span class="payment-status-inactive">Inactive</span>
                @endif
              </td>
              <td class="tw-text-secondary">{{ $method->sort_order }}</td>
              <td class="tw-text-end">
                <div class="tw-flex tw-items-center tw-gap-2 tw-justify-end">
                  <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="payment-btn-edit" title="Edit">
                    <i class="fa-solid fa-pen" style="font-size:0.6875rem;"></i>
                  </a>
                  <button type="button"
                    class="delete-method-btn payment-btn-delete"
                    data-id="{{ $method->id }}"
                    data-name="{{ $method->name }}"
                    data-url="{{ route('admin.payment-methods.destroy', $method->id) }}"
                    title="Delete">
                    <i class="fa-solid fa-trash" style="font-size:0.6875rem;"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="tw-text-center tw-py-5" style="color:#9ca3af;">
                <i class="fa-solid fa-credit-card d-block mb-2" style="font-size:1.5rem;"></i>
                No payment methods found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($methods->hasPages())
      <div class="tw-p-3 tw-p-4 tw-border-b" style="border-top:1px solid #f3f4f6;" id="paginationWrapper">
        {{ $methods->links() }}
      </div>
    @endif
  </div>
</div>

<!-- ===== DELETE MODAL - EXACT TAILWIND STYLE ===== -->
<div id="deleteModal" class="delete-modal-overlay">
  <div class="delete-modal-box">
    <div class="delete-modal-icon">
      <i class="fa-solid fa-trash-can"></i>
    </div>
    <h3 class="delete-modal-title">Delete Method?</h3>
    <p class="delete-modal-text">
      Are you sure you want to delete <span id="deleteModalItemName" class="highlight-name">this method</span>? This action cannot be undone.
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
  const searchInput = document.getElementById('methodSearchInput');
  const tableBody = document.getElementById('methodsTableBody');
  const loadingIcon = document.getElementById('searchLoadingIcon');
  const paginationBox = document.getElementById('paginationWrapper');
  const searchUrl = "{{ route('admin.payment-methods.search') }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

  const typeLabels = { cod: 'Cash on Delivery', mobile_wallet: 'Mobile Wallet', bank: 'Bank' };
  const typeClasses = {
    cod: 'payment-badge-cod',
    mobile_wallet: 'payment-badge-wallet',
    bank: 'payment-badge-bank',
  };

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
        renderRows(data.methods);
        if (paginationBox) paginationBox.style.display = query ? 'none' : '';
      })
      .catch(() => {
        tableBody.innerHTML = `<tr><td colspan="10" class="text-center text-danger py-4">Something went wrong.</td></tr>`;
      })
      .finally(() => loadingIcon.style.display = 'none');
    }, 300);
  });

  function esc(str) { const d = document.createElement('div'); d.textContent = str ?? ''; return d.innerHTML; }

  function renderRows(methods) {
    if (!methods.length) {
      tableBody.innerHTML = `<tr><td colspan="10" class="tw-text-center tw-py-5" style="color:#9ca3af;"><i class="fa-solid fa-credit-card d-block mb-2" style="font-size:1.5rem;"></i>No methods found.</td></tr>`;
      return;
    }
    tableBody.innerHTML = methods.map(m => {
      const badgeClass = typeClasses[m.type] || 'payment-badge-default';
      const badgeLabel = typeLabels[m.type] || m.type;
      const statusBadge = m.is_active
        ? `<span class="payment-status-active">Active</span>`
        : `<span class="payment-status-inactive">Inactive</span>`;
      const logoCell = m.logo_url
        ? `<img src="${m.logo_url}" alt="${esc(m.name)} logo" class="payment-logo">`
        : `<div class="payment-logo-placeholder"><i class="fa-solid fa-image" style="font-size:0.75rem;"></i></div>`;
      return `
        <tr data-row-id="${m.id}">
          <td class="tw-text-secondary">${m.id}</td>
          <td>${logoCell}</td>
          <td class="tw-font-medium tw-text-dark"><span class="mr-1">${esc(m.icon) || '💳'}</span>${esc(m.name)}</td>
          <td><span class="${badgeClass}">${badgeLabel}</span></td>
          <td class="tw-text-secondary">${esc(m.account_title) || '—'}</td>
          <td class="tw-text-secondary tw-text-xs tw-truncate tw-max-w-140">${esc(m.account_number) || esc(m.iban) || '—'}</td>
          <td class="tw-text-secondary tw-text-xs tw-truncate tw-max-w-120" style="color:#9ca3af;">${esc(m.deep_link) || '—'}</td>
          <td>${statusBadge}</td>
          <td class="tw-text-secondary">${m.sort_order ?? 0}</td>
          <td class="tw-text-end">
            <div class="tw-flex tw-items-center tw-gap-2 tw-justify-end">
              <a href="${m.edit_url}" class="payment-btn-edit"><i class="fa-solid fa-pen" style="font-size:0.6875rem;"></i></a>
              <button type="button" class="delete-method-btn payment-btn-delete" data-id="${m.id}" data-name="${esc(m.name)}" data-url="${m.delete_url}"><i class="fa-solid fa-trash" style="font-size:0.6875rem;"></i></button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // ============================================================
  // DELETE MODAL - EXACT TAILWIND STYLE
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
    deleteModalItemName.textContent = name || 'this method';
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
    const btn = e.target.closest('.delete-method-btn');
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
          tableBody.innerHTML = `<tr><td colspan="10" class="tw-text-center tw-py-5" style="color:#9ca3af;"><i class="fa-solid fa-credit-card d-block mb-2" style="font-size:1.5rem;"></i>No methods found.</td></tr>`;
        }
        showToast('Payment method deleted successfully.', 'success');
        closeDeleteModal();
      } else {
        showToast(data.message || 'Failed to delete payment method.', 'error');
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
@extends('admin.layouts.master')
@section('title', 'View | Products')

@section('content')
<style>
  .prod-page * { box-sizing: border-box; }
  .prod-full-width { max-width: 1280px; margin: 0 auto; padding: 0 16px; }
  @media (min-width: 1280px) { .prod-full-width { padding: 0; } }

  .prod-header h1 { font-size: 24px !important; font-weight: 700 !important; color: #1f2937; margin: 0; }
  .prod-header p { font-size: 14px; color: #6b7280; margin: 4px 0 0 0; }

  .prod-add-btn {
    background: linear-gradient(to right, #ff2d7a, #ff4b91);
    color: #fff; font-weight: 500; font-size: 14px;
    padding: 10px 20px; border-radius: 12px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.25);
    border: none; display: inline-flex; align-items: center; gap: 8px;
    text-decoration: none; white-space: nowrap; transition: all .2s;
  }
  .prod-add-btn:hover { opacity: .9; color: #fff; text-decoration: none; }

  .prod-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }

  .prod-search-wrap { position: relative; width: 100%; max-width: 320px; }
  .prod-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }
  .prod-search {
    width: 100%; padding: 10px 16px 10px 36px; border-radius: 12px;
    border: 1px solid #e5e7eb; font-size: 14px; outline: none; transition: all .2s;
  }
  .prod-search:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.15); }
  .prod-search-spinner {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #9ca3af; font-size: 13px; display: none;
  }

  .prod-table-responsive {
    width: 100%;
    overflow-x: scroll !important;
    overflow-y: hidden;
    border-radius: 0 0 16px 16px;
  }
  .prod-table-responsive::-webkit-scrollbar { height: 8px; }
  .prod-table-responsive::-webkit-scrollbar-track {
    background: linear-gradient(to right, #ff2d7a, #ff6fa5);
    border-radius: 0 0 16px 16px;
  }
  .prod-table-responsive::-webkit-scrollbar-thumb { background: #111827; border-radius: 9999px; }
  .prod-table-responsive::-webkit-scrollbar-thumb:hover { background: #000; }
  .prod-table-responsive::-webkit-scrollbar-button { display: none; width: 0; height: 0; }
  .prod-table-responsive { scrollbar-width: thin; scrollbar-color: #111827 #ff2d7a; }

  .prod-page table.prod-table { width: 100%; min-width: 1400px; font-size: 14px; text-align: left; border-collapse: collapse; margin-bottom: 0; }
  .prod-page .prod-table thead { background: #f9fafb; color: #6b7280; text-transform: uppercase; font-size: 11px; letter-spacing: .05em; }
  .prod-page .prod-table thead th { padding: 12px 24px; font-weight: 600; white-space: nowrap; border-bottom: 1px solid #e5e7eb; }
  .prod-page .prod-table tbody tr { border-top: 1px solid #f3f4f6; }
  .prod-page .prod-table tbody tr:first-child { border-top: none; }
  .prod-page .prod-table tbody td { padding: 12px 24px; vertical-align: middle; color: #374151; }

  .prod-img { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb; display: block; }
  .prod-img-placeholder {
    width: 48px; height: 48px; border-radius: 8px; background: #f3f4f6;
    display: flex; align-items: center; justify-content: center; color: #d1d5db;
  }

  .prod-category-badge {
    background: #fdf2f8; color: #ff2d7a; border: 1px solid #fbcfe8;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .prod-status-active {
    background: #ecfdf5; color: #16a34a; border: 1px solid #bbf7d0;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .prod-status-inactive {
    background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .prod-sale-badge {
    background: #ecfdf5; color: #059669; border: 1px solid #bbf7d0;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .prod-variant-chip {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 8px;
    font-size: 12px; font-weight: 500;
    background: #fdf2f8; color: #ff2d7a; border: 1px solid #fbcfe8;
    white-space: nowrap;
  }
  .prod-variant-chip .separator { color: #9ca3af; font-weight: 400; }
  .prod-variant-chip .old-price { text-decoration: line-through; color: #9ca3af; }

  .prod-btn-edit {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; background: #eff6ff; color: #2563eb; transition: all .2s;
  }
  .prod-btn-edit:hover { background: #dbeafe; color: #2563eb; }
  .prod-btn-delete {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; background: #fef2f2; color: #dc2626; transition: all .2s;
  }
  .prod-btn-delete:hover { background: #fee2e2; color: #dc2626; }

  .prod-variants-wrap { display: flex; flex-wrap: wrap; gap: 6px; max-width: 260px; }
  .prod-variants-wrap .no-variant {
    padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 500;
    background: #f9fafb; color: #9ca3af; border: 1px solid #e5e7eb;
  }

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
    background: linear-gradient(to right, #ff2d7a, #ff4b91); transition: width 5s linear;
  }
  .toast-item.error .toast-progress { background: #dc2626; }

  .prod-truncate { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="prod-page">
  <div class="prod-full-width">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div class="prod-header">
        <h1>Product Management</h1>
        <p>Manage all products from here.</p>
      </div>
      <a href="{{ route('admin.products.create') }}" class="prod-add-btn">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Product</span>
      </a>
    </div>

    <div class="prod-card">

      <div class="p-3 p-md-4" style="border-bottom:1px solid #f3f4f6;">
        <div class="prod-search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="productSearchInput" placeholder="Search products by name, description or category..." class="prod-search" autocomplete="off">
          <i id="searchLoadingIcon" class="fa-solid fa-circle-notch fa-spin prod-search-spinner"></i>
        </div>
      </div>

      <div class="prod-table-responsive">
        <table class="prod-table">
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Category</th>
              <th>Description</th>
              <th>Old Price</th>
              <th>Sale Price</th>
              <th>Weight</th>
              <th>Variation</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="productsTableBody">
            @forelse($products as $product)
              <tr data-row-id="{{ $product->id }}">
                <td>
                  @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="prod-img">
                  @else
                    <div class="prod-img-placeholder"><i class="fa-solid fa-image"></i></div>
                  @endif
                </td>
                <td class="fw-medium text-dark">{{ $product->name }}</td>
                <td>
                  <span class="prod-category-badge">{{ $product->category->name ?? '—' }}</span>
                </td>
                <td class="text-secondary prod-truncate">{{ Str::limit($product->description, 45) }}</td>
                <td class="text-secondary whitespace-nowrap">{{ $product->price !== null ? 'Rs. ' . number_format((float) $product->price) : '—' }}</td>
                <td class="whitespace-nowrap">
                  @if($product->sale_price)
                    <span class="prod-sale-badge">Rs. {{ number_format((float) $product->sale_price) }}</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-secondary whitespace-nowrap">{{ $product->weight ?: '—' }}</td>
                <td>
                  @if(is_array($product->variation) && count($product->variation) > 0)
                    <div class="prod-variants-wrap">
                      @foreach($product->variation as $variant)
                        <span class="prod-variant-chip">
                          <i class="fa-solid fa-weight-hanging" style="font-size:10px; opacity:0.6;"></i>
                          {{ $variant['weight'] ?? '—' }}
                          <span class="separator">•</span>
                          @if(!empty($variant['old_price']) && $variant['old_price'] > ($variant['price'] ?? 0))
                            <span class="old-price">Rs.{{ number_format((float) $variant['old_price']) }}</span>
                          @endif
                          Rs.{{ number_format((float) ($variant['price'] ?? 0)) }}
                        </span>
                      @endforeach
                    </div>
                  @else
                    <span class="no-variant">No variants</span>
                  @endif
                </td>
                <td>
                  @if($product->status === 'active')
                    <span class="prod-status-active">Active</span>
                  @else
                    <span class="prod-status-inactive">Inactive</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="prod-btn-edit" title="Edit">
                      <i class="fa-solid fa-pen" style="font-size:11px;"></i>
                    </a>
                    <button type="button"
                      class="delete-product-btn prod-btn-delete"
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-url="{{ route('admin.products.destroy', $product->id) }}"
                      title="Delete">
                      <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr id="emptyRow">
                <td colspan="10" class="text-center text-secondary py-5">
                  <i class="fa-solid fa-box-open d-block mb-2" style="font-size:24px;"></i>
                  No products found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($products->hasPages())
        <div class="p-3 p-md-4" style="border-top:1px solid #f3f4f6;" id="paginationWrapper">
          {{ $products->links() }}
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
    <h3 class="delete-modal-title">Delete Product?</h3>
    <p class="delete-modal-text">
      Are you sure you want to delete <span id="deleteModalProductName" class="highlight-name">this product</span>? This action cannot be undone.
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
    const searchInput = document.getElementById('productSearchInput');
    const tableBody = document.getElementById('productsTableBody');
    const loadingIcon = document.getElementById('searchLoadingIcon');
    const paginationBox = document.getElementById('paginationWrapper');
    const searchUrl = "{{ route('admin.products.search') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

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
                renderRows(data.products);
                if (paginationBox) paginationBox.style.display = query ? 'none' : '';
            })
            .catch(() => {
                tableBody.innerHTML = `<tr><td colspan="10" class="text-center text-danger py-4">Something went wrong. Try again.</td></tr>`;
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

    function formatMoney(val) {
        const n = parseFloat(val);
        if (isNaN(n)) return '—';
        return 'Rs. ' + n.toLocaleString(undefined, { maximumFractionDigits: 0 });
    }

    function renderVariationChips(variants) {
        if (!Array.isArray(variants) || variants.length === 0) {
            return `<span class="no-variant">No variants</span>`;
        }
        const chips = variants.map(v => {
            const weight = escapeHtml(v.weight ?? '—');
            const price = parseFloat(v.price) || 0;
            const oldPrice = parseFloat(v.old_price) || 0;
            const oldPriceHtml = (oldPrice > price)
                ? `<span class="old-price">Rs.${oldPrice.toLocaleString(undefined, { maximumFractionDigits: 0 })}</span> `
                : '';
            return `
                <span class="prod-variant-chip">
                    <i class="fa-solid fa-weight-hanging" style="font-size:10px; opacity:0.6;"></i>
                    ${weight}
                    <span class="separator">•</span>
                    ${oldPriceHtml}Rs.${price.toLocaleString(undefined, { maximumFractionDigits: 0 })}
                </span>
            `;
        }).join('');
        return `<div class="prod-variants-wrap">${chips}</div>`;
    }

    function renderRows(products) {
        if (!products.length) {
            tableBody.innerHTML = `
                <tr><td colspan="10" class="text-center text-secondary py-5">
                    <i class="fa-solid fa-box-open d-block mb-2" style="font-size:24px;"></i>
                    No products found.
                </td></tr>`;
            return;
        }
        tableBody.innerHTML = products.map(p => `
            <tr data-row-id="${p.id}">
                <td>
                    ${p.image
                        ? `<img src="${p.image}" alt="${escapeHtml(p.name)}" class="prod-img">`
                        : `<div class="prod-img-placeholder"><i class="fa-solid fa-image"></i></div>`
                    }
                </td>
                <td class="fw-medium text-dark">${escapeHtml(p.name)}</td>
                <td><span class="prod-category-badge">${escapeHtml(p.category)}</span></td>
                <td class="text-secondary prod-truncate">${escapeHtml(truncate(p.description, 45))}</td>
                <td class="text-secondary whitespace-nowrap">${formatMoney(p.price)}</td>
                <td class="whitespace-nowrap">
                    ${p.sale_price
                        ? `<span class="prod-sale-badge">${formatMoney(p.sale_price)}</span>`
                        : `<span class="text-muted">—</span>`
                    }
                </td>
                <td class="text-secondary whitespace-nowrap">${escapeHtml(p.weight || '—')}</td>
                <td>${renderVariationChips(p.variants)}</td>
                <td>
                    ${p.status === 'active'
                        ? `<span class="prod-status-active">Active</span>`
                        : `<span class="prod-status-inactive">Inactive</span>`
                    }
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="${p.edit_url}" class="prod-btn-edit"><i class="fa-solid fa-pen" style="font-size:11px;"></i></a>
                        <button type="button" class="delete-product-btn prod-btn-delete" data-id="${p.id}" data-name="${escapeHtml(p.name)}" data-url="${p.delete_url}"><i class="fa-solid fa-trash" style="font-size:11px;"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // ============================================================
    // DELETE MODAL
    // ============================================================
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalNameEl = document.getElementById('deleteModalProductName');
    const deleteModalCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteModalConfirmBtn = document.getElementById('deleteModalConfirmBtn');
    const deleteModalConfirmText = document.getElementById('deleteModalConfirmText');
    const deleteModalSpinner = document.getElementById('deleteModalSpinner');

    let pendingDeleteUrl = null;
    let pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url;
        pendingDeleteRow = row;
        deleteModalNameEl.textContent = name ? `"${name}"` : 'this product';
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

    tableBody.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-product-btn');
        if (!btn) return;
        e.preventDefault();
        openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
    });

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
                    tableBody.innerHTML = `<tr><td colspan="10" class="text-center text-secondary py-5"><i class="fa-solid fa-box-open d-block mb-2" style="font-size:24px;"></i>No products found.</td></tr>`;
                }
                showToast('Product deleted successfully.', 'success');
                closeDeleteModal();
            } else {
                showToast(data.message || 'Failed to delete product.', 'error');
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
@extends('admin.layouts.master')
@section('title', 'View Users')

@section('content')
<style>
  .user-page * { box-sizing: border-box; }
  .user-full-width { max-width: 1280px; margin: 0 auto; padding: 0 16px; width: 100%; }
  @media (min-width: 1280px) { .user-full-width { padding: 0; } }

  .user-gradient {
    background: linear-gradient(to right, #ff2d7a, #ff6fa5);
    border-radius: 16px;
    padding: 24px 30px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  }
  .user-gradient h1 { color: #fff; font-size: 20px; font-weight: 700; margin: 0; }
  .user-gradient p { color: rgba(255,255,255,0.9); font-size: 14px; margin: 0; }

  .user-add-btn {
    background: #fff; color: #ff2d7a; font-weight: 600; font-size: 14px;
    padding: 10px 20px; border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    border: none; display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.2s; text-decoration: none; white-space: nowrap;
  }
  .user-add-btn:hover { background: #fdf2f8; color: #ff2d7a; text-decoration: none; }

  .user-card {
    border: 1px solid #fce7f3; border-radius: 16px; overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05); background: #fff;
  }

  .user-search-wrap { position: relative; width: 100%; max-width: 320px; }
  .user-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }
  .user-search {
    border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 10px 12px 10px 36px; font-size: 14px; width: 100%;
    outline: none; transition: all 0.2s;
  }
  .user-search:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }

  .user-table thead th {
    background: #fdf2f8; color: #ff2d7a; text-transform: uppercase;
    font-size: 11px; letter-spacing: 0.5px; font-weight: 600;
    padding: 12px 24px; border-bottom: 1px solid #fce7f3;
    white-space: nowrap;
  }
  .user-table tbody td {
    padding: 12px 24px; vertical-align: middle;
    border-bottom: 1px solid #fdf2f8; font-size: 14px;
  }
  .user-table tbody tr:last-child td { border-bottom: none; }

  .user-role-admin {
    background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .user-role-user {
    background: #ecfdf5; color: #16a34a; border: 1px solid #bbf7d0;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }
  .user-role-manager {
    background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;
    border-radius: 9999px; padding: 4px 10px; font-size: 11px; font-weight: 600; display: inline-block;
  }

  .user-btn-edit {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; background: #fffbeb; color: #d97706; transition: all 0.2s;
  }
  .user-btn-edit:hover { background: #fef3c7; color: #d97706; }
  .user-btn-delete {
    width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none; background: #fef2f2; color: #dc2626; transition: all 0.2s;
  }
  .user-btn-delete:hover { background: #fee2e2; color: #dc2626; }

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

<div class="user-page">
  <div class="user-full-width">

    <!-- Gradient Header -->
    <div class="user-gradient mb-4">
      <div class="row align-items-center">
        <div class="col-12 col-sm-8">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center text-white" style="width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,0.15); font-size:20px;">👥</div>
            <div>
              <h1>User Management</h1>
              <p>Manage all users, assign roles, and control access</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-4 mt-3 mt-sm-0 text-sm-end">
          <a href="{{ route('admin.users.create') }}" class="user-add-btn">
            <i class="fa-solid fa-plus" style="font-size:12px;"></i>
            <span>Add User</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Card -->
    <div class="user-card">
      <!-- Search -->
      <div class="p-3 p-md-4" style="border-bottom:1px solid #fdf2f8;">
        <div class="user-search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="userSearchInput" placeholder="Search by name, email, phone..." class="user-search" autocomplete="off">
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="user-table table mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City</th>
              <th>Role</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="usersTableBody">
            @forelse($users as $user)
              <tr data-row-id="{{ $user->id }}">
                <td class="text-secondary">{{ $user->id }}</td>
                <td class="fw-medium text-dark">{{ $user->name }}</td>
                <td class="text-secondary">{{ $user->email }}</td>
                <td class="text-secondary">{{ $user->phone }}</td>
                <td class="text-secondary">{{ $user->city ?? '—' }}</td>
                <td>
                  @php $roleLabels = [1 => 'Admin', 2 => 'User', 3 => 'Manager']; @endphp
                  <span class="@if($user->role_id == 1) user-role-admin @elseif($user->role_id == 3) user-role-manager @else user-role-user @endif">
                    {{ $roleLabels[$user->role_id] ?? 'Unknown' }}
                  </span>
                </td>
                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="user-btn-edit" title="Edit">
                      <i class="fa-solid fa-pen" style="font-size:11px;"></i>
                    </a>
                    <button type="button"
                      class="delete-user-btn user-btn-delete"
                      data-id="{{ $user->id }}"
                      data-name="{{ $user->name }}"
                      data-url="{{ route('admin.users.destroy', $user->id) }}"
                      title="Delete">
                      <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-secondary py-5">
                  <i class="fa-solid fa-users d-block mb-2" style="font-size:24px;"></i>
                  No users found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($users->hasPages())
        <div class="p-3 p-md-4" style="border-top:1px solid #fdf2f8;" id="paginationWrapper">
          {{ $users->links() }}
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
    <h3 class="delete-modal-title">Delete User?</h3>
    <p class="delete-modal-text">
      Are you sure you want to delete <span id="deleteModalItemName" class="highlight-name">this user</span>?
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
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";
    const tableBody = document.getElementById('usersTableBody');
    const searchInput = document.getElementById('userSearchInput');
    const searchUrl = "{{ route('admin.users.search') }}";

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
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        debounceTimer = setTimeout(() => {
            fetch(`${searchUrl}?q=${encodeURIComponent(q)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => renderRows(data.users));
        }, 300);
    });

    function renderRows(users) {
        if (!users.length) {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-secondary py-5"><i class="fa-solid fa-users d-block mb-2" style="font-size:24px;"></i>No users found.</td></tr>`;
            return;
        }
        tableBody.innerHTML = users.map(u => {
            let roleClass = 'user-role-user';
            let roleLabel = 'User';
            if (u.role_id == 1) { roleClass = 'user-role-admin'; roleLabel = 'Admin'; }
            else if (u.role_id == 3) { roleClass = 'user-role-manager'; roleLabel = 'Manager'; }
            return `
            <tr data-row-id="${u.id}">
                <td class="text-secondary">${u.id}</td>
                <td class="fw-medium text-dark">${u.name}</td>
                <td class="text-secondary">${u.email}</td>
                <td class="text-secondary">${u.phone}</td>
                <td class="text-secondary">${u.city || '—'}</td>
                <td><span class="${roleClass}">${roleLabel}</span></td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="${u.edit_url}" class="user-btn-edit"><i class="fa-solid fa-pen" style="font-size:11px;"></i></a>
                        <button class="delete-user-btn user-btn-delete" data-url="${u.delete_url}" data-name="${u.name}"><i class="fa-solid fa-trash" style="font-size:11px;"></i></button>
                    </div>
                </td>
            </tr>
        `}).join('');
    }

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
        deleteModalItemName.textContent = name || 'this user';
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

    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-user-btn');
        if (btn) {
            e.preventDefault();
            openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
        }
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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
            closeDeleteModal();
        }
    });

    deleteModalConfirmBtn.addEventListener('click', function() {
        if (!pendingDeleteUrl) return;

        deleteModalConfirmBtn.disabled = true;
        deleteModalConfirmText.textContent = 'Deleting...';
        deleteModalSpinner.classList.add('show');

        fetch(pendingDeleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
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
                    tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-secondary py-5"><i class="fa-solid fa-users d-block mb-2" style="font-size:24px;"></i>No users found.</td></tr>`;
                }
                showToast(data.message, 'success');
                closeDeleteModal();
            } else {
                showToast(data.message || 'Failed to delete user.', 'error');
                closeDeleteModal();
            }
        })
        .catch(err => {
            showToast(err.message || 'Something went wrong.', 'error');
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
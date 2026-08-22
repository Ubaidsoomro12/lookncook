@extends('admin.layouts.master')
@section('title', 'View Users')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 bg-gradient-to-r from-[#ff2d7a] to-[#ff6fa5] rounded-2xl px-6 py-6 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center text-xl">👥</div>
            <div>
                <h1 class="text-xl font-bold text-white">User Management</h1>
                <p class="text-sm text-pink-50/90 mt-0.5">Manage all users, assign roles, and control access</p>
            </div>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-white text-[#ff2d7a] font-semibold px-5 py-2.5 rounded-xl shadow-md hover:bg-pink-50 transition-all">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add User</span>
        </a>
    </div>

    <div class="bg-white border border-pink-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-pink-50">
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="userSearchInput" placeholder="Search by name, email, phone..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-pink-50/50 text-[#ff2d7a] uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">City</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="divide-y divide-pink-50">
                    @forelse($users as $user)
                        <tr data-row-id="{{ $user->id }}">
                            <td class="px-6 py-3 text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->phone }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->city ?? '—' }}</td>
                            <td class="px-6 py-3">
                                @php $roleLabels = [1 => 'Admin', 2 => 'User', 3 => 'Manager']; @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border 
                                    @if($user->role_id == 1) bg-red-50 text-red-600 border-red-200
                                    @elseif($user->role_id == 3) bg-blue-50 text-blue-600 border-blue-200
                                    @else bg-green-50 text-green-600 border-green-200 @endif">
                                    {{ $roleLabels[$user->role_id] ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all" title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                                    <button type="button" class="delete-user-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-url="{{ route('admin.users.destroy', $user->id) }}" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-users text-2xl mb-2 block"></i>No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-pink-50">{{ $users->links() }}</div>
        @endif
    </div>
</div>

<!-- Delete Modal (Pink Theme, Dynamic) -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden">
    <div id="deleteModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div id="deleteModalBox" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4 ring-8 ring-red-50/50">
                <i class="fa-solid fa-trash-can text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 text-center">Delete User?</h3>
            <p class="text-sm text-gray-500 text-center mt-2 leading-relaxed">
                Are you sure you want to delete <span id="deleteModalItemName" class="font-semibold text-gray-700">this user</span>?
            </p>
            <div class="flex items-center gap-3 mt-6">
                <button type="button" id="deleteModalCancelBtn" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50">Cancel</button>
                <button type="button" id="deleteModalConfirmBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-red-600 text-white font-medium">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-5 right-5 z-[200] flex flex-col gap-3 w-full max-w-sm px-4 sm:px-0"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";
    const tableBody = document.getElementById('usersTableBody');
    const searchInput = document.getElementById('userSearchInput');
    const searchUrl = "{{ route('admin.users.search') }}";
    const toastContainer = document.getElementById('toastContainer');

    // Toast Function
    function showToast(message, type = 'success') {
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
            <button class="toast-close-btn text-gray-300 hover:text-gray-500 transition-colors shrink-0"><i class="fa-solid fa-xmark text-sm"></i></button>
            <div class="absolute bottom-0 left-0 h-1 ${isSuccess ? 'bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91]' : 'bg-red-400'} toast-progress" style="width:100%;"></div>
        `;
        toastContainer.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-x-[120%]', 'opacity-0'));
        setTimeout(() => {
            toast.classList.add('translate-x-[120%]', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
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

    // Search
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
            tableBody.innerHTML = `<tr><td colspan="7" class="px-6 py-10 text-center text-gray-400"><i class="fa-solid fa-users text-2xl mb-2 block"></i>No users found.</td></tr>`;
            return;
        }
        tableBody.innerHTML = users.map(u => `
            <tr data-row-id="${u.id}">
                <td class="px-6 py-3 text-gray-500">${u.id}</td>
                <td class="px-6 py-3 font-medium text-gray-800">${u.name}</td>
                <td class="px-6 py-3 text-gray-600">${u.email}</td>
                <td class="px-6 py-3 text-gray-600">${u.phone}</td>
                <td class="px-6 py-3 text-gray-600">${u.city || '—'}</td>
                <td class="px-6 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold border ${u.role_id == 1 ? 'bg-red-50 text-red-600 border-red-200' : u.role_id == 3 ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-green-50 text-green-600 border-green-200'}">${u.role_id == 1 ? 'Admin' : u.role_id == 3 ? 'Manager' : 'User'}</span></td>
                <td class="px-6 py-3 text-right"><div class="flex justify-end gap-2"><a href="${u.edit_url}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100"><i class="fa-solid fa-pen text-xs"></i></a><button class="delete-user-btn w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" data-url="${u.delete_url}" data-name="${u.name}"><i class="fa-solid fa-trash text-xs"></i></button></div></td>
            </tr>
        `).join('');
    }

    // Delete Modal Logic
    const deleteModal = document.getElementById('deleteModal');
    const deleteBackdrop = document.getElementById('deleteModalBackdrop');
    const deleteBox = document.getElementById('deleteModalBox');
    const deleteItemName = document.getElementById('deleteModalItemName');
    const deleteCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteConfirmBtn = document.getElementById('deleteModalConfirmBtn');

    let pendingDeleteUrl = null;
    let pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url;
        pendingDeleteRow = row;
        deleteItemName.textContent = name || 'this user';
        deleteModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            deleteBackdrop.classList.remove('opacity-0');
            deleteBox.classList.remove('scale-95', 'opacity-0');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteBackdrop.classList.add('opacity-0');
        deleteBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { deleteModal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
        pendingDeleteUrl = null;
        pendingDeleteRow = null;
    }

    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-user-btn');
        if (btn) openDeleteModal(btn.dataset.url, btn.closest('tr'), btn.dataset.name);
    });

    deleteCancelBtn.addEventListener('click', closeDeleteModal);
    deleteBackdrop.addEventListener('click', closeDeleteModal);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) closeDeleteModal();
    });

    deleteConfirmBtn.addEventListener('click', function() {
        if (!pendingDeleteUrl) return;
        deleteConfirmBtn.disabled = true;
        deleteConfirmBtn.textContent = 'Deleting...';

        fetch(pendingDeleteUrl, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                pendingDeleteRow?.remove();
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Failed to delete user.', 'error');
            }
        })
        .catch(() => showToast('Something went wrong.', 'error'))
        .finally(() => {
            deleteConfirmBtn.disabled = false;
            deleteConfirmBtn.textContent = 'Yes, Delete';
            closeDeleteModal();
        });
    });
});
</script>
@endsection
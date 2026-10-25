@extends('admin.layouts.master')
@section('title', 'View | Reviews')

@section('content')
<style>
    /* ============================================
       PREMIUM COMPACT DESIGN (FITS 100% SCREEN)
       ============================================ */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary: #ff2d7a;
        --primary-light: #fff0f6;
        --text-dark: #1e293b; 
        --text-muted: #64748b;
        --bg-body: #f8fafc;
        --card-bg: #ffffff;
        --border-light: #e2e8f0;
    }

    .reviews-dashboard {
        padding: 15px 25px;
        max-width: 1400px;
        margin: 0 auto;
        font-family: 'Inter', sans-serif;
    }

    /* --- Header Section --- */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .page-title h1 {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-title p {
        color: var(--text-muted);
        margin: 4px 0 0;
        font-size: 13px;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-staff-reviews {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(255, 45, 122, 0.2);
        transition: all 0.3s ease;
    }

    .btn-staff-reviews:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 45, 122, 0.3);
        background: #e91e63;
        color: #fff;
    }

    .btn-delete-all {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-delete-all:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }

    /* --- Stats Row (Compact) --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 15px;
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    
    .stat-icon.pending { background: #fffbeb; color: #d97706; }
    .stat-icon.approved { background: #ecfdf5; color: #059669; }
    .stat-icon.rejected { background: #fef2f2; color: #dc2626; }
    .stat-icon.total { background: #fff0f6; color: var(--primary); }

    .stat-info h3 {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0;
        line-height: 1.2;
    }

    .stat-info p {
        color: var(--text-muted);
        margin: 0;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* --- Table Container --- */
    .table-container {
        background: var(--card-bg);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .premium-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .premium-table th {
        background: #f8fafc;
        padding: 10px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .premium-table td {
        padding: 10px 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-dark);
        font-size: 13px;
    }

    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tr:hover td { background: #f8fafc; }

    /* Table Elements */
    .reviewer-cell { display: flex; align-items: center; gap: 10px; }
    .avatar-sm {
        width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9;
        color: var(--text-dark); display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 13px; border: 1px solid var(--border-light); flex-shrink: 0;
    }
    .reviewer-info h5 { margin: 0; font-size: 13px; font-weight: 700; color: var(--text-dark); }
    .reviewer-info span { font-size: 11px; color: var(--text-muted); font-weight: 500; }

    .rating-stars { color: #f59e0b; font-size: 13px; letter-spacing: 1px; }
    .rating-stars .empty { color: #cbd5e1; }

    .review-message-text {
        max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        color: var(--text-dark); font-weight: 500; font-size: 13px;
    }

    .status-badge {
        padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px;
    }
    .status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .status-approved { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .status-rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .date-text { font-size: 12px; color: var(--text-muted); font-weight: 500; white-space: nowrap; }

    /* Action Buttons */
    .action-group { display: flex; gap: 4px; justify-content: flex-end; }
    .btn-icon {
        width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--border-light);
        background: #fff; display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s; color: var(--text-muted); font-size: 12px;
    }
    .btn-icon:hover { background: #f1f5f9; color: var(--text-dark); }
    .btn-icon.approve:hover { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .btn-icon.reject:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .btn-icon.delete:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

    /* Empty State */
    .empty-state { text-align: center; padding: 40px 20px; }
    .empty-state i { font-size: 36px; color: #cbd5e1; margin-bottom: 10px; }
    .empty-state h3 { color: var(--text-dark); font-weight: 700; font-size: 16px; margin-bottom: 4px; }
    .empty-state p { color: var(--text-muted); font-size: 13px; }

    /* Pagination */
    .pagination-container {
        padding: 10px 16px; border-top: 1px solid var(--border-light);
        display: flex; justify-content: space-between; align-items: center; background: #fff;
    }
    .pagination-info { color: var(--text-muted); font-size: 12px; font-weight: 500; }
    .pagination-container .pagination { margin: 0; gap: 4px; }
    .pagination-container .page-link {
        background: #fff; border: 1px solid var(--border-light); color: var(--text-dark);
        padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; transition: all 0.2s;
    }
    .pagination-container .page-link:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }
    .pagination-container .active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .premium-table th, .premium-table td { padding: 10px 12px; }
    }
</style>

<div class="reviews-dashboard">
    
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h1>📋 Feedback Center</h1>
            <p>Manage customer feedback efficiently without page clutter.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-delete-all" onclick="openDeleteAllModal()">
                <i class="fa-solid fa-trash-can"></i> Delete All
            </button>
            <a href="{{ route('admin.reviews.staff') }}" class="btn-staff-reviews">
                <i class="fa-solid fa-star"></i> Staff Reviews
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pending"><i class="fa-regular fa-clock"></i></div>
            <div class="stat-info">
                <h3>{{ $reviews->where('status', 'pending')->count() }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved"><i class="fa-regular fa-circle-check"></i></div>
            <div class="stat-info">
                <h3>{{ $reviews->where('status', 'approved')->count() }}</h3>
                <p>Approved</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected"><i class="fa-regular fa-circle-xmark"></i></div>
            <div class="stat-info">
                <h3>{{ $reviews->where('status', 'rejected')->count() }}</h3>
                <p>Rejected</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon total"><i class="fa-solid fa-chart-simple"></i></div>
            <div class="stat-info">
                <h3>{{ $reviews->total() }}</h3>
                <p>Total</p>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        @if($reviews->count() > 0)
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Reviewer</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr id="review-row-{{ $review->id }}">
                        <!-- Reviewer -->
                        <td>
                            <div class="reviewer-cell">
                                <div class="avatar-sm">{{ strtoupper(substr($review->name, 0, 1)) }}</div>
                                <div class="reviewer-info">
                                    <h5>{{ $review->name }}</h5>
                                    <span>{{ $review->email }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Rating -->
                        <td>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) ★ @else <span class="empty">★</span> @endif
                                @endfor
                            </div>
                        </td>
                        
                        <!-- Message -->
                        <td>
                            <div class="review-message-text" title="{{ $review->message }}">
                                "{{ $review->message }}"
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td>
                            <span class="status-badge status-{{ $review->status }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </td>
                        
                        <!-- Date -->
                        <td>
                            <span class="date-text">{{ $review->created_at->format('M d, Y') }}</span>
                        </td>
                        
                        <!-- Actions -->
                        <td>
                            <div class="action-group">
                                @if($review->status == 'pending')
                                    <button class="btn-icon approve" onclick="updateStatus({{ $review->id }}, 'approve')" title="Approve">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button class="btn-icon reject" onclick="updateStatus({{ $review->id }}, 'reject')" title="Reject">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @elseif($review->status == 'approved')
                                    <button class="btn-icon reject" onclick="updateStatus({{ $review->id }}, 'reject')" title="Undo Approve">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                @elseif($review->status == 'rejected')
                                    <button class="btn-icon approve" onclick="updateStatus({{ $review->id }}, 'approve')" title="Approve Instead">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                @endif
                                <button class="btn-icon delete" onclick="openDeleteModal({{ $review->id }}, '{{ addslashes($review->name) }}')" title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} entries
                </div>
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fa-regular fa-folder-open"></i>
                <h3>No Reviews Found</h3>
                <p>There are no customer reviews to display at the moment.</p>
            </div>
        @endif
    </div>

</div>

<!-- ✅ Custom Delete Modal (Single) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-body text-center p-4">
                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: #fef2f2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h5 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Delete Review?</h5>
                <p style="color: #475569; font-size: 14px; margin-bottom: 20px; font-weight: 500;">
                    Are you sure you want to delete the review by <strong id="deleteReviewName" style="color: #0f172a;">this customer</strong>? <br>
                    This action cannot be undone.
                </p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal" style="border: 1px solid #e2e8f0;">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold" id="confirmDeleteBtn" style="background: #ef4444; border: none; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">
                        <i class="fa-solid fa-trash-can me-1"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Custom Delete All Modal -->
<div class="modal fade" id="deleteAllModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-body text-center p-4">
                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: #fef2f2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h5 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Delete All Reviews?</h5>
                <p style="color: #475569; font-size: 14px; margin-bottom: 20px; font-weight: 500;">
                    Are you sure you want to delete <strong>ALL</strong> customer reviews? <br>
                    This action is permanent and cannot be undone!
                </p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal" style="border: 1px solid #e2e8f0;">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold" id="confirmDeleteAllBtn" style="background: #ef4444; border: none; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">
                        <i class="fa-solid fa-trash-can me-1"></i> Delete Everything
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 99999; display: flex; flex-direction: column; gap: 10px;"></div>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        
        const bg = type === 'success' ? '#10b981' : '#ef4444';
        const icon = type === 'success' ? '✅' : '❌';

        toast.style.cssText = `
            padding: 12px 20px; border-radius: 8px; color: #fff; font-weight: 600; font-size: 13px;
            background: ${bg}; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 10px;
            animation: slideInRight 0.4s ease forwards; min-width: 200px; font-family: 'Inter', sans-serif;
        `;

        toast.innerHTML = `<span>${icon}</span> ${message}`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.4s ease';
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }

    // ============================================
    // UPDATE STATUS (Approve / Reject)
    // ============================================
    function updateStatus(id, action) {
        const url = action === 'approve' 
            ? "{{ route('admin.reviews.approve', ['id' => ':id']) }}".replace(':id', id)
            : "{{ route('admin.reviews.reject', ['id' => ':id']) }}".replace(':id', id);

        const row = document.getElementById(`review-row-${id}`);
        const btns = row.querySelectorAll('.btn-icon');
        
        btns.forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.5';
        });

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Something went wrong.', 'error');
                btns.forEach(btn => {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                });
            }
        })
        .catch(error => {
            showToast('An error occurred. Please try again.', 'error');
            btns.forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });
        });
    }

    // ============================================
    // DELETE SINGLE REVIEW
    // ============================================
    let reviewToDeleteId = null;
    const deleteModalElement = document.getElementById('deleteModal');
    const deleteModal = new bootstrap.Modal(deleteModalElement);
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    function openDeleteModal(id, name) {
        reviewToDeleteId = id;
        document.getElementById('deleteReviewName').textContent = name;
        deleteModal.show();
    }

    confirmDeleteBtn.addEventListener('click', function() {
        if (!reviewToDeleteId) return;

        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Deleting...';
        this.disabled = true;

        const url = "{{ route('admin.reviews.destroy', ['id' => ':id']) }}".replace(':id', reviewToDeleteId);
        const row = document.getElementById(`review-row-${reviewToDeleteId}`);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                deleteModal.hide();
                
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.95)';
                    setTimeout(() => row.remove(), 300);
                    setTimeout(() => window.location.reload(), 1500); 
                }
            } else {
                showToast(data.message || 'Failed to delete review.', 'error');
                this.innerHTML = originalHtml;
                this.disabled = false;
            }
        })
        .catch(error => {
            showToast('An error occurred. Please try again.', 'error');
            this.innerHTML = originalHtml;
            this.disabled = false;
        });
    });

    // ============================================
    // DELETE ALL REVIEWS
    // ============================================
    const deleteAllModalElement = document.getElementById('deleteAllModal');
    const deleteAllModal = new bootstrap.Modal(deleteAllModalElement);
    const confirmDeleteAllBtn = document.getElementById('confirmDeleteAllBtn');

    function openDeleteAllModal() {
        deleteAllModal.show();
    }

    confirmDeleteAllBtn.addEventListener('click', function() {
        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Deleting...';
        this.disabled = true;

        // The route for delete all (MUST BE DEFINED IN web.php)
        const url = "{{ route('admin.reviews.destroy-all') }}";

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                deleteAllModal.hide();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Failed to delete all reviews.', 'error');
                this.innerHTML = originalHtml;
                this.disabled = false;
            }
        })
        .catch(error => {
            showToast('An error occurred. Please try again.', 'error');
            this.innerHTML = originalHtml;
            this.disabled = false;
        });
    });
</script>

@endsection
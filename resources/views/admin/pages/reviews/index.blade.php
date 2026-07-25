{{-- resources/views/admin/pages/reviews/index.blade.php --}}
@extends('admin.layouts.master')

@section('content')
<style>
    /* ============================================
       INTERNAL CSS - REVIEWS MANAGEMENT
       FIXED COLORS FOR DARK THEME
    ============================================ */
    .reviews-container {
        padding: 20px 25px;
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .reviews-header h2 {
        font-size: 26px;
        font-weight: 700;
        color: #cd0a86; /* ✅ White text */
        margin: 0;
    }

    .reviews-header h2 small {
        font-size: 14px;
        font-weight: 400;
        color: #94a3b8; /* ✅ Light gray */
        display: block;
        margin-top: 4px;
    }

    .stats-badge-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .stat-badge {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 13px;
        color: #cbd5e1; /* ✅ Light gray */
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-badge .count {
        font-weight: 700;
        color: #ffffff; /* ✅ White */
        font-size: 16px;
    }

    .stat-badge .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .dot-pending { background: #f59e0b; }
    .dot-approved { background: #10b981; }
    .dot-rejected { background: #ef4444; }

    .table-wrapper {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .table {
        margin: 0;
        color: #e2e8f0; /* ✅ Light text */
        font-size: 14px;
    }

    .table thead th {
        background: rgba(255, 255, 255, 0.05);
        color: #94a3b8; /* ✅ Visible header text */
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        white-space: nowrap;
    }

    .table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        color: #e2e8f0; /* ✅ Visible text */
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .review-name {
        font-weight: 600;
        color: #ffffff; /* ✅ White */
        white-space: nowrap;
    }

    .review-email {
        font-size: 12px;
        color: #94a3b8; /* ✅ Visible gray */
        display: block;
    }

    .rating-stars {
        color: #f59e0b; /* ✅ Gold stars */
        font-size: 15px;
        letter-spacing: 1px;
    }

    .rating-stars .empty {
        color: #475569; /* ✅ Darker for empty stars */
    }

    .rating-stars .rating-number {
        color: #94a3b8;
        font-size: 12px;
        margin-left: 4px;
    }

    .review-text {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #cbd5e1; /* ✅ Visible light gray */
        font-size: 13px;
        margin: 0;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .review-text:hover {
        color: #ffffff; /* ✅ White on hover */
    }

    .review-image {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(255, 45, 122, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: #ff2d7a; /* ✅ Pink color */
        border: 1px solid rgba(255, 45, 122, 0.2);
    }

    .status-badge {
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
        letter-spacing: 0.3px;
        display: inline-block;
        white-space: nowrap;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24; /* ✅ Yellow */
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-approved {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399; /* ✅ Green */
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171; /* ✅ Red */
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .review-date {
        font-size: 13px;
        color: #94a3b8; /* ✅ Visible gray */
        white-space: nowrap;
    }

    .action-btns {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-action i {
        font-size: 12px;
    }

    .btn-approve {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .btn-approve:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
        transform: scale(1.05);
    }

    .btn-reject {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .btn-reject:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
        transform: scale(1.05);
    }

    .btn-delete {
        background: rgba(255, 255, 255, 0.06);
        color: #94a3b8;
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .btn-delete:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
        transform: scale(1.05);
    }

    .btn-action:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none !important;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-wrapper .pagination {
        margin: 0;
    }

    .pagination-wrapper .pagination .page-link {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #94a3b8;
        padding: 6px 14px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .pagination-wrapper .pagination .page-link:hover {
        background: rgba(255, 45, 122, 0.1);
        border-color: #ff2d7a;
        color: #ffffff;
    }

    .pagination-wrapper .pagination .active .page-link {
        background: #ff2d7a;
        border-color: #ff2d7a;
        color: #ffffff;
    }

    .pagination-wrapper .pagination .disabled .page-link {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .pagination-info {
        color: #94a3b8;
        font-size: 13px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 48px;
        color: #475569;
        margin-bottom: 16px;
        display: block;
    }

    .empty-state h4 {
        color: #e2e8f0;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Toast Notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .toast-message {
        padding: 14px 24px;
        border-radius: 12px;
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        animation: slideInRight 0.4s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 280px;
        backdrop-filter: blur(10px);
    }

    .toast-message.success {
        background: rgba(16, 185, 129, 0.95);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .toast-message.error {
        background: rgba(239, 68, 68, 0.95);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .toast-message i {
        font-size: 20px;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .reviews-header {
            flex-direction: column;
            align-items: stretch;
        }

        .stats-badge-group {
            justify-content: flex-start;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .table {
            min-width: 900px;
        }
    }

    @media (max-width: 576px) {
        .reviews-container {
            padding: 12px 14px;
        }

        .reviews-header h2 {
            font-size: 20px;
        }

        .stat-badge {
            font-size: 12px;
            padding: 5px 12px;
        }

        .action-btns {
            flex-direction: column;
            gap: 4px;
        }

        .btn-action {
            padding: 3px 10px;
            font-size: 11px;
        }
    }
</style>

<div class="reviews-container">
    <!-- Header -->
    <div class="reviews-header">
        <div>
            <h2>
                📋 Reviews
                <small>Manage customer reviews and feedback</small>
            </h2>
        </div>
        <div class="stats-badge-group">
            <span class="stat-badge">
                <span class="dot dot-pending"></span>
                Pending: <span class="count">{{ $reviews->where('status', 'pending')->count() }}</span>
            </span>
            <span class="stat-badge">
                <span class="dot dot-approved"></span>
                Approved: <span class="count">{{ $reviews->where('status', 'approved')->count() }}</span>
            </span>
            <span class="stat-badge">
                <span class="dot dot-rejected"></span>
                Rejected: <span class="count">{{ $reviews->where('status', 'rejected')->count() }}</span>
            </span>
            <span class="stat-badge">
                📊 Total: <span class="count">{{ $reviews->total() }}</span>
            </span>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        @if($reviews->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $index => $review)
                    <tr id="review-row-{{ $review->id }}">
                        <td style="color: #94a3b8;">{{ $reviews->firstItem() + $index }}</td>
                        <td>
                            <div class="review-name">{{ $review->name }}</div>
                            <span class="review-email">{{ $review->email }}</span>
                        </td>
                        <td>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        ★
                                    @else
                                        <span class="empty">★</span>
                                    @endif
                                @endfor
                                <span class="rating-number">({{ $review->rating }})</span>
                            </div>
                        </td>
                        <td>
                            <p class="review-text" title="{{ $review->message }}" data-fulltext="{{ $review->message }}">
                                {{ Str::limit($review->message, 50) }}
                            </p>
                        </td>
                        <td>
                            <div class="review-image">
                                {{ strtoupper(substr($review->name, 0, 1)) }}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $review->status }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="action-btns">
                                @if($review->status == 'pending')
                                    <button class="btn-action btn-approve" onclick="updateStatus({{ $review->id }}, 'approve')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn-action btn-reject" onclick="updateStatus({{ $review->id }}, 'reject')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @elseif($review->status == 'approved')
                                    <button class="btn-action btn-reject" onclick="updateStatus({{ $review->id }}, 'reject')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @elseif($review->status == 'rejected')
                                    <button class="btn-action btn-approve" onclick="updateStatus({{ $review->id }}, 'approve')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                @endif
                                <button class="btn-action btn-delete" onclick="deleteReview({{ $review->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} reviews
                </div>
                {{ $reviews->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-star"></i>
                <h4>No Reviews Found</h4>
                <p>There are no customer reviews to display yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast-message ${type}`;
        const icon = type === 'success' ? '✅' : '❌';
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

        const btn = document.querySelector(`#review-row-${id} .btn-${action === 'approve' ? 'approve' : 'reject'}`);
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }

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
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = action === 'approve' ? '<i class="fas fa-check"></i> Approve' : '<i class="fas fa-times"></i> Reject';
                }
            }
        })
        .catch(error => {
            showToast('An error occurred. Please try again.', 'error');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = action === 'approve' ? '<i class="fas fa-check"></i> Approve' : '<i class="fas fa-times"></i> Reject';
            }
        });
    }

    // ============================================
    // DELETE REVIEW
    // ============================================
    function deleteReview(id) {
        if (!confirm('Are you sure you want to delete this review?')) {
            return;
        }

        const row = document.getElementById(`review-row-${id}`);
        const deleteBtn = row.querySelector('.btn-delete');
        if (deleteBtn) {
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }

        const url = "{{ route('admin.reviews.destroy', ['id' => ':id']) }}".replace(':id', id);

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
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    setTimeout(() => row.remove(), 300);
                    setTimeout(() => window.location.reload(), 1500);
                }
            } else {
                showToast(data.message || 'Failed to delete review.', 'error');
                if (deleteBtn) {
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
                }
            }
        })
        .catch(error => {
            showToast('An error occurred. Please try again.', 'error');
            if (deleteBtn) {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
            }
        });
    }

    // ============================================
    // READ MORE - Show full review on click
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.review-text').forEach(function(el) {
            el.addEventListener('click', function() {
                const fullText = this.getAttribute('data-fulltext');
                if (fullText && fullText.length > 50) {
                    alert(fullText);
                }
            });
        });
    });
</script>

@endsection
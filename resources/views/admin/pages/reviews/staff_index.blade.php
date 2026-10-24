@extends('admin.layouts.master')
@section('title', 'Staff Reviews | Admin')

@section('content')
<style>
    .reviews-container { padding: 20px 25px; }
    .reviews-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .reviews-header h2 { font-size: 26px; font-weight: 700; color: #cd0a86; margin: 0; }
    .reviews-header h2 small { font-size: 14px; font-weight: 400; color: #64748b; display: block; margin-top: 4px; }
    
    /* Enhanced Search Bar */
    .search-wrapper { position: relative; width: 320px; max-width: 100%; }
    .search-input {
        width: 100%; background: #111217 !important; 
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px; padding: 12px 16px 12px 42px; 
        color: #ffffff !important; 
        font-size: 14px; transition: all 0.3s ease;
    }
    .search-input:hover { border-color: rgba(255,45,122,0.5); background: #1a1b23 !important; }
    .search-input:focus { outline: none; border-color: #ff2d7a; background: #1a1b23 !important; box-shadow: 0 0 0 4px rgba(255,45,122,0.15); }
    .search-input::placeholder { color: #64748b !important; }
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 16px; }
    
    /* Table Styling */
    .table-wrapper { background: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); min-height: 200px; }
    .table { margin: 0; font-size: 14px; width: 100%; border-collapse: collapse; }
    .table thead th { background: #f8fafc; color: #475569 !important; font-weight: 700; font-size: 12px; text-transform: uppercase; padding: 14px 16px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .table tbody td { padding: 14px 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #0f172a !important; }
    .table tbody tr:hover { background: #f8fafc; }
    
    /* FORCE Staff Name & ID to be dark and visible */
    .staff-id { font-weight: 600; color: #64748b !important; font-size: 13px; }
    .staff-name { font-weight: 700; color: #0f172a !important; white-space: nowrap; font-size: 15px; }
    
    .rating-stars { color: #f59e0b; font-size: 15px; letter-spacing: 1px; }
    .rating-stars .empty { color: #cbd5e1; }
    
    /* FORCE Message Text to be dark and bold */
    .review-text { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #0f172a !important; font-size: 14px; margin: 0; font-weight: 600; }
    
    /* Read-Only Performance Bar (Same as Staff side) */
    .performance-bar-wrapper { display: flex; flex-direction: column; gap: 4px; width: 120px; }
    .performance-bar { height: 6px; border-radius: 4px; width: 100%; background: #e2e8f0; overflow: hidden; }
    .performance-bar::after { content: ''; display: block; height: 100%; border-radius: 4px; }
    .performance-text { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .perf-poor::after { width: 20%; background: #ef4444; }
    .perf-poor + .performance-text { color: #ef4444 !important; }
    
    .perf-average::after { width: 40%; background: #f59e0b; }
    .perf-average + .performance-text { color: #f59e0b !important; }
    
    .perf-good::after { width: 60%; background: #3b82f6; }
    .perf-good + .performance-text { color: #3b82f6 !important; }
    
    .perf-verygood::after { width: 80%; background: #10b981; }
    .perf-verygood + .performance-text { color: #10b981 !important; }
    
    .perf-excellent::after { width: 100%; background: #8b5cf6; }
    .perf-excellent + .performance-text { color: #8b5cf6 !important; }

    .action-btns { display: flex; gap: 6px; }
    .btn-action { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; border: none; transition: 0.25s; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 4px; }
    .btn-delete { background: #fee2e2; color: #ef4444 !important; border: 1px solid #fecaca; }
    .btn-delete:hover { background: #ef4444; color: #fff !important; }
    
    .btn-back {
        background: #ffffff; color: #475569 !important; border: 1px solid #e2e8f0;
        padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
        transition: all 0.2s ease; margin-bottom: 20px;
    }
    .btn-back:hover { background: #f8fafc; color: #0f172a !important; border-color: #cbd5e1; }

    .pagination-wrapper-fix { padding: 16px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .pagination-wrapper-fix .pagination { margin: 0; gap: 6px; }
    .pagination-wrapper-fix .page-item .page-link { background: #ffffff; border: 1px solid #e2e8f0; color: #475569 !important; padding: 6px 14px; font-size: 13px; border-radius: 8px; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; }
    .pagination-wrapper-fix .page-item .page-link:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a !important; }
    .pagination-wrapper-fix .page-item.active .page-link { background: #ff2d7a; border-color: #ff2d7a; color: #ffffff !important; }
</style>

<div class="reviews-container">
    <a href="{{ route('admin.reviews.index') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Back to All Reviews
    </a>

    <div class="reviews-header">
        <div>
            <h2>
                ⭐ Staff Reviews
                <small>Manage reviews and view performance ratings</small>
            </h2>
        </div>
        <!-- Search Bar -->
        <div class="search-wrapper">
            <i class="fa-solid fa-search search-icon"></i>
            <input type="text" id="ajaxSearchInput" class="search-input" placeholder="Search by Name, ID, Rating, Date..." autocomplete="off">
        </div>
    </div>

    <div class="table-wrapper" id="tableDataContainer">
        @if($reviews->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Table</th>
                        <th>Staff ID</th>
                        <th>Staff Name</th>
                        <th>Form Rating</th>
                        <th>Review Message</th>
                        <th>Performance</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $index => $review)
                    <tr id="review-row-{{ $review->id }}">
                        <td style="color: #64748b !important;">{{ $reviews->firstItem() + $index }}</td>
                        <td><span class="status-badge" style="background: #e2e8f0; color: #0f172a !important; padding: 4px 10px; border-radius: 6px; font-weight: 600;">Table {{ $review->table_id }}</span></td>
                        
                        <td><div class="staff-id">#{{ $review->staff_id }}</div></td>
                        <td><div class="staff-name">{{ $review->name }}</div></td>
                        
                        <td>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) ★ @else <span class="empty">★</span> @endif
                                @endfor
                            </div>
                        </td>
                        <td>
                            <p class="review-text" title="{{ $review->staff_review }}">
                                {{ Str::limit($review->staff_review, 60) }}
                            </p>
                        </td>
                        <td>
                            <!-- Read-only Performance Bar for Admin -->
                            @php
                                $perfClass = '';
                                $perfText = '';
                                if($review->staff_rating == 1) { $perfText = 'Poor'; $perfClass = 'perf-poor'; }
                                elseif($review->staff_rating == 2) { $perfText = 'Average'; $perfClass = 'perf-average'; }
                                elseif($review->staff_rating == 3) { $perfText = 'Good'; $perfClass = 'perf-good'; }
                                elseif($review->staff_rating == 4) { $perfText = 'Very Good'; $perfClass = 'perf-verygood'; }
                                elseif($review->staff_rating == 5) { $perfText = 'Excellent'; $perfClass = 'perf-excellent'; }
                            @endphp
                            <div class="performance-bar-wrapper">
                                <div class="performance-bar {{ $perfClass }}"></div>
                                <span class="performance-text">{{ $perfText }}</span>
                            </div>
                        </td>
                        <td><span style="color:#64748b !important; font-size:13px; font-weight: 500;">{{ $review->created_at->format('M d, Y') }}</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-action btn-delete" onclick="deleteReview({{ $review->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="pagination-wrapper-fix">
                <div style="color: #64748b !important; font-size: 13px; font-weight: 500;">
                    Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} results
                </div>
                {{ $reviews->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #64748b !important;">
                <i class="fas fa-search" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                <h4 style="color: #0f172a !important; font-weight: 700;">No Staff Reviews Found</h4>
                <p>There are no reviews yet.</p>
            </div>
        @endif
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // --- Live AJAX Search ---
    let searchTimeout = null;
    const searchInput = document.getElementById('ajaxSearchInput');
    const tableContainer = document.getElementById('tableDataContainer');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        // Show a loading state (optional, but good for UX)
        tableContainer.style.opacity = '0.5';

        searchTimeout = setTimeout(() => {
            const baseUrl = "{{ route('admin.reviews.staff') }}";
            const url = query !== '' ? `${baseUrl}?search=${encodeURIComponent(query)}` : baseUrl;
            
            // We fetch the page normally, but add a header to tell Laravel it's AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html' // We are expecting HTML back
                }
            })
            .then(res => res.text())
            .then(html => {
                // Create a temporary DOM element to parse the HTML string
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Extract only the table container from the returned full page
                const newTableContent = tempDiv.querySelector('#tableDataContainer').innerHTML;
                
                // Replace the current table content with the new one
                tableContainer.innerHTML = newTableContent;
                tableContainer.style.opacity = '1';
            })
            .catch(err => {
                console.error('Search error:', err);
                tableContainer.style.opacity = '1';
            });
        }, 300); // 300ms debounce
    });

    // --- Delete Logic ---
    function deleteReview(id) {
        if (!confirm('Are you sure you want to delete this staff review?')) return;

        const url = "{{ route('admin.reviews.destroy', ['id' => ':id']) }}".replace(':id', id);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error deleting review.');
            }
        });
    }
</script>
@endsection
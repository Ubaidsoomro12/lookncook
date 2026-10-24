@extends('staff.layouts.master')
@section('title', 'My Reviews | Staff')

@section('content')
<style>
    .staff-reviews-container { padding: 20px 25px; }
    .staff-reviews-header h2 { font-size: 26px; font-weight: 700; color: #cd0a86; margin: 0; }
    .staff-reviews-header h2 small { font-size: 14px; font-weight: 400; color: #64748b; display: block; margin-top: 4px; }
    
    .table-wrapper { background: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); margin-top: 25px; }
    .table { margin: 0; font-size: 14px; width: 100%; border-collapse: collapse; }
    .table thead th { background: #f8fafc; color: #475569 !important; font-weight: 700; font-size: 12px; text-transform: uppercase; padding: 14px 16px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .table tbody td { padding: 14px 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #0f172a !important; }
    .table tbody tr:hover { background: #f8fafc; }
    
    .rating-stars { color: #f59e0b; font-size: 15px; letter-spacing: 1px; }
    .rating-stars .empty { color: #cbd5e1; }
    
    /* FORCE Message Text to be dark and bold */
    .review-text { color: #0f172a !important; font-size: 14px; margin: 0; font-weight: 600; }
    
    /* Performance Bar */
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
</style>

<div class="staff-reviews-container">
    <div class="staff-reviews-header">
        <h2>
            ⭐ My Reviews
            <small>View your submitted reviews and performance status</small>
        </h2>
    </div>

    <div class="table-wrapper">
        @if($reviews->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Table</th>
                        <th>Form Rating</th>
                        <th>My Review</th>
                        <th>Admin Rating (Performance)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $index => $review)
                    <tr>
                        <td style="color: #64748b !important;">{{ $reviews->firstItem() + $index }}</td>
                        <td><span class="status-badge" style="background: #e2e8f0; color: #0f172a !important; padding: 4px 14px; border-radius: 30px; font-weight: 600;">Table {{ $review->table_id }}</span></td>
                        
                        <!-- Form Rating (by customer) -->
                        <td>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) ★ @else <span class="empty">★</span> @endif
                                @endfor
                            </div>
                        </td>
                        
                        <td>
                            <p class="review-text">{{ $review->staff_review }}</p>
                        </td>
                        
                        <!-- Admin Performance Bar -->
                        <td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                {{ $reviews->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #64748b !important;">
                <i class="fas fa-star" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                <h4 style="color: #0f172a !important; font-weight: 700;">No Reviews Found</h4>
                <p>You haven't submitted any reviews yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
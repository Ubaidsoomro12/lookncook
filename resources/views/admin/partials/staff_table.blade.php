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
                <th>Admin Rating (Slide)</th>
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
                    <!-- Dynamic Slider for Admin -->
                    <div class="admin-slider-wrapper">
                        <input type="range" min="1" max="5" value="{{ $review->staff_rating }}" class="admin-slider" data-id="{{ $review->id }}" oninput="this.nextElementSibling.innerText = this.value + ' Stars'" onchange="updateRating(this)">
                        <div class="slider-value">{{ $review->staff_rating }} Stars</div>
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
        <h4 style="color: #0f172a !important; font-weight: 700;">No Reviews Found</h4>
        <p>We couldn't find any reviews matching your search. Try different keywords.</p>
    </div>
@endif
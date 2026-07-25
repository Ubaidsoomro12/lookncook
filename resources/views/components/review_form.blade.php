<!-- =========================================
     REVIEW FORM - CART STYLE (NO SCROLL)
========================================= -->

<!-- Floating Review Button -->
<button class="review-trigger" id="reviewTrigger" title="Leave a Review">
    <i class="fas fa-star"></i>
    <span class="tooltip-text">✨ Leave a Review</span>
    <span class="badge-pulse">+</span>
</button>

<!-- Overlay -->
<div class="review-overlay" id="reviewOverlay"></div>

<!-- Review Panel -->
<div class="review-panel" id="reviewPanel">
    <!-- Panel Header -->
    <div class="review-header">
        <div class="header-top">
            <h3><i class="fas fa-star" style="color:#ffc107;"></i> Leave a Review</h3>
            <button class="close-btn" id="closePanel">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="header-sub">We value your feedback</p>
    </div>

    <!-- Panel Body -->
    <div class="review-body" id="reviewBody">
        <!-- Success Message -->
        <div class="success-message" id="successMessage">
            <div class="check-icon">
                <i class="fas fa-check"></i>
            </div>
            <h4>Thank You! 🎉</h4>
            <p>Your review has been submitted successfully.</p>
            <button class="btn-close-success" onclick="closePanel()">
                <i class="fas fa-arrow-left"></i> Close
            </button>
        </div>

        <!-- Error Message -->
        <div class="error-message" id="errorMessage" style="display:none; background:#f8d7da; color:#721c24; padding:12px 16px; border-radius:8px; margin-bottom:12px;">
            <i class="fas fa-exclamation-circle"></i>
            <span id="errorText"></span>
        </div>

        <!-- Review Form -->
        <div id="reviewFormContainer">
            <form method="POST" action="{{ route('review.submit') }}" id="reviewForm">
                @csrf

                <!-- Name + Email -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               placeholder="John Doe"
                               value="{{ old('name') }}" 
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="john@example.com"
                               value="{{ old('email') }}" 
                               required>
                    </div>
                </div>

                <!-- Profession -->
                <div class="form-group">
                    <label class="form-label">Profession</label>
                    <input type="text" 
                           name="profession" 
                           class="form-control" 
                           placeholder="e.g. Software Developer"
                           value="{{ old('profession') }}" 
                           required>
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label class="form-label">Your Message</label>
                    <textarea name="message" 
                              rows="3" 
                              class="form-control" 
                              placeholder="Tell us about your experience..." 
                              required>{{ old('message') }}</textarea>
                </div>

                <!-- Rating -->
                <div class="form-group">
                    <label class="form-label">Rating</label>
                    <div class="rating-box">
                        <div class="stars-row">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" 
                                       id="star{{ $i }}" 
                                       name="rating" 
                                       value="{{ $i }}"
                                       {{ old('rating') == $i ? 'checked' : '' }}
                                       required>
                                <label for="star{{ $i }}" class="star" data-value="{{ $i }}">★</label>
                            @endfor
                        </div>
                        <div class="rating-label">
                            <span id="ratingLabelText">
                                @if(old('rating'))
                                    @php
                                        $labels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
                                    @endphp
                                    {{ $labels[old('rating')] ?? 'Select Rating' }}
                                @else
                                    Select Rating
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Consent -->
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="consent" name="consent" required>
                        <span>I agree to the <a href="#">Privacy Policy</a></span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Review
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* ============================================
       FONT AWESOME
    ============================================ */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

    /* ============================================
       RESET - Website scroll allowed
    ============================================ */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        max-width: 100%;
        overflow-x: hidden;
    }

    /* ============================================
       FLOATING TRIGGER BUTTON
    ============================================ */
    .review-trigger {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        width: 58px;
        height: 58px;
        background: linear-gradient(135deg, #ff2d7a, #ff6b9d);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 25px rgba(255, 45, 122, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid rgba(255, 255, 255, 0.2);
        animation: pulse 2s ease-in-out infinite;
        color: white;
    }

    .review-trigger i {
        font-size: 24px;
        color: white;
    }

    .review-trigger:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 35px rgba(255, 45, 122, 0.6);
    }

    .review-trigger .tooltip-text {
        position: absolute;
        right: 72px;
        background: rgba(0,0,0,0.85);
        color: white;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s ease;
        pointer-events: none;
        font-weight: 500;
    }

    .review-trigger .tooltip-text::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 50%;
        transform: translateY(-50%);
        border-left: 8px solid rgba(0,0,0,0.85);
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
    }

    .review-trigger:hover .tooltip-text {
        opacity: 1;
        transform: translateX(0);
    }

    .review-trigger .badge-pulse {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 20px;
        height: 20px;
        background: #ffc107;
        border-radius: 50%;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        color: #000;
        animation: badgePulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 6px 25px rgba(255, 45, 122, 0.4); }
        50% { box-shadow: 0 6px 40px rgba(255, 45, 122, 0.7); }
    }

    @keyframes badgePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    /* ============================================
       OVERLAY
    ============================================ */
    .review-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s ease;
    }

    .review-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* ============================================
       PANEL - NO SCROLL
    ============================================ */
    .review-panel {
        position: fixed;
        top: 0;
        right: -420px;
        width: 400px;
        height: 100vh;
        background: #ffffff;
        z-index: 9999;
        box-shadow: -10px 0 40px rgba(0, 0, 0, 0.15);
        transition: right 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
    }

    .review-panel.open {
        right: 0;
    }

    /* ============================================
       PANEL HEADER
    ============================================ */
    .review-header {
        padding: 18px 24px 14px;
        border-bottom: 1px solid #f0f0f0;
        background: #ffffff;
        flex-shrink: 0;
    }

    .review-header .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .review-header h3 {
        font-size: 19px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .review-header h3 i {
        margin-right: 8px;
        font-size: 17px;
    }

    .review-header .close-btn {
        width: 34px;
        height: 34px;
        background: #f5f5f5;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .review-header .close-btn:hover {
        background: #ff2d7a;
        color: white;
        transform: rotate(90deg);
    }

    .review-header .header-sub {
        font-size: 13px;
        color: #888;
        margin: 3px 0 0;
    }

    /* ============================================
       PANEL BODY - NO SCROLL
    ============================================ */
    .review-body {
        padding: 20px 24px 24px;
        flex: 1;
        overflow: hidden !important;
        background: #fafafa;
        display: flex;
        flex-direction: column;
    }

    #reviewFormContainer {
        flex: 1;
        overflow: hidden;
    }

    #reviewForm {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* ============================================
       FORM - COMPACT
    ============================================ */
    .form-row {
        display: flex;
        gap: 12px;
        margin-bottom: 10px;
    }

    .form-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        font-size: 12px;
        color: #1a1a2e;
        display: block;
        margin-bottom: 4px;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e8ecf1;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #1a1a2e;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #ff2d7a;
        box-shadow: 0 0 0 4px rgba(255, 45, 122, 0.08);
        outline: none;
    }

    .form-control::placeholder {
        color: #a0a5b5;
        font-size: 12px;
    }

    textarea.form-control {
        resize: none;
        min-height: 55px;
        font-family: inherit;
    }

    /* ============================================
       RATING BOX - COMPACT
    ============================================ */
    .rating-box {
        background: #ffffff;
        border-radius: 10px;
        padding: 12px 16px;
        border: 2px solid #e8ecf1;
        text-align: center;
        transition: all 0.3s ease;
    }

    .rating-box:hover {
        border-color: #ff2d7a;
    }

    .stars-row {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 4px;
    }

    .stars-row input {
        display: none;
    }

    .stars-row .star {
        font-size: 2rem;
        color: #d0d5dd;
        cursor: pointer;
        transition: all 0.2s ease;
        line-height: 1;
        padding: 0 2px;
        user-select: none;
    }

    .stars-row input:checked ~ .star,
    .stars-row .star:hover,
    .stars-row .star:hover ~ .star {
        color: #ffc107;
        transform: scale(1.05);
    }

    .stars-row .star:hover {
        transform: scale(1.15);
    }

    .rating-label {
        margin-top: 6px;
        padding-top: 6px;
        border-top: 2px dashed #e8ecf1;
    }

    .rating-label span {
        font-size: 14px;
        font-weight: 700;
        color: #ff2d7a;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ============================================
       CHECKBOX - COMPACT
    ============================================ */
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #4a4a5a;
        cursor: pointer;
    }

    .checkbox-label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #ff2d7a;
        cursor: pointer;
        flex-shrink: 0;
    }

    .checkbox-label a {
        color: #ff2d7a;
        text-decoration: none;
        font-weight: 600;
    }

    .checkbox-label a:hover {
        text-decoration: underline;
    }

    /* ============================================
       SUBMIT BUTTON - COMPACT
    ============================================ */
    .btn-submit {
        width: 100%;
        padding: 11px;
        background: linear-gradient(135deg, #ff2d7a, #ff6b9d);
        border: none;
        border-radius: 50px;
        color: white;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 24px rgba(255, 45, 122, 0.3);
        margin-top: 4px;
        flex-shrink: 0;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 35px rgba(255, 45, 122, 0.5);
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .btn-submit i {
        margin-right: 6px;
    }

    /* ============================================
       SUCCESS MESSAGE
    ============================================ */
    .success-message {
        display: none;
        text-align: center;
        padding: 30px 15px;
        animation: fadeIn 0.5s ease;
        flex: 1;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .success-message .check-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #00c853, #69f0ae);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 26px;
        color: white;
        box-shadow: 0 8px 30px rgba(0, 200, 83, 0.3);
        animation: popIn 0.5s ease;
    }

    .success-message h4 {
        font-size: 19px;
        color: #1a1a2e;
        margin: 0 0 4px;
    }

    .success-message p {
        color: #666;
        font-size: 13px;
        margin: 0 0 14px;
    }

    .btn-close-success {
        padding: 8px 24px;
        border: 2px solid #e8ecf1;
        border-radius: 30px;
        background: transparent;
        color: #1a1a2e;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
    }

    .btn-close-success:hover {
        border-color: #ff2d7a;
        color: #ff2d7a;
    }

    /* ============================================
       ERROR MESSAGE
    ============================================ */
    .error-message {
        display: none;
        background: #f8d7da;
        color: #721c24;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 10px;
        font-size: 13px;
        flex-shrink: 0;
    }

    .error-message i {
        margin-right: 6px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* ============================================
       RESPONSIVE
    ============================================ */

    @media (max-width: 768px) {
        .review-panel {
            right: -100%;
            width: 100%;
        }

        .review-panel.open {
            right: 0;
        }

        .review-trigger {
            width: 50px;
            height: 50px;
            bottom: 18px;
            right: 18px;
        }

        .review-trigger i {
            font-size: 20px;
        }

        .review-trigger .tooltip-text {
            display: none;
        }

        .review-header {
            padding: 16px 20px 14px;
        }

        .review-header h3 {
            font-size: 17px;
        }

        .review-body {
            padding: 16px 18px 20px;
        }

        .form-row {
            flex-direction: column;
            gap: 10px;
            margin-bottom: 10px;
        }

        .form-row .form-group {
            flex: none;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-control {
            padding: 8px 10px;
            font-size: 13px;
        }

        textarea.form-control {
            min-height: 50px;
        }

        .stars-row .star {
            font-size: 1.8rem;
        }

        .rating-label span {
            font-size: 14px;
        }

        .btn-submit {
            padding: 11px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .review-trigger {
            width: 44px;
            height: 44px;
            bottom: 14px;
            right: 14px;
        }

        .review-trigger i {
            font-size: 17px;
        }

        .review-trigger .badge-pulse {
            width: 16px;
            height: 16px;
            font-size: 8px;
            top: -3px;
            right: -3px;
        }

        .review-header {
            padding: 14px 16px 12px;
        }

        .review-header h3 {
            font-size: 16px;
        }

        .review-header .header-sub {
            font-size: 12px;
        }

        .review-header .close-btn {
            width: 30px;
            height: 30px;
            font-size: 13px;
        }

        .review-body {
            padding: 14px 14px 18px;
        }

        .form-label {
            font-size: 12px;
        }

        .form-control {
            padding: 7px 10px;
            font-size: 13px;
            border-radius: 8px;
        }

        textarea.form-control {
            min-height: 45px;
        }

        .rating-box {
            padding: 10px 12px;
        }

        .stars-row .star {
            font-size: 1.6rem;
        }

        .rating-label span {
            font-size: 13px;
        }

        .btn-submit {
            padding: 9px;
            font-size: 13px;
        }

        .checkbox-label {
            font-size: 12px;
        }

        .success-message .check-icon {
            width: 45px;
            height: 45px;
            font-size: 20px;
        }

        .success-message h4 {
            font-size: 16px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Panel Controls
        const trigger = document.getElementById('reviewTrigger');
        const panel = document.getElementById('reviewPanel');
        const overlay = document.getElementById('reviewOverlay');
        const closeBtn = document.getElementById('closePanel');

        window.openPanel = function() {
            panel.classList.add('open');
            overlay.classList.add('active');
        };

        window.closePanel = function() {
            panel.classList.remove('open');
            overlay.classList.remove('active');
        };

        if (trigger) trigger.addEventListener('click', openPanel);
        if (closeBtn) closeBtn.addEventListener('click', closePanel);
        if (overlay) overlay.addEventListener('click', closePanel);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePanel();
        });

        // Rating
        const ratingLabels = { 1: 'Terrible', 2: 'Poor', 3: 'Average', 4: 'Good', 5: 'Excellent' };
        const ratingLabelText = document.getElementById('ratingLabelText');

        document.querySelectorAll('.stars-row input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const value = parseInt(this.value);
                if (ratingLabelText) {
                    ratingLabelText.textContent = ratingLabels[value] || 'Select Rating';
                }
            });
            if (radio.checked) {
                radio.dispatchEvent(new Event('change'));
            }
        });

        document.querySelectorAll('.stars-row .star').forEach(function(label) {
            label.addEventListener('mouseenter', function() {
                const value = parseInt(this.dataset.value);
                if (ratingLabelText) {
                    ratingLabelText.textContent = ratingLabels[value] || 'Select Rating';
                }
            });

            label.addEventListener('mouseleave', function() {
                const checked = document.querySelector('.stars-row input[type="radio"]:checked');
                if (checked) {
                    const value = parseInt(checked.value);
                    if (ratingLabelText) {
                        ratingLabelText.textContent = ratingLabels[value] || 'Select Rating';
                    }
                } else {
                    if (ratingLabelText) {
                        ratingLabelText.textContent = 'Select Rating';
                    }
                }
            });
        });

        // =========================================
        // FORM SUBMIT - WITH AUTO REDIRECT
        // =========================================
        const reviewForm = document.getElementById('reviewForm');
        const formContainer = document.getElementById('reviewFormContainer');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');

        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Hide previous errors
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                    errorText.textContent = '';
                }

                const formData = new FormData(this);
                const submitBtn = this.querySelector('.btn-submit');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                submitBtn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // ✅ Show success message
                        formContainer.style.display = 'none';
                        successMessage.style.display = 'flex';

                        // ✅ After 3 seconds, redirect to home page
                        setTimeout(function() {
                            window.location.href = "{{ route('home') }}";
                        }, 3000);

                    } else {
                        // Show errors
                        if (errorMessage) {
                            errorMessage.style.display = 'block';
                            if (data.errors) {
                                const errors = Object.values(data.errors).flat();
                                errorText.textContent = errors.join(', ');
                            } else {
                                errorText.textContent = data.message || 'Something went wrong. Please try again.';
                            }
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    if (errorMessage) {
                        errorMessage.style.display = 'block';
                        errorText.textContent = 'An error occurred. Please try again.';
                    }
                })
                .finally(function() {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
</script>
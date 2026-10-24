@extends('layouts.master')

@section('title', 'Staff & Customer Reviews')

@section('content')
<style>
    /* =========================================
       PREMIUM REVIEW PAGE STYLING (COMPACT FIT)
       ========================================= */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary: #ff2d7a;
        --primary-light: #ff6b9d;
        --text-main: #111;
        --text-muted: #777;
    }

    .review-page-wrapper {
        font-family: 'Outfit', sans-serif;
        background: #fdfbfb;
        min-height: calc(100vh - 180px); 
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 20px;
        overflow: hidden;
        position: relative;
    }

    /* =========================================
       BACKGROUND FLOATING ICONS (ANIMATED)
       ========================================= */
    .background-icons {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        overflow: hidden;
        pointer-events: none; 
        z-index: 0;
    }

    .bg-icon-wrapper {
        position: absolute;
        color: rgba(255, 45, 122, 0.15); /* Darker color */
        font-size: 3rem;
        will-change: transform;
    }

    /* Specific Adjustments */
    .bg-icon-plane {
        top: 15%;
        left: -10%;
        font-size: 2.5rem;
    }

    .bg-icon-flying-bird {
        top: 25%;
        left: 50%;
        font-size: 3.5rem;
    }

    .bg-icon-sitting-bird {
        bottom: 10%;
        right: 10%;
        font-size: 4rem;
        position: relative;
    }
    
    /* New icons at the bottom */
    .bg-icon-bottom-left {
        bottom: 15%;
        left: 10%;
        font-size: 3rem;
    }

    .bg-icon-bottom-center {
        bottom: 5%;
        left: 40%;
        font-size: 2.5rem;
    }

    .bg-icon-bottom-right {
        bottom: 20%;
        right: 5%;
        font-size: 2rem;
    }
    
    /* Branch underneath the sitting bird */
    .bg-branch {
        position: absolute;
        bottom: 8%;
        right: 5%;
        width: 150px;
        height: 10px;
        background: rgba(255, 45, 122, 0.15);
        border-radius: 50%;
        transform: rotate(-10deg);
    }

    /* =========================================
       FORM CARD
       ========================================= */
    .review-card {
        background: #ffffff;
        width: 100%;
        max-width: 750px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(255, 45, 122, 0.08), 0 5px 15px rgba(0, 0, 0, 0.03);
        padding: 25px 40px; 
        position: relative;
        border: 1px solid rgba(255, 45, 122, 0.1);
        z-index: 10;
        transition: box-shadow 0.4s ease, border-color 0.4s ease;
    }

    /* Glow effect when clicked/focused */
    .review-card:focus-within, 
    .review-card.glowing {
        box-shadow: 0 20px 60px rgba(255, 45, 122, 0.2), 0 10px 25px rgba(0, 0, 0, 0.05);
        border-color: rgba(255, 45, 122, 0.4);
    }

    .review-card::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 45, 122, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
    }

    .review-content {
        position: relative;
        z-index: 1;
    }

    .review-header { text-align: center; margin-bottom: 15px; }
    .review-header .icon-badge {
        width: 50px; height: 50px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white; border-radius: 14px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 22px; margin-bottom: 10px;
        box-shadow: 0 8px 20px rgba(255, 45, 122, 0.25);
    }
    .review-header h1 { font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 4px; letter-spacing: -0.5px; }
    .review-header p { color: var(--text-muted); font-size: 13px; margin: 0 auto; }

    .custom-form-group { margin-bottom: 12px; position: relative; }
    .custom-form-group label { font-size: 11px; font-weight: 700; color: #333; margin-bottom: 4px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
    .custom-input {
        width: 100%; background: #f9f9fa; border: 1.5px solid #eaebec; border-radius: 10px;
        padding: 10px 14px; font-size: 14px; font-weight: 500; color: #222;
        transition: all 0.3s ease; font-family: 'Outfit', sans-serif;
    }
    .custom-input:focus { outline: none; background: #fff; border-color: var(--primary); box-shadow: 0 3px 10px rgba(255, 45, 122, 0.1); }
    select.custom-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23777' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center; background-size: 14px; cursor: pointer;
    }
    textarea.custom-input { resize: none; min-height: 65px; max-height: 80px; }

    .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 6px; }
    .star-rating input { display: none; }
    .star-rating label { font-size: 28px; color: #e0e0e0; cursor: pointer; transition: all 0.2s ease; margin-bottom: 0; }
    .star-rating input:checked ~ label, .star-rating label:hover, .star-rating label:hover ~ label {
        color: #ffb800; text-shadow: 0 4px 10px rgba(255, 184, 0, 0.4); transform: scale(1.1);
    }

    .btn-submit-review {
        width: 100%; background: linear-gradient(135deg, var(--primary) 0%, #e91e63 100%);
        color: #fff; border: none; border-radius: 10px; padding: 12px;
        font-size: 15px; font-weight: 700; letter-spacing: 0.5px; cursor: pointer;
        transition: all 0.3s ease; box-shadow: 0 8px 20px rgba(255, 45, 122, 0.3);
        display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 6px;
    }
    .btn-submit-review:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(255, 45, 122, 0.4); }

    .custom-alert { padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .custom-alert.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .custom-alert.error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* =========================================
       PREMIUM ANIMATED OVERLAY (GSAP)
       ========================================= */
    #premium-overlay {
        position: fixed; inset: 0; z-index: 999999;
        display: none; justify-content: center; align-items: center;
        background: rgba(10, 15, 30, 0.6);
        backdrop-filter: blur(8px);
        opacity: 0;
    }

    .overlay-panel {
        background: rgba(255, 255, 255, 0.95);
        width: 90%; max-width: 420px;
        border-radius: 24px;
        padding: 40px 30px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 1);
        position: relative; overflow: hidden;
        text-align: center;
        border: 1px solid transparent;
        background-clip: padding-box;
    }
    
    .overlay-panel::before {
        content: ''; position: absolute; top: -2px; left: -2px; right: -2px; bottom: -2px;
        background: linear-gradient(135deg, rgba(100, 100, 255, 0.2) 0%, rgba(255, 45, 122, 0.1) 100%);
        z-index: -1; border-radius: 26px;
    }

    .glow-ring-wrapper {
        position: relative;
        width: 90px; height: 90px;
        margin: 0 auto 25px;
        display: flex; justify-content: center; align-items: center;
    }
    
    .glow-ring {
        position: absolute; inset: 0;
        border-radius: 50%;
        border: 4px solid rgba(100, 100, 255, 0.1);
        border-top-color: #4a4aff;
        border-right-color: #ff2d7a;
        animation: spinRing 1.2s linear infinite;
        box-shadow: 0 0 20px rgba(74, 74, 255, 0.2);
    }

    .success-check {
        width: 90px; height: 90px;
        position: absolute; inset: 0;
        display: none;
    }
    
    .success-check svg {
        width: 100%; height: 100%;
        stroke-dasharray: 100; stroke-dashoffset: 100;
    }

    @keyframes spinRing { 100% { transform: rotate(360deg); } }

    .status-text {
        font-family: 'Outfit', sans-serif;
        font-size: 20px; font-weight: 700;
        color: #1a1a3a;
        margin: 0; min-height: 28px;
    }

    .loading-dots {
        display: flex; justify-content: center; gap: 6px; margin-top: 15px;
    }
    .dot {
        width: 8px; height: 8px;
        background: #4a4aff;
        border-radius: 50%;
        opacity: 0.3;
    }

    @media (prefers-reduced-motion: reduce) {
        .glow-ring { animation: none; border-color: #4a4aff; border-top-color: transparent; }
        .dot { animation: none !important; opacity: 1; }
        #premium-overlay, .overlay-panel { transition: none !important; }
    }

    @media (max-width: 768px) {
        .review-page-wrapper { padding: 10px; min-height: calc(100vh - 80px); }
        .review-card { padding: 20px; }
        .star-rating label { font-size: 28px; }
    }
</style>

<div class="review-page-wrapper">
    <!-- Floating Background Icons -->
    <div class="background-icons" id="bgIcons">
        <!-- Top / Middle Icons -->
        <i class="fa-solid fa-paper-plane bg-icon-wrapper bg-icon-plane"></i>
        <i class="fa-solid fa-dove bg-icon-wrapper bg-icon-flying-bird"></i>
        
        <!-- Bottom Icons -->
        <i class="fa-solid fa-mug-hot bg-icon-wrapper bg-icon-bottom-left"></i>
        <i class="fa-solid fa-utensils bg-icon-wrapper bg-icon-bottom-center"></i>
        
        <!-- Sitting Bird & Branch -->
        <div class="bg-branch"></div>
        <i class="fa-solid fa-crow bg-icon-wrapper bg-icon-sitting-bird"></i>
        <i class="fa-solid fa-leaf bg-icon-wrapper bg-icon-bottom-right"></i>
    </div>

    <div class="review-card">
        <div class="review-content">
            
            <div class="review-header">
                <div class="icon-badge">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h1>Staff & Customer Review</h1>
                <p>We value your feedback. Please take a moment to rate your experience.</p>
            </div>

            @if(session('success'))
                <div class="custom-alert success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="custom-alert error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('staff.reviews.store') }}" method="POST" id="reviewForm">
                @csrf

                <div class="custom-form-group">
                    <label>Staff Name</label>
                    <input type="text" name="name" class="custom-input" value="{{ auth()->user()->name }}" readonly required>
                </div>

                <div class="custom-form-group">
                    <label>Select Table</label>
                    <select name="table_id" class="custom-input" required>
                        <option value="" disabled selected>Choose a table...</option>
                        @if(isset($tables))
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}">Table {{ $table->table_number ?? $table->name ?? $table->id }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="custom-form-group">
                    <label>Message</label>
                    <textarea name="message" id="reviewMessage" class="custom-input" placeholder="Write your feedback here (min 5 words)..." required></textarea>
                    <small id="wordCountError" style="color: #dc2626; display: none; font-size: 12px; margin-top: 4px;">Please write at least 5 words.</small>
                </div>

                <div class="custom-form-group">
                    <label>Ratings</label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required />
                        <label for="star5" title="5 stars"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" />
                        <label for="star4" title="4 stars"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" />
                        <label for="star3" title="3 stars"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" />
                        <label for="star2" title="2 stars"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" />
                        <label for="star1" title="1 star"><i class="fa-solid fa-star"></i></label>
                    </div>
                </div>

                <button type="submit" class="btn-submit-review" id="submitBtn">
                    <i class="fa-solid fa-paper-plane"></i> Submit Review
                </button>
            </form>

        </div>
    </div>
</div>

<!-- Premium Overlay -->
<div id="premium-overlay">
    <div class="overlay-panel" id="overlayPanel">
        <div class="glow-ring-wrapper">
            <div class="glow-ring" id="glowRing"></div>
            <div class="success-check" id="successCheck">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" stroke="#10b981" stroke-width="6" stroke-linecap="round"/>
                    <path d="M30 50 L45 65 L70 35" stroke="#10b981" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <h3 class="status-text" id="statusText">Submitting your feedback</h3>
        <div class="loading-dots" id="loadingDots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
</div>

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reviewForm');
        const messageInput = document.getElementById('reviewMessage');
        const wordCountError = document.getElementById('wordCountError');
        const overlay = document.getElementById('premium-overlay');
        const overlayPanel = document.getElementById('overlayPanel');
        const statusText = document.getElementById('statusText');
        const loadingDots = document.getElementById('loadingDots');
        const glowRing = document.getElementById('glowRing');
        const successCheck = document.getElementById('successCheck');
        const reviewCard = document.querySelector('.review-card');
        
        let isSubmitting = false;

        // --- 1. Background Icons Custom Animation ---
        const paperPlane = document.querySelector('.bg-icon-plane');
        const flyingBird = document.querySelector('.bg-icon-flying-bird');
        const sittingBird = document.querySelector('.bg-icon-sitting-bird');
        const bottomLeft = document.querySelector('.bg-icon-bottom-left');
        const bottomCenter = document.querySelector('.bg-icon-bottom-center');
        const bottomRight = document.querySelector('.bg-icon-bottom-right');

        // Paper Plane: Flies across the screen
        gsap.set(paperPlane, { x: -100, y: "20vh", rotation: 30 });
        gsap.to(paperPlane, {
            x: "110vw",
            y: "-10vh",
            rotation: -10,
            duration: 12,
            repeat: -1,
            ease: "none",
            delay: 1
        });

        // Flying Bird: Flaps wings and flies in a curve
        gsap.set(flyingBird, { x: "110vw", y: "30vh", rotation: -20 });
        gsap.to(flyingBird, {
            x: "-20vw",
            y: "10vh",
            rotation: 10,
            duration: 18,
            repeat: -1,
            ease: "sine.inOut",
            delay: 2
        });
        // Simulate wing flapping by scaling vertically slightly
        gsap.to(flyingBird, {
            scaleY: 0.8,
            duration: 0.3,
            repeat: -1,
            yoyo: true,
            ease: "power1.inOut"
        });

        // Sitting Bird: Hops slightly and turns head
        gsap.to(sittingBird, {
            y: -5,
            rotation: 5,
            duration: 2,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut"
        });

        // Bottom Icons floating effect
        [bottomLeft, bottomCenter, bottomRight].forEach((icon, index) => {
            gsap.to(icon, {
                y: `+=${Math.random() * 20 + 10}`,
                rotation: `+=${Math.random() * 20 - 10}`,
                duration: 4 + index,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });
        });

        // --- 2. Form Glow Effect ---
        reviewCard.addEventListener('click', () => {
            reviewCard.classList.add('glowing');
            setTimeout(() => reviewCard.classList.remove('glowing'), 1000);
        });

        // --- 3. Form Submission & Validation Logic ---
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            const message = messageInput.value.trim();
            const wordCount = message ? message.split(/\s+/).length : 0;

            if (wordCount < 5) {
                e.preventDefault();
                wordCountError.style.display = 'block';
                messageInput.style.borderColor = '#dc2626';
                return;
            }

            wordCountError.style.display = 'none';
            messageInput.style.borderColor = '#eaebec';

            if (!form.checkValidity()) {
                return; 
            }

            e.preventDefault(); 
            startPremiumAnimation(e);
        });

        messageInput.addEventListener('input', function() {
            const words = this.value.trim() ? this.value.trim().split(/\s+/).length : 0;
            if (words >= 5) {
                wordCountError.style.display = 'none';
                messageInput.style.borderColor = '#eaebec';
            }
        });

        // --- 4. Premium Animation Logic ---
        function startPremiumAnimation(e) {
            isSubmitting = true;
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...';

            overlay.style.display = 'flex';
            gsap.to(overlay, { opacity: 1, duration: 0.3 });
            gsap.fromTo(overlayPanel, 
                { y: 30, scale: 0.95, opacity: 0 }, 
                { y: 0, scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.5)" }
            );

            gsap.to(".dot", {
                opacity: 1,
                y: -5,
                duration: 0.4,
                stagger: 0.15,
                repeat: -1,
                yoyo: true,
                ease: "power1.inOut"
            });

            // Text Sequence (Starts directly with Submitting)
            let textTl = gsap.timeline();
            textTl.to(statusText, { opacity: 0, duration: 0.3, delay: 1.5 })
                  .add(() => { statusText.innerText = "Finalizing"; })
                  .to(statusText, { opacity: 1, duration: 0.3 });

            const startTime = Date.now();
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/plain, */*'
                }
            })
            .then(async response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const data = await response.json();
                    return { isJson: true, ok: response.ok, data: data };
                } else {
                    return { isJson: false, ok: response.ok, data: null };
                }
            })
            .then(res => {
                if (res.isJson && !res.data.success) {
                    handleError(res.data);
                } else if (!res.ok && !res.isJson) {
                    handleError({ message: 'Server error occurred.' });
                } else {
                    enfor9yMnTm4NSzvG9rrwjM2ec8xZgh1cafXH8();
                }
            })
            .catch(err => {
                console.error("Submission Error:", err);
                handleError({ message: 'Network error. Please try again.' });
            });

            function enfor9yMnTm4NSzvG9rrwjM2ec8xZgh1cafXH8() {
                const elapsed = Date.now() - startTime;
                const remaining = Math.max(0, 3000 - elapsed);

                setTimeout(() => {
                    textTl.kill();
                    gsap.killTweensOf(".dot");
                    
                    gsap.to(glowRing, { opacity: 0, duration: 0.2 });
                    gsap.to(loadingDots, { opacity: 0, duration: 0.2 });
                    statusText.innerText = "Review submitted successfully";
                    statusText.style.color = "#10b981"; 
                    
                    successCheck.style.display = 'block';
                    gsap.fromTo(successCheck.querySelector('circle'), 
                        { strokeDashoffset: 100 }, 
                        { strokeDashoffset: 0, duration: 0.6, ease: "power2.out" }
                    );
                    gsap.fromTo(successCheck.querySelector('path'), 
                        { strokeDashoffset: 100 }, 
                        { strokeDashoffset: 0, duration: 0.6, delay: 0.4, ease: "power2.out" }
                    );

                    setTimeout(() => {
                        gsap.to(overlay, { opacity: 0, duration: 0.4, onComplete: () => {
                            window.location.reload();
                        }});
                    }, 2000);

                }, remaining);
            }

            function handleError(data) {
                gsap.to(overlay, { opacity: 0, duration: 0.3, onComplete: () => {
                    overlay.style.display = 'none';
                }});

                isSubmitting = false;
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Review';
                
                let errorMsg = data.message || 'Something went wrong.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }
                alert("Error:\n" + errorMsg);
            }
        }
    });
</script>
@endsection
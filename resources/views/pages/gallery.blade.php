@extends('layouts.master') 

@section('title', 'Our Premium Gallery - Look N Cook')

@section('content')

<style>
    /* Global Page Styles & Theme */
    body {
        background-color: #0b0f19;
        color: #ffffff;
        font-family: 'Poppins', sans-serif;
    }
    
    /* SECTION 1: HERO / INTRO STYLES */
    .gallery-hero-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #0b0f19 0%, #111827 100%);
    }
    .gallery-tagline {
        color: #ff2d7a;
        font-weight: 600;
        letter-spacing: 2px;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    .gallery-title {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .gallery-title span {
        color: #ff2d7a;
        border-bottom: 3px solid #ff2d7a;
        padding-bottom: 5px;
    }
    .gallery-desc {
        color: #9ca3af;
        font-size: 1.1rem;
        max-width: 500px;
    }
    /* Polaroids Stack Feature */
    .polaroid-stack {
        position: relative;
        height: 450px;
    }
    .polaroid-card {
        background: #ffffff;
        padding: 15px 15px 35px 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        position: absolute;
        max-width: 280px;
        border-radius: 4px;
        transition: transform 0.3s ease;
    }
    .polaroid-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .polaroid-card p {
        color: #333333;
        font-family: 'Caveat', cursive, sans-serif;
        font-size: 1.2rem;
        text-align: center;
        margin-top: 15px;
        margin-bottom: 0;
    }
    .p1 { transform: rotate(-8deg); top: 20px; left: 10%; z-index: 1; }
    .p2 { transform: rotate(4deg); top: 10px; left: 35%; z-index: 2; }
    .p3 { transform: rotate(-3deg); top: 140px; left: 25%; z-index: 3; }
    .polaroid-card:hover {
        z-index: 10;
        transform: scale(1.05) rotate(0deg);
    }

    /* SECTION 2: MASONRY GALLERY GRID STYLES */
    .masonry-section {
        padding: 60px 0;
    }
    .section-header-center h2 {
        font-size: 2.5rem;
        font-weight: 700;
    }
    .section-header-center p {
        color: #6b7280;
    }
    /* Grid layout simulating a beautiful masonry balance */
    .gallery-grid-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 20px;
    }
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .gallery-item:hover img {
        transform: scale(1.08);
    }
    /* Assign heights based on the provided UI layout mapping */
    .h-tall { height: 420px; }
    .h-medium { height: 300px; }
    .h-short { height: 200px; }
    /* Grid row spans for specific items */
    .span-2-rows { grid-row: span 2; }

    /* PINTEREST HOVER OVERLAY ENGINE FOR GALLERY CARDS */
    .gallery-hover-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0.7) 100%);
        opacity: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 18px;
        transition: opacity 0.3s ease;
        z-index: 2;
    }
    .gallery-item:hover .gallery-hover-overlay {
        opacity: 1;
    }
    .gallery-heart-action-btn {
        align-self: flex-end;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: none;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .gallery-heart-action-btn:hover {
        transform: scale(1.12);
        background: #fff5f8;
    }
    .gallery-heart-action-btn:active {
        transform: scale(0.92);
    }
    
    /* Interactive Heart Specific Colors */
    .gallery-heart-action-btn .bi-heart {
        color: #6b7280; /* Neutral grey/white look when unliked */
    }
    .gallery-heart-action-btn .bi-heart-fill {
        color: #ff2d7a; /* Signature pink/red vibrant tone when active */
    }

    .gallery-asset-title {
        color: #ffffff;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
        transform: translateY(10px);
        transition: transform 0.3s ease;
    }
    .gallery-item:hover .gallery-asset-title {
        transform: translateY(0);
    }

    /* SECTION 3: EVENT HIGHLIGHTS & STATS */
    .highlights-section {
        padding: 60px 0;
    }
    .stat-card {
        background-color: #161c2a;
        border: 1px solid #232d42;
        border-radius: 16px;
        padding: 30px;
        text-align: left;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    .icon-pink { background-color: rgba(255, 45, 122, 0.15); color: #ff2d7a; }
    .icon-purple { background-color: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
    .icon-blue { background-color: rgba(6, 182, 212, 0.15); color: #06b6d4; }
    .icon-orange { background-color: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .stat-label {
        color: #9ca3af;
        font-size: 0.9rem;
    }

    /* SECTION 4: OUR PROCESS STYLES */
    .process-section {
        padding: 60px 0;
    }
    .process-step-card {
        background: #161c2a;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #232d42;
        height: 100%;
    }
    .process-img-wrapper {
        position: relative;
        height: 220px;
    }
    .process-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .step-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #ff2d7a;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.85rem;
    }
    .process-body {
        padding: 25px;
    }
    .process-title-box {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }
    .process-icon-small {
        color: #ff2d7a;
        font-size: 1.2rem;
    }
    .process-body h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    .process-body p {
        color: #9ca3af;
        font-size: 0.9rem;
        margin: 0;
    }
    /* Decorative connecting arrow setup */
    .arrow-col {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff2d7a;
        font-size: 1.5rem;
    }

    /* SECTION 5: CALL TO ACTION (CTA) STYLES */
    .cta-section {
        background: linear-gradient(115deg, #e01e63 0%, #7b1fa2 100%);
        padding: 80px 0;
        border-radius: 0px;
        text-align: center;
        position: relative;
    }
    .cta-icon-top {
        background: rgba(255, 255, 255, 0.2);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    .cta-section h2 {
        font-size: 2.8rem;
        font-weight: 700;
    }
    .cta-section h2 span {
        text-decoration: underline;
    }
    .cta-section p {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
    }
    .cta-btn-white {
        background-color: #ffffff;
        color: #ff2d7a;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
    }
    .cta-btn-white:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
    }
    .cta-btn-outline {
        background-color: transparent;
        color: #ffffff;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 8px;
        border: 2px solid #ffffff;
        transition: all 0.3s ease;
    }
    .cta-btn-outline:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }
    .cta-brand-footer {
        margin-top: 50px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
    }
    .cta-brand-footer span {
        color: #f59e0b;
        font-weight: bold;
        border-bottom: 2px solid #f59e0b;
    }

    /* RESPONSIVE MEDIA QUERIES */
    @media (max-width: 991px) {
        .gallery-title { font-size: 2.8rem; }
        .polaroid-stack { height: auto; margin-top: 50px; }
        .polaroid-card { position: relative; transform: none !important; display: inline-block; margin: 10px; top:auto !important; left:auto !important; }
        .gallery-grid-container { grid-template-columns: repeat(2, 1fr); }
        .arrow-col { transform: rotate(90deg); padding: 15px 0; }
        
        /* Mobile Touchscreen Compatibility: Retain overlay indicators without relying on desktop hovers */
        .gallery-hover-overlay {
            opacity: 1;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.6) 100%);
        }
        .gallery-asset-title {
            transform: translateY(0);
            font-size: 13px;
        }
        .gallery-heart-action-btn {
            width: 38px;
            height: 38px;
            font-size: 16px;
        }
    }
    @media (max-width: 576px) {
        .gallery-title { font-size: 2.2rem; }
        .gallery-grid-container { grid-template-columns: 1fr; }
        .h-tall, .h-medium, .h-short { height: 260px; }
    }
</style>

<section class="gallery-hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <span class="gallery-tagline">OUR GALLERY</span>
                <h1 class="gallery-title my-3">Moments<br>That<br><span>Inspire</span></h1>
                <p class="gallery-desc mt-4">Experience the artistry behind every event. From intimate gatherings to grand celebrations, we capture the essence of premium catering and unforgettable dining experiences.</p>
            </div>
            <div class="col-lg-7">
                <div class="polaroid-stack">
                    <div class="polaroid-card p1">
                        <img src="https://images.unsplash.com/photo-1555244162-803834f70033?q=80&w=600" alt="Catering Setup">
                        <p>Exquisite Details</p>
                    </div>
                    <div class="polaroid-card p2">
                        <img src="https://images.unsplash.com/photo-1537633552985-df8429e8048b?q=80&w=600" alt="Fine Buffet">
                        <p>Luxury Tablescapes</p>
                    </div>
                    <div class="polaroid-card p3">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600" alt="Plating Artistry">
                        <p>Gourmet Artistry</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="border-secondary my-0Opacity">

<section class="masonry-section">
    <div class="container">
        <div class="section-header-center text-center mb-5">
            <h2>Our Premium Gallery</h2>
            <p>Explore our collection of exquisite culinary creations and memorable events</p>
        </div>
        
        <div class="gallery-grid-container">
            <div class="gallery-item h-tall span-2-rows">
                <img src="https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?q=80&w=600" alt="Chef Preparing Dish">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Chef Cooking Presentation', 1, 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="1">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Chef Culinary Artistry</div>
                </div>
            </div>

            <div class="gallery-item h-short">
                <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=600" alt="Event Dinner Hall">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Event Dinner Hall Setup', 2, 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="2">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Luxury Hall Atmosphere</div>
                </div>
            </div>

            <div class="gallery-item h-medium">
                <img src="https://images.unsplash.com/photo-1579871494447-9811cf80d66c?q=80&w=600" alt="Sushi Platter">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Premium Sushi Presentation', 3, 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="3">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Gourmet Seafood Platter</div>
                </div>
            </div>
            
            <div class="gallery-item h-medium">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=600" alt="Steak Cooking">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Sizzling Gourmet Steak', 4, 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="4">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Signature Seared Steak</div>
                </div>
            </div>
            
            <div class="gallery-item h-short">
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600" alt="Healthy Salad Bowl">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Artisan Green Salad', 5, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="5">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Fresh Nutrient Selection</div>
                </div>
            </div>

            <div class="gallery-item h-tall span-2-rows">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600" alt="Premium Roasted Meat">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Royal Roasted Presentation', 6, 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="6">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Premium Carving Station</div>
                </div>
            </div>
            
            <div class="gallery-item h-short">
                <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=600" alt="Macarons & Sweets">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Luxury French Patisserie', 7, 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="7">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Fine Dessert Collection</div>
                </div>
            </div>
            
            <div class="gallery-item h-short">
                <img src="https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?q=80&w=600" alt="Wine Toasting">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Elite Corporate Toasting', 8, 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="8">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Premium Drinks Hosting</div>
                </div>
            </div>

            <div class="gallery-item h-medium">
                <img src="https://images.unsplash.com/photo-1551183053-bf91a1d81141?q=80&w=600" alt="Italian Pasta Meal">
                <div class="gallery-hover-overlay">
                    <button onclick="toggleWishlist(this, 'Gourmet Italian Specialty', 9, 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?q=80&w=600')" class="gallery-heart-action-btn" title="Save Asset" data-id="9">
                        <i class="bi bi-heart"></i>
                    </button>
                    <div class="gallery-asset-title">Artisan Pasta Display</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="highlights-section">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Event Highlights</h3>
            <p class="text-muted small">Celebrating countless moments of joy and success</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-pink">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <div class="stat-number">120+</div>
                    <div class="stat-label">Wedding Events</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-purple">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <div class="stat-number">85+</div>
                    <div class="stat-label">Birthday Events</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-blue">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stat-number">60+</div>
                    <div class="stat-label">Corporate Events</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-orange">
                        <i class="bi bi-stars"></i>
                    </div>
                    <div class="stat-number">95+</div>
                    <div class="stat-label">Custom Setups</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="process-section">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Our Process</h3>
            <p class="text-muted small">From concept to celebration, we handle every detail with precision and care</p>
        </div>
        <div class="row align-items-stretch">
            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                <div class="process-step-card">
                    <div class="process-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=600" alt="Food Preparation">
                        <div class="step-badge">1</div>
                    </div>
                    <div class="process-body">
                        <div class="process-title-box">
                            <i class="bi bi-egg-fried process-icon-small"></i>
                            <h4>Food Preparation</h4>
                        </div>
                        <p>Expert chefs crafting gourmet selections with premium, fresh ingredients curated exclusively for your custom event profile.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-1 col-md-12 arrow-col mb-3 mb-lg-0">
                <i class="bi bi-arrow-right"></i>
            </div>
            
            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                <div class="process-step-card">
                    <div class="process-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=600" alt="Event Setup">
                        <div class="step-badge">2</div>
                    </div>
                    <div class="process-body">
                        <div class="process-title-box">
                            <i class="bi bi-sliders process-icon-small"></i>
                            <h4>Event Setup</h4>
                        </div>
                        <p>Professional handling ensures arrangements match your desired luxury layout flawlessly before your first guest walks in.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-1 col-md-12 arrow-col mb-3 mb-lg-0">
                <i class="bi bi-arrow-right"></i>
            </div>
            
            <div class="col-lg-3 col-md-12">
                <div class="process-step-card">
                    <div class="process-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?q=80&w=600" alt="Memorable Moments">
                        <div class="step-badge">3</div>
                    </div>
                    <div class="process-body">
                        <div class="process-title-box">
                            <i class="bi bi-camera-fill process-icon-small"></i>
                            <h4>Memorable Moments</h4>
                        </div>
                        <p>Relax, step back and experience unparalleled fine dining memories that you and your guests will treasure forever.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-icon-top">
            <i class="bi bi-stars text-white"></i>
        </div>
        <h2 class="text-white mb-3">Ready To Make Your Event <span>Special?</span></h2>
        <p class="mb-5">Let us handle the food while you enjoy the moments.<br>Premium catering for unique, unforgettable celebrations.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <button class="cta-btn-white"><i class="bi bi-calendar-check me-2"></i>Book Catering Now</button>
            <button class="cta-btn-outline"><i class="bi bi-telephone-fill me-2"></i>Call Us Now</button>
        </div>
        <div class="cta-brand-footer">
            Premium Catering by <span>Look <span class="text-white">N</span> Cook</span>
        </div>
    </div>
</section>

<script>
    function toggleWishlist(element, itemName, itemId, itemImage) {
        // Prevent default button behavior focusing layout jumps
        element.blur();

        // Target the icon container nested inside the button
        const icon = element.querySelector('i');

        // Check current status by analyzing Bootstrap Icon class lists
        if (icon.classList.contains('bi-heart')) {
            // 1. Flip icon style layout state to Filled (Turns Pink/Red automatically via CSS)
            icon.classList.remove('bi-heart');
            icon.classList.add('bi-heart-fill');

            // 2. Call your custom addition function
            if (typeof addToWishlist === "function") {
                addToWishlist(itemName, itemId, itemImage);
            } else {
                console.log(`Added to wishlist: ${itemName} (ID: ${itemId})`);
            }
        } else {
            // 1. Revert icon style state layout back to regular layout Outline (Turns gray/white)
            icon.classList.remove('bi-heart-fill');
            icon.classList.add('bi-heart');

            // 2. Call your custom subtraction/removal function
            if (typeof removeFromWishlist === "function") {
                removeFromWishlist(itemId);
            } else {
                console.log(`Removed from wishlist: ${itemName} (ID: ${itemId})`);
            }
        }
    }

    // Backup Declarations so code doesn't break if these aren't defined in your main scripts yet
    if (typeof addToWishlist !== "function") {
        window.addToWishlist = function(name, id, image) {
            // Put your actual backend session/localStorage AJAX code here if needed!
        };
    }
    
    if (typeof removeFromWishlist !== "function") {
        window.removeFromWishlist = function(id) {
            // Put your actual removal AJAX/handling logic here!
        };
    }
</script>

@endsection
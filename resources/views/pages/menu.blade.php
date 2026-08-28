@extends('layouts.master')
@section('title', 'Menu')

@section('content')
@include('components.banner', [
    'miniTitle' => 'Menu',
    'title' => 'Delicious Food',
    'highlight' => 'Collection',
    'description' => 'Explore premium dishes, authentic flavors, and unforgettable catering experiences.'
])

<section class="menu-section">
    <div class="container">
        <div class="row">

            <!-- =========================================
                 LEFT CATEGORY SIDEBAR (Dynamic)
            ========================================= -->
            <div class="col-xl-3 col-lg-4 mb-4">
                <div class="category-sidebar">
                    <h4 class="sidebar-title">Categories</h4>

                    <button class="category-btn active" data-category="all">
                        All Menu
                    </button>

                    @foreach($categories as $category)
                        <button class="category-btn" data-category="{{ Str::slug($category->name) }}" title="{{ $category->name }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- =========================================
                 RIGHT FOOD AREA (Dynamic)
            ========================================= -->
            <div class="col-xl-9 col-lg-8">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h2 class="menu-heading">Food Menu</h2>
                    </div>
                    <select class="form-select menu-sort">
                        <option value="default">Sort By</option>
                        <option value="low">Price Low To High</option>
                        <option value="high">Price High To Low</option>
                    </select>
                </div>

                <!-- FOOD GRID -->
                <div class="row g-4 food-grid">
                    @forelse($products as $product)
                        @php
                            // ==========================================================
                            // Build ONE unified list of price options for this product:
                            // 1) the base price/weight (Old Price / Sale Price / Weight fields)
                            // 2) every dynamic variant added in admin (variation column)
                            // This mirrors exactly what the detail page does with its
                            // "variant_base" radio + the @foreach($product->variation) loop,
                            // so every price you add anywhere shows up here too.
                            // ==========================================================
                            $baseOldPrice = ($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                ? (float) $product->price
                                : null;
                            $basePrice = ($product->sale_price && $product->sale_price > 0)
                                ? (float) $product->sale_price
                                : (float) $product->price;

                            $allOptions = [
                                [
                                    'weight'    => $product->weight,
                                    'price'     => $basePrice,
                                    'old_price' => $baseOldPrice,
                                ],
                            ];

                            if (is_array($product->variation)) {
                                foreach ($product->variation as $variant) {
                                    $vPrice = (float) ($variant['price'] ?? 0);
                                    $vOldPrice = (!empty($variant['old_price']) && $variant['old_price'] > $vPrice)
                                        ? (float) $variant['old_price']
                                        : null;

                                    $allOptions[] = [
                                        'weight'    => $variant['weight'] ?? 'Option',
                                        'price'     => $vPrice,
                                        'old_price' => $vOldPrice,
                                    ];
                                }
                            }

                            // Default displayed price = first option (the base), same as
                            // the detail page where variant_base is checked by default.
                            $displayPrice = $allOptions[0]['price'];
                            $displayOldPrice = $allOptions[0]['old_price'];
                            $hasAnyDiscount = $displayOldPrice !== null;
                            
                            // Truncate description to 3 lines (approx 100 characters)
                            $shortDescription = Str::limit($product->description, 100, '...');
                        @endphp
                        <div class="col-xl-4 col-md-6 food-item"
                             data-category="{{ $product->category ? Str::slug($product->category->name) : 'uncategorized' }}"
                             data-price="{{ $displayPrice }}">

                            <div class="food-card">
                                <div class="food-image-wrapper">
                                    @if($hasAnyDiscount)
                                        <span class="discount-badge">SALE</span>
                                    @endif
                                    <img src="{{ asset($product->image) }}" class="food-image" alt="{{ $product->name }}">
                                </div>
                                <div class="food-content">
                                    <span class="food-category-badge">{{ $product->category->name ?? 'Menu Item' }}</span>

                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                        <h5 class="food-title mb-0">{{ $product->name }}</h5>
                                        <a href="{{ route('product.details', $product->id ?? 1) }}" class="view-details-eye-btn" title="View Details">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                    </div>
                                    
                                    <!-- Description limited to 3 lines -->
                                    <p class="food-description food-description-truncated">{{ $shortDescription }}</p>

                                    <!-- Middle wrapper forces this area to expand equally -->
                                    <div class="food-details-wrapper">
                                        <div class="variation-wrapper">
                                            <label class="variation-label">
                                                <i class="fa-solid fa-weight-hanging"></i> Select Weight
                                            </label>
                                            <div class="weight-btn-group">
                                                @foreach($allOptions as $i => $opt)
                                                    <button type="button"
                                                            class="weight-btn {{ $i === 0 ? 'active' : '' }}"
                                                            data-price="{{ $opt['price'] }}"
                                                            data-old-price="{{ $opt['old_price'] !== null ? $opt['old_price'] : '' }}"
                                                            data-weight="{{ $opt['weight'] }}">
                                                        {{ $opt['weight'] }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="food-bottom">
                                        <div class="food-rating">
                                            ★★★★★ <span>5.0</span>
                                        </div>
                                        <div class="food-price">
                                            <span class="old-price" style="{{ $hasAnyDiscount ? '' : 'display:none;' }}">
                                                PKR <span class="old-price-value">{{ $hasAnyDiscount ? number_format($displayOldPrice) : '' }}</span>
                                            </span>
                                            PKR <span class="price-value">{{ number_format($displayPrice) }}</span>
                                        </div>
                                    </div>

                                    <button class="add-cart-btn"
                                            data-base-name="{{ addslashes($product->name) }}"
                                            data-image="{{ asset($product->image) }}"
                                            onclick="handleAddToCart(this)">
                                        Add To Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No dishes available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</section>

<!-- =========================================
     CSS
========================================= -->

<style>
    .gallery-hero {
        background: #0f172a;
        padding: 120px 0;
        position: relative;
    }

    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right,
                rgba(15, 23, 42, 0.95),
                rgba(15, 23, 42, 0.82));
    }

    .hero-mini-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        letter-spacing: 3px;
        font-size: 14px;
        color: #ffffff;
        text-transform: uppercase;
    }

    .mini-highlight {
        color: #ff2d7a;
        font-weight: 800;
    }

    .hero-title {
        color: #fff;
        font-size: 72px;
        font-weight: 800;
        margin-top: 20px;
    }

    .hero-title span {
        color: #ff2d7a;
    }

    .hero-text {
        color: rgba(255, 255, 255, 0.75);
        max-width: 700px;
        margin: auto;
        margin-top: 20px;
        line-height: 1.9;
    }

    .floating-food {
        position: absolute;
        width: 110px;
        z-index: 2;
        animation: float 5s ease-in-out infinite;
    }

    .food-1 { left: 5%; top: 90px; }
    .food-2 { right: 7%; top: 80px; }
    .food-3 { right: 10%; bottom: 50px; }

    @keyframes float {
        0% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
        100% { transform: translateY(0); }
    }

    .menu-section {
        padding: 60px 0;
        background: #fff7fb;
    }

    /* ============ CATEGORY SIDEBAR — FIXED OVERFLOW ============ */
    .category-sidebar {
        background: #fff;
        border-radius: 30px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 110px;
        overflow: hidden;
    }

    .sidebar-title {
        font-weight: 800;
        margin-bottom: 25px;
    }

    .category-btn {
        display: block;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        border: none;
        background: #f5f5f5;
        padding: 13px 16px;
        margin-bottom: 12px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 14.5px;
        line-height: 1.4;
        transition: 0.3s;
        text-align: left;
        cursor: pointer;
        white-space: normal;
        overflow-wrap: break-word;
        word-break: break-word;
        hyphens: auto;
    }

    .category-btn.active {
        background: #ff2d7a;
        color: #fff;
    }

    .menu-heading {
        font-size: 42px;
        font-weight: 800;
        color: #ff2d7a;
    }

    .menu-sort {
        width: 220px;
        border-radius: 14px;
    }

    .food-card {
        background: #fff;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: 0.4s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .food-card:hover {
        transform: translateY(-10px);
    }

    .food-image-wrapper {
        overflow: hidden;
        position: relative;
    }

    .food-image {
        width: 100%;
        height: 240px;
        object-fit: cover;
        transition: 0.5s;
    }

    .food-card:hover .food-image {
        transform: scale(1.08);
    }

    .discount-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 2;
        background: #ff2d7a;
        color: #fff;
        font-weight: 800;
        font-size: 11.5px;
        letter-spacing: 0.5px;
        padding: 6px 14px;
        border-radius: 999px;
        box-shadow: 0 6px 14px rgba(255, 45, 122, 0.35);
    }

    .food-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* ============ CATEGORY BADGE (matches detail page) ============ */
    .food-category-badge {
        display: inline-block;
        align-self: flex-start;
        background: #fff2f7;
        color: #ff2d7a;
        border: 1px solid #ffd6e6;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 8px;
    }

    .food-title {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.3;
    }

    .view-details-eye-btn {
        background: #fff7fb;
        color: #ff2d7a;
        border: 1px solid #ffe1ec;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 16px;
        transition: 0.3s ease;
        text-decoration: none;
        flex-shrink: 0;
    }

    .view-details-eye-btn:hover {
        background: #ff2d7a;
        color: #fff;
        border-color: #ff2d7a;
        box-shadow: 0 4px 10px rgba(255, 45, 122, 0.2);
    }

    /* ============ DESCRIPTION - TRUNCATED TO 3 LINES ============ */
    .food-description {
        color: #666;
        line-height: 1.5;
        font-size: 14px;
        margin-top: 8px;
        margin-bottom: 10px;
    }

    .food-description-truncated {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        max-height: 63px; /* 3 lines × 1.5 line-height × 14px font-size */
        line-height: 1.5;
    }

    /* Pushes content below it to the absolute bottom of the container layout */
    .food-details-wrapper {
        flex-grow: 1; 
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .food-weight {
        margin-top: 4px;
        margin-bottom: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #ff2d7a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ============ VARIATION / WEIGHT BUTTONS ============ */
    .variation-wrapper {
        background: #fff7fb;
        border: 1px solid #ffe1ec;
        border-radius: 12px;
        padding: 8px 10px;
    }

    .variation-label {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #ff2d7a;
        margin-bottom: 6px;
    }

    .weight-btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .weight-btn {
        border: 1px solid #ffe1ec;
        background: #fff;
        color: #333;
        font-weight: 700;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .weight-btn:hover {
        border-color: #ff2d7a;
        color: #ff2d7a;
    }

    .weight-btn.active {
        background: #ff2d7a;
        border-color: #ff2d7a;
        color: #fff;
        box-shadow: 0 4px 10px rgba(255, 45, 122, 0.25);
    }

    .food-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        flex-wrap: wrap;
        gap: 4px;
    }

    .food-rating {
        color: #ffb400;
        font-size: 14px;
    }

    .food-rating span {
        color: #777;
    }

    .food-price {
        color: #ff2d7a;
        font-weight: 800;
        font-size: 20px;
        display: flex;
        align-items: baseline;
        gap: 6px;
        white-space: nowrap;
    }

    .food-price .old-price {
        color: #aaa;
        font-weight: 600;
        font-size: 13px;
        text-decoration: line-through;
    }

    .add-cart-btn {
        width: 100%;
        margin-top: 14px;
        border: none;
        background: #ff2d7a;
        color: #fff;
        padding: 10px;
        border-radius: 12px;
        font-weight: 700;
        transition: 0.3s;
    }

    .add-cart-btn:hover {
        background: #111;
    }

    @media(max-width:991px) {
        .hero-title { font-size: 52px; }
        .category-sidebar { position: relative; top: 0; }
    }

    @media(max-width:767px) {
        .hero-title { font-size: 38px; }
        .hero-text { font-size: 15px; padding: 0 10px; }
        .floating-food { width: 70px; }
        .menu-heading { font-size: 32px; }
        .food-image { height: 220px; }
    }
</style>

<!-- =========================================
     FILTER + SORT + VARIATION SCRIPT
========================================= -->

<script>
    document.addEventListener('DOMContentLoaded', function () {

        if (performance.navigation.type === 1) {
            window.location.href = "/";
        }

        const categoryBtns = document.querySelectorAll('.category-btn');
        const foodItems    = document.querySelectorAll('.food-item');
        const sortSelect   = document.querySelector('.menu-sort');
        const foodGrid     = document.querySelector('.food-grid');

        /* =========================================
           CATEGORY FILTER
        ========================================= */
        categoryBtns.forEach(button => {
            button.addEventListener('click', function () {
                categoryBtns.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const category = this.getAttribute('data-category');

                foodItems.forEach(item => {
                    if (category === 'all') {
                        item.style.display = 'block';
                    } else {
                        item.style.display = (item.getAttribute('data-category') === category) ? 'block' : 'none';
                    }
                });
            });
        });

        /* =========================================
           SORT SYSTEM
        ========================================= */
        sortSelect.addEventListener('change', function () {
            const items = Array.from(document.querySelectorAll('.food-item'));

            if (this.value === 'low') {
                items.sort((a, b) => a.dataset.price - b.dataset.price);
            } else if (this.value === 'high') {
                items.sort((a, b) => b.dataset.price - a.dataset.price);
            } else {
                window.location.reload();
                return;
            }

            items.forEach(item => foodGrid.appendChild(item));
        });

        /* =========================================
           WEIGHT BUTTON SELECTOR
        ========================================= */
        document.querySelectorAll('.weight-btn-group').forEach(group => {
            const buttons       = group.querySelectorAll('.weight-btn');
            const foodItem      = group.closest('.food-item');
            const priceEl       = foodItem.querySelector('.price-value');
            const oldPriceWrap  = foodItem.querySelector('.old-price');
            const oldPriceEl    = foodItem.querySelector('.old-price-value');
            const badge          = foodItem.querySelector('.discount-badge');

            buttons.forEach(btn => {
                btn.addEventListener('click', function () {
                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const price    = parseFloat(this.dataset.price) || 0;
                    const oldPrice = parseFloat(this.dataset.oldPrice) || 0;

                    foodItem.dataset.price = price;
                    priceEl.textContent = Math.round(price).toLocaleString();

                    if (oldPrice > price) {
                        oldPriceEl.textContent = Math.round(oldPrice).toLocaleString();
                        oldPriceWrap.style.display = '';
                        if (badge) badge.style.display = '';
                    } else {
                        oldPriceWrap.style.display = 'none';
                        if (badge) badge.style.display = 'none';
                    }
                });
            });
        });

    });

    function handleAddToCart(button) {
        const foodItem = button.closest('.food-item');
        const baseName = button.dataset.baseName;
        const image    = button.dataset.image;
        const price    = parseFloat(foodItem.dataset.price) || 0;

        const activeWeightBtn = foodItem.querySelector('.weight-btn.active');

        let selectedWeight = activeWeightBtn ? activeWeightBtn.dataset.weight : '';

        // Combine base name with weight context if it exists
        const finalName = selectedWeight ? `${baseName} (${selectedWeight})` : baseName;

        if (typeof addToCart === 'function') {
            addToCart(finalName, price, image);
        } else {
            console.error('addToCart function is not defined globally.');
        }
    }
</script>
@endsection
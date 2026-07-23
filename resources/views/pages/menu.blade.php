@extends('layouts.master')
@section('title', 'Menu')


<!-- =========================================
  Banner section here
========================================= -->
@section('content')
@include('components.banner', [
    'miniTitle' => 'Menu',
    'title' => 'Delicious Food',
    'highlight' => 'Collection',
    'description' => 'Explore premium dishes, authentic flavors, and unforgettable catering experiences.'
])

<!-- =========================================
     MAIN MENU SECTION
========================================= -->

<section class="menu-section">

    <div class="container">

        <div class="row">

            <!-- =========================================
                 LEFT CATEGORY SIDEBAR
            ========================================= -->

            <div class="col-xl-3 col-lg-4 mb-4">

                <div class="category-sidebar">

                    <h4 class="sidebar-title">

                        Categories

                    </h4>

                    <button class="category-btn active" data-category="all">

                        All Menu

                    </button>

                    <button class="category-btn" data-category="main">

                        Main Course

                    </button>

                    <button class="category-btn" data-category="chinese">

                        Chinese

                    </button>

                    <button class="category-btn" data-category="bbq">

                        BBQ

                    </button>

                    <button class="category-btn" data-category="dal">

                        Dal / Sabzi

                    </button>

                    <button class="category-btn" data-category="dessert">

                        Desserts

                    </button>

                </div>

            </div>

            <!-- =========================================
                 RIGHT FOOD AREA
            ========================================= -->

            <div class="col-xl-9 col-lg-8">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                    <div>

                        <h2 class="menu-heading">

                            Food Menu

                        </h2>

                    </div>

                    <select class="form-select menu-sort">

                        <option value="default">

                            Sort By

                        </option>

                        <option value="low">

                            Price Low To High

                        </option>

                        <option value="high">

                            Price High To Low

                        </option>

                    </select>

                </div>

                <!-- FOOD GRID -->

                <div class="row g-4 food-grid">

                    <!-- ITEM 1 -->

                    <div class="col-xl-4 col-md-6 food-item" data-category="main" data-price="650">

                        <div class="food-card">

                            <div class="food-image-wrapper">

                                <img src="{{ asset('images/qorma.jpg') }}" class="food-image">

                            </div>

                            <div class="food-content">

                                <h5 class="food-title">

                                    Mutton Qorma

                                </h5>

                                <p class="food-description">

                                    Traditional desi qorma
                                    cooked with aromatic spices.

                                </p>

                                <div class="food-bottom">

                                    <div class="food-rating">

                                        ★★★★★

                                        <span>

                                            4.9

                                        </span>

                                    </div>

                                    <div class="food-price">

                                        PKR 650

                                    </div>

                                </div>

                                <button class="add-cart-btn" onclick="addToCart(
                                            'Mutton Qorma',
                                            650,
                                            '{{ asset('images/qorma.jpg') }}'
                                        )">

                                    Add To Cart

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- ITEM 2 -->

                    <div class="col-xl-4 col-md-6 food-item" data-category="main" data-price="500">

                        <div class="food-card">

                            <div class="food-image-wrapper">

                                <img src="{{ asset('images/briyani.jpg') }}" class="food-image">

                            </div>

                            <div class="food-content">

                                <h5 class="food-title">

                                    Chicken Biryani

                                </h5>

                                <p class="food-description">

                                    Premium biryani
                                    with authentic spices.

                                </p>

                                <div class="food-bottom">

                                    <div class="food-rating">

                                        ★★★★★

                                        <span>

                                            4.8

                                        </span>

                                    </div>

                                    <div class="food-price">

                                        PKR 500

                                    </div>

                                </div>

                                <button class="add-cart-btn" onclick="addToCart(
                                            'Chicken Biryani',
                                            500,
                                            '{{ asset('images/briyani.jpg') }}'
                                        )">

                                    Add To Cart

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- ITEM 3 -->

                    <div class="col-xl-4 col-md-6 food-item" data-category="chinese" data-price="550">

                        <div class="food-card">

                            <div class="food-image-wrapper">

                                <img src="{{ asset('images/chinese.jpg') }}" class="food-image">

                            </div>

                            <div class="food-content">

                                <h5 class="food-title">

                                    Dynamite Chicken

                                </h5>

                                <p class="food-description">

                                    Crispy chicken tossed
                                    in spicy creamy sauce.

                                </p>

                                <div class="food-bottom">

                                    <div class="food-rating">

                                        ★★★★★

                                        <span>

                                            4.7

                                        </span>

                                    </div>

                                    <div class="food-price">

                                        PKR 550

                                    </div>

                                </div>

                                <button class="add-cart-btn" onclick="addToCart(
                                            'Dynamite Chicken',
                                            550,
                                            '{{ asset('images/chinese.jpg') }}'
                                        )">

                                    Add To Cart

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- ITEM 4 -->

                    <div class="col-xl-4 col-md-6 food-item" data-category="bbq" data-price="750">

                        <div class="food-card">

                            <div class="food-image-wrapper">

                                <img src="{{ asset('images/boti.jpg') }}" class="food-image">

                            </div>

                            <div class="food-content">

                                <h5 class="food-title">

                                    Malai Boti

                                </h5>

                                <p class="food-description">

                                    Juicy chicken boti
                                    with creamy BBQ flavor.

                                </p>

                                <div class="food-bottom">

                                    <div class="food-rating">

                                        ★★★★★

                                        <span>

                                            4.9

                                        </span>

                                    </div>

                                    <div class="food-price">

                                        PKR 750

                                    </div>

                                </div>

                                <button class="add-cart-btn" onclick="addToCart(
                                            'Malai Boti',
                                            750,
                                            '{{ asset('images/boti.jpg') }}'
                                        )">

                                    Add To Cart

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- ITEM 5 -->

                    <div class="col-xl-4 col-md-6 food-item" data-category="dal" data-price="350">

                        <div class="food-card">

                            <div class="food-image-wrapper">

                                <img src="{{ asset('images/dal.jpg') }}" class="food-image">

                            </div>

                            <div class="food-content">

                                <h5 class="food-title">

                                    Dal Mash

                                </h5>

                                <p class="food-description">

                                    Creamy dal mash
                                    with butter and spices.

                                </p>

                                <div class="food-bottom">

                                    <div class="food-rating">

                                        ★★★★★

                                        <span>

                                            4.5

                                        </span>

                                    </div>

                                    <div class="food-price">

                                        PKR 350

                                    </div>

                                </div>

                                <button class="add-cart-btn" onclick="addToCart(
                                            'Dal Mash',
                                            350,
                                            '{{ asset('images/dal.jpg') }}'
                                        )">

                                    Add To Cart

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- ITEM 6 -->

                    <div class="col-xl-4 col-md-6 food-item" data-category="dessert" data-price="300">

                        <div class="food-card">

                            <div class="food-image-wrapper">

                                <img src="{{ asset('images/kheer.jpg') }}" class="food-image">

                            </div>

                            <div class="food-content">

                                <h5 class="food-title">

                                    Special Kheer

                                </h5>

                                <p class="food-description">

                                    Traditional sweet kheer
                                    with dry fruits.

                                </p>

                                <div class="food-bottom">

                                    <div class="food-rating">

                                        ★★★★★

                                        <span>

                                            4.6

                                        </span>

                                    </div>

                                    <div class="food-price">

                                        PKR 300

                                    </div>

                                </div>

                                <button class="add-cart-btn" onclick="addToCart(
                                            'Special Kheer',
                                            300,
                                            '{{ asset('images/kheer.jpg') }}'
                                        )">

                                    Add To Cart

                                </button>

                            </div>

                        </div>

                    </div>

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
        /* modern premium look */
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

    .food-1 {
        left: 5%;
        top: 90px;
    }

    .food-2 {
        right: 7%;
        top: 80px;
    }

    .food-3 {
        right: 10%;
        bottom: 50px;
    }

    @keyframes float {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-12px);
        }

        100% {
            transform: translateY(0);
        }
    }

    .menu-section {
        padding: 90px 0;
        background: #fff7fb;
    }

    .category-sidebar {
        background: #fff;
        border-radius: 30px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 110px;
    }

    .sidebar-title {
        font-weight: 800;
        margin-bottom: 25px;
    }

    .category-btn {
        width: 100%;
        border: none;
        background: #f5f5f5;
        padding: 15px;
        margin-bottom: 12px;
        border-radius: 16px;
        font-weight: 600;
        transition: 0.3s;
        text-align: left;
        cursor: pointer;
    }

    .category-btn.active {
        background: #ff2d7a;
        color: #fff;
    }

    .menu-heading {
        font-size: 42px;
        font-weight: 800;
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
    }

    .food-card:hover {
        transform: translateY(-10px);
    }

    .food-image-wrapper {
        overflow: hidden;
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

    .food-content {
        padding: 24px;
    }

    .food-title {
        font-size: 24px;
        font-weight: 700;
    }

    .food-description {
        color: #666;
        line-height: 1.8;
        margin-top: 12px;
    }

    .food-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 18px;
    }

    .food-rating {
        color: #ffb400;
    }

    .food-rating span {
        color: #777;
    }

    .food-price {
        color: #ff2d7a;
        font-weight: 800;
        font-size: 22px;
    }

    .add-cart-btn {
        width: 100%;
        margin-top: 22px;
        border: none;
        background: #ff2d7a;
        color: #fff;
        padding: 14px;
        border-radius: 16px;
        font-weight: 700;
        transition: 0.3s;
    }

    .add-cart-btn:hover {
        background: #111;
    }

    @media(max-width:991px) {
        .hero-title {
            font-size: 52px;
        }

        .category-sidebar {
            position: relative;
            top: 0;
        }
    }

    @media(max-width:767px) {
        .hero-title {
            font-size: 38px;
        }

        .hero-text {
            font-size: 15px;
            padding: 0 10px;
        }

        .floating-food {
            width: 70px;
        }

        .menu-heading {
            font-size: 32px;
        }

        .food-image {
            height: 220px;
        }
    }
</style>

<!-- =========================================
     FILTER + SORT SCRIPT
========================================= -->

<script>

    document.addEventListener('DOMContentLoaded', function () {

        /* =========================================
           GO BACK TO HOME ON REFRESH
        ========================================= */

        if (
            performance.navigation.type === 1
        ) {
            window.location.href = "/";
        }

        const categoryBtns =
            document.querySelectorAll('.category-btn');

        const foodItems =
            document.querySelectorAll('.food-item');

        const sortSelect =
            document.querySelector('.menu-sort');

        const foodGrid =
            document.querySelector('.food-grid');

        /* =========================================
           CATEGORY FILTER
        ========================================= */

        categoryBtns.forEach(button => {

            button.addEventListener('click', function () {

                categoryBtns.forEach(btn => {
                    btn.classList.remove('active');
                });

                this.classList.add('active');

                const category =
                    this.getAttribute('data-category');

                foodItems.forEach(item => {

                    if (category === 'all') {
                        item.style.display = 'block';
                    }
                    else {
                        if (
                            item.getAttribute('data-category')
                            === category
                        ) {
                            item.style.display = 'block';
                        }
                        else {
                            item.style.display = 'none';
                        }
                    }

                });

            });

        });

        /* =========================================
           SORT SYSTEM
        ========================================= */

        sortSelect.addEventListener('change', function () {

            const items =
                Array.from(
                    document.querySelectorAll('.food-item')
                );

            if (this.value === 'low') {

                items.sort((a, b) => {
                    return a.dataset.price - b.dataset.price;
                });

            }

            else if (this.value === 'high') {

                items.sort((a, b) => {
                    return b.dataset.price - a.dataset.price;
                });

            }

            else {
                window.location.reload();
            }

            items.forEach(item => {
                foodGrid.appendChild(item);
            });

        });

    });

</script>
@endsection
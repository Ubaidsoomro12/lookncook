<section class="py-5" style="background:#fff; overflow:hidden;">
    <div class="container-fluid px-2 px-sm-3 px-md-5">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 px-2 px-md-4">
            <div>
                <span class="fw-semibold" style="color:#ff2d7a; letter-spacing:1px; font-size:14px;">
                    FOOD CATEGORIES
                </span>
                <h2 class="fw-bold mt-2 mb-0 cuisine-section-heading" style="color:#111; line-height:1.1;">
                    Browse By <span style="color:#ff2d7a;">Cuisine</span>
                </h2>
            </div>

            <a href="{{ route('menu') }}" class="text-decoration-none fw-semibold mt-2 mt-sm-0"
                style="color:#ff2d7a; font-size:15px;">
                View All →
            </a>
        </div>

        <div class="position-relative cuisine-main-wrapper px-4 px-sm-5">

            <button class="btn cuisine-arrow cuisine-left-arrow scroll-left-btn">
                ‹
            </button>

            <button class="btn cuisine-arrow cuisine-right-arrow scroll-right-btn">
                ›
            </button>

            <div class="cuisine-hidden-area">
                <div class="d-flex align-items-start justify-content-start cuisine-slider-track">

                    <a href="{{ route('menu') }}?category=main&item=briyani"
                        class="text-decoration-none text-dark cuisine-slider-link">

                        <div class="cuisine-item text-center">

                            <!-- CIRCLE ICON AREA -->
                            <div class="cuisine-image d-flex align-items-center justify-content-center"
                                style="background:#f955ad;">

                                <i class="fa-solid fa-location-dot text-white" style="font-size: 50px;"></i>

                            </div>

                            <!-- TITLE -->
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1" style="color:#f955ad;">
                                Karachi
                            </h6>

                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=main&item=briyani"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/briyani.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Biryani">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Biryani</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=bbq&item=malai-boti"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/bbq.jpg') }}" class="w-100 h-100 object-fit-cover" alt="BBQ">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">BBQ</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=dessert&item=special-kheer"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/desert.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Dessert">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Dessert</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=chinese&item=dynamite-chicken"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/chinese.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Chinese">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Chinese</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=fastfood&item=pizza"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/pizza.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Pizza">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Pizza</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=main&item=karahi"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/karahi.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Karahi">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Karahi</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=fastfood&item=burger"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/fastfood.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Fast Food">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Fast Food</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=main&item=beef"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/beef.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Beef">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Beef</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=main&item=roti"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/roti.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Roti">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Roti</h6>
                        </div>
                    </a>

                    <a href="{{ route('menu') }}?category=main&item=chicken"
                        class="text-decoration-none text-dark cuisine-slider-link">
                        <div class="cuisine-item text-center">
                            <div class="cuisine-image">
                                <img src="{{ asset('images/chicken.jpg') }}" class="w-100 h-100 object-fit-cover"
                                    alt="Chicken">
                            </div>
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1">Chicken</h6>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .cuisine-main-wrapper {
        position: relative;
        width: 100%;
    }

    .cuisine-hidden-area {
        overflow: hidden;
        width: 100%;
    }

    .cuisine-slider-track {
        transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
        width: 100%;
    }

    /* Base Link Wrapper sizing */
    .cuisine-slider-link {
        width: 12.5%;
        /* Default 8 items visible on large screens */
        flex-shrink: 0;
        display: block;
    }

    .cuisine-item {
        padding: 10px 5px;
        transition: 0.3s ease;
        cursor: pointer;
    }

    /* Image Wrapper staying structural */
    .cuisine-image {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
        position: relative;
    }

    .cuisine-image::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.12));
        pointer-events: none;
    }

    .cuisine-image img {
        transition: transform 0.4s ease;
    }

    /* Internal Image zooms, main circle stays completely anchored */
    .cuisine-slider-link:hover .cuisine-image {
        box-shadow: 0 8px 24px rgba(255, 45, 122, 0.25);
        border-color: rgba(255, 45, 122, 0.1);
    }

    .cuisine-slider-link:hover img {
        transform: scale(1.15);
    }

    .cuisine-item h6 {
        transition: color 0.3s ease;
        font-size: 15px;
        color: #333;
    }

    .cuisine-slider-link:hover h6 {
        color: #ff2d7a;
    }

    /* Nav Arrows persistent properties across all devices */
    .cuisine-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        border: none !important;
        background: #fff !important;
        font-size: 32px;
        width: 40px;
        height: 40px;
        line-height: 36px;
        text-align: center;
        font-weight: bold;
        color: #ff2d7a !important;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.25s ease;
        padding: 0;
    }

    .cuisine-arrow:hover {
        background: #ff2d7a !important;
        color: #fff !important;
        transform: translateY(-50%) scale(1.1);
    }

    .cuisine-left-arrow {
        left: 0px;
    }

    .cuisine-right-arrow {
        right: 0px;
    }

    /* Dynamic Responsive Layout Rules */
    .cuisine-section-heading {
        font-size: 48px;
    }

    @media(max-width: 1400px) {
        .cuisine-slider-link {
            width: 14.28%;
        }

        /* 7 Items visible */
    }

    @media(max-width: 1200px) {
        .cuisine-slider-link {
            width: 16.66%;
        }

        /* 6 Items visible */
        .cuisine-image {
            width: 110px;
            height: 110px;
        }
    }

    @media(max-width: 992px) {
        .cuisine-slider-link {
            width: 20%;
        }

        /* 5 Items visible */
        .cuisine-section-heading {
            font-size: 38px;
        }
    }

    @media(max-width: 768px) {
        .cuisine-slider-link {
            width: 25%;
        }

        /* 4 Items visible */
        .cuisine-image {
            width: 95px;
            height: 95px;
        }

        .cuisine-arrow {
            font-size: 28px;
            width: 36px;
            height: 36px;
            line-height: 32px;
        }
    }

    @media(max-width: 576px) {
        .cuisine-slider-link {
            width: 33.33%;
        }

        /* 3 Items visible */
        .cuisine-section-heading {
            font-size: 32px;
        }
    }

    @media(max-width: 380px) {
        .cuisine-slider-link {
            width: 50%;
        }

        /* 2 Items visible */
        .cuisine-image {
            width: 85px;
            height: 85px;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Target wrappers to let multiple instances coexist perfectly without ID conflicts
        const wrappers = document.querySelectorAll('.cuisine-main-wrapper');

        wrappers.forEach(wrapper => {
            const slider = wrapper.querySelector('.cuisine-slider-track');
            const links = wrapper.querySelectorAll('.cuisine-slider-link');
            const leftArrow = wrapper.querySelector('.scroll-left-btn');
            const rightArrow = wrapper.querySelector('.scroll-right-btn');

            if (!slider || !leftArrow || !rightArrow) return;

            let currentIndex = 0;

            function getItemsPerView() {
                const width = window.innerWidth;
                if (width <= 380) return 2;
                if (width <= 576) return 3;
                if (width <= 768) return 4;
                if (width <= 992) return 5;
                if (width <= 1200) return 6;
                if (width <= 1400) return 7;
                return 8;
            }

            function updateSliderPosition() {
                if (links.length === 0) return;

                const itemsPerView = getItemsPerView();
                const maxIndex = Math.max(0, links.length - itemsPerView);

                if (currentIndex > maxIndex) {
                    currentIndex = maxIndex;
                }

                const singleItemWidth = links[0].getBoundingClientRect().width;
                slider.style.transform = `translateX(-${currentIndex * singleItemWidth}px)`;

                // Handle arrow look states smoothly
                leftArrow.style.opacity = currentIndex === 0 ? "0.3" : "1";
                leftArrow.style.pointerEvents = currentIndex === 0 ? "none" : "auto";

                rightArrow.style.opacity = currentIndex === maxIndex ? "0.3" : "1";
                rightArrow.style.pointerEvents = currentIndex === maxIndex ? "none" : "auto";
            }

            rightArrow.addEventListener('click', (e) => {
                e.preventDefault();
                const itemsPerView = getItemsPerView();
                const maxIndex = Math.max(0, links.length - itemsPerView);
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSliderPosition();
                }
            });

            leftArrow.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSliderPosition();
                }
            });

            window.addEventListener('resize', updateSliderPosition);
            updateSliderPosition();
        });
    });
</script>
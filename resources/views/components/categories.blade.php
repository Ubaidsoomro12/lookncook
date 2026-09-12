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

                    {{-- ===== STATIC KARACHI CATEGORY (HARDCODED) ===== --}}
                    <a href="{{ route('menu') }}?category=karachi"
                        class="text-decoration-none text-dark cuisine-slider-link">

                        <div class="cuisine-item text-center">

                            <!-- CIRCLE ICON AREA -->
                            <div class="cuisine-image d-flex align-items-center justify-content-center"
                                style="background:#f955ad;">

                                <i class="fa-solid fa-location-dot text-white" style="font-size: 50px;"></i>

                            </div>

                            <!-- TITLE -->
                            <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1" style="color:#111111;">
                                Karachi
                            </h6>

                        </div>
                    </a>

                    {{-- ===== DYNAMIC CATEGORIES FROM DATABASE ===== --}}
                    @foreach($categories as $category)
                        {{-- Skip if category name is Karachi (to avoid duplicate) --}}
                        @if($category->name != 'Karachi')
                        <a href="{{ route('menu') }}?category={{ $category->slug }}"
                            class="text-decoration-none text-dark cuisine-slider-link">

                            <div class="cuisine-item text-center">

                                <!-- CIRCLE ICON AREA -->
                                <div class="cuisine-image d-flex align-items-center justify-content-center"
                                    style="background:#f955ad;">

                                    @if($category->icon)
                                        <i class="{{ $category->icon }} text-white" style="font-size: 50px;"></i>
                                    @elseif($category->image)
                                        <img src="{{ asset($category->image) }}" class="w-100 h-100 object-fit-cover"
                                            alt="{{ $category->name }}">
                                    @else
                                        <i class="fa-solid fa-utensils text-white" style="font-size: 50px;"></i>
                                    @endif

                                </div>

                                <!-- TITLE -->
                                <h6 class="fw-semibold mt-3 mb-0 text-truncate px-1" style="color:#111111;">
                                {{ $category->name }}
                                </h6>

                            </div>
                        </a>
                        @endif
                    @endforeach

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

    .cuisine-slider-link {
        width: 12.5%;
        flex-shrink: 0;
        display: block;
    }

    .cuisine-item {
        padding: 10px 5px;
        transition: 0.3s ease;
        cursor: pointer;
    }

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
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

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

    .cuisine-section-heading {
        font-size: 48px;
    }

    @media(max-width: 1400px) {
        .cuisine-slider-link {
            width: 14.28%;
        }
    }

    @media(max-width: 1200px) {
        .cuisine-slider-link {
            width: 16.66%;
        }
        .cuisine-image {
            width: 110px;
            height: 110px;
        }
    }

    @media(max-width: 992px) {
        .cuisine-slider-link {
            width: 20%;
        }
        .cuisine-section-heading {
            font-size: 38px;
        }
    }

    @media(max-width: 768px) {
        .cuisine-slider-link {
            width: 25%;
        }
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
        .cuisine-section-heading {
            font-size: 32px;
        }
    }

    @media(max-width: 380px) {
        .cuisine-slider-link {
            width: 50%;
        }
        .cuisine-image {
            width: 85px;
            height: 85px;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
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
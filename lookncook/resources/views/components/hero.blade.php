@props(['banners' => collect()])

<section class="hero-section">

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">

        <!-- INDICATORS (Dynamic) -->
        <div class="carousel-indicators">
            @if($banners->where('section', 'hero')->count() > 0)
                @foreach($banners->where('section', 'hero') as $index => $banner)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            @else
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            @endif
        </div>

        <!-- CAROUSEL INNER -->
        <div class="carousel-inner">

            @if($banners->where('section', 'hero')->count() > 0)
                @foreach($banners->where('section', 'hero') as $index => $banner)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image_url }}" class="hero-image" alt="{{ $banner->title ?? 'Hero Banner' }}">
                        <div class="hero-overlay"></div>
                        <div class="hero-content-wrapper">
                            <div class="hero-content">
                                @if($banner->subtitle)<span class="hero-subtitle">{{ $banner->subtitle }}</span>@endif
                                @if($banner->title)<h1 class="hero-title">{!! nl2br($banner->title) !!}</h1>@endif
                                @if($banner->description)<p class="hero-description">{{ $banner->description }}</p>@endif
                                @if($banner->link)
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ $banner->link }}" class="hero-btn">{{ $banner->button_text ?? 'Learn More' }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <img src="{{ asset('images/logo.jpg') }}" class="hero-image" alt="Luxury Catering">
                    <div class="hero-overlay"></div>
                    <div class="hero-content-wrapper">
                        <div class="hero-content">
                            <span class="hero-subtitle">Best In Town</span>
                            <h1 class="hero-title">Our Menu At <span style="color: #e6007e;">LOOK N COOK</span> Offers Fresh Ingredients And Comforting Food</h1>
                            <p class="hero-description">Birthdays, Anniversaries, Cocktail Parties, Get Togethers, Special Occasions.</p>
                            <div class="d-flex justify-content-center"><a href="{{ route('menu') }}" class="hero-btn">Order Now</a></div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/logo2.jpg') }}" class="hero-image" alt="Chef Cooking">
                    <div class="hero-overlay"></div>
                    <div class="hero-content-wrapper">
                        <div class="hero-content">
                            <span class="hero-subtitle">Finest Chefs</span>
                            <h1 class="hero-title">Finest Chefs For <span style="color: #e6007e;">House Parties</span> & Multi-Cuisine Experts</h1>
                            <p class="hero-description">Premium quality catering services with unforgettable delicious experiences.</p>
                            <div class="d-flex justify-content-center"><a href="{{ route('gallery') }}" class="hero-btn">Learn More</a></div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/logo3.jpg') }}" class="hero-image" alt="Restaurant Food">
                    <div class="hero-overlay"></div>
                    <div class="hero-content-wrapper">
                        <div class="hero-content">
                            <span class="hero-subtitle">Elegant Dining</span>
                            <h1 class="hero-title">Bringing Taste & Happiness To <span style="color: #e6007e;">Every Celebration</span></h1>
                            <p class="hero-description">From intimate dinners to grand events, enjoy premium catering with beautiful presentation and unforgettable taste.</p>
                            <div class="d-flex justify-content-center"><a href="{{ route('contact') }}" class="hero-btn">Book Now</a></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <button class="carousel-control-prev hero-arrow" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next hero-arrow" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- ✅ CLEAN INDICATOR CSS (Simple Circles, Moved Up) -->
<style>
    #heroCarousel .carousel-indicators {
        bottom: 40px !important; /* Moved up further inside the container */
        margin-bottom: 0 !important;
        z-index: 10 !important; 
    }

    #heroCarousel .carousel-indicators button {
        width: 10px !important;
        height: 10px !important;
        border-radius: 50% !important;
        background-color: rgba(255, 255, 255, 0.7) !important;
        border: 1px solid #fff !important;
        margin: 0 4px !important;
        opacity: 1 !important;
    }

    #heroCarousel .carousel-indicators .active {
        background-color: #ff2d7a !important;
        border-color: #ff2d7a !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = document.querySelector('#heroCarousel');
        if (myCarousel) {
            new bootstrap.Carousel(myCarousel, {
                interval: 6000,
                wrap: true,
                pause: false
            });
        }
    });
</script>
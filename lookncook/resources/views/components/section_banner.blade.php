@props(['banners' => collect()])

<section class="quality-section">
    @php $sectionBanners = $banners->where('section', 'section_banner')->values(); @endphp

    <div id="qualityCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000" data-bs-wrap="true" data-bs-pause="false">

        <!-- INDICATORS -->
        @if($sectionBanners->count() > 1)
            <div class="carousel-indicators quality-indicators">
                @foreach($sectionBanners as $index => $banner)
                    <button type="button" data-bs-target="#qualityCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner">
            @if($sectionBanners->count() > 0)
                @foreach($sectionBanners as $index => $banner)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image_url }}" class="quality-image" alt="{{ $banner->title ?? 'Quality Banner' }}">
                        <div class="quality-overlay"></div>
                        <div class="quality-content-wrapper">
                            <div class="quality-content">
                                @if($banner->subtitle)
                                    <span class="quality-subtitle">{!! $banner->subtitle !!}</span>
                                @endif
                                @if($banner->title)
                                    <h2 class="quality-title">{!! nl2br($banner->title) !!}</h2>
                                @endif
                                @if($banner->description)
                                    <p class="quality-description">{{ $banner->description }}</p>
                                @endif
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" class="quality-btn">{{ $banner->button_text ?? 'Learn More' }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- STATIC FALLBACK -->
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1920&auto=format&fit=crop" class="quality-image" alt="Quality">
                    <div class="quality-overlay"></div>
                    <div class="quality-content-wrapper">
                        <div class="quality-content">
                            <span class="quality-subtitle">Why Choose Us?</span>
                            <h2 class="quality-title">Best Quality <span class="quality-accent">Item</span> Ingredient</h2>
                            <p class="quality-description">We use only fresh ingredients, premium spices, and authentic recipes to create unforgettable catering experiences for every celebration.</p>
                            <a href="#" class="quality-btn">Order Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1920&auto=format&fit=crop" class="quality-image" alt="Catering">
                    <div class="quality-overlay"></div>
                    <div class="quality-content-wrapper">
                        <div class="quality-content">
                            <span class="quality-subtitle">Premium Catering</span>
                            <h2 class="quality-title">Delicious <span class="quality-accent">Food</span> For Every Event</h2>
                            <p class="quality-description">We make every celebration special, from intimate dinners to grand events.</p>
                            <a href="#" class="quality-btn">Explore Menu</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
    /* Fully self-contained — no dependency on hero.blade.php or any external stylesheet */
    .quality-section { position: relative !important; overflow: hidden !important; height: 500px !important; }
    #qualityCarousel, #qualityCarousel .carousel-inner, #qualityCarousel .carousel-item { height: 100% !important; }

    .quality-image {
        display: block !important;
        width: 100% !important;
        height: 100% !important;
        max-width: none !important;
        max-height: none !important;
        object-fit: cover !important;
        object-position: center center !important;
    }

    .quality-overlay {
        position: absolute !important;
        inset: 0 !important;
        background: rgba(255,255,255,0.55) !important;
        z-index: 1 !important;
    }

    /* ⭐ FIXED: Made text sit higher (align-items: flex-start + padding-top) */
    .quality-content-wrapper {
        position: absolute !important;
        inset: 0 !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        display: flex !important;
        align-items: flex-start !important;  /* Align to top */
        justify-content: center !important;
        z-index: 2 !important;
        padding: 0 20px !important;
        padding-top: 80px !important;  /* Adjust this to make text higher/lower */
    }

    .quality-content { text-align: center; max-width: 780px; margin: 0 auto; }
    .quality-subtitle { display: block; font-size: 18px; font-style: italic; font-weight: 600; color: #ff2d7a; letter-spacing: 1px; margin-bottom: 10px; }
    .quality-title { font-size: 52px; font-weight: 800; line-height: 1.15; color: #1d3557; text-transform: uppercase; margin-bottom: 18px; }
    .quality-accent { color: #ff2d7a; }
    .quality-description { font-size: 18px; line-height: 1.7; color: #333; font-weight: 500; margin-bottom: 20px; }
    .quality-btn { background: #ff2d7a; color: #fff; padding: 12px 32px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-block; }
    .quality-btn:hover { background: #e6007e; color: #fff; }

    .quality-indicators { bottom: 20px !important; margin-bottom: 0 !important; z-index: 3; }
    .quality-indicators button { width: 10px !important; height: 10px !important; border-radius: 50% !important; background-color: rgba(0,0,0,0.25) !important; border: none !important; margin: 0 4px !important; opacity: 1 !important; }
    .quality-indicators .active { background-color: #ff2d7a !important; }

    @media(max-width: 992px) {
        .quality-section, #qualityCarousel, #qualityCarousel .carousel-inner, #qualityCarousel .carousel-item { height: 420px !important; }
        .quality-title { font-size: 36px; }
        .quality-description { font-size: 16px; }
        .quality-content-wrapper { padding-top: 60px !important; }
    }
    @media(max-width: 768px) {
        .quality-section, #qualityCarousel, #qualityCarousel .carousel-inner, #qualityCarousel .carousel-item { height: 360px !important; }
        .quality-title { font-size: 26px; }
        .quality-description { font-size: 14px; }
        .quality-subtitle { font-size: 15px; }
        .quality-btn { padding: 10px 24px; font-size: 14px; }
        .quality-content-wrapper { padding-top: 40px !important; }
    }
</style>
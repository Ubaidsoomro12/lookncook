<section class="hero-section">

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">

        <!-- INDICATORS -->

        <div class="carousel-indicators">

            <!-- SLIDE 1 -->

            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1">
            </button>

            <!-- SLIDE 2 -->

            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2">
            </button>

            <!-- SLIDE 3 -->

            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3">
            </button>

        </div>

        <!-- CAROUSEL INNER -->

        <div class="carousel-inner">

            <!-- =========================
                 SLIDE 1
            ========================= -->

            <div class="carousel-item active">

                <!-- IMAGE -->

                <img src="{{ asset('images/logo.jpg') }}" class="hero-image" alt="Luxury Catering">

                <!-- OVERLAY -->

                <div class="hero-overlay"></div>

                <!-- CONTENT -->

                <div class="hero-content-wrapper">

                    <div class="hero-content">

                        <!-- SUBTITLE -->

                        <span class="hero-subtitle">

                            Best In Town

                        </span>

                        <!-- TITLE -->

                        <h1 class="hero-title">

                            Our Menu At

                            <span style="color: #e6007e;">

                                LOOK N COOK

                            </span>

                            Offers Fresh Ingredients
                            And Comforting Food

                        </h1>

                        <!-- DESCRIPTION -->

                        <p class="hero-description">

                            Birthdays, Anniversaries,
                            Cocktail Parties, Get Togethers,
                            Special Occasions.

                        </p>

                        <!-- BUTTON -->

                        <div class="d-flex justify-content-center">

                            <a href="{{ route('menu') }}" class="hero-btn">

                                Order Now

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <!-- =========================
                 SLIDE 2
            ========================= -->

            <div class="carousel-item">

                <!-- IMAGE -->

                <img src="{{ asset('images/logo2.jpg') }}" class="hero-image" alt="Chef Cooking">

                <!-- OVERLAY -->

                <div class="hero-overlay"></div>

                <!-- CONTENT -->

                <div class="hero-content-wrapper">

                    <div class="hero-content">

                        <!-- SUBTITLE -->

                        <span class="hero-subtitle">

                            Finest Chefs

                        </span>

                        <!-- TITLE -->

                        <h1 class="hero-title">

                            Finest Chefs For
                            <span style="color: #e6007e;">

                                House
                                Parties

                            </span> & Multi-Cuisine Experts

                        </h1>

                        <!-- DESCRIPTION -->

                        <p class="hero-description">

                            Premium quality catering services
                            with unforgettable delicious experiences.

                        </p>

                        <!-- BUTTON -->

                        <div class="d-flex justify-content-center">

                            <a  href="{{ route('gallery') }}" class="hero-btn">

                                Learn More

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <!-- =========================
                 SLIDE 3
            ========================= -->

            <div class="carousel-item">

                <!-- IMAGE -->

                <img src="{{ asset('images/logo3.jpg') }}" class="hero-image" alt="Restaurant Food">

                <!-- OVERLAY -->

                <div class="hero-overlay"></div>

                <!-- CONTENT -->

                <div class="hero-content-wrapper">

                    <div class="hero-content">

                        <!-- SUBTITLE -->

                        <span class="hero-subtitle">

                            Elegant Dining

                        </span>

                        <!-- TITLE -->

                        <h1 class="hero-title">

                            Bringing Taste &
                            Happiness To

                            <span style="color: #e6007e;">

                                Every Celebration

                            </span>

                        </h1>

                        <!-- DESCRIPTION -->

                        <p class="hero-description">

                            From intimate dinners to grand events,
                            enjoy premium catering with beautiful
                            presentation and unforgettable taste.

                        </p>

                        <!-- BUTTON -->

                        <div class="d-flex justify-content-center">

                            <a href="{{ route('contact') }}" class="hero-btn">

                                Book Now

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- =========================
             PREVIOUS BUTTON
        ========================= -->

        <button class="carousel-control-prev hero-arrow" type="button" data-bs-target="#heroCarousel"
            data-bs-slide="prev">

            <span class="carousel-control-prev-icon"></span>

        </button>

        <!-- =========================
             NEXT BUTTON
        ========================= -->

        <button class="carousel-control-next hero-arrow" type="button" data-bs-target="#heroCarousel"
            data-bs-slide="next">

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

</section>
<style>
    #heroCarousel .carousel-indicators {
        margin-bottom: 0 !important;
    }
</style>
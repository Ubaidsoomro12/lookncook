<section class="position-relative overflow-hidden quality-carousel-section" style="margin-bottom: 0 !important; padding-bottom: 0 !important;">

    <style>

        /* =========================================
           IMAGE ANIMATION
        ========================================= */

        .quality-image{
            transform:scale(1);
            transition:transform 7s ease;
        }

        .carousel-item.active .quality-image{
            transform:scale(1.08);
        }

        /* =========================================
           GLOBAL DESKTOP STYLES
        ========================================= */
        
        .quality-carousel-section {
            height: 550px !important;
        }

        .quality-slide {
            height: 550px !important;
            padding: 40px 0 !important;
        }

        .quality-slide .row {
            height: 100% !important;
        }

        .quality-title {
            font-size: 68px !important;
            line-height: 1.1 !important;
            color: #1d3557;
            text-transform: uppercase;
        }

        /* =========================================
           RESPONSIVE FIX FOR LAPTOPS & TABLETS
        ========================================= */

        @media(max-width:992px){

            .quality-carousel-section {
                height: 480px !important;
            }

            .quality-title{
                font-size:44px !important;
            }
            
            .quality-slide {
                height: 480px !important;
            }

        }

        /* =========================================
           RESPONSIVE FIX FOR MOBILES
        ========================================= */

        @media(max-width:768px){

            .quality-carousel-section {
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                margin-bottom: 0 !important;
            }

            .quality-title{
                font-size: 28px !important;
                line-height: 1.2 !important;
            }

            .quality-description{
                font-size: 15px !important;
                line-height: 1.6 !important;
            }

            .quality-slide{
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                padding: 60px 0 !important;
            }

            .quality-slide .row {
                height: auto !important;
                min-height: auto !important;
            }

        }

        /* =========================================
           RESPONSIVE FIX FOR SMALL MOBILES
        ========================================= */

        @media(max-width:576px){

            .quality-title{
                font-size: 24px !important;
            }

            .quality-description{
                font-size: 14px !important;
            }

            .quality-slide{
                padding: 50px 0 !important;
            }

        }

    </style>

    <div id="qualityCarousel"
         class="carousel slide carousel-fade h-100"
         data-bs-ride="carousel"
         data-bs-interval="2500"
         style="height: 100% !important;">

        <div class="carousel-inner h-100" style="height: 100% !important;">

            <!-- =========================================
                 SLIDE 1
            ========================================= -->

            <div class="carousel-item active h-100" style="height: 100% !important;">

                <section class="quality-slide position-relative overflow-hidden">

                    <img src="{{ asset('images/logo4.jpg') }}"
                         alt="Background"
                         class="quality-image position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                         style="
                            z-index:1;
                            filter:brightness(100%);
                            height:100% !important;
                         ">

                    <!-- LIGHT OVERLAY -->

                    <div class="position-absolute top-0 start-0 w-100 h-100"
                         style="
                            background:rgba(255,255,255,0.30);
                            z-index:2;
                         ">
                    </div>

                    <div class="container position-relative z-3">

                        <div class="row justify-content-center align-items-center text-center">

                            <div class="col-xl-8 col-lg-10">

                                <span class="fw-semibold"
                                      style="
                                        color:#ff2d7a;
                                        letter-spacing:1px;
                                        font-size:18px;
                                        font-style:italic;
                                      ">

                                    Why Choose Us?

                                </span>

                                <h2 class="quality-title fw-bold mt-3 mb-4">

                                    Best Quality Item

                                    <br>

                                    <span style="color:#ff2d7a;">

                                        Ingredient

                                    </span>

                                </h2>

                                <p class="quality-description mx-auto"
                                   style="
                                        max-width:760px;
                                        color:#222;
                                        font-size:19px;
                                        line-height:1.9;
                                        font-weight:500;
                                   ">

                                    We use only fresh ingredients,
                                    premium spices, and authentic recipes
                                    to create unforgettable catering experiences
                                    for every celebration.

                                </p>

                                <a href="#"
                                   class="hero-btn mt-4 px-5 py-3 d-inline-block">

                                    Order Now

                                </a>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

            <!-- =========================================
                 SLIDE 2
            ========================================= -->

            <div class="carousel-item h-100" style="height: 100% !important;">

                <section class="quality-slide position-relative overflow-hidden">

                    <img src="{{ asset('images/logo5.jpg') }}"
                         alt="Background"
                         class="quality-image position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                         style="
                            z-index:1;
                            filter:brightness(100%);
                            height:100% !important;
                         ">

                    <!-- LIGHT OVERLAY -->

                    <div class="position-absolute top-0 start-0 w-100 h-100"
                         style="
                            background:rgba(255,255,255,0.30);
                            z-index:2;
                         ">
                    </div>

                    <div class="container position-relative z-3">

                        <div class="row justify-content-center align-items-center text-center">

                            <div class="col-xl-8 col-lg-10">

                                <span class="fw-semibold"
                                      style="
                                        color:#ff2d7a;
                                        letter-spacing:1px;
                                        font-size:18px;
                                        font-style:italic;
                                      ">

                                    Premium Catering

                                </span>

                                <h2 class="quality-title fw-bold mt-3 mb-4">

                                    Delicious Food

                                    <br>

                                    <span style="color:#ff2d7a;">

                                        For Every Event

                                    </span>

                                </h2>

                                <p class="quality-description mx-auto"
                                   style="
                                        max-width:760px;
                                        color:#222;
                                        font-size:19px;
                                        line-height:1.9;
                                        font-weight:500;
                                   ">

                                    Weddings, birthdays,
                                    corporate events and celebrations
                                    made special with premium taste
                                    and elegant presentation.

                                </p>

                                <a href="#"
                                   class="hero-btn mt-4 px-5 py-3 d-inline-block">

                                    Explore Menu

                                </a>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

            <!-- =========================================
                 SLIDE 3
            ========================================= -->

            <div class="carousel-item h-100" style="height: 100% !important;">

                <section class="quality-slide position-relative overflow-hidden">

                    <img src="{{ asset('images/logo6.jpg') }}"
                         alt="Background"
                         class="quality-image position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                         style="
                            z-index:1;
                            filter:brightness(100%);
                            height:100% !important;
                         ">

                    <!-- LIGHT OVERLAY -->

                    <div class="position-absolute top-0 start-0 w-100 h-100"
                         style="
                            background:rgba(255,255,255,0.30);
                            z-index:2;
                         ">
                    </div>

                    <div class="container position-relative z-3">

                        <div class="row justify-content-center align-items-center text-center">

                            <div class="col-xl-8 col-lg-10">

                                <span class="fw-semibold"
                                      style="
                                        color:#ff2d7a;
                                        letter-spacing:1px;
                                        font-size:18px;
                                        font-style:italic;
                                      ">

                                    Authentic Taste

                                </span>

                                <h2 class="quality-title fw-bold mt-3 mb-4">

                                    Bhopali Taste

                                    <br>

                                    <span style="color:#ff2d7a;">

                                        In Your Town

                                    </span>

                                </h2>

                                <p class="quality-description mx-auto"
                                   style="
                                        max-width:760px;
                                        color:#222;
                                        font-size:19px;
                                        line-height:1.9;
                                        font-weight:500;
                                   ">

                                    Enjoy restaurant-quality meals
                                    with unforgettable flavors,
                                    authentic recipes,
                                    and luxury catering services.

                                </p>

                                <a href="#"
                                   class="hero-btn mt-4 px-5 py-3 d-inline-block">

                                    Book Now

                                </a>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

        </div>

    </div>

</section>
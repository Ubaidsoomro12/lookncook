<!-- =========================================
     ABOUT US SECTION
========================================= -->

<section class="py-5"
         style="
            background:#fff7fb;
         ">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- =========================================
                 LEFT CONTENT
            ========================================= -->

            <div class="col-lg-6">

                <!-- SUBTITLE -->

                <span class="fw-semibold"
                      style="
                        color:#ff2d7a;
                        letter-spacing:1px;
                        font-size:15px;
                      ">

                    ABOUT US

                </span>

                <!-- TITLE -->

                <h2 class="fw-bold mt-2 mb-3"
                    style="
                        font-size:48px;
                        line-height:1.2;
                        color:#111;
                    ">

                    Look N Cook Home Chef
                    Catering Services

                </h2>

                <!-- SMALL HEADING -->

                <h5 class="mb-4"
                    style="
                        color:#ff2d7a;
                        font-weight:600;
                    ">

                    More Than Just Food

                </h5>

                <!-- PARAGRAPH -->

                <p class="text-secondary"
                   style="
                        line-height:1.9;
                        font-size:16px;
                   ">

                    Welcome to Look N Cook Home Chef.
                    We bring delicious flavors,
                    premium catering services,
                    and unforgettable dining experiences
                    for weddings, birthdays,
                    corporate events, and special occasions.

                </p>

                <!-- PARAGRAPH -->

                <p class="text-secondary"
                   style="
                        line-height:1.9;
                        font-size:16px;
                   ">

                    Our chefs prepare fresh meals
                    with passion and creativity,
                    making every event memorable
                    with luxury presentation
                    and exceptional taste.

                </p>

                <!-- BUTTON -->

                <a href="#"
                   class="hero-btn mt-3">

                    Our Story

                </a>

            </div>

            <!-- =========================================
                 RIGHT IMAGES
            ========================================= -->

            <div class="col-lg-6">

                <div class="row g-3">

                    <!-- BIG IMAGE -->

                    <div class="col-7">

                        <div class="overflow-hidden rounded-4 shadow-lg h-100 about-image-box">

                            <img src="{{ asset('images/about1.jpg') }}"
                                 class="w-100 h-100 object-fit-cover about-image"
                                 style="
                                    min-height:420px;
                                 "
                                 alt="Food">

                        </div>

                    </div>

                    <!-- SMALL IMAGES -->

                    <div class="col-5">

                        <div class="d-flex flex-column gap-3 h-100">

                            <!-- TOP IMAGE -->

                            <div class="overflow-hidden rounded-4 shadow-lg about-image-box">

                                <img src="{{ asset('images/about2.jpg') }}"
                                     class="w-100 object-fit-cover about-image"
                                     style="
                                        height:200px;
                                     "
                                     alt="Dining">

                            </div>

                            <!-- BOTTOM IMAGE -->

                            <div class="overflow-hidden rounded-4 shadow-lg about-image-box">

                                <img src="{{ asset('images/about3.jpg') }}"
                                     class="w-100 object-fit-cover about-image"
                                     style="
                                        height:200px;
                                     "
                                     alt="Cuisine">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- =========================================
     INTERNAL STYLING
========================================= -->

<style>

.about-image-box{
    transition:0.5s;
}

.about-image{
    transition:0.6s;
}

.about-image-box:hover .about-image{
    transform:scale(1.08);
}

.about-image-box:hover{
    transform:translateY(-6px);
}

@media(max-width:992px){

    .about-image{
        height:100% !important;
    }

}

@media(max-width:768px){

    .about-image{
        min-height:auto !important;
    }

}

@media(max-width:576px){

    .about-image{
        height:180px !important;
    }

}

</style>
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

                <!-- LEFT WALI BADI IMAGE + RIGHT WALI CHHOTI IMAGES -->
                <div class="d-flex gap-3">

                    <!-- LEFT: BADI IMAGE (Full dikhegi) -->
                    <div class="about-big-box">
                        <img src="{{ asset('images/about1.jpg') }}"
                             class="w-100 h-100 about-image"
                             alt="Food">
                    </div>

                    <!-- RIGHT: 2 CHHOTI IMAGES (Ek ke upar ek) -->
                    <div class="d-flex flex-column gap-3 about-small-col">
                        
                        <div class="about-small-box">
                            <img src="{{ asset('images/about2.jpg') }}"
                                 class="w-100 h-100 about-image"
                                 alt="Dining">
                        </div>

                        <div class="about-small-box">
                            <img src="{{ asset('images/about3.jpg') }}"
                                 class="w-100 h-100 about-image"
                                 alt="Cuisine">
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

/* SABSE IMPORTANT: Images ko box ke andar perfect fit karega */
.about-image{
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition:0.6s;
}

.about-big-box, .about-small-box{
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition:0.5s;
}

.about-big-box:hover, .about-small-box:hover{
    transform:translateY(-6px);
}

/* Desktop ke liye heights */
@media(min-width: 993px){
    .about-big-box {
        width: 60%; /* Badi image ki width */
        height: 420px; 
    }
    .about-small-col {
        width: 40%; /* Chhoti images ki width */
    }
    .about-small-box {
        height: 200px; /* 200px + 200px + 20px gap = 420px */
    }
}

/* Tablet ke liye */
@media(max-width: 992px){
    .about-big-box {
        width: 55%;
        height: 300px;
    }
    .about-small-col {
        width: 45%;
    }
    .about-small-box {
        height: 140px;
    }
}

/* Mobile (Chhoti screen) */
@media(max-width: 576px){
    .d-flex.gap-3 {
        flex-direction: column; /* Mobile pe ek ke neeche ek */
    }
    .about-big-box {
        width: 100%;
        height: 250px;
    }
    .about-small-col {
        width: 100%;
        flex-direction: row; /* Chhoti images side-by-side */
    }
    .about-small-box {
        width: 50%;
        height: 150px;
    }
}

</style>
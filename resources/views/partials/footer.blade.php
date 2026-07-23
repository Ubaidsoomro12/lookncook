<!-- =========================================
     FOOTER SECTION
========================================= -->

<footer style="
            background:#050505;
            padding-top:40px;
            position:relative;
            overflow:hidden;
        ">

    <div class="container">

        <div class="row gy-5">

            <!-- =========================================
                 LOGO + ABOUT
            ========================================= -->

            <div class="col-lg-3 col-md-6">

                <!-- LOGO -->

                <div class="   ">

                    <img src="{{ asset('images/lock-logo.png') }}" alt="Look N Cook"
                        class="rounded-circle object-fit-cover" style="
                            width:120px;
                            height:120px;
                            border:4px solid rgba(255,255,255,0.08);
                         ">

                </div>

                <!-- DESCRIPTION -->

                <p style="
                        color:#d0d0d0;
                        line-height:1.9;
                        font-size:17px;
                   ">

                    Look N Cook is a home-based bakery
                    and catering brand by Farzana,
                    crafting delightful sugries and custom
                    treats with love — straight from Karachi
                    to your table.

                </p>

            </div>

            <!-- =========================================
                 NEED HELP
            ========================================= -->

            <div class="col-lg-3 col-md-6">

                <!-- TITLE -->

                <h4 class="fw-bold text-white    " style="
                        font-size:34px;
                    ">

                    Need Help

                </h4>

                <!-- PINK LINE -->
                <div style="
        width:45px;
        height:3px;
        background:#ff2d7a;
        margin-bottom:30px;
    " class="mx-auto mx-md-auto mx-lg-0">
                </div>

                <!-- =========================================
     FOOTER CONTACT INFO (FONT AWESOME 6 UPGRADE)
========================================= -->

                <div class="mb-4">
                    <span style="
        color: #777;
        font-size: 13px;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 5px;
    ">
                        Call Us
                    </span>
                    <h5 class="mb-0">
                        <a href="tel:03222360017" class="d-flex align-items-center gap-2 text-decoration-none" style="
            color: #e5e5e5;
            font-weight: 500;
            transition: color 0.3s ease;
        " onmouseover="this.style.color='#ff2d7a'" onmouseout="this.style.color='#e5e5e5'">
                            <span
                                style="color: #ff2d7a; font-size: 1.25rem; display: inline-flex; align-items: center;">
                                <i class="fa-solid fa-phone-flip"></i>
                            </span>
                            0322 2360017
                        </a>
                    </h5>
                </div>

                <div class="mb-4">
                    <span style="
        color: #777;
        font-size: 13px;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 5px;
    ">
                        Email
                    </span>
                    <h5 class="mb-0">
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=lookncook569@gmail.com" target="_blank"
                            class="d-flex align-items-center gap-2 text-decoration-none" style="
            color: #e5e5e5;
            font-weight: 500;
            transition: color 0.3s ease;
        " onmouseover="this.style.color='#ff2d7a'" onmouseout="this.style.color='#e5e5e5'">
                            <span
                                style="color: #ff2d7a; font-size: 1.25rem; display: inline-flex; align-items: center;">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <span class="email-text">lookncook569@gmail.com</span>
                        </a>
                    </h5>
                </div>

                <!-- ADDRESS SECTION -->
                <div>
                    <span style="
        color: #777;
        font-size: 13px;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 5px;
    ">
                        Address
                    </span>
                    <h5 class="mb-0 d-flex align-items-start gap-2" style="
        color: #e5e5e5;
        font-weight: 500;
        line-height: 1.6;
    ">
                        <!-- 🌟 Font Awesome 6 Premium Location Dot Icon (Aligned nicely with multi-line text) -->
                        <span
                            style="color: #ff2d7a; font-size: 1.35rem; display: inline-flex; align-items: center; margin-top: 3px;">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <p style="font-size: 17px;">
                            Latefy Housing Society
                            Gulistan E Johar Near
                            Johar Moor, Karachi,
                            Pakistan
                        </p>
                    </h5>
                </div>

            </div>

            <!-- =========================================
                 QUICK LINKS
            ========================================= -->

            <div class="col-lg-3 col-md-6">

                <!-- TITLE -->

                <h4 class="fw-bold text-white    " style="
                        font-size:34px;
                    ">

                    Quick Links

                </h4>

                <!-- PINK LINE -->

                <div style="
        width:45px;
        height:3px;
        background:#ff2d7a;
        margin-bottom:30px;
    " class="mx-auto mx-md-auto mx-lg-0">
                </div>

                <!-- LINKS -->

                <ul class="list-unstyled m-0 p-0">
                    <li class="mb-3">
                        <a href="{{ route('home') }}"
                            class="text-decoration-none footer-link d-inline-flex align-items-center gap-2">
                            <span style="color: #ff2d7a; font-size: 14px;">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                            <span>Home</span>
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="{{ route('menu') }}"
                            class="text-decoration-none footer-link d-inline-flex align-items-center gap-2">
                            <span style="color: #ff2d7a; font-size: 14px;">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                            <span>Menu</span>
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="{{ route('about') }}"
                            class="text-decoration-none footer-link d-inline-flex align-items-center gap-2">
                            <span style="color: #ff2d7a; font-size: 14px;">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                            <span>About</span>
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="{{ route('services') }}"
                            class="text-decoration-none footer-link d-inline-flex align-items-center gap-2">
                            <span style="color: #ff2d7a; font-size: 14px;">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                            <span>Services</span>
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="{{ route('gallery') }}"
                            class="text-decoration-none footer-link d-inline-flex align-items-center gap-2">
                            <span style="color: #ff2d7a; font-size: 14px;">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                            <span>Gallery</span>
                        </a>
                    </li>

                    <li class="mb-3">
                        <a href="{{ route('contact') }}"
                            class="text-decoration-none footer-link d-inline-flex align-items-center gap-2">
                            <span style="color: #ff2d7a; font-size: 14px;">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- =========================================
                 NEWSLETTER
            ========================================= -->

            <div class="col-lg-3 col-md-6">

                <!-- TITLE -->

                <h4 class="fw-bold text-white    " style="
                        font-size:34px;
                    ">

                    Newsletter

                </h4>

                <!-- PINK LINE -->

                <div style="
        width:45px;
        height:3px;
        background:#ff2d7a;
        margin-bottom:30px;
    " class="mx-auto mx-md-auto mx-lg-0">
                </div>

                <!-- TEXT -->

                <p style="
                        color:#d0d0d0;
                        line-height:1.9;
                        font-size:17px;
                   ">

                    Subscribe for exclusive offers,
                    new menu items, and updates
                    straight to your inbox.

                </p>

                <!-- FORM -->

                <form class="mt-4">

                    <div class="d-flex">

                        <!-- INPUT -->

                        <input type="email" placeholder="Your email address" class="form-control border-0 shadow-none"
                            style="
                                    height:60px;
                                    background:#0d0d0d;
                                    color:#fff;
                                    border-radius:12px 0 0 12px;
                                    border:1px solid rgba(255,255,255,0.05);
                               ">

                        <!-- BUTTON -->

                        <button type="submit" class="border-0" style="
                                    width:70px;
                                    background:#ff2d7a;
                                    color:#fff;
                                    border-radius:0 12px 12px 0;
                                    font-size:22px;
                                    transition:0.3s;
                                ">

                            ✈

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- =========================================
             FOOTER BOTTOM
        ========================================= -->

        <div class="mt-1 pt-4 pb-4" style="
                border-top:1px solid rgba(255,255,255,0.08);
             ">

            <div class="row align-items-center gy-4">

                <!-- SOCIAL ICONS -->

                <div class="col-lg-6">

                    <div class="d-flex flex-wrap gap-3">

                        <a href="#" class="footer-social" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>

                        <a href="#" class="footer-social" aria-label="Twitter">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>

                        <a href="#" class="footer-social" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>

                        <a href="#" class="footer-social" aria-label="Pinterest">
                            <i class="fa-brands fa-pinterest-p"></i>
                        </a>

                        <a href="#" class="footer-social" aria-label="Youtube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>

                    </div>

                </div>

                <!-- COPYRIGHT -->

                <div class="col-lg-6 text-lg-end">

                    <p class="mb-0" style="
                            color:#9b9b9b;
                            font-size:16px;
                       ">

                        Copyright © 2026 All Rights Reserved.
                        Developed by

                        <span style="
                                color:#ff2d7a;
                                font-weight:600;
                             ">

                            Global Dezigns

                        </span>

                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- FLOATING BUTTON -->
    
    
    <a href="#" class="position-fixed d-flex align-items-center justify-content-center text-decoration-none" style="
        width:58px;
        height:58px;
        background:#ff2d7a;
        color:#fff;
        border-radius:50%;
        right:25px;
        bottom:25px;
        z-index:999;
        font-size:24px;
        box-shadow:0 10px 30px rgba(255,45,122,0.35);
">

        <i class="fa-solid fa-phone-volume"></i>

    </a>
</footer>

<!-- =========================================
     INTERNAL FOOTER STYLE
========================================= -->

<style>
    .footer-link {
        color: #d0d0d0;
        font-size: 18px;
        transition: 0.3s;
    }

    .footer-link:hover {
        color: #ff2d7a;
        padding-left: 6px;
    }

    .footer-social {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: 0.3s;
        background: #0b0b0b;
        font-size: 18px;
    }

    .footer-social:hover {
        background: #ff2d7a;
        border-color: #ff2d7a;
        transform: translateY(-4px);
        color: #fff;
    }

    footer input::placeholder {
        color: #999 !important;
    }

    @media(max-width:992px) {

        footer {
            text-align: center;
        }

        .footer-social {
            margin: auto;
        }

    }

    /* =========================================
   FOOTER CONTACT FIX
========================================= */

    .footer-small-title {
        color: #777;
        font-size: 13px;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: block;
        /* margin-bottom: 12px; */
    }

    .footer-contact-row {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .footer-contact-icon {
        color: #ff2d7a;
        font-size: 18px;
        flex-shrink: 0;
        width: 18px;
        margin-top: 2px;
    }

    .footer-contact-value {
        color: #e5e5e5;
        font-size: 16px;
        line-height: 1.8;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .email-text {
        font-size: 15px;
    }

    @media(max-width:1200px) {

        .footer-contact-value {
            font-size: 15px;
        }

        .email-text {
            font-size: 14px;
        }

    }

    @media(max-width:992px) {

        .footer-contact-row {
            justify-content: center;
        }

    }

    /* ⚡ Optional Hover Animation for Premium Feel */
    .footer-link {
        color: #e5e5e5;
        /* Aapka link ka default color */
        transition: all 0.3s ease;
    }

    .footer-link span i {
        transition: transform 0.3s ease;
    }

    .footer-link:hover {
        color: #ff2d7a;
        /* Hover par text ka color change */
    }

    .footer-link:hover span i {
        transform: translateX(4px);
        /* Hover karne par arrow soft side par push hoga */
    }
</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>looknCook | @yield('title')</title>
<link rel="shortcut icon" href="{{ asset('images/lock-logo.png') }}" type="image/jpeg">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<!-- Bootstrap 5 -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Font -->

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<!-- Font Faimlly style link here   -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<!-- Font Awsome Link       -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        overflow-x: hidden;
        background: #fff;
    }

    /* =========================
   TOPBAR
========================= */

    .topbar {
        background: #000;
        color: #fff;
        font-size: 13px;
        padding: 8px 0;
    }

    /* =========================
   NAVBAR
========================= */

    .custom-navbar {
        background: #fff;
        padding: 16px 0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        z-index: 999;
        transition: 0.3s;
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 32px;
    }

    .navbar-nav .nav-link {
        color: #111 !important;
        font-weight: 600;
        margin: 0 12px;
        transition: 0.3s;
        position: relative;
    }

    .navbar-nav .nav-link:hover {
        color: #ff2d7a !important;
    }

    /* NAV LINK ANIMATION */

    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 0%;
        height: 2px;
        background: #ff2d7a;
        transition: 0.3s;
    }

    .navbar-nav .nav-link:hover::after {
        width: 100%;
    }

    /* LOGIN BUTTON */

    .login-btn {
        background: #ff2d7a;
        color: #fff;
        border-radius: 8px;
        padding: 10px 28px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        display: inline-block;
    }

    .login-btn:hover {
        background: #e91e63;
        color: #fff;
        transform: translateY(-2px);
    }

    /* =========================
   HERO SECTION
========================= */

    .hero-section {
        position: relative;
    }

    /* CAROUSEL */

    .carousel-item {
        height: 92vh;
        min-height: 700px;
        position: relative;
        overflow: hidden;
    }

    /* HERO IMAGE */

    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(40%);
        transform: scale(1);
        transition: transform 7s ease;
    }

    /* ACTIVE IMAGE EFFECT */

    .carousel-item.active .hero-image {
        transform: scale(1.08);
    }

    /* OVERLAY */

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
    }

    /* CONTENT WRAPPER */

    .hero-content-wrapper {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 5;
        width: 100%;
        padding: 20px;
    }

    /* GLASS CONTAINER */

    .hero-content {
        max-width: 850px;
        margin: auto;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 60px;
        border-radius: 24px;
        text-align: center;
        color: #fff;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);

        animation: contentFade 1.2s ease;
    }

    /* CONTENT ANIMATION */

    @keyframes contentFade {

        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    /* SUBTITLE */

    .hero-subtitle {
        color: #ff5ca8;
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 16px;
        display: block;
    }

    /* TITLE */

    .hero-title {
        font-size: 68px;
        line-height: 1.1;
        font-weight: 800;
        margin-bottom: 24px;
    }

    /* DESCRIPTION */

    .hero-description {
        font-size: 22px;
        line-height: 1.7;
        color: #f1f1f1;
        margin-bottom: 34px;
    }

    /* HERO BUTTON */

    .hero-btn {
        background: #ff2d7a;
        color: #fff;
        padding: 14px 36px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        display: inline-block;
    }

    .hero-btn:hover {
        background: #e91e63;
        color: #fff;
        transform: translateY(-3px);
    }

    /* =========================
   CAROUSEL ARROWS
========================= */

    .hero-arrow {
        width: 60px !important;
        height: 60px !important;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1 !important;
        background: transparent !important;
        border: none !important;
        z-index: 1000;
    }

    /* ARROW POSITIONS */

    .carousel-control-prev {
        left: 20px;
    }

    .carousel-control-next {
        right: 20px;
    }

    /* THIN LIGHT PINK ARROWS */

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 22px;
        height: 22px;
        background-size: 100% 100%;
        opacity: 0.9;
    }

    /* LEFT ARROW */

    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath d='M10.5 1.5L4 8l6.5 6.5' stroke='%23ffb6d5' stroke-width='1.8' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    }

    /* RIGHT ARROW */

    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath d='M5.5 1.5L12 8l-6.5 6.5' stroke='%23ffb6d5' stroke-width='1.8' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    }

    /* =========================
   INDICATORS
========================= */

    .carousel-indicators {
        margin-bottom: 30px;
    }

    .carousel-indicators button {
        width: 12px !important;
        height: 12px !important;
        border-radius: 50%;
        background-color: #fff !important;
        opacity: 0.5;
    }

    .carousel-indicators .active {
        opacity: 1;
        background-color: #ff2d7a !important;
    }

    /* =========================
   RESPONSIVE
========================= */

    @media(max-width:1200px) {

        .hero-title {
            font-size: 54px;
        }

    }

    /* TABLET */

    @media(max-width:992px) {

        .hero-content {
            padding: 45px;
        }

        .hero-title {
            font-size: 42px;
        }

        .hero-description {
            font-size: 18px;
        }

        .navbar-nav {
            padding-top: 20px;
            text-align: center;
        }

        .login-btn {
            margin-top: 10px;
        }

    }

    /* MOBILE */

    @media(max-width:768px) {

        .carousel-item {
            height: 80vh;
            min-height: 550px;
        }

        .hero-content {
            padding: 35px 25px;
        }

        .hero-title {
            font-size: 34px;
        }

        .hero-description {
            font-size: 16px;
        }

        .hero-arrow {
            width: 45px !important;
            height: 45px !important;
        }

        .carousel-control-prev {
            left: 10px;
        }

        .carousel-control-next {
            right: 10px;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 18px;
            height: 18px;
        }

    }

    /* SMALL MOBILE */

    @media(max-width:576px) {

        .carousel-item {
            height: 75vh;
            min-height: 500px;
        }

        .hero-content {
            padding: 28px 18px;
            border-radius: 18px;
        }

        .hero-title {
            font-size: 26px;
        }

        .hero-description {
            font-size: 14px;
            line-height: 1.6;
        }

        .hero-btn {
            width: 100%;
        }

        .topbar {
            display: none;
        }

    }
</style>
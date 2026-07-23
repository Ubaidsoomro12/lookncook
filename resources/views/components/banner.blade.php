<section class="banner-hero position-relative overflow-hidden" style="background-image: url('{{ asset('images/page_banner_2.png') }}');">

    <div class="banner-overlay"></div>

    <img src="{{ asset('images/briyani.png') }}" class="floating-food food-1">
    <img src="{{ asset('images/chinese.png') }}" class="floating-food food-2">
    <img src="{{ asset('images/qorma.png') }}" class="floating-food food-3">

    <div class="container position-relative z-3">
        <div class="text-center">

            <span class="hero-mini-title">
                LOOK N COOK
                <span class="mini-highlight">{{ $miniTitle }}</span>
            </span>

            <h1 class="hero-title">
                {{ $title }}
                <span>
                    {{ $highlight }}
                </span>
            </h1>

            <p class="hero-text">
                {{ $description }}
            </p>

        </div>
    </div>

</section>

<style>
    .banner-hero {
        background: #0f172a;
        padding: 120px 0;
        position: relative;
    }

    .banner-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right,
                rgba(15, 23, 42, 0.69),
                rgba(15, 23, 42, 0.82));
    }

    .hero-mini-title {
        font-family: 'Poppins', sans-serif;
        /* modern premium look */
        font-weight: 700;
        letter-spacing: 3px;
        font-size: 14px;
        color: #ffffff;
        text-transform: uppercase;
    }

    .mini-highlight {
        color: #ff2d7a;
        font-weight: 800;
    }

    .hero-title {
        color: #fff;
        font-size: 60px;
        font-weight: 700;
        margin-top: 20px;
    }

    .hero-title span {
        color: #ff2d7a;
    }

    .hero-text {
        color: rgba(255, 255, 255, 0.75);
        max-width: 700px;
        margin: auto;
        margin-top: 20px;
        line-height: 1.9;
    }

    .floating-food {
        position: absolute;
        width: 110px;
        z-index: 2;
        animation: float 5s ease-in-out infinite;
    }

    .food-1 {
        left: 5%;
        top: 90px;
    }

    .food-2 {
        right: 7%;
        top: 80px;
    }

    .food-3 {
        right: 10%;
        bottom: 50px;
    }

    @keyframes float {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-12px);
        }

        100% {
            transform: translateY(0);
        }
    }
</style>
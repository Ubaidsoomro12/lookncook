<section class="py-5 bg-white">
    <div class="container">

        <div class="text-center mb-5">
            <span class="text-uppercase fw-semibold" style="color:#ff4fa0; letter-spacing:1px;">
                Our Services
            </span>
            <h2 class="fw-bold mt-2" style="color: #1a1a1a;">
                We Are Providing Food For
            </h2>
        </div>

        <style>
            .service-card-wrapper {
                position: relative;
            }

            /* Desktop Flow Connection Layout (min-width: 992px) */
            @media (min-width: 992px) {
                
                /* 1 & 2. Perfect Short Wave Arrows with Dead-Straight Facing Tips (01->02, 02->03, 04->05, 05->06) */
                .arrow-flow-up::after,
                .arrow-flow-down::after {
                    content: "";
                    position: absolute;
                    top: 40px; /* Aligned with circle centers */
                    right: -25%;
                    width: 100px;
                    height: 50px;
                    background-repeat: no-repeat;
                    background-size: contain;
                    z-index: 10;
                }

                /* 01 -> 02 and 04 -> 05 */
                .arrow-flow-up::after {
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 50' fill='none'%3E%3Cpath d='M5,35 Q45,5 80,25' stroke='%23ff4fa0' stroke-width='2' stroke-linecap='round' stroke-dasharray='4 4'/%3E%3Cpath d='M72,25 L84,25 L80,15' stroke='%23ff4fa0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
                }

                /* 02 -> 03 and 05 -> 06 */
                .arrow-flow-down::after {
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 50' fill='none'%3E%3Cpath d='M5,15 Q45,45 80,25' stroke='%23ff4fa0' stroke-width='2' stroke-linecap='round' stroke-dasharray='4 4'/%3E%3Cpath d='M72,25 L84,25 L80,35' stroke='%23ff4fa0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
                }

                /* 3. The Big Loop Return Arrow (03 -> 04) */
                /* This loops beautifully to the edge and comes back with a solid, highly visible forward-facing head on row 2 */
            
                
                /* Generous margin between rows so the loop arrow doesn't overlap text */
                .row-space-fix {
                    margin-top: 6.5rem !important;
                }
            }

            /* Clean Presentation Number Badge */
            .custom-badge {
                width: 32px;
                height: 32px;
                font-size: 11px;
                background: #ffffff;
                color: #ff4fa0;
                border: 2px solid #ff4fa0;
                border-radius: 50%;
                box-shadow: 0 4px 10px rgba(242, 67, 145, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                position: absolute;
                top: 2px;
                left: 2px;
                z-index: 5;
            }

            /* Smooth Hover Effect */
            .service-card-wrapper .rounded-circle {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .service-card-wrapper:hover .rounded-circle {
                transform: scale(1.05);
                box-shadow: 0 10px 20px rgba(242, 67, 145, 0.3) !important;
            }
        </style>

        <div class="row g-5 justify-content-center">

            <div class="col-lg-4 col-md-6 service-card-wrapper arrow-flow-up">
                <div class="text-center">
                    <div class="position-relative d-inline-block">
                        <span class="custom-badge">01</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                             style="width:130px; height:130px; background:#f24391;">
                             <img src="{{ asset('images/service-icon-office.png') }}" alt="Office Lunch Boxes" class="service-circle-img"style="width: 60%;">
                        </div>
                    </div>
                    <h5 class="mt-4 fw-bold" style="color: #222;">Office Lunch Boxes</h5>
                    <p class="text-muted px-3" style="font-size: 0.95rem; opacity: 0.85;">
                        Delicious, nutritious, and customizable office lunch boxes designed to keep you energized throughout your workday!
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 service-card-wrapper arrow-flow-down">
                <div class="text-center">
                    <div class="position-relative d-inline-block">
                        <span class="custom-badge">02</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                             style="width:130px; height:130px; background:#f24391;">
                             <img src="{{ asset('images/service-isoc-lunch.png') }}" alt="Office Lunch Boxes" class="service-circle-img"style="width: 60%;">
                        </div>
                    </div>
                    <h5 class="mt-4 fw-bold" style="color: #222;">Daily Dinner Lunch</h5>
                    <p class="text-muted px-3" style="font-size: 0.95rem; opacity: 0.85;">
                        Enjoy a selection of fresh, flavorful meals with our Daily Dinner Lunch options, perfect for a satisfying midday break that keeps you fueled for the afternoon!
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 service-card-wrapper arrow-flow-return">
                <div class="text-center">
                    <div class="position-relative d-inline-block">
                        <span class="custom-badge">03</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                             style="width:130px; height:130px; background:#f24391;">
                              <img src="{{ asset('images/service-isoc-birthday.png') }}" alt="Office Lunch Boxes" class="service-circle-img"style="width: 60%;">
                        </div>
                    </div>
                    <h5 class="mt-4 fw-bold" style="color: #222;">Birthday Parties</h5>
                    <p class="text-muted px-3" style="font-size: 0.95rem; opacity: 0.85;">
                        Celebrate your special day with our customizable birthday party packages, featuring delicious food and unforgettable experiences!
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 row-space-fix service-card-wrapper arrow-flow-up">
                <div class="text-center">
                    <div class="position-relative d-inline-block">
                        <span class="custom-badge">04</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                             style="width:130px; height:130px; background:#f24391;">
                              <img src="{{ asset('images/service-isoc-place.png') }}" alt="Office Lunch Boxes" class="service-circle-img"style="width: 60%;">
                        </div>
                    </div>
                    <h5 class="mt-4 fw-bold" style="color: #222;">Work Places</h5>
                    <p class="text-muted px-3" style="font-size: 0.95rem; opacity: 0.85;">
                        Enhance your workplace with our convenient meal solutions, designed to keep your team energized and productive!
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 row-space-fix service-card-wrapper arrow-flow-down">
                <div class="text-center">
                    <div class="position-relative d-inline-block">
                        <span class="custom-badge">05</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                             style="width:130px; height:130px; background:#f24391;">
                              <img src="{{ asset('images/service-isoc-wedding.png') }}" alt="Office Lunch Boxes" class="service-circle-img"style="width: 60%;">
                        </div>
                    </div>
                    <h5 class="mt-4 fw-bold" style="color: #222;">Weddings</h5>
                    <p class="text-muted px-3" style="font-size: 0.95rem; opacity: 0.85;">
                        Make your special day unforgettable with our tailored wedding catering services, featuring exquisite dishes and personalized touches!
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 row-space-fix service-card-wrapper">
                <div class="text-center">
                    <div class="position-relative d-inline-block">
                        <span class="custom-badge">06</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                             style="width:130px; height:130px; background:#f24391;">
                             <img src="{{ asset('images/service-isoc-tea.png') }}" alt="Office Lunch Boxes" class="service-circle-img"style="width: 60%;">
                        </div>
                    </div>
                    <h5 class="mt-4 fw-bold" style="color: #222;">Hi-Tea</h5>
                    <p class="text-muted px-3" style="font-size: 0.95rem; opacity: 0.85;">
                        Indulge in our delightful Hi-Tea offerings, featuring a scrumptious selection of savory bites and sweet treats for the perfect afternoon break!
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
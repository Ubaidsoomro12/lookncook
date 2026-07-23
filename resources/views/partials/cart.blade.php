<!-- =========================================
     CART SIDEBAR
========================================= -->

<div id="cartSidebar"
     style="
        position:fixed;
        top:0;
        right:-420px;
        width:400px;
        max-width:100%;
        height:100vh;
        background:#fff;
        z-index:999999;
        transition:0.4s;
        box-shadow:-10px 0 40px rgba(0,0,0,0.12);
        display:flex;
        flex-direction:column;
     ">

    <!-- TOP -->

    <div class="d-flex justify-content-between align-items-center p-4 border-bottom">

        <h4 class="fw-bold mb-0">

            Your Cart

        </h4>

        <button onclick="closeCart()"
                class="btn p-0 border-0 shadow-none"
                style="
                    font-size:32px;
                    color:#ff2d7a;
                ">

            ×

        </button>

    </div>

    <!-- ITEMS -->

    <div id="cartItems"
         class="flex-grow-1 overflow-auto p-4">

        <div class="text-center text-secondary mt-5">

            Cart Is Empty

        </div>

    </div>

    <!-- BOTTOM -->

    <div class="border-top p-4">

        <div class="d-flex justify-content-between mb-3">

            <h5 class="fw-bold">

                Total

            </h5>

            <h5 class="fw-bold"
                id="cartTotal">

                PKR 0

            </h5>

        </div>

        <button class="hero-btn w-100 py-3">

            Checkout

        </button>

    </div>

</div>

<!-- OVERLAY -->

<div id="cartOverlay"
     onclick="closeCart()"
     style="
        position:fixed;
        inset:0;
        background:rgba(0,0,0,0.45);
        z-index:999998;
        display:none;
     ">
</div>
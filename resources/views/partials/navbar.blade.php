<!-- Topbar Area -->
<div class="topbar d-none d-lg-block">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span style="font-size:20px; line-height:1;">🛵</span>
                <span style="font-weight:500; letter-spacing:0.3px;">03222360017</span>
            </div>
            <div class="d-flex align-items-center gap-2 text-end">
                <span>📍</span>
                <span>Latefy Housing Society Gulistan E Johar Near Johar Moor, Karachi, Pakistan</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Sticky Navbar Section -->
<nav class="navbar navbar-expand-lg custom-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-1" href="#">
            <img src="{{ asset('images/lock-logo.png') }}" alt="Logo" width="58" height="58"
                class="img-fluid rounded-circle object-fit-cover shadow-sm">
            <div class="d-flex flex-column lh-1">
                <span
                    style="font-size:30px; font-weight:800; letter-spacing:2px; color:#ff2d7a; text-transform:uppercase; line-height:1;">LOOK
                    N</span>
                <span
                    style="font-size:30px; font-weight:800; letter-spacing:2px; color:#111; text-transform:uppercase; line-height:1;">COOK</span>
                <span
                    style="font-size:10px; letter-spacing:4px; color:#888; margin-top:4px; text-transform:uppercase; font-weight:600;">Premium
                    Catering</span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <div class="custom-burger-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('menu') }}">Menu</a></li>
                <li class="nav-item"><a class="nav-link text-decoration-none" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center justify-content-center gap-3 mt-4 mt-lg-0">
                <!-- Cart Trigger Button -->
                <div class="position-relative">
                    <button onclick="openCart()" class="border-0 position-relative cart-btn-custom" type="button"
                        style="width:52px; height:52px; border-radius:50%; background:rgba(255,45,122,0.10); border:1px solid rgba(255,45,122,0.20); display:flex; align-items:center; justify-content:center; color:#ff2d7a; font-size:24px; transition:0.3s ease; backdrop-filter:blur(8px); box-shadow:0 6px 20px rgba(255,45,122,0.12);">
                        <span style="transform:translateY(-1px);">🛒</span>
                        <span id="cartCount"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                            style="background:#ff2d7a; font-size:10px; min-width:20px; height:20px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(255,45,122,0.35);">0</span>
                    </button>
                </div>

                <!-- Wishlist Trigger Button -->
                <div class="position-relative">
                    <button onclick="openWishlist()" class="border-0 position-relative wishlist-btn-custom"
                        type="button"
                        style="width:52px; height:52px; border-radius:50%; background:rgba(255,45,122,0.10); border:1px solid rgba(255,45,122,0.20); display:flex; align-items:center; justify-content:center; color:#ff2d7a; font-size:24px; transition:0.3s ease; backdrop-filter:blur(8px); box-shadow:0 6px 20px rgba(255,45,122,0.12);">
                        <span style="transform:translateY(0px);">❤️</span>
                        <span id="wishlistCount"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                            style="background:#ff2d7a; font-size:10px; min-width:20px; height:20px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(255,45,122,0.35);">0</span>
                    </button>
                </div>

                @auth
                    <style>
                        /* =========================
                           USER PROFILE DROPDOWN
                        ========================= */

                        .user-profile-dropdown {
                            height: 52px;
                            padding: 0 18px;
                            border-radius: 14px !important;
                            background: rgba(255, 45, 122, 0.10) !important;
                            border: 1px solid rgba(255, 45, 122, 0.20) !important;
                            color: #ff2d7a !important;
                            font-weight: 600;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            transition: 0.3s ease;
                            backdrop-filter: blur(8px);
                            box-shadow: 0 6px 20px rgba(255, 45, 122, 0.12);
                        }

                        .user-profile-dropdown:hover,
                        .user-profile-dropdown:focus,
                        .user-profile-dropdown.show {
                            background: #ff2d7a !important;
                            color: #ffffff !important;
                            transform: translateY(-2px);
                            box-shadow: 0 10px 24px rgba(255, 45, 122, 0.25) !important;
                        }

                        .user-profile-dropdown:hover i,
                        .user-profile-dropdown.show i {
                            color: #ffffff !important;
                        }

                        .custom-dropdown-menu {
                            border: none !important;
                            border-radius: 18px !important;
                            padding: 10px;
                            min-width: 180px;
                            /* margin-top: 14px !important; */
                            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.10);
                            background: #ffffff;
                            overflow: hidden;
                        }

                        .custom-dropdown-menu .dropdown-item {
                            border-radius: 12px;
                            padding: 12px 14px;
                            font-weight: 500;
                            transition: 0.25s ease;
                            display: flex;
                            align-items: center;
                        }

                        .custom-dropdown-menu .dropdown-item:hover {
                            background: rgba(255, 45, 122, 0.10);
                            color: #ff2d7a !important;
                            transform: translateX(4px);
                        }

                        .custom-dropdown-menu .dropdown-item i {
                            transition: 0.25s ease;
                        }

                        .custom-dropdown-menu .dropdown-item:hover i {
                            color: #ff2d7a !important;
                        }

                        .logout-item:hover {
                            background: rgba(255, 45, 122, 0.10) !important;
                            color: #ff2d7a !important;
                        }
                        
                    </style>
                    <div class="dropdown">
                        <button class="btn user-profile-dropdown dropdown-toggle" type="button" id="userMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2 text-primary-color"></i>
                            {{ Auth::user()->name }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu" aria-labelledby="userMenuButton">

                            <!-- <li class="dropdown-header text-muted">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        Location: {{ Auth::user()->city }}
                                                    </li> -->


                            <li>
                                <a class="dropdown-item text-danger logout-item" href="#">
                                    <i class="bi bi-person-circle me-2"></i>
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger logout-item" href="#">
                                      <i class="bi bi-bag-check me-2"></i>
                                    My Order
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger logout-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </a>
                            </li>

                        </ul>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>

                @else

                    <a href="{{ route('login') }}" class="login-btn">
                        Login
                    </a>

                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Custom Cart Sidebar Component -->
<div id="cartSidebar"
    style="position:fixed; top:0; right:-420px; width:400px; max-width:100%; height:100vh; background:#fff; z-index:99999; transition:0.35s ease; box-shadow:-10px 0 30px rgba(0,0,0,0.12); display:flex; flex-direction:column;">
    <div
        style="padding:25px; border-bottom:1px solid rgba(0,0,0,0.08); display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h4 class="fw-bold mb-1" style="color:#111;">Your Cart</h4>
            <span style="color:#777; font-size:14px;">Selected Food Items</span>
        </div>
        <button onclick="closeCart()" class="border-0 bg-transparent"
            style="font-size:28px; color:#ff2d7a; line-height:1;">×</button>
    </div>

    <div id="cartItems" style="flex:1; overflow-y:auto; padding:20px;">
        <div id="emptyCartText" class="text-center mt-5">
            <div style="font-size:70px; opacity:0.2;">🛒</div>
            <h5 class="fw-bold mt-3" style="color:#111;">Your Cart Is Empty</h5>
            <p style="color:#777; font-size:14px;">Add delicious food items now.</p>
        </div>
    </div>

    <div style="padding:20px; border-top:1px solid rgba(0,0,0,0.08); background:#fff7fb;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Total</h5>
            <h5 class="fw-bold mb-0" style="color:#ff2d7a;">PKR <span id="cartTotal">0</span></h5>
        </div>
        <a href="{{ route('cart') }}" class="text-decoration-none d-flex align-items-center justify-content-center"
            style="height:52px; background:#ff2d7a; color:#fff; border-radius:14px; font-weight:600; transition:0.3s; box-shadow:0 10px 25px rgba(255,45,122,0.20);">Proceed
            To Checkout</a>
    </div>
</div>

<!-- Custom Wishlist Sidebar Component -->
<div id="wishlistSidebar"
    style="position:fixed; top:0; right:-420px; width:400px; max-width:100%; height:100vh; background:linear-gradient(185deg, #fffdfd 0%, #fff5f8 100%); z-index:99999; transition:0.38s cubic-bezier(0.25, 1, 0.5, 1); box-shadow:-15px 0 40px rgba(255,45,122,0.08); display:flex; flex-direction:column;">
    <div
        style="padding:25px; border-bottom:1px solid rgba(255,45,122,0.08); display:flex; justify-content:space-between; align-items:center; background: rgba(255,255,255,0.6); backdrop-filter: blur(10px);">
        <div>
            <h4 class="fw-bold mb-1" style="color:#111; letter-spacing: 0.3px;">Saved Pins ❤️</h4>
            <span style="color:#ff2d7a; font-size:13px; font-weight: 500;">Gallery Collection</span>
        </div>
        <button onclick="closeWishlist()" class="border-0 bg-transparent"
            style="font-size:28px; color:#ff2d7a; line-height:1; transition: 0.2s;"
            onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">×</button>
    </div>

    <div id="wishlistItems" style="flex:1; overflow-y:auto; padding:20px;">
        <div id="emptyWishlistText" class="text-center mt-5 py-4">
            <div style="font-size:65px; filter: drop-shadow(0 10px 15px rgba(255,45,122,0.15));">💖</div>
            <h5 class="fw-bold mt-4" style="color:#111;">Your Collection is Empty</h5>
            <p style="color:#888; font-size:14px; max-width: 220px; margin: 8px auto 0;">Save beautiful food images from
                our gallery layout!</p>
        </div>
    </div>

    <div style="padding:20px; border-top:1px solid rgba(255,45,122,0.08); background:#ffffff;">
        <button onclick="downloadAllImages()"
            class="border-0 w-100 d-flex align-items-center justify-content-center gap-2"
            style="height:52px; background:linear-gradient(135deg, #111111 0%, #333333 100%); color:#fff; border-radius:14px; font-weight:600; transition:0.3s; box-shadow:0 8px 22px rgba(0,0,0,0.15);"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 26px rgba(0,0,0,0.25)';"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 22px rgba(0,0,0,0.15)';">
            <span>📥</span> Download All to Device
        </button>
    </div>
</div>

<!-- Dynamic Overlay Mask -->
<div id="cartOverlay" onclick="closeAllSidebars()"
    style="position:fixed; inset:0; background:rgba(17,12,14,0.4); z-index:99998; opacity:0; visibility:hidden; transition:0.3s; backdrop-filter:blur(3px);">
</div>

<!-- Bootstrap Native Offcanvas Back-up System -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="bootstrapCartOffcanvas"
    aria-labelledby="bootstrapCartOffcanvasLabel" style="width:420px; border:none;">
    <div class="offcanvas-header border-bottom py-4 px-4">
        <div>
            <h4 class="fw-bold mb-1" id="bootstrapCartOffcanvasLabel" style="color:#111;">Your Cart</h4>
            <span style="color:#777; font-size:14px;">Delicious food waiting for you 🍽️</span>
        </div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-4">
        <div class="text-center py-5">
            <div style="font-size:70px; line-height:1;">🛒</div>
            <h5 class="fw-bold mt-4 mb-2">Your Cart Is Empty</h5>
            <p style="color:#777; line-height:1.8;">Add your favorite dishes from our delicious menu.</p>
        </div>

        <div class="d-none">
            <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                <div class="rounded-4 overflow-hidden flex-shrink-0" style="width:90px; height:90px;">
                    <img src="{{ asset('images/briyani.jpg') }}" class="w-100 h-100 object-fit-cover" alt="Food">
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">Chicken Biryani</h6>
                    <span style="color:#ff2d7a; font-weight:600;">PKR 450</span>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button class="btn btn-sm"
                            style="background:#fff0f6; color:#ff2d7a; width:32px; height:32px;">-</button>
                        <span class="fw-semibold">1</span>
                        <button class="btn btn-sm"
                            style="background:#fff0f6; color:#ff2d7a; width:32px; height:32px;">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <span class="fw-semibold" style="color:#555;">Total</span>
            <h5 class="fw-bold mb-0">PKR 0</h5>
        </div>
        <a href="#" class="hero-btn w-100 text-center py-3 d-block">Proceed To Checkout</a>
    </div>
</div>

<style>
    .cart-btn-custom:hover,
    .wishlist-btn-custom:hover {
        transform: translateY(-2px) scale(1.04);
        background: rgba(255, 45, 122, 0.18) !important;
        box-shadow: 0 10px 24px rgba(255, 45, 122, 0.20) !important;
    }

    .cart-btn-custom:active,
    .wishlist-btn-custom:active {
        transform: scale(0.95);
    }

    .cart-item-box {
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 15px;
        background: #fff;
        transition: 0.3s;
    }

    .cart-item-box:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }

    .wishlist-item-box {
        border: 1px solid rgba(255, 45, 122, 0.06);
        border-radius: 20px;
        padding: 12px;
        margin-bottom: 14px;
        background: #ffffff;
        transition: 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
    }

    .wishlist-item-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(255, 45, 122, 0.05);
        border-color: rgba(255, 45, 122, 0.15);
    }

    @media (max-width: 360px) {
        .wishlist-item-box {
            padding: 8px;
            gap: 8px !important;
        }

        .wishlist-item-box img {
            width: 50px !important;
            height: 50px !important;
        }

        .wishlist-item-box h6 {
            font-size: 13px !important;
        }
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: #ffffff !important;
            padding: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            z-index: 9999 !important;
        }
    }

    .custom-burger-icon {
        width: 24px;
        height: 18px;
        position: relative;
        transform: rotate(0deg);
        transition: .5s ease-in-out;
        cursor: pointer;
    }

    .custom-burger-icon span {
        display: block;
        position: absolute;
        height: 3px;
        width: 100%;
        background: #111111;
        border-radius: 9px;
        opacity: 1;
        left: 0;
        transform: rotate(0deg);
        transition: .25s ease-in-out;
    }

    .custom-burger-icon span:nth-child(1) {
        top: 0px;
    }

    .custom-burger-icon span:nth-child(2) {
        top: 7px;
    }

    .custom-burger-icon span:nth-child(3) {
        top: 14px;
    }

    button[aria-expanded="true"] .custom-burger-icon span:nth-child(1) {
        top: 7px;
        transform: rotate(135deg);
        background: #ff2d7a;
    }

    button[aria-expanded="true"] .custom-burger-icon span:nth-child(2) {
        opacity: 0;
        left: -60px;
    }

    button[aria-expanded="true"] .custom-burger-icon span:nth-child(3) {
        top: 7px;
        transform: rotate(-135deg);
        background: #ff2d7a;
    }

    [style*="position:fixed"][style*="background:#ff2d7a"],
    [style*="position: fixed"][style*="background: rgb(255, 45, 122)"],
    .phone-fixed-btn {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    [style*="position:fixed"][style*="background:#ff2d7a"] i,
    [style*="position:fixed"][style*="background:#ff2d7a"] span,
    .phone-fixed-btn i,
    .phone-fixed-btn span {
        font-size: 26px !important;
        line-height: 1 !important;
        display: inline-block !important;
    }
</style>

<script>
    let cart = [];
    let wishlist = [];

    document.addEventListener("DOMContentLoaded", function () {
        if (localStorage.getItem("look_n_cook_cart")) {
            try {
                cart = JSON.parse(localStorage.getItem("look_n_cook_cart"));
                updateCart();
            } catch (e) {
                cart = [];
            }
        }
        if (localStorage.getItem("look_n_cook_wishlist")) {
            try {
                wishlist = JSON.parse(localStorage.getItem("look_n_cook_wishlist"));
                updateWishlist();
            } catch (e) {
                wishlist = [];
            }
        }
    });

    function openCart() {
        closeWishlist();
        document.getElementById('cartSidebar').style.right = '0';
        showOverlay();
    }

    function closeCart() {
        document.getElementById('cartSidebar').style.right = '-420px';
        hideOverlay();
    }

    function openWishlist() {
        closeCart();
        document.getElementById('wishlistSidebar').style.right = '0';
        showOverlay();
    }

    function closeWishlist() {
        document.getElementById('wishlistSidebar').style.right = '-420px';
        hideOverlay();
    }

    function closeAllSidebars() {
        document.getElementById('cartSidebar').style.right = '-420px';
        document.getElementById('wishlistSidebar').style.right = '-420px';
        hideOverlay();
    }

    function showOverlay() {
        let overlay = document.getElementById('cartOverlay');
        if (overlay) {
            overlay.style.opacity = '1';
            overlay.style.visibility = 'visible';
        }
    }

    function hideOverlay() {
        let overlay = document.getElementById('cartOverlay');
        if (overlay) {
            overlay.style.opacity = '0';
            overlay.style.visibility = 'hidden';
        }
    }

    function addToCart(name, price, image) {
        let existingIndex = cart.findIndex(item => item.name === name);
        if (existingIndex !== -1) {
            cart[existingIndex].quantity = (cart[existingIndex].quantity || 1) + 1;
        } else {
            cart.push({
                name: name,
                price: parseInt(price),
                image: image,
                quantity: 1
            });
        }
        saveCartState();
        updateCart();
    }

    function saveCartState() {
        localStorage.setItem("look_n_cook_cart", JSON.stringify(cart));
        window.dispatchEvent(new Event('cartUpdated'));
    }

    function updateCart() {
        let cartItems = document.getElementById('cartItems');
        let cartCount = document.getElementById('cartCount');
        let cartTotal = document.getElementById('cartTotal');
        let emptyCartText = document.getElementById('emptyCartText');

        if (!cartItems) return;

        cartItems.innerHTML = '';
        let total = 0;
        let totalCount = 0;

        if (cart.length === 0) {
            if (emptyCartText) cartItems.appendChild(emptyCartText);
            if (cartCount) cartCount.innerText = 0;
            if (cartTotal) cartTotal.innerText = 0;
            return;
        }

        cart.forEach((item, index) => {
            let itemQty = item.quantity || 1;
            let itemTotal = item.price * itemQty;
            total += itemTotal;
            totalCount += itemQty;

            cartItems.innerHTML += `
            <div class="cart-item-box d-flex align-items-center gap-3">
                <img src="${item.image}" style="width:70px; height:70px; object-fit:cover; border-radius:16px;">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">${item.name} <span class="text-muted" style="font-size: 12px;">(x${itemQty})</span></h6>
                    <span style="color:#ff2d7a; font-weight:600;">PKR ${itemTotal}</span>
                </div>
                <button onclick="removeCartItem(${index})" class="border-0 bg-transparent" style="color:red; font-size:20px;">
                    ×
                </button>
            </div>
        `;
        });

        if (cartCount) cartCount.innerText = totalCount;
        if (cartTotal) cartTotal.innerText = total;
    }

    function removeCartItem(index) {
        cart.splice(index, 1);
        saveCartState();
        updateCart();
    }

    function addToWishlist(name, price, image) {
        let alreadyExists = wishlist.some(item => item.image === image);
        if (alreadyExists) return;

        wishlist.push({
            name: name,
            price: price,
            image: image
        });

        saveWishlistState();
        updateWishlist();
    }

    function saveWishlistState() {
        localStorage.setItem("look_n_cook_wishlist", JSON.stringify(wishlist));
    }

    function updateWishlist() {
        let wishlistItems = document.getElementById('wishlistItems');
        let wishlistCount = document.getElementById('wishlistCount');
        let emptyWishlistText = document.getElementById('emptyWishlistText');

        if (!wishlistItems) return;
        wishlistItems.innerHTML = '';

        if (wishlist.length === 0) {
            if (emptyWishlistText) wishlistItems.appendChild(emptyWishlistText);
            if (wishlistCount) wishlistCount.innerText = 0;
            return;
        }

        wishlist.forEach((item, index) => {
            wishlistItems.innerHTML += `
            <div class="wishlist-item-box d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width: 0;">
                    <img src="${item.image}" style="width:65px; height:65px; object-fit:cover; border-radius:14px; border: 1px solid rgba(255,45,122,0.08); flex-shrink: 0;">
                    <div style="min-width: 0;">
                        <h6 class="fw-bold mb-1 text-truncate" style="font-size:15px; color:#111;">${item.name}</h6>
                        <span style="color:#777; font-size:12px; display:block;">Saved Asset</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <button onclick="triggerSingleDownload('${item.image}', '${item.name}')" class="border-0 rounded-circle" title="Download to Device" style="width:34px; height:34px; background:#f1f1f1; color:#111; display:flex; align-items:center; justify-content:center; font-size:14px; transition:0.2s;">
                        📥
                    </button>
                    <button onclick="removeWishlistItem(${index})" class="border-0 bg-transparent text-muted" style="font-size:20px; line-height:1; padding-left:2px;">
                        ×
                    </button>
                </div>
            </div>
        `;
        });

        if (wishlistCount) wishlistCount.innerText = wishlist.length;
    }

    function removeWishlistItem(index) {
        wishlist.splice(index, 1);
        saveWishlistState();
        updateWishlist();
    }

    function triggerSingleDownload(imagePath, filename) {
        let downloadAnchor = document.createElement('a');
        downloadAnchor.href = imagePath;
        downloadAnchor.download = filename.replace(/\s+/g, '-').toLowerCase() || 'lookncook-gallery';
        document.body.appendChild(downloadAnchor);
        downloadAnchor.click();
        document.body.removeChild(downloadAnchor);
    }

    function downloadAllImages() {
        if (wishlist.length === 0) {
            alert("Your saved collection is empty!");
            return;
        }
        wishlist.forEach((item, idx) => {
            setTimeout(() => {
                triggerSingleDownload(item.image, item.name);
            }, idx * 250);
        });
    }
</script>
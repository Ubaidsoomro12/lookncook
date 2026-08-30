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
                <span style="font-size:30px; font-weight:800; letter-spacing:2px; color:#ff2d7a; text-transform:uppercase; line-height:1;">LOOK N</span>
                <span style="font-size:30px; font-weight:800; letter-spacing:2px; color:#111; text-transform:uppercase; line-height:1;">COOK</span>
                <span style="font-size:10px; letter-spacing:4px; color:#888; margin-top:4px; text-transform:uppercase; font-weight:600;">Premium Catering</span>
            </div>
        </a>

        <!-- ====== TOGGLER - SIRF SMALL SCREEN PE DIKHEGA ====== -->
        <button class="navbar-toggler toggler-custom border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
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
                    
                    <script>
                        (function() {
                            try {
                                const stored = localStorage.getItem('look_n_cook_cart');
                                if (stored) {
                                    const parsed = JSON.parse(stored);
                                    const count = parsed.reduce((sum, item) => sum + (item.quantity || 1), 0);
                                    const el = document.getElementById('cartCount');
                                    if (el && count > 0) el.innerText = count;
                                }
                            } catch (e) {}
                        })();
                    </script>
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
                        .user-profile-dropdown:hover, .user-profile-dropdown:focus, .user-profile-dropdown.show {
                            background: #ff2d7a !important;
                            color: #ffffff !important;
                            transform: translateY(-2px);
                            box-shadow: 0 10px 24px rgba(255, 45, 122, 0.25) !important;
                        }
                        .user-profile-dropdown:hover i, .user-profile-dropdown.show i {
                            color: #ffffff !important;
                        }
                        .custom-dropdown-menu {
                            border: none !important;
                            border-radius: 18px !important;
                            padding: 10px;
                            min-width: 180px;
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
                    <a href="{{ route('login') }}" class="login-btn">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- ====== CART SIDEBAR ====== -->
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
        transition:0.4s cubic-bezier(.25,1,.5,1);
        box-shadow:-10px 0 40px rgba(0,0,0,0.12);
        display:flex;
        flex-direction:column;
     ">
    <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
        <h4 class="fw-bold mb-0">Your Cart</h4>
        <button onclick="closeCart()" class="btn p-0 border-0 shadow-none"
                style="font-size:32px; color:#ff2d7a;">
            ×
        </button>
    </div>

    <div id="cartItems" class="flex-grow-1 overflow-auto p-4">
        <div class="text-center text-secondary mt-5" id="emptyCartText">
            <div style="font-size:70px; opacity:0.2;">🛒</div>
            <h5 class="fw-bold mt-3" style="color:#111;">Your Cart Is Empty</h5>
            <p style="color:#777; font-size:14px;">Add delicious food items now.</p>
        </div>
    </div>

    <div class="border-top p-4">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold">Total</h5>
            <h5 class="fw-bold" id="cartTotal">PKR 0</h5>
        </div>
        <a href="{{ route('cart') }}" class="text-decoration-none d-flex align-items-center justify-content-center"
           style="height:52px; background:#ff2d7a; color:#fff; border-radius:14px; font-weight:600; transition:0.3s; box-shadow:0 10px 25px rgba(255,45,122,0.20);">
            Proceed To Checkout
        </a>
    </div>
</div>

<!-- OVERLAY -->
<div id="cartOverlay" onclick="closeCart()"
     style="
        position:fixed;
        inset:0;
        background:rgba(0,0,0,0.45);
        z-index:999998;
        display:none;
     ">
</div>

<!-- ====== WISHLIST SIDEBAR ====== -->
<div id="wishlistSidebar"
     style="
        position:fixed;
        top:0;
        right:-420px;
        width:400px;
        max-width:100%;
        height:100vh;
        background:linear-gradient(185deg, #fffdfd 0%, #fff5f8 100%);
        z-index:999999;
        transition:0.38s cubic-bezier(0.25, 1, 0.5, 1);
        box-shadow:-15px 0 40px rgba(255,45,122,0.08);
        display:flex;
        flex-direction:column;
     ">
    <div style="padding:25px; border-bottom:1px solid rgba(255,45,122,0.08); display:flex; justify-content:space-between; align-items:center; background: rgba(255,255,255,0.6); backdrop-filter: blur(10px);">
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
            <p style="color:#888; font-size:14px; max-width: 220px; margin: 8px auto 0;">Save beautiful food images from our gallery layout!</p>
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

<!-- Add To Cart Toast Notification -->
<div id="addToCartToast" class="add-cart-toast">
    <div class="toast-check"><i class="fa-solid fa-check"></i></div>
    <img id="toastItemImage" src="" alt="">
    <div class="toast-info">
        <div class="toast-title" id="toastItemName">Item added</div>
        <div class="toast-sub" id="toastItemSub"></div>
        <a href="{{ route('cart') }}" class="toast-cart-link">View Cart →</a>
    </div>
    <button class="toast-close" onclick="hideAddToCartToast()">×</button>
</div>

<style>
    /* ============================================
       TOGGLER - SIRF MOBILE PE DIKHEGA
       ============================================ */
    
    /* Default: Toggler ko hide karein */
    .toggler-custom {
        display: none !important;
    }

    /* Sirf mobile screens (under 992px) pe show */
    @media (max-width: 991.98px) {
        .toggler-custom {
            display: flex !important;
            align-items: center;
            justify-content: center;
            padding: 8px 12px !important;
            border: 2px solid #ff2d7a !important;
            border-radius: 10px !important;
            background: rgba(255, 45, 122, 0.08) !important;
            transition: all 0.3s ease;
        }

        .toggler-custom:hover {
            background: rgba(255, 45, 122, 0.15) !important;
            transform: scale(1.05);
        }

        .toggler-custom:focus {
            box-shadow: 0 0 0 3px rgba(255, 45, 122, 0.25) !important;
            outline: none !important;
        }

        .toggler-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 45, 122, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2.5' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
            width: 26px !important;
            height: 26px !important;
            display: inline-block !important;
        }
    }

    /* Extra small screens pe thoda chhota */
    @media (max-width: 575.98px) {
        .toggler-custom {
            padding: 6px 10px !important;
        }
        .toggler-custom .navbar-toggler-icon {
            width: 22px !important;
            height: 22px !important;
        }
    }

    /* ============================================
       EXISTING STYLES (Kuch change nahi)
    ============================================ */
    
    .topbar {
        background: #000;
        color: #fff;
        font-size: 13px;
        padding: 8px 0;
    }

    .custom-navbar {
        background: #fff;
        padding: 16px 0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        z-index: 999;
        transition: 0.3s;
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

    .cart-btn-custom:hover, .wishlist-btn-custom:hover {
        transform: translateY(-2px) scale(1.04);
        background: rgba(255, 45, 122, 0.18) !important;
        box-shadow: 0 10px 24px rgba(255, 45, 122, 0.20) !important;
    }
    .cart-btn-custom:active, .wishlist-btn-custom:active { transform: scale(0.95); }

    .cart-item-box {
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 15px;
        background: #fff;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .cart-item-box:hover { box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06); }

    .cart-qty-control {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fff0f6;
        border-radius: 10px;
        padding: 2px 6px;
    }
    .cart-qty-control button {
        border: none;
        background: transparent;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff2d7a;
        font-weight: bold;
        font-size: 15px;
        border-radius: 8px;
        transition: 0.2s;
        cursor: pointer;
    }
    .cart-qty-control button:hover { background: rgba(255,45,122,0.12); }
    .cart-qty-control span {
        min-width: 28px;
        text-align: center;
        font-weight: 600;
        color: #111;
        font-size: 15px;
    }

    @media (max-width: 480px) {
        #cartSidebar { width: 100%; right: -100%; }
        .cart-item-box { flex-wrap: wrap; }
    }

    .add-cart-toast {
        position: fixed;
        top: 110px;
        right: -420px;
        width: 340px;
        max-width: calc(100% - 30px);
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.18);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        z-index: 999999;
        transition: right 0.4s cubic-bezier(.25,1,.5,1), opacity 0.4s ease;
        opacity: 0;
        border-left: 5px solid #ff2d7a;
    }
    .add-cart-toast.show { right: 20px; opacity: 1; }
    .add-cart-toast .toast-check { width: 42px; height: 42px; border-radius: 50%; background: rgba(255,45,122,0.12); color: #ff2d7a; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .add-cart-toast img { width: 50px; height: 50px; border-radius: 12px; object-fit: cover; flex-shrink: 0; }
    .add-cart-toast .toast-info { flex: 1; min-width: 0; }
    .add-cart-toast .toast-title { font-weight: 700; color: #111; font-size: 14px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .add-cart-toast .toast-sub { font-size: 12px; color: #777; margin-bottom: 4px; }
    .add-cart-toast .toast-cart-link { color: #ff2d7a; font-size: 12px; font-weight: 700; text-decoration: none; }
    .add-cart-toast .toast-close { border: none; background: transparent; color: #aaa; font-size: 20px; cursor: pointer; line-height: 1; align-self: flex-start; }
    
    @media (max-width: 480px) {
        .add-cart-toast { width: calc(100% - 20px); right: -100%; }
        .add-cart-toast.show { right: 10px; }
    }
</style>

<script>
    // ----- Global State -----
    let cart = [];
    let wishlist = [];
    let toastTimer = null;

    document.addEventListener("DOMContentLoaded", function () {
        syncCartFromStorage();
        syncWishlistFromStorage();
        updateCartUI();
        updateWishlistUI();
    });

    // ----- CART SYNC & PERSISTENCE -----
    function syncCartFromStorage() {
        const stored = localStorage.getItem("look_n_cook_cart");
        if (stored) {
            try { cart = JSON.parse(stored); } catch (e) { cart = []; }
        } else {
            cart = [];
        }
    }

    function saveCartToStorage() {
        localStorage.setItem("look_n_cook_cart", JSON.stringify(cart));
        window.dispatchEvent(new Event('cartUpdated'));
    }

    // ----- CART UI UPDATE -----
    function updateCartUI() {
        syncCartFromStorage();

        const container = document.getElementById('cartItems');
        const countEl = document.getElementById('cartCount');
        const totalEl = document.getElementById('cartTotal');
        const emptyMsg = document.getElementById('emptyCartText');

        if (!container) return;

        const emptyClone = emptyMsg ? emptyMsg.cloneNode(true) : null;
        container.innerHTML = '';

        if (cart.length === 0) {
            if (emptyClone) container.appendChild(emptyClone);
            if (countEl) countEl.innerText = '0';
            if (totalEl) totalEl.innerText = '0';
            return;
        }

        let total = 0;
        let totalCount = 0;

        cart.forEach((item, index) => {
            const qty = item.quantity || 1;
            const lineTotal = item.price * qty;
            total += lineTotal;
            totalCount += qty;

            const itemDiv = document.createElement('div');
            itemDiv.className = 'cart-item-box';
            itemDiv.innerHTML = `
                <img src="${item.image}" style="width:70px; height:70px; object-fit:cover; border-radius:14px; flex-shrink:0;">
                <div style="flex-grow:1; min-width:0;">
                    <h6 class="fw-bold mb-1" style="font-size:15px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.name}</h6>
                    <span style="color:#ff2d7a; font-weight:600; font-size:14px;">PKR ${lineTotal}</span>
                </div>
                <div class="cart-qty-control">
                    <button onclick="changeCartQty(${index}, -1)">-</button>
                    <span>${qty}</span>
                    <button onclick="changeCartQty(${index}, 1)">+</button>
                </div>
                <button onclick="removeCartItem(${index})" class="border-0 bg-transparent" style="color:red; font-size:20px; line-height:1; padding:0 4px;">×</button>
            `;
            container.appendChild(itemDiv);
        });

        if (countEl) countEl.innerText = totalCount;
        if (totalEl) totalEl.innerText = total.toLocaleString();
    }

    // ----- CART ACTIONS -----
    function addToCart(name, price, image, quantity = 1) {
        syncCartFromStorage();

        const key = name.trim();
        const existingIndex = cart.findIndex(item => item.name.trim() === key);

        if (existingIndex !== -1) {
            cart[existingIndex].quantity = (cart[existingIndex].quantity || 1) + quantity;
        } else {
            cart.push({
                name: key,
                price: parseInt(price),
                image: image,
                quantity: quantity
            });
        }

        saveCartToStorage();
        updateCartUI();
        showAddToCartToast(key, image, price, quantity);
    }

    function changeCartQty(index, delta) {
        syncCartFromStorage();
        if (!cart[index]) return;

        const newQty = (cart[index].quantity || 1) + delta;
        if (newQty <= 0) {
            cart.splice(index, 1);
        } else {
            cart[index].quantity = newQty;
        }

        saveCartToStorage();
        updateCartUI();
    }

    function removeCartItem(index) {
        syncCartFromStorage();
        cart.splice(index, 1);
        saveCartToStorage();
        updateCartUI();
    }

    // ----- CART SIDEBAR TOGGLES -----
    function openCart() {
        closeWishlist();
        document.getElementById('cartSidebar').style.right = '0';
        document.getElementById('cartOverlay').style.display = 'block';
        updateCartUI();
    }

    function closeCart() {
        document.getElementById('cartSidebar').style.right = '-420px';
        document.getElementById('cartOverlay').style.display = 'none';
    }

    function openWishlist() {
        closeCart();
        document.getElementById('wishlistSidebar').style.right = '0';
        updateWishlistUI();
    }

    function closeWishlist() {
        document.getElementById('wishlistSidebar').style.right = '-420px';
    }

    // ----- TOAST -----
    function showAddToCartToast(name, image, price, quantity = 1) {
        const toast = document.getElementById('addToCartToast');
        if (!toast) return;

        document.getElementById('toastItemImage').src = image;
        document.getElementById('toastItemName').textContent = name;
        document.getElementById('toastItemSub').textContent = `Qty: ${quantity} • PKR ${parseInt(price)}`;

        toast.classList.add('show');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function hideAddToCartToast() {
        const toast = document.getElementById('addToCartToast');
        if (toast) toast.classList.remove('show');
        if (toastTimer) clearTimeout(toastTimer);
    }

    // ----- WISHLIST -----
    function syncWishlistFromStorage() {
        const stored = localStorage.getItem("look_n_cook_wishlist");
        if (stored) {
            try { wishlist = JSON.parse(stored); } catch (e) { wishlist = []; }
        } else {
            wishlist = [];
        }
    }

    function saveWishlistToStorage() {
        localStorage.setItem("look_n_cook_wishlist", JSON.stringify(wishlist));
    }

    function updateWishlistUI() {
        syncWishlistFromStorage();
        const container = document.getElementById('wishlistItems');
        const countEl = document.getElementById('wishlistCount');
        const emptyMsg = document.getElementById('emptyWishlistText');

        if (!container) return;
        const emptyClone = emptyMsg ? emptyMsg.cloneNode(true) : null;
        container.innerHTML = '';

        if (wishlist.length === 0) {
            if (emptyClone) container.appendChild(emptyClone);
            if (countEl) countEl.innerText = '0';
            return;
        }

        wishlist.forEach((item, index) => {
            container.innerHTML += `
                <div class="wishlist-item-box d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width:0;">
                        <img src="${item.image}" style="width:65px; height:65px; object-fit:cover; border-radius:14px; border:1px solid rgba(255,45,122,0.08); flex-shrink:0;">
                        <div style="min-width:0;">
                            <h6 class="fw-bold mb-1 text-truncate" style="font-size:15px; color:#111;">${item.name}</h6>
                            <span style="color:#777; font-size:12px; display:block;">Saved Asset</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <button onclick="triggerSingleDownload('${item.image}', '${item.name}')" class="border-0 rounded-circle" title="Download" style="width:34px; height:34px; background:#f1f1f1; color:#111; display:flex; align-items:center; justify-content:center; font-size:14px; transition:0.2s;">📥</button>
                        <button onclick="removeWishlistItem(${index})" class="border-0 bg-transparent text-muted" style="font-size:20px; line-height:1; padding-left:2px;">×</button>
                    </div>
                </div>
            `;
        });
        if (countEl) countEl.innerText = wishlist.length;
    }

    function addToWishlist(name, price, image) {
        syncWishlistFromStorage();
        if (wishlist.some(item => item.image === image)) return;
        wishlist.push({ name, price, image });
        saveWishlistToStorage();
        updateWishlistUI();
    }

    function removeWishlistItem(index) {
        syncWishlistFromStorage();
        wishlist.splice(index, 1);
        saveWishlistToStorage();
        updateWishlistUI();
    }

    function triggerSingleDownload(imagePath, filename) {
        const a = document.createElement('a');
        a.href = imagePath;
        a.download = filename.replace(/\s+/g, '-').toLowerCase() || 'lookncook-gallery';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    function downloadAllImages() {
        if (wishlist.length === 0) { alert("Your saved collection is empty!"); return; }
        wishlist.forEach((item, idx) => {
            setTimeout(() => triggerSingleDownload(item.image, item.name), idx * 250);
        });
    }
</script>
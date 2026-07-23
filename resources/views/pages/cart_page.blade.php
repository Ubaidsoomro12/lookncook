@extends('layouts.master')
@section('title', 'Contact')


@section('content')
    @include('components.banner', [
        'miniTitle' => 'LOOK N COOK',
        'title' => 'Shopping Cart',
        'highlight' => 'Slect Delicious Food',
        'description' => 'Review your selected delicious items and continue to secure checkout'
    ])


<section class="cart-page-section" style="padding: 80px 0; background: #fcfcfc;">
    <div class="container">
        <div class="row g-4 justify-content-center">
            
            <div class="col-lg-8">
                <div class="cart-box" style="background: #fff; padding: 30px; border-radius: 24px; box-shadow: 0 10px 35px rgba(0,0,0,0.03);">
                    <h3 class="cart-heading fw-bold mb-4" style="color: #111;">Your Cart Items</h3>
                    
                    <div id="mainPageCartItems">
                        </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-box" style="background: #fff; padding: 30px; border-radius: 24px; box-shadow: 0 10px 35px rgba(0,0,0,0.03); border: 1px solid rgba(255,45,122,0.05); position: sticky; top: 110px;">
                    <h3 class="fw-bold mb-4" style="color: #111; font-size: 22px;">Order Summary</h3>
                    
                    <div class="summary-row d-flex justify-content-between mb-3" style="font-size: 16px; color: #555;">
                        <span>Total Quantity</span>
                        <span id="summaryTotalCount" class="fw-bold" style="color: #111;">0</span>
                    </div>
                    
                    <div class="summary-row d-flex justify-content-between mb-3" style="font-size: 16px; color: #555;">
                        <span>Subtotal</span>
                        <span class="fw-bold" style="color: #111;">PKR <span id="summarySubtotal">0</span></span>
                    </div>
                    
                    <div class="summary-row d-flex justify-content-between mb-3" style="font-size: 16px; color: #555;">
                        <span>Delivery</span>
                        <span class="text-success fw-semibold">Free</span>
                    </div>
                    
                    <hr style="border-color: rgba(0,0,0,0.06); margin: 20px 0;">
                    
                    <div class="summary-row total d-flex justify-content-between mb-4">
                        <h5 class="fw-bold mb-0">Total Amount</h5>
                        <h5 class="fw-bold mb-0" style="color: #ff2d7a;">PKR <span id="summaryTotalAmount">0</span></h5>
                    </div>
                    
                    <a href="{{ route('payment') }}" id="paymentProceedBtn" class="checkout-btn text-decoration-none d-flex align-items-center justify-content-center" style="height: 54px; background: #ff2d7a; color: #fff; border-radius: 16px; font-weight: 600; box-shadow: 0 10px 25px rgba(255,45,122,0.25); transition: 0.3s ease;">
                        Proceed To Payment
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
/* CSS RULES PROVIDING ALIGNED ELEMENT CONTROLS */
.main-cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    gap: 15px;
}
.main-cart-item:last-child {
    border-bottom: none;
}
.main-cart-img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 18px;
}
.page-quantity-box {
    display: flex;
    align-items: center;
    background: #fff0f6;
    border-radius: 12px;
    padding: 4px;
}
.page-quantity-box button {
    border: none;
    background: transparent;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ff2d7a;
    font-weight: bold;
    font-size: 16px;
    border-radius: 8px;
    transition: 0.2s;
}
.page-quantity-box button:hover {
    background: rgba(255,45,122,0.1);
}
.page-quantity-box span {
    width: 35px;
    text-align: center;
    font-weight: 600;
    color: #111;
}
.checkout-btn:hover {
    background: #e02066 !important;
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(255,45,122,0.35) !important;
}
@media(max-width: 767.98px) {
    .main-cart-item {
        flex-direction: column;
        align-items: flex-start;
    }
    .main-cart-right {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
}
</style>

<script>
// Main Page LocalStorage Processing Script Implementation
document.addEventListener("DOMContentLoaded", function() {
    renderPageCart();

    // Re-verify updates across shared operations
    window.addEventListener('cartUpdated', function() {
        renderPageCart();
    });
});

function renderPageCart() {
    let localCart = [];
    if(localStorage.getItem("look_n_cook_cart")) {
        try {
            localCart = JSON.parse(localStorage.getItem("look_n_cook_cart"));
        } catch(e) {
            localCart = [];
        }
    }

    let targetContainer = document.getElementById("mainPageCartItems");
    let summaryQty = document.getElementById("summaryTotalCount");
    let summarySub = document.getElementById("summarySubtotal");
    let summaryTotal = document.getElementById("summaryTotalAmount");
    let paymentBtn = document.getElementById("paymentProceedBtn");

    if(!targetContainer) return;

    if(localCart.length === 0) {
        targetContainer.innerHTML = `
            <div class="text-center py-5">
                <div style="font-size:80px; opacity: 0.25;">🛒</div>
                <h4 class="fw-bold mt-4" style="color:#111;">Your Shopping Cart is Empty</h4>
                <p style="color:#777;">Head back to our menu to discover premium catering delicacies.</p>
                <a href="{{ route('menu') }}" class="btn mt-3" style="background:#ff2d7a; color:#fff; border-radius:12px; padding: 10px 24px; font-weight:600;">Browse Our Menu</a>
            </div>
        `;
        if(summaryQty) summaryQty.innerText = "0";
        if(summarySub) summarySub.innerText = "0";
        if(summaryTotal) summaryTotal.innerText = "0";
        if(paymentBtn) {
            paymentBtn.style.pointerEvents = "none";
            paymentBtn.style.opacity = "0.5";
        }
        return;
    }

    if(paymentBtn) {
        paymentBtn.style.pointerEvents = "auto";
        paymentBtn.style.opacity = "1";
    }

    targetContainer.innerHTML = '';
    let globalTotal = 0;
    let globalQty = 0;

    localCart.forEach((item, index) => {
        let itemQty = item.quantity || 1;
        let lineTotal = item.price * itemQty;
        globalTotal += lineTotal;
        globalQty += itemQty;

        targetContainer.innerHTML += `
            <div class="main-cart-item">
                <div class="d-flex align-items-center gap-3">
                    <img src="${item.image}" class="main-cart-img">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#111;">${item.name}</h5>
                        <p class="mb-0" style="color:#777; font-size:14px;">Premium Catering Dish</p>
                    </div>
                </div>
                <div class="main-cart-right d-flex align-items-center gap-4">
                    <div class="page-quantity-box">
                        <button onclick="modifyPageQty(${index}, -1)">-</button>
                        <span>${itemQty}</span>
                        <button onclick="modifyPageQty(${index}, 1)">+</button>
                    </div>
                    <div class="text-end" style="min-width: 100px;">
                        <h5 class="fw-bold mb-0" style="color:#ff2d7a;">PKR ${lineTotal}</h5>
                        <small class="text-muted" style="font-size:11px;">PKR ${item.price} each</small>
                    </div>
                    <button onclick="deletePageItem(${index})" class="border-0 bg-transparent text-danger fw-bold" style="font-size:22px; line-height:1;">×</button>
                </div>
            </div>
        `;
    });

    if(summaryQty) summaryQty.innerText = globalQty;
    if(summarySub) summarySub.innerText = globalTotal;
    if(summaryTotal) summaryTotal.innerText = globalTotal;
}

function modifyPageQty(index, offset) {
    let localCart = JSON.parse(localStorage.getItem("look_n_cook_cart")) || [];
    if(!localCart[index]) return;

    let targetQty = (localCart[index].quantity || 1) + offset;
    if(targetQty <= 0) {
        localCart.splice(index, 1);
    } else {
        localCart[index].quantity = targetQty;
    }

    localStorage.setItem("look_n_cook_cart", JSON.stringify(localCart));
    renderPageCart();
    
    // Sync update back onto layout header element references implicitly
    if(typeof updateCart === "function") {
        updateCart();
    }
}

function deletePageItem(index) {
    let localCart = JSON.parse(localStorage.getItem("look_n_cook_cart")) || [];
    localCart.splice(index, 1);
    localStorage.setItem("look_n_cook_cart", JSON.stringify(localCart));
    renderPageCart();
    
    if(typeof updateCart === "function") {
        updateCart();
    }
}
</script>
@endsection
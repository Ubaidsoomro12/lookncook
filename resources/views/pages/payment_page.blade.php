

@extends('layouts.master')
@section('title', 'Contact')


@section('content')
    @include('components.banner', [
        'miniTitle' => 'LOOK N COOK',
        'title' => 'Payment Checkout',
        'highlight' => 'Orde Now',
        'description' => 'Complete your order details and proceed with secure payment.'
    ])




<section class="payment-page-section" style="padding: 100px 0; background: #f7f7f7; min-height: 100vh;">

<div class="container">

<div class="row g-4">

<div class="col-lg-8">

<div class="payment-form-box" style="background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">

<h2 class="payment-title" style="font-size: 35px; font-weight: 800; margin-bottom: 30px;">

Checkout Details

</h2>

<form id="checkoutForm">
@csrf

<div class="row">

<div class="col-md-6 mb-4">

<label class="payment-label" style="font-weight: 600; margin-bottom: 10px; display: block;">

Full Name

</label>

<input type="text"
class="form-control payment-input"
placeholder="Enter your full name"
style="height: 60px; border-radius: 12px; border: 1px solid #ddd; padding-left: 20px;"
required>

</div>

<div class="col-md-6 mb-4">

<label class="payment-label" style="font-weight: 600; margin-bottom: 10px; display: block;">

Phone Number

</label>

<input type="text"
class="form-control payment-input"
placeholder="Enter phone number"
style="height: 60px; border-radius: 12px; border: 1px solid #ddd; padding-left: 20px;"
required>

</div>

<div class="col-md-6 mb-4">

<label class="payment-label" style="font-weight: 600; margin-bottom: 10px; display: block;">

Email

</label>

<input type="email"
class="form-control payment-input"
placeholder="Enter email"
style="height: 60px; border-radius: 12px; border: 1px solid #ddd; padding-left: 20px;"
required>

</div>

<div class="col-md-6 mb-4">

<label class="payment-label" style="font-weight: 600; margin-bottom: 10px; display: block;">

City

</label>

<input type="text"
class="form-control payment-input"
placeholder="Enter city"
style="height: 60px; border-radius: 12px; border: 1px solid #ddd; padding-left: 20px;"
required>

</div>

<div class="col-12 mb-4">

<label class="payment-label" style="font-weight: 600; margin-bottom: 10px; display: block;">

Delivery Address

</label>

<textarea class="form-control payment-textarea"
placeholder="Enter complete address"
style="height: 150px; border-radius: 12px; padding: 20px; resize: none;"
required></textarea>

</div>

</div>

<h4 class="method-title" style="margin: 30px 0 20px; font-weight: 700;">

Select Payment Method

</h4>

<div class="payment-methods" style="display: flex; gap: 20px; flex-wrap: wrap;">

<div class="payment-card active-method">

💵 Cash On Delivery

</div>

<div class="payment-card">

💳 Credit Card

</div>

<div class="payment-card">

📱 EasyPaisa / JazzCash

</div>

</div>

<button type="submit"
class="place-order-btn"
style="width: 100%; margin-top: 40px; height: 60px; border: none; border-radius: 12px; background: #ff2d7a; color: white; font-size: 18px; font-weight: 700; transition: .4s;">

Place Order

</button>

</form>

</div>

</div>

<div class="col-lg-4">

<div class="payment-summary" style="background: #081224; padding: 35px; border-radius: 20px; color: white; position: sticky; top: 120px;">

<h3 style="margin-bottom: 30px; font-weight: 800;">

Order Summary

</h3>

<div id="paymentPageCartItems"></div>

<hr style="border-color: rgba(255,255,255,0.1);">

<div class="summary-item">

<span>Delivery</span>

<span>Free</span>

</div>

<hr style="border-color: rgba(255,255,255,0.1);">

<div class="summary-item total" style="font-size: 22px; font-weight: 800; color: #ff2d7a;">

<span>Total</span>

<span>PKR <span id="paymentTotalAmount">0</span></span>

</div>

</div>

</div>

</div>

</div>

</section>


<style>
.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 18px;
}
.payment-input:focus,
.payment-textarea:focus {
    border-color: #ff2d7a !important;
    box-shadow: none !important;
}
.payment-card {
    padding: 15px 25px;
    border: 2px solid #eee;
    border-radius: 12px;
    cursor: pointer;
    transition: .4s;
    font-weight: 600;
    background: #fff;
}
.active-method {
    border-color: #ff2d7a !important;
    background: #fff0f7 !important;
    color: #ff2d7a !important;
}
.place-order-btn:hover {
    background: #e01d65 !important;
}
@media(max-width:768px) {
    .payment-form-box {
        padding: 25px !important;
    }
    .payment-title {
        font-size: 28px !important;
    }
    .payment-methods {
        flex-direction: column;
    }
    .payment-card {
        text-align: center;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    renderPaymentSummary();
});

function renderPaymentSummary() {
    let localCart = [];
    
    // Read from the exact storage key used on your cart page
    if(localStorage.getItem("look_n_cook_cart")) {
        try {
            localCart = JSON.parse(localStorage.getItem("look_n_cook_cart"));
        } catch(e) {
            localCart = [];
        }
    }

    let targetContainer = document.getElementById("paymentPageCartItems");
    let totalContainer = document.getElementById("paymentTotalAmount");

    if(!targetContainer) return;

    if(localCart.length === 0) {
        targetContainer.innerHTML = `
            <div class="summary-item">
                <span>No items selected</span>
                <span>PKR 0</span>
            </div>
        `;
        if(totalContainer) totalContainer.innerText = "0";
        return;
    }

    targetContainer.innerHTML = '';
    let globalTotal = 0;

    localCart.forEach((item) => {
        let itemQty = item.quantity || 1;
        let lineTotal = item.price * itemQty;
        globalTotal += lineTotal;

        targetContainer.innerHTML += `
            <div class="summary-item">
                <span>${item.name} (x${itemQty})</span>
                <span>PKR ${lineTotal}</span>
            </div>
        `;
    });

    if(totalContainer) totalContainer.innerText = globalTotal;
}
</script>

@endsection
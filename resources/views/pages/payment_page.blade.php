@extends('layouts.master')
@section('title', 'Payment')

@section('content')
    @include('components.banner', [
        'miniTitle' => 'LOOK N COOK',
        'title' => isset($orderConfirmed) && $orderConfirmed ? 'Order Confirmed! 🎉' : 'Payment Checkout',
        'highlight' => isset($orderConfirmed) && $orderConfirmed ? 'Thank You' : 'Order Now',
        'description' => isset($orderConfirmed) && $orderConfirmed
            ? 'Your order has been placed successfully.'
            : 'Complete your order details and proceed with secure payment.'
    ])

    <section class="payment-page-section">
        <div class="container">
            <div class="row justify-content-center">

                @if(isset($orderConfirmed) && $orderConfirmed)
                    {{-- ========== CONFIRMATION MODE ========== --}}

                    {{-- Thank-you popup: centered, auto-dismisses after 3 seconds --}}
                    <div id="thankYouOverlay" class="thank-you-overlay">
                        <div class="thank-you-box">
                            <div class="thank-you-icon">🎉</div>
                            <h3>Thanks for shopping with us!</h3>
                            <p>Your order <strong>{{ $order->order_number }}</strong> has been placed successfully.</p>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="success-card">
                            <div class="success-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h2 class="success-title">Order Placed Successfully!</h2>
                            <p class="success-message">
                                Thank you for your order. We will process it as soon as possible.
                            </p>

                            <div class="order-details-box">
                                <h4><i class="bi bi-receipt"></i> Order Details</h4>
                                <table class="order-table">
                                    <tr>
                                        <td><strong>Order Number</strong></td>
                                        <td><span class="badge-order">{{ $order->order_number }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Amount</strong></td>
                                        <td><span class="amount-highlight">PKR {{ number_format($order->total_amount, 2) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method</strong></td>
                                        <td>
                                            @if($order->payment_method_slug === 'cod')
                                                Cash on Delivery
                                            @elseif($order->payment_method_slug === 'safepay')
                                                Safepay
                                            @else
                                                {{ ucfirst($order->payment_method_slug) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Status</strong></td>
                                        <td>
                                            @if($order->payment_status === 'paid')
                                                <span class="status-badge success">Paid</span>
                                            @elseif($order->payment_status === 'pending')
                                                <span class="status-badge warning">Pending</span>
                                            @else
                                                <span class="status-badge danger">{{ ucfirst($order->payment_status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Delivery Address</strong></td>
                                        <td>{{ $order->delivery_address }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer</strong></td>
                                        <td>{{ $order->customer_name }} ({{ $order->customer_phone }})</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="success-actions">
                                <a href="{{ route('home') }}" class="btn-home">
                                    <i class="bi bi-house-door"></i> Back to Home
                                </a>
                                <a href="{{ route('menu') }}" class="btn-menu">
                                    <i class="bi bi-grid"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- ========== CHECKOUT FORM MODE ========== --}}
                    <div class="col-lg-8">
                        <div class="payment-form-box">

                            <div class="form-box-header">
                                <h2 class="payment-title">Checkout Details</h2>
                                <p class="payment-subtitle">Fill in your information to complete the order</p>
                            </div>

                            <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
                                @csrf

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Hidden fields --}}
                                <input type="hidden" name="payment_method" id="paymentMethodInput" value="cod">
                                <input type="hidden" name="cart_data" id="cartDataInput" value="">
                                <input type="hidden" name="bank_name" id="bankNameInput">
                                <input type="hidden" name="account_title" id="accountTitleInput">
                                <input type="hidden" name="account_number" id="accountNumberInput">
                                <input type="hidden" name="transaction_ref" id="transactionRefInput">

                                <h6 class="section-label"><i class="bi bi-person-lines-fill"></i> Contact Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="payment-label">Full Name</label>
                                        <input type="text" name="full_name" class="form-control payment-input" placeholder="Enter your full name" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="payment-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control payment-input" placeholder="Enter phone number" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="payment-label">Email</label>
                                        <input type="email" name="email" class="form-control payment-input" placeholder="Enter email" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="payment-label">City</label>
                                        <input type="text" name="city" class="form-control payment-input" placeholder="Enter city" required>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label class="payment-label">Delivery Address</label>
                                        <textarea name="address" class="form-control payment-textarea" placeholder="Enter complete address" required></textarea>
                                    </div>
                                </div>

                                <hr class="section-divider">

                                <h6 class="section-label"><i class="bi bi-wallet2"></i> Select Payment Method</h6>

                                <div class="payment-methods">
                                    @foreach($paymentMethods as $method)
                                        @php
                                            $icon = $method->slug === 'cod' ? '💵' : '🏦';
                                            $dataMethod = $method->slug === 'cod' ? 'cod' : 'bank';
                                            $isActive = $loop->first ? 'active-method' : '';
                                        @endphp
                                        <div class="payment-card {{ $isActive }}" data-method="{{ $dataMethod }}">
                                            <div class="payment-card-icon">{{ $icon }}</div>
                                            <div class="payment-card-text">
                                                <span class="payment-card-title">{{ $method->name }}</span>
                                                <span class="payment-card-desc">{{ $method->description }}</span>
                                            </div>
                                            <div class="payment-card-check"><i class="bi bi-check-circle-fill"></i></div>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="bankDetailsBox" class="bank-details-box">
                                    <h6 class="bank-box-title"><i class="bi bi-bank"></i> Your Bank Account Details</h6>
                                    <p class="bank-box-note">Enter the account you'll be transferring from, so we can verify and confirm your payment.</p>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="payment-label">Bank Name</label>
                                            <select name="bank_name_select" class="form-select payment-input bank-field">
                                                <option value="">Select your bank</option>
                                                <option>HBL - Habib Bank Limited</option>
                                                <option>UBL - United Bank Limited</option>
                                                <option>MCB Bank</option>
                                                <option>Meezan Bank</option>
                                                <option>Allied Bank</option>
                                                <option>Bank Alfalah</option>
                                                <option>Faysal Bank</option>
                                                <option>Standard Chartered</option>
                                                <option>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="payment-label">Account Title</label>
                                            <input type="text" name="account_title_input" class="form-control payment-input bank-field" placeholder="Name on the account">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="payment-label">Account Number / IBAN</label>
                                            <input type="text" name="account_number_input" class="form-control payment-input bank-field" placeholder="e.g. PK00XXXX0000000000000000">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="payment-label">Transaction / Reference ID <span class="optional-tag">(optional)</span></label>
                                            <input type="text" name="transaction_ref_input" class="form-control payment-input bank-field" placeholder="If you've already transferred">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="place-order-btn">
                                    <i class="bi bi-bag-check-fill me-2"></i> Place Order
                                </button>
                            </form>

                        </div>
                    </div>
                    {{-- RIGHT: ORDER SUMMARY --}}
                    <div class="col-lg-4">
                        <div class="payment-summary">
                            <h3 class="summary-heading"><i class="bi bi-receipt"></i> Order Summary</h3>
                            <div id="paymentPageCartItems"></div>
                            <hr class="summary-divider">
                            <div class="summary-item">
                                <span>Delivery</span>
                                <span class="text-success-soft">Free</span>
                            </div>
                            <hr class="summary-divider">
                            <div class="summary-item total">
                                <span>Total</span>
                                <span>PKR <span id="paymentTotalAmount">0</span></span>
                            </div>
                            <div class="summary-badge">
                                <i class="bi bi-shield-check"></i> Secure & simple checkout
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

    {{-- ====== Styles & Scripts (only for form mode) ====== --}}
    @if(!isset($orderConfirmed) || !$orderConfirmed)
        <style>
            .payment-page-section { padding: 80px 0 100px; background: #fff7fb; min-height: 100vh; }
            .payment-form-box { background: #fff; padding: 40px; border-radius: 28px; box-shadow: 0 10px 35px rgba(0,0,0,0.05); }
            .form-box-header { margin-bottom: 30px; }
            .payment-title { font-size: 32px; font-weight: 800; color: #111; margin-bottom: 6px; }
            .payment-subtitle { color: #777; font-size: 14.5px; margin-bottom: 0; }
            .section-label { font-weight: 800; color: #ff2d7a; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
            .section-divider { border-color: rgba(0,0,0,0.06); margin: 30px 0; }
            .payment-label { font-weight: 600; margin-bottom: 8px; display: block; color: #333; font-size: 14px; }
            .payment-input, .payment-textarea { border-radius: 12px; border: 1.5px solid #eee; background: #fbfbfb; transition: 0.25s ease; }
            .form-control.payment-input, .form-select.payment-input { height: 56px; padding-left: 18px; }
            .payment-textarea { height: 130px; padding: 16px 18px; resize: none; }
            .payment-input:focus, .payment-textarea:focus { border-color: #ff2d7a !important; background: #fff; box-shadow: 0 0 0 4px rgba(255,45,122,0.08) !important; }
            .optional-tag { color: #aaa; font-weight: 500; font-size: 12px; }
            .payment-methods { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 10px; }
            .payment-card { position: relative; display: flex; align-items: center; gap: 14px; padding: 18px 20px; border: 2px solid #f0f0f0; border-radius: 16px; cursor: pointer; transition: 0.25s ease; background: #fbfbfb; }
            .payment-card:hover { border-color: rgba(255,45,122,0.35); transform: translateY(-2px); }
            .payment-card-icon { font-size: 26px; line-height: 1; flex-shrink: 0; }
            .payment-card-text { display: flex; flex-direction: column; gap: 2px; }
            .payment-card-title { font-weight: 700; color: #111; font-size: 15px; }
            .payment-card-desc { font-size: 12px; color: #888; }
            .payment-card-check { position: absolute; top: 12px; right: 12px; color: #ff2d7a; font-size: 18px; opacity: 0; transform: scale(0.6); transition: 0.25s ease; }
            .payment-card.active-method { border-color: #ff2d7a; background: #fff0f7; box-shadow: 0 8px 20px rgba(255,45,122,0.12); }
            .payment-card.active-method .payment-card-check { opacity: 1; transform: scale(1); }
            .bank-details-box { max-height: 0; overflow: hidden; opacity: 0; transition: max-height 0.45s ease, opacity 0.35s ease, margin 0.45s ease; background: #fff7fb; border: 1.5px dashed rgba(255,45,122,0.3); border-radius: 18px; padding: 0 22px; margin-top: 0; }
            .bank-details-box.open { max-height: 500px; opacity: 1; padding: 22px; margin-top: 20px; margin-bottom: 10px; }
            .bank-box-title { font-weight: 800; color: #111; font-size: 15px; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
            .bank-box-note { color: #888; font-size: 13px; margin-bottom: 20px; }
            .place-order-btn { width: 100%; margin-top: 30px; height: 58px; border: none; border-radius: 14px; background: #ff2d7a; color: #fff; font-size: 16.5px; font-weight: 700; letter-spacing: 0.3px; transition: 0.3s ease; box-shadow: 0 12px 26px rgba(255,45,122,0.28); }
            .place-order-btn:hover { background: #e01d65; transform: translateY(-2px); box-shadow: 0 16px 32px rgba(255,45,122,0.35); }
            .payment-summary { background: #0f172a; padding: 34px; border-radius: 28px; color: #fff; position: sticky; top: 120px; box-shadow: 0 15px 40px rgba(15,23,42,0.25); }
            .summary-heading { margin-bottom: 26px; font-weight: 800; font-size: 20px; display: flex; align-items: center; gap: 10px; color: #fff; }
            .summary-item { display: flex; justify-content: space-between; margin-bottom: 16px; color: rgba(255,255,255,0.75); font-size: 14.5px; }
            .summary-item.total { font-size: 21px; font-weight: 800; color: #ff2d7a; margin-bottom: 0; }
            .summary-divider { border-color: rgba(255,255,255,0.1); margin: 18px 0; }
            .text-success-soft { color: #4ade80; font-weight: 600; }
            .summary-badge { margin-top: 26px; background: rgba(255,45,122,0.12); border: 1px solid rgba(255,45,122,0.25); color: #ff7fac; font-size: 12.5px; font-weight: 600; padding: 12px 14px; border-radius: 12px; display: flex; align-items: center; gap: 8px; }
            .alert-danger { border-radius: 12px; margin-bottom: 20px; }
            @media(max-width:768px) { .payment-form-box { padding: 24px !important; border-radius: 22px; } .payment-title { font-size: 26px; } .payment-methods { grid-template-columns: 1fr; } .payment-summary { position: relative; top: 0; } }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                renderPaymentSummary();
                setupPaymentMethodToggle();
                setupFormSubmit();
            });

            function setupPaymentMethodToggle() {
                const cards = document.querySelectorAll('.payment-card');
                const bankBox = document.getElementById('bankDetailsBox');
                const bankFields = document.querySelectorAll('.bank-field');

                cards.forEach(card => {
                    card.addEventListener('click', function () {
                        cards.forEach(c => c.classList.remove('active-method'));
                        this.classList.add('active-method');
                        const method = this.getAttribute('data-method');
                        const slug = method === 'bank' ? 'safepay' : 'cod';
                        document.getElementById('paymentMethodInput').value = slug;

                        if (method === 'bank') {
                            bankBox.classList.add('open');
                            bankFields.forEach(f => f.setAttribute('required', 'required'));
                        } else {
                            bankBox.classList.remove('open');
                            bankFields.forEach(f => { f.removeAttribute('required'); f.value = ''; });
                            ['bankNameInput','accountTitleInput','accountNumberInput','transactionRefInput']
                                .forEach(id => document.getElementById(id).value = '');
                        }
                    });
                });
            }

            function renderPaymentSummary() {
                let localCart = [];
                if (localStorage.getItem("look_n_cook_cart")) {
                    try { localCart = JSON.parse(localStorage.getItem("look_n_cook_cart")); } catch(e) { localCart = []; }
                }
                let targetContainer = document.getElementById("paymentPageCartItems");
                let totalContainer = document.getElementById("paymentTotalAmount");
                if (!targetContainer) return;

                if (localCart.length === 0) {
                    targetContainer.innerHTML = `<div class="summary-item"><span>No items selected</span><span>PKR 0</span></div>`;
                    if (totalContainer) totalContainer.innerText = "0";
                    return;
                }

                targetContainer.innerHTML = '';
                let globalTotal = 0;
                localCart.forEach((item) => {
                    let itemQty = item.quantity || 1;
                    let lineTotal = item.price * itemQty;
                    globalTotal += lineTotal;
                    targetContainer.innerHTML += `<div class="summary-item"><span>${item.name} (x${itemQty})</span><span>PKR ${lineTotal}</span></div>`;
                });
                if (totalContainer) totalContainer.innerText = globalTotal;
            }

            function setupFormSubmit() {
                const form = document.getElementById('checkoutForm');
                if (!form) return;
                form.addEventListener('submit', function(e) {
                    const cart = JSON.parse(localStorage.getItem('look_n_cook_cart') || '[]');
                    document.getElementById('cartDataInput').value = JSON.stringify(cart);
                    const method = document.getElementById('paymentMethodInput').value;
                    if (method === 'safepay') {
                        document.getElementById('bankNameInput').value = document.querySelector('select[name="bank_name_select"]')?.value || '';
                        document.getElementById('accountTitleInput').value = document.querySelector('input[name="account_title_input"]')?.value || '';
                        document.getElementById('accountNumberInput').value = document.querySelector('input[name="account_number_input"]')?.value || '';
                        document.getElementById('transactionRefInput').value = document.querySelector('input[name="transaction_ref_input"]')?.value || '';
                    }
                });
            }
        </script>
    @endif

    {{-- ====== Styles & Scripts for confirmation mode ====== --}}
    @if(isset($orderConfirmed) && $orderConfirmed)
        <style>
            .success-card {
                background: #fff;
                padding: 50px 40px;
                border-radius: 28px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.06);
                text-align: center;
            }
            .success-icon { font-size: 72px; color: #28a745; margin-bottom: 16px; }
            .success-title { font-size: 32px; font-weight: 800; color: #111; margin-bottom: 10px; }
            .success-message { color: #555; font-size: 16px; margin-bottom: 35px; }
            .order-details-box { background: #fafafa; border-radius: 16px; padding: 25px 30px; margin-bottom: 30px; text-align: left; }
            .order-details-box h4 { font-weight: 700; color: #111; margin-bottom: 18px; font-size: 18px; display: flex; align-items: center; gap: 8px; }
            .order-table { width: 100%; border-collapse: collapse; }
            .order-table tr { border-bottom: 1px solid #eee; }
            .order-table tr:last-child { border-bottom: none; }
            .order-table td { padding: 12px 0; font-size: 14.5px; color: #333; }
            .order-table td:first-child { font-weight: 600; width: 40%; color: #555; }
            .order-table td:last-child { text-align: right; }
            .badge-order { background: #0f172a; color: #fff; padding: 4px 14px; border-radius: 20px; font-weight: 600; font-size: 13px; letter-spacing: 0.3px; display: inline-block; }
            .amount-highlight { font-weight: 700; font-size: 17px; color: #ff2d7a; }
            .status-badge { padding: 4px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; }
            .status-badge.success { background: #d4edda; color: #155724; }
            .status-badge.warning { background: #fff3cd; color: #856404; }
            .status-badge.danger { background: #f8d7da; color: #721c24; }
            .success-actions { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
            .success-actions a { display: inline-flex; align-items: center; gap: 8px; padding: 12px 32px; border-radius: 40px; font-weight: 600; font-size: 14.5px; transition: 0.25s ease; text-decoration: none; }
            .btn-home { background: #0f172a; color: #fff; }
            .btn-home:hover { background: #1e293b; color: #fff; transform: translateY(-2px); }
            .btn-menu { background: #ff2d7a; color: #fff; }
            .btn-menu:hover { background: #e01d65; color: #fff; transform: translateY(-2px); }
            @media(max-width: 768px) {
                .success-card { padding: 30px 20px; }
                .success-title { font-size: 24px; }
                .order-details-box { padding: 18px 16px; }
                .order-table td { font-size: 13px; padding: 10px 0; }
                .success-actions { flex-direction: column; align-items: center; }
                .success-actions a { width: 100%; justify-content: center; }
            }

            /* Thank-you popup */
            .thank-you-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.55);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.35s ease;
            }
            .thank-you-overlay.show {
                opacity: 1;
                pointer-events: auto;
            }
            .thank-you-overlay.hide {
                opacity: 0;
            }
            .thank-you-box {
                background: #fff;
                padding: 40px 44px;
                border-radius: 24px;
                text-align: center;
                max-width: 380px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                transform: scale(0.85);
                transition: transform 0.35s ease;
            }
            .thank-you-overlay.show .thank-you-box {
                transform: scale(1);
            }
            .thank-you-icon { font-size: 54px; margin-bottom: 10px; }
            .thank-you-box h3 { font-weight: 800; color: #111; font-size: 22px; margin-bottom: 10px; }
            .thank-you-box p { color: #666; font-size: 14.5px; margin-bottom: 0; }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const overlay = document.getElementById('thankYouOverlay');
                if (!overlay) return;

                requestAnimationFrame(() => overlay.classList.add('show'));

                setTimeout(() => {
                    overlay.classList.remove('show');
                    overlay.classList.add('hide');
                    setTimeout(() => overlay.remove(), 400);
                }, 3000);
            });
        </script>
    @endif
@endsection
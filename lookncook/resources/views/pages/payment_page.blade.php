@extends('layouts.master')
@section('title', 'Payment')

{{-- DECLARE THESE GLOBALLY so they are available on BOTH the Checkout and Confirmation pages --}}
@php
    $codMethods = collect();
    $walletMethods = collect();
    $bankMethods = collect();

    if (isset($paymentMethods) && $paymentMethods instanceof \Illuminate\Support\Collection) {
        $codMethods = $paymentMethods->where('type', 'cod');
        $walletMethods = $paymentMethods->where('type', 'mobile_wallet');
        $bankMethods = $paymentMethods->where('type', 'bank');
    }
@endphp

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
                    {{-- ========== CONFIRMATION MODE (INLINE CSS FOR 100% RELIABILITY) ========== --}}

                    <!-- Stylish Popup Overlay -->
                    <div id="thankYouOverlay" style="position: fixed !important; inset: 0 !important; background: rgba(15, 23, 42, 0.55) !important; display: flex !important; align-items: center !important; justify-content: center !important; z-index: 999999 !important; opacity: 0 !important; pointer-events: none !important; transition: opacity 0.35s ease !important;">
                        <div style="background: #fff; padding: 40px 44px; border-radius: 24px; text-align: center; max-width: 380px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); transform: scale(0.85); transition: transform 0.35s ease;">
                            <div style="font-size: 54px; margin-bottom: 10px;">🎉</div>
                            <h3 style="font-weight: 800; color: #111; font-size: 22px; margin-bottom: 10px;">Thanks for shopping with us!</h3>
                            <p style="color: #666; font-size: 14.5px; margin-bottom: 0;">Your order <strong>{{ $order->order_number }}</strong> has been placed successfully.</p>
                        </div>
                    </div>

                    <div class="col-lg-8 mx-auto">
                        <div class="success-card">
                            <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
                            <h2 class="success-title">Order Placed Successfully!</h2>
                            <p class="success-message">Thank you for your order. We will process it as soon as possible.</p>
                            <div class="order-details-box">
                                <h4><i class="bi bi-receipt"></i> Order Details</h4>
                                <table class="order-table">
                                    <tr><td><strong>Order Number</strong></td><td><span class="badge-order">{{ $order->order_number }}</span></td></tr>
                                    <tr><td><strong>Total Amount</strong></td><td><span class="amount-highlight">PKR {{ number_format($order->total_amount, 2) }}</span></td></tr>
                                    <tr><td><strong>Payment Method</strong></td><td>
                                        @if($order->paymentMethod?->logo)
                                            <img src="{{ asset($order->paymentMethod->logo) }}" alt="{{ $order->paymentMethod->name }}" style="height:18px;vertical-align:middle;margin-right:6px;">
                                        @endif
                                        {{ $order->paymentMethod?->name ?? ucfirst($order->payment_method_slug) }}
                                    </td></tr>
                                    <tr><td><strong>Payment Status</strong></td><td>
                                        @if($order->payment_status === 'approved')
                                            <span class="status-badge success">Approved</span>
                                        @elseif($order->payment_status === 'pending')
                                            <span class="status-badge warning">Pending</span>
                                        @else
                                            <span class="status-badge danger">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td></tr>
                                    <tr><td><strong>Delivery Address</strong></td><td>{{ $order->delivery_address }}</td></tr>
                                    <tr><td><strong>Customer</strong></td><td>{{ $order->customer_name }} ({{ $order->customer_phone }})</td></tr>
                                    @if($order->bank_name || $order->account_number)
                                    <tr><td><strong>Your Account Details</strong></td><td>
                                        @if($order->bank_name) {{ $order->bank_name }} @endif
                                        @if($order->account_title) &middot; {{ $order->account_title }} @endif
                                        @if($order->account_number) &middot; {{ $order->account_number }} @endif
                                        @if($order->transaction_reference)<br><small>Ref: {{ $order->transaction_reference }}</small>@endif
                                    </td></tr>
                                    @endif
                                    @if($order->payment_screenshot)
                                    <tr><td><strong>Payment Screenshot</strong></td><td>
                                        <a href="{{ asset($order->payment_screenshot) }}" target="_blank">
                                            <img src="{{ asset($order->payment_screenshot) }}" alt="Payment proof" style="max-width:120px;border-radius:10px;border:1px solid #eee;">
                                        </a>
                                    </td></tr>
                                    @endif
                                </table>
                            </div>
                            <div class="success-actions">
                                <a href="{{ route('home') }}" class="btn-home"><i class="bi bi-house-door"></i> Back to Home</a>
                                <a href="{{ route('menu') }}" class="btn-menu"><i class="bi bi-grid"></i> Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- ========== CHECKOUT FORM ========== --}}
                    <div class="col-lg-8">
                        <div class="payment-form-box">
                            <div class="form-box-header">
                                <h2 class="payment-title">Checkout Details</h2>
                                <p class="payment-subtitle">Fill in your information to complete the order</p>
                            </div>

                            <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                @endif

                                <input type="hidden" name="payment_method_id" id="paymentMethodIdInput" value="">
                                <input type="hidden" name="cart_data" id="cartDataInput" value="">

                                <h6 class="section-label"><i class="bi bi-person-lines-fill"></i> Contact Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-4"><label class="payment-label">Full Name</label><input type="text" name="full_name" class="form-control payment-input" placeholder="Enter your full name" required></div>
                                    <div class="col-md-6 mb-4"><label class="payment-label">Phone Number</label><input type="text" name="phone" class="form-control payment-input" placeholder="Enter phone number" required></div>
                                    <div class="col-md-6 mb-4"><label class="payment-label">Email</label><input type="email" name="email" class="form-control payment-input" placeholder="Enter email" required></div>
                                    <div class="col-md-6 mb-4"><label class="payment-label">City</label><input type="text" name="city" class="form-control payment-input" placeholder="Enter city" required></div>
                                    <div class="col-12 mb-2"><label class="payment-label">Delivery Address</label><textarea name="address" class="form-control payment-textarea" placeholder="Enter complete address" required></textarea></div>
                                </div>

                                <hr class="section-divider">
                                <h6 class="section-label"><i class="bi bi-wallet2"></i> Select Payment Method</h6>

                                <div class="method-tabs" id="methodTabs">
                                    @if($codMethods->count())
                                    <button type="button" class="method-tab active" data-tab="cod" data-method-id="{{ $codMethods->first()?->id ?? '' }}">
                                        @if($codMethods->first()?->logo)
                                            <img src="{{ asset($codMethods->first()->logo) }}" alt="Cash on Delivery" class="method-tab-logo" loading="eager" onerror="this.style.display='none'; this.insertAdjacentText('beforebegin', '💵');">
                                        @else
                                            💵
                                        @endif
                                        Cash on Delivery
                                    </button>
                                    @endif
                                    @if($walletMethods->count())
                                    <button type="button" class="method-tab" data-tab="wallet">📱 Mobile Wallet</button>
                                    @endif
                                    @if($bankMethods->count())
                                    <button type="button" class="method-tab" data-tab="bank">🏦 Bank Transfer</button>
                                    @endif
                                </div>

                                {{-- COD Panel --}}
                                @if($codMethods->count())
                                <div class="method-panel" data-panel="cod">
                                    <div class="cod-note"><i class="bi bi-cash-coin"></i><div><strong>Cash on Delivery</strong><p>Pay in cash when your order arrives at your doorstep.</p></div></div>
                                </div>
                                @endif

                                {{-- Mobile Wallet Panel --}}
                                @if($walletMethods->count())
                                <div class="method-panel" data-panel="wallet">
                                    <div class="wallet-picker">
                                        @foreach($walletMethods as $wallet)
                                            <div class="wallet-card" data-wallet-id="{{ $wallet->id }}">
                                                @if($wallet->logo)
                                                    <img src="{{ asset($wallet->logo) }}" alt="{{ $wallet->name }}" class="wallet-logo" loading="eager" onerror="this.outerHTML='<span class=&quot;wallet-icon&quot;>{{ $wallet->icon ?? '📱' }}</span>';">
                                                @else
                                                    <span class="wallet-icon">{{ $wallet->icon ?? '📱' }}</span>
                                                @endif
                                                <span class="wallet-name">{{ $wallet->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="detail-card" id="walletDetailCard" style="display:none;">
                                        <div class="detail-header">
                                            <span class="detail-icon" id="walletDetailIconWrap">
                                                <img id="walletDetailLogo" src="" alt="" class="detail-logo-img hidden" loading="eager">
                                                <span id="walletDetailIcon">📱</span>
                                            </span>
                                            <div><h6 class="detail-title" id="walletDetailTitle">Mobile Wallet</h6><p class="detail-subtitle">Please send payment and fill the details</p></div>
                                        </div>
                                        <div class="admin-info-box">
                                            <div class="info-row"><span class="info-label">Account Holder</span><span class="info-value" id="walletAccountTitle">—</span></div>
                                            <div class="info-row"><span class="info-label">Account Number</span><span class="info-value-wrap"><span class="info-value" id="walletAccountNumber">—</span><button type="button" class="copy-btn" data-copy-target="walletAccountNumber">Copy</button></span></div>
                                        </div>
                                        <a href="#" target="_blank" id="walletDeepLinkBtn" class="deep-link-btn" style="display:none;"><i class="bi bi-box-arrow-up-right"></i> Open App to Pay</a>
                                        <div class="mb-3 mt-3"><label class="payment-label">Your Account / Sender Number <span class="req">*</span></label><input type="text" name="account_number" class="form-control payment-input wallet-field" placeholder="The number you paid from" required></div>
                                        <div class="mb-3"><label class="payment-label">Transaction ID <span class="optional-tag">(optional)</span></label><input type="text" name="transaction_ref" class="form-control payment-input" placeholder="e.g. TRX123456"></div>
                                        <div class="mb-1"><label class="payment-label">Screenshot <span class="req">*</span></label><input type="file" name="screenshot" accept="image/*" class="form-control payment-input wallet-field" required></div>
                                    </div>
                                </div>
                                @endif

                                {{-- BANK TRANSFER PANEL --}}
                                @if($bankMethods->count())
                                <div class="method-panel" data-panel="bank">
                                    <div class="detail-card">
                                        <div class="detail-header">
                                            <span class="detail-icon" id="bankDetailIconWrap">
                                                <img id="bankDetailLogo" src="" alt="" class="detail-logo-img hidden" loading="eager">
                                                <span id="bankDetailIcon">🏦</span>
                                            </span>
                                            <div><h6 class="detail-title">Bank Transfer</h6><p class="detail-subtitle">Please select your bank and fill the details</p></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="payment-label">Bank Selection <span class="req">*</span></label>
                                            <select id="bankSelect" class="form-select payment-input">
                                                <option value="">Select a bank</option>
                                                @foreach($bankMethods as $bank)
                                                    <option
                                                        value="{{ $bank->id }}"
                                                        data-bank-name="{{ $bank->bank_name }}"
                                                        data-account-title="{{ $bank->account_title }}"
                                                        data-account-number="{{ $bank->display_account }}"
                                                        data-logo="{{ $bank->logo ? asset($bank->logo) : '' }}">
                                                        {{ $bank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="bankInfoBox" class="admin-info-box hidden">
                                            <div class="info-row"><span class="info-label">Bank Name</span><span class="info-value" id="bankInfoName">—</span></div>
                                            <div class="info-row"><span class="info-label">Account Holder</span><span class="info-value" id="bankInfoTitle">—</span></div>
                                            <div class="info-row"><span class="info-label">IBAN / Account</span><span class="info-value-wrap"><span class="info-value" id="bankInfoAccount">—</span><button type="button" class="copy-btn" data-copy-target="bankInfoAccount">Copy</button></span></div>
                                        </div>
                                        <div class="mb-3 mt-3"><label class="payment-label">Account / Card Holder Name <span class="req">*</span></label><input type="text" name="account_title" class="form-control payment-input bank-field" placeholder="Enter account or card holder name" required></div>
                                        <div class="mb-3"><label class="payment-label">Your Account Number / IBAN <span class="req">*</span></label><input type="text" name="account_number" class="form-control payment-input bank-field" placeholder="Enter your account number or IBAN" required></div>
                                        <div class="mb-1"><label class="payment-label">Screenshot <span class="req">*</span></label><input type="file" name="screenshot" accept="image/*" class="form-control payment-input bank-field" required></div>
                                        <p class="detail-footnote">Fill in your bank details and upload screenshot.</p>
                                    </div>
                                </div>
                                @endif

                                <button type="submit" class="place-order-btn" id="placeOrderBtn"><i class="bi bi-bag-check-fill me-2"></i> Place Order</button>
                            </form>
                        </div>
                    </div>

                    {{-- Right Summary --}}
                    <div class="col-lg-4">
                        <div class="payment-summary">
                            <h3 class="summary-heading"><i class="bi bi-receipt"></i> Order Summary</h3>

                            <div class="summary-instruction mb-3 p-2 rounded" style="background-color: #fef2f6; border: 1px solid #ffd6e6; color: #ff2d7a; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Select the items you want to purchase now. Unchecked items will stay in your cart for later.</span>
                            </div>

                            <div id="paymentPageCartItems"></div>
                            <hr class="summary-divider">
                            <div class="summary-item"><span>Delivery</span><span class="text-success-soft">Free</span></div>
                            <hr class="summary-divider">
                            <div class="summary-item total"><span>Total</span><span>PKR <span id="paymentTotalAmount">0</span></span></div>
                            <div class="summary-badge"><i class="bi bi-shield-check"></i> Secure & simple checkout</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ====== CHECKOUT STYLES & SCRIPTS ====== --}}
    @if(!isset($orderConfirmed) || !$orderConfirmed)
    <style>
        .payment-page-section { padding: 80px 0 100px; background: #f8f9fa; min-height: 100vh; }
        .payment-form-box { background: #ffffff; padding: 40px; border-radius: 28px; box-shadow: 0 10px 35px rgba(0,0,0,0.08); border: 1px solid #e9ecef; }
        .form-box-header { margin-bottom: 30px; }
        .payment-title { font-size: 32px; font-weight: 800; color: #111; margin-bottom: 6px; }
        .payment-subtitle { color: #6c757d; font-size: 14.5px; margin-bottom: 0; }
        .section-label { font-weight: 800; color: #ff2d7a; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
        .section-divider { border-color: #e9ecef; margin: 30px 0; }
        .payment-label { font-weight: 600; margin-bottom: 8px; display: block; color: #343a40; font-size: 14px; }
        .req { color: #dc3545; }
        .payment-input, .payment-textarea { border-radius: 12px; border: 1.5px solid #ced4da; background: #fff; color: #212529; transition: 0.25s ease; }
        .form-control.payment-input, .form-select.payment-input { height: 56px; padding-left: 18px; }
        .payment-textarea { height: 130px; padding: 16px 18px; resize: none; }
        .payment-input::placeholder, .payment-textarea::placeholder { color: #adb5bd; }
        .payment-input:focus, .payment-textarea:focus { border-color: #ff2d7a !important; background: #fff; box-shadow: 0 0 0 4px rgba(255,45,122,0.12) !important; color: #212529; }
        .optional-tag { color: #6c757d; font-weight: 500; font-size: 12px; }
        .method-tabs { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 22px; }
        .method-tab { display: inline-flex; align-items: center; gap: 8px; border: 1.5px solid #dee2e6; background: #fff; color: #495057; font-weight: 700; font-size: 13.5px; padding: 10px 20px; border-radius: 14px; cursor: pointer; transition: 0.2s ease; }
        .method-tab:hover { border-color: #ff2d7a; color: #ff2d7a; }
        .method-tab.active { background: #ff2d7a; border-color: #ff2d7a; color: #fff; }
        .method-tab-logo { height: 20px; width: 20px; object-fit: contain; border-radius: 4px; background: #fff; }
        .method-panel { display: none; }
        .method-panel.active { display: block; }
        .cod-note { display: flex; gap: 14px; align-items: flex-start; background: #f8f9fa; border: 1.5px dashed #ff2d7a; border-radius: 18px; padding: 20px 22px; color: #212529; }
        .cod-note i { font-size: 26px; color: #ff2d7a; }
        .cod-note strong { font-size: 15px; }
        .cod-note p { color: #6c757d; font-size: 13.5px; margin: 4px 0 0; }
        .wallet-picker { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; margin-bottom: 18px; }
        .wallet-card { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px 10px; border: 2px solid #e9ecef; border-radius: 16px; background: #fff; cursor: pointer; transition: 0.2s ease; }
        .wallet-card:hover { border-color: #ff2d7a; }
        .wallet-card.active-wallet { border-color: #ff2d7a; background: rgba(255,45,122,0.05); }
        .wallet-icon { font-size: 26px; }
        .wallet-logo { height: 32px; width: 32px; object-fit: contain; border-radius: 6px; }
        .wallet-name { color: #212529; font-weight: 700; font-size: 12.5px; text-align: center; }
        .detail-card { background: #f8f9fa; border: 1.5px solid #e9ecef; border-radius: 18px; padding: 24px; }
        .detail-header { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
        .detail-icon { font-size: 24px; display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; }
        .detail-logo-img { height: 32px; width: 32px; object-fit: contain; border-radius: 6px; }
        .detail-title { color: #212529; font-weight: 800; font-size: 16px; margin: 0; }
        .detail-subtitle { color: #6c757d; font-size: 12.5px; margin: 2px 0 0; font-style: italic; }
        .admin-info-box { background: #fff; border: 1px solid #e9ecef; border-radius: 14px; padding: 16px 18px; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f3f5; gap: 12px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6c757d; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
        .info-value { color: #212529; font-weight: 700; font-size: 14px; text-align: right; word-break: break-all; }
        .info-value-wrap { display: flex; align-items: center; gap: 8px; }
        .copy-btn { border: 1px solid #ff2d7a; background: transparent; color: #ff2d7a; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; cursor: pointer; white-space: nowrap; transition: 0.2s ease; }
        .copy-btn:hover { background: rgba(255,45,122,0.08); }
        .copy-btn.copied { background: #ff2d7a; color: #fff; border-color: #ff2d7a; }
        .deep-link-btn { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 14px; padding: 12px; border-radius: 12px; background: rgba(255,45,122,0.08); border: 1px solid #ff2d7a; color: #ff2d7a; font-weight: 700; font-size: 13.5px; text-decoration: none; transition: 0.2s ease; }
        .deep-link-btn:hover { background: rgba(255,45,122,0.15); color: #ff2d7a; }
        .detail-footnote { color: #6c757d; font-size: 12px; text-align: center; margin: 14px 0 0; }
        .place-order-btn { width: 100%; margin-top: 30px; height: 58px; border: none; border-radius: 14px; background: #ff2d7a; color: #fff; font-size: 16.5px; font-weight: 700; letter-spacing: 0.3px; transition: 0.3s ease; box-shadow: 0 12px 26px rgba(255,45,122,0.25); }
        .place-order-btn:hover { background: #e01d65; transform: translateY(-2px); box-shadow: 0 16px 32px rgba(255,45,122,0.3); }
        .payment-summary { background: #fff; padding: 34px; border-radius: 28px; color: #212529; position: sticky; top: 120px; box-shadow: 0 15px 40px rgba(0,0,0,0.06); border: 1px solid #e9ecef; }
        .summary-heading { margin-bottom: 26px; font-weight: 800; font-size: 20px; display: flex; align-items: center; gap: 10px; color: #212529; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 16px; color: #495057; font-size: 14.5px; }
        .summary-item.total { font-size: 21px; font-weight: 800; color: #ff2d7a; margin-bottom: 0; }
        .summary-divider { border-color: #e9ecef; margin: 18px 0; }
        .text-success-soft { color: #28a745; font-weight: 600; }
        .summary-badge { margin-top: 26px; background: rgba(255,45,122,0.06); border: 1px solid rgba(255,45,122,0.2); color: #ff2d7a; font-size: 12.5px; font-weight: 600; padding: 12px 14px; border-radius: 12px; display: flex; align-items: center; gap: 8px; }
        .alert-danger { border-radius: 12px; margin-bottom: 20px; }
        .hidden { display: none !important; }
        .admin-info-box.hidden { display: none !important; }
        .order-item-checkbox { width: 18px; height: 18px; accent-color: #ff2d7a; cursor: pointer; flex-shrink: 0; }
        .summary-item .d-flex { min-width: 0; }
        .summary-item .d-flex span { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        @media(max-width:768px) {
            .payment-form-box { padding: 24px !important; border-radius: 22px; }
            .payment-title { font-size: 26px; }
            .payment-summary { position: relative; top: 0; }
            .info-row { flex-direction: column; align-items: flex-start; gap: 2px; }
            .info-value { text-align: left; }
            .summary-item .d-flex { flex-wrap: wrap; }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderPaymentSummary();
            setupMethodTabs();
            setupWalletPicker();
            setupBankSelection();
            setupCopyButtons();
            setupFormSubmit();
        });

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
            localCart.forEach((item, index) => {
                let itemQty = item.quantity || 1;
                let lineTotal = item.price * itemQty;
                globalTotal += lineTotal;

                targetContainer.innerHTML += `
                    <div class="summary-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2" style="min-width: 0; flex: 1;">
                            <input type="checkbox" class="order-item-checkbox" data-index="${index}" checked>
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.name} (x${itemQty})</span>
                        </div>
                        <span style="flex-shrink: 0; margin-left: 10px;">PKR ${lineTotal}</span>
                    </div>
                `;
            });

            if (totalContainer) totalContainer.innerText = globalTotal;

            document.querySelectorAll('.order-item-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    updateOrderTotal();
                });
            });
        }

        function updateOrderTotal() {
            let localCart = JSON.parse(localStorage.getItem("look_n_cook_cart") || "[]");
            let total = 0;
            document.querySelectorAll('.order-item-checkbox').forEach(cb => {
                if (cb.checked) {
                    let idx = parseInt(cb.dataset.index);
                    let item = localCart[idx];
                    if (item) {
                        total += (item.price * (item.quantity || 1));
                    }
                }
            });
            let totalContainer = document.getElementById('paymentTotalAmount');
            if (totalContainer) totalContainer.innerText = total;
        }

        function setupMethodTabs() {
            const tabs = document.querySelectorAll('.method-tab');
            const panels = document.querySelectorAll('.method-panel');
            if (!tabs.length) return;

            function activate(tabName) {
                tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === tabName));
                panels.forEach(p => p.classList.toggle('active', p.dataset.panel === tabName));
                syncRequiredFields(tabName);
                updateButtonLabel(tabName);
            }

            tabs.forEach(tab => tab.addEventListener('click', () => activate(tab.dataset.tab)));
            const initial = document.querySelector('.method-tab.active');
            if (initial) activate(initial.dataset.tab);
        }

        function updateButtonLabel(tabName) {
            const btn = document.getElementById('placeOrderBtn');
            if (!btn) return;
            const labels = {
                cod: '<i class="bi bi-bag-check-fill me-2"></i> Place Order (Cash on Delivery)',
                wallet: '<i class="bi bi-bag-check-fill me-2"></i> Submit Mobile Wallet Payment',
                bank: '<i class="bi bi-bag-check-fill me-2"></i> Submit Bank Transfer',
            };
            btn.innerHTML = labels[tabName] || '<i class="bi bi-bag-check-fill me-2"></i> Place Order';
        }

        // Only the fields inside the ACTIVE panel should ever be "required"
        // in the browser. This also matters at submit-time (see setupFormSubmit)
        // where we disable inactive-panel inputs entirely so their empty
        // "screenshot" file field never overwrites the one you actually filled in.
        function syncRequiredFields(activeTab) {
            document.querySelectorAll('.bank-field, .wallet-field').forEach(f => {
                const panel = f.closest('.method-panel');
                const isActive = panel && panel.dataset.panel === activeTab;
                if (isActive) {
                    f.setAttribute('required', 'required');
                } else {
                    f.removeAttribute('required');
                }
            });
        }

        function setupWalletPicker() {
            const cards = document.querySelectorAll('.wallet-card');
            if (!cards.length) return;

            const walletData = {
                @foreach($walletMethods as $wallet)
                "{{ $wallet->id }}": {
                    name: @json($wallet->name),
                    icon: @json($wallet->icon ?? '📱'),
                    logo: @json($wallet->logo ? asset($wallet->logo) : null),
                    accountTitle: @json($wallet->account_title),
                    accountNumber: @json($wallet->display_account),
                    deepLink: @json($wallet->deep_link),
                },
                @endforeach
            };

            cards.forEach(card => {
                card.addEventListener('click', function () {
                    cards.forEach(c => c.classList.remove('active-wallet'));
                    this.classList.add('active-wallet');

                    const data = walletData[this.dataset.walletId];
                    const detailCard = document.getElementById('walletDetailCard');
                    if (!data || !detailCard) return;

                    const logoImg = document.getElementById('walletDetailLogo');
                    const iconSpan = document.getElementById('walletDetailIcon');
                    if (data.logo) {
                        logoImg.onerror = function() {
                            logoImg.classList.add('hidden');
                            iconSpan.textContent = data.icon;
                            iconSpan.classList.remove('hidden');
                        };
                        logoImg.src = data.logo;
                        logoImg.classList.remove('hidden');
                        iconSpan.classList.add('hidden');
                    } else {
                        logoImg.classList.add('hidden');
                        iconSpan.textContent = data.icon;
                        iconSpan.classList.remove('hidden');
                    }

                    document.getElementById('walletDetailTitle').textContent = data.name;
                    document.getElementById('walletAccountTitle').textContent = data.accountTitle || '—';
                    document.getElementById('walletAccountNumber').textContent = data.accountNumber || '—';

                    const linkBtn = document.getElementById('walletDeepLinkBtn');
                    if (data.deepLink) {
                        linkBtn.href = data.deepLink;
                        linkBtn.style.display = 'flex';
                    } else {
                        linkBtn.style.display = 'none';
                    }

                    detailCard.style.display = 'block';
                });
            });

            if (cards[0]) cards[0].click();
        }

        function setupBankSelection() {
            const select = document.getElementById('bankSelect');
            if (!select) return;

            const infoBox = document.getElementById('bankInfoBox');
            const logoImg = document.getElementById('bankDetailLogo');
            const iconSpan = document.getElementById('bankDetailIcon');

            function applySelectedBank() {
                const opt = select.options[select.selectedIndex];
                if (!opt || !opt.value) {
                    if (infoBox) infoBox.classList.add('hidden');
                    document.getElementById('bankInfoName').textContent = '—';
                    document.getElementById('bankInfoTitle').textContent = '—';
                    document.getElementById('bankInfoAccount').textContent = '—';
                    logoImg.classList.add('hidden');
                    iconSpan.classList.remove('hidden');
                    return;
                }

                if (infoBox) infoBox.classList.remove('hidden');
                document.getElementById('bankInfoName').textContent = opt.dataset.bankName || '—';
                document.getElementById('bankInfoTitle').textContent = opt.dataset.accountTitle || '—';
                document.getElementById('bankInfoAccount').textContent = opt.dataset.accountNumber || '—';

                const logoUrl = opt.dataset.logo;
                if (logoUrl) {
                    logoImg.onerror = function() {
                        logoImg.classList.add('hidden');
                        iconSpan.classList.remove('hidden');
                    };
                    logoImg.src = logoUrl;
                    logoImg.classList.remove('hidden');
                    iconSpan.classList.add('hidden');
                } else {
                    logoImg.classList.add('hidden');
                    iconSpan.classList.remove('hidden');
                }
            }

            select.addEventListener('change', applySelectedBank);
            applySelectedBank();
        }

        function setupCopyButtons() {
            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetEl = document.getElementById(this.dataset.copyTarget);
                    if (!targetEl) return;
                    const text = targetEl.textContent.trim();
                    if (!text || text === '—') return;

                    navigator.clipboard.writeText(text).then(() => {
                        const original = this.textContent;
                        this.textContent = 'Copied!';
                        this.classList.add('copied');
                        setTimeout(() => {
                            this.textContent = original;
                            this.classList.remove('copied');
                        }, 1500);
                    });
                });
            });
        }

        // ⭐ BULLETPROOF FORM SUBMIT
        function setupFormSubmit() {
            const form = document.getElementById('checkoutForm');
            if (!form) return;
            form.addEventListener('submit', function(e) {
                const activeBtn = document.querySelector('.method-tab.active');
                if (!activeBtn) { e.preventDefault(); return; }

                const tabName = activeBtn.dataset.tab;
                let methodId = '';

                // 1. Determine the CORRECT ID based on the active tab
                if (tabName === 'cod') {
                    const codBtn = document.querySelector('.method-tab[data-tab="cod"]');
                    methodId = codBtn ? codBtn.dataset.methodId : '';
                } else if (tabName === 'bank') {
                    const select = document.getElementById('bankSelect');
                    methodId = select ? select.value : '';
                } else if (tabName === 'wallet') {
                    const activeWallet = document.querySelector('.wallet-card.active-wallet');
                    methodId = activeWallet ? activeWallet.dataset.walletId : '';
                }

                // 2. Block submit if method ID is missing
                if (!methodId) {
                    e.preventDefault();
                    alert('Please select a valid payment method.');
                    return;
                }

                // 3. Assign the EXACT ID to the hidden input right before sending
                document.getElementById('paymentMethodIdInput').value = methodId;

                // 4. Validate screenshot for the ACTIVE tab manually (only wallet/bank need it)
                if (tabName === 'wallet' || tabName === 'bank') {
                    const activePanel = document.querySelector(`.method-panel[data-panel="${tabName}"]`);
                    const fileInput = activePanel ? activePanel.querySelector('input[name="screenshot"]') : null;
                    if (!fileInput || fileInput.files.length === 0) {
                        e.preventDefault();
                        alert('Please upload a payment screenshot for this payment method.');
                        return;
                    }
                }

                // 5. CRITICAL: disable every input inside the INACTIVE panels.
                //    Both the Wallet and Bank panels contain an
                //    <input name="screenshot"> — if both stay enabled, the
                //    browser submits both under the same field name and PHP
                //    silently keeps only the last one, which is empty. This
                //    is what causes "screenshot field is required" even on
                //    COD or on the panel you actually filled in. Disabling
                //    inactive-panel inputs removes them from the submission
                //    entirely, so only the active panel's data (and its
                //    screenshot, if any) reaches the server.
                document.querySelectorAll('.method-panel').forEach(panel => {
                    const isActive = panel.dataset.panel === tabName;
                    panel.querySelectorAll('input, select, textarea').forEach(el => {
                        el.disabled = !isActive;
                    });
                });

                // 6. Process Cart Data
                let localCart = JSON.parse(localStorage.getItem('look_n_cook_cart') || '[]');
                let filteredCart = [];
                let remainingCart = [];

                document.querySelectorAll('.order-item-checkbox').forEach(cb => {
                    let idx = parseInt(cb.dataset.index);
                    if (localCart[idx]) {
                        if (cb.checked) {
                            filteredCart.push(localCart[idx]);
                        } else {
                            remainingCart.push(localCart[idx]);
                        }
                    }
                });

                localStorage.setItem('look_n_cook_cart', JSON.stringify(remainingCart));

                if (typeof updateCart === 'function') {
                    updateCart();
                }
                window.dispatchEvent(new Event('cartUpdated'));

                document.getElementById('cartDataInput').value = JSON.stringify(filteredCart);
            });
        }
    </script>
    @endif

    {{-- ====== CONFIRMATION STYLES & SCRIPT ====== --}}
    @if(isset($orderConfirmed) && $orderConfirmed)
    <style>
        .success-card { background: #fff; padding: 50px 40px; border-radius: 28px; box-shadow: 0 10px 40px rgba(0,0,0,0.06); text-align: center; }
        .success-icon { font-size: 72px; color: #28a745; margin-bottom: 16px; }
        .success-title { font-size: 32px; font-weight: 800; color: #111; margin-bottom: 10px; }
        .success-message { color: #555; font-size: 16px; margin-bottom: 35px; }
        .order-details-box { background: #fafafa; border-radius: 16px; padding: 25px 30px; margin-bottom: 30px; text-align: left; }
        .order-details-box h4 { font-weight: 700; color: #111; margin-bottom: 18px; font-size: 18px; display: flex; align-items: center; gap: 8px; }
        .order-table { width: 100%; border-collapse: collapse; }
        .order-table tr { border-bottom: 1px solid #eee; }
        .order-table tr:last-child { border-bottom: none; }
        .order-table td { padding: 12px 0; font-size: 14.5px; color: #333; vertical-align: top; }
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
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Show the fixed overlay and remove it nicely
            const overlay = document.getElementById('thankYouOverlay');
            if (!overlay) return;

            requestAnimationFrame(() => {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            });

            setTimeout(() => {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.remove(), 400);
            }, 3000);
        });
    </script>
    @endif
@endsection
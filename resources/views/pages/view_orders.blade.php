@extends('layouts.master')
@section('title', 'My Orders')

@section('content')
    @include('components.banner', [
        'miniTitle' => 'ORDERS',
        'title' => 'My Orders',
        'highlight' => 'Track Your Delivery',
        'description' => 'Track your orders and delivery status in real-time'
    ])

    <div class="orders-page">
        <div class="container">
            <!-- Top Section: Map + Orders List -->
            <div class="top-section">
                <!-- Left: Map -->
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d28950.123456789!2d67.001234!3d24.861234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33e06656d4b6d%3A0xb4b5f8d9e8f1c2b3!2sKarachi%2C%20Pakistan!5e0!3m2!1sen!2s!4v1700000000000" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>

                <!-- Right: Orders List -->
                <div class="orders-list-container">
                    <div class="orders-list-header">
                        <h3><i class="fas fa-list-ul"></i> Orders List</h3>
                        <span class="badge-count">{{ $orders->count() }}</span>
                    </div>
                    <div class="orders-list-body">
                        @forelse($orders as $order)
                            <!-- Order Item -->
                            <div class="order-item {{ $loop->first ? 'active' : '' }}">
                                <div class="order-header">
                                    <span class="order-id">#{{ $order->order_number ?? 'ORD-'.$order->id }}</span>
                                    <span class="order-time">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <div class="product-list">
                                    @foreach($order->items as $item)
                                        <div class="product-item">
                                            <span class="product-name"><i class="fas fa-utensils"></i> {{ $item->product->name ?? 'Product' }}</span>
                                            <span class="product-qty">× {{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="address-row">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $order->address ?? 'No address provided' }}</span>
                                </div>
                                <div class="order-footer">
                                    <span class="total-amount">₨ {{ number_format($order->total_amount, 2) }}</span>
                                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5>No Orders Found</h5>
                                <p class="text-muted">You haven't placed any orders yet.</p>
                                <a href="{{ route('menu') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Bottom Section: 3 Carts -->
            <div class="bottom-section">
                @forelse($orders->take(3) as $order)
                    <!-- Cart -->
                    <div class="order-cart {{ $loop->first ? 'active' : '' }}">
                        <div class="cart-header">
                            <span class="cart-title">
                                <i class="fas fa-truck"></i>
                                Order #{{ $loop->iteration }}
                            </span>
                            <span class="cart-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="cart-details">
                            <div class="detail-row">
                                <i class="fas fa-user"></i>
                                <span class="label">Rider:</span>
                                <span>{{ $order->rider_name ?? 'Not Assigned' }}</span>
                            </div>
                            <div class="detail-row">
                                <i class="fas fa-motorcycle"></i>
                                <span class="label">Vehicle No:</span>
                                <span>{{ $order->vehicle_no ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-row">
                                <i class="fas fa-phone"></i>
                                <span class="label">Contact:</span>
                                <span>{{ $order->rider_contact ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="cart-footer">
                            <span class="cart-total">₨ {{ number_format($order->total_amount, 2) }}</span>
                            <button class="view-btn"><i class="fas fa-eye"></i> View</button>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No orders to display</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        /* Your existing styles here */
        .orders-page {
            padding: 40px 0 60px;
            background: #f8f9fa;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Top Section */
        .top-section {
            display: flex;
            gap: 30px;
            height: 520px;
            margin-bottom: 30px;
        }

        /* Map */
        .map-container {
            flex: 1;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Orders List */
        .orders-list-container {
            flex: 1;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .orders-list-header {
            padding: 16px 20px;
            background: #fdf2f8;
            border-bottom: 2px solid #fbcfe8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .orders-list-header h3 {
            margin: 0;
            color: #be185d;
            font-size: 18px;
            font-weight: 600;
        }

        .orders-list-header h3 i {
            margin-right: 8px;
        }

        .orders-list-header .badge-count {
            background: #ec4899;
            color: white;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .orders-list-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
        }

        /* Order Item */
        .order-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 12px;
            border-left: 4px solid #ec4899;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .order-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15);
        }

        .order-item.active {
            background: #fce7f3;
            border-left-color: #be185d;
        }

        .order-item .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .order-item .order-id {
            font-weight: 600;
            color: #db2777;
            font-size: 14px;
        }

        .order-item .order-time {
            font-size: 12px;
            color: #6b7280;
        }

        .order-item .product-list {
            margin: 8px 0;
            padding: 8px 12px;
            background: white;
            border-radius: 8px;
        }

        .order-item .product-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item .product-item:last-child {
            border-bottom: none;
        }

        .order-item .product-name {
            color: #374151;
        }

        .order-item .product-name i {
            color: #ec4899;
            margin-right: 6px;
        }

        .order-item .product-qty {
            color: #6b7280;
            font-weight: 500;
        }

        .order-item .address-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            padding: 8px 12px;
            background: white;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
        }

        .order-item .address-row i {
            color: #ec4899;
        }

        .order-item .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .order-item .total-amount {
            font-weight: 700;
            color: #db2777;
            font-size: 16px;
        }

        .order-item .status-badge {
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        .status-delivered {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .status-paid {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-preparing {
            background: #fff3cd;
            color: #856404;
        }
        .status-ready {
            background: #d4edda;
            color: #155724;
        }
        .status-on-the-way {
            background: #cce5ff;
            color: #004085;
        }

        /* Bottom Carts */
        .bottom-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 10px;
        }

        .order-cart {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .order-cart:hover {
            border-color: #f472b6;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.15);
            transform: translateY(-3px);
        }

        .order-cart.active {
            border-color: #db2777;
            background: #fdf2f8;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.2);
        }

        .order-cart .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f3f4f6;
        }

        .order-cart .cart-title {
            font-weight: 700;
            color: #1f2937;
            font-size: 16px;
        }

        .order-cart .cart-title i {
            color: #ec4899;
            margin-right: 8px;
        }

        .order-cart .cart-status {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 14px;
            border-radius: 20px;
            text-transform: capitalize;
        }

        .order-cart .cart-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 12px 0;
        }

        .order-cart .detail-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #374151;
            padding: 6px 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .order-cart .detail-row i {
            width: 20px;
            color: #ec4899;
            font-size: 16px;
            text-align: center;
        }

        .order-cart .detail-row .label {
            font-weight: 500;
            color: #6b7280;
            min-width: 70px;
        }

        .order-cart .cart-footer {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 2px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-cart .cart-total {
            font-weight: 700;
            color: #db2777;
            font-size: 17px;
        }

        .order-cart .view-btn {
            background: #ec4899;
            color: white;
            border: none;
            padding: 7px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .order-cart .view-btn:hover {
            background: #db2777;
        }

        /* Scrollbar */
        .orders-list-body::-webkit-scrollbar {
            width: 6px;
        }

        .orders-list-body::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }

        .orders-list-body::-webkit-scrollbar-thumb {
            background: #f472b6;
            border-radius: 10px;
        }

        .orders-list-body::-webkit-scrollbar-thumb:hover {
            background: #db2777;
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }
        .empty-state i {
            color: #d1d5db;
            margin-bottom: 20px;
        }
        .empty-state h4 {
            color: #374151;
            margin-bottom: 10px;
        }
        .empty-state p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .top-section {
                flex-direction: column;
                height: auto;
            }
            .map-container {
                height: 350px;
            }
            .orders-list-container {
                max-height: 450px;
            }
        }

        @media (max-width: 992px) {
            .bottom-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .bottom-section {
                grid-template-columns: 1fr;
            }
            .orders-page {
                padding: 30px 0 40px;
            }
            .order-cart {
                padding: 16px;
            }
        }

        @media (max-width: 576px) {
            .map-container {
                height: 250px;
            }
            .order-item .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            .order-item .product-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }
            .order-item .order-footer {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            .order-cart .cart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .order-cart .detail-row {
                font-size: 13px;
                flex-wrap: wrap;
            }
            .order-cart .detail-row .label {
                min-width: 60px;
            }
        }
    </style>

    <script>
        // Click to highlight order items
        document.querySelectorAll('.order-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.order-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Click to highlight carts
        document.querySelectorAll('.order-cart').forEach(cart => {
            cart.addEventListener('click', function() {
                document.querySelectorAll('.order-cart').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endsection
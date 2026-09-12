<div class="container-fluid p-0">
    <!-- Order Info Grid -->
    <div class="row g-3">
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Order #</label>
            <p class="fw-bold text-dark fs-5 mb-0">{{ $order->order_number ?? $order->id }}</p>
        </div>
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Date</label>
            <p class="fw-medium text-dark mb-0">{{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}</p>
        </div>
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Customer</label>
            <p class="fw-semibold text-dark mb-0">{{ $order->customer_name ?? $order->user?->name ?? 'Guest' }}</p>
            <small class="text-muted">{{ $order->customer_email ?? $order->user?->email ?? '' }}</small>
        </div>
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Phone</label>
            <p class="fw-medium text-dark mb-0">{{ $order->customer_phone ?? '—' }}</p>
        </div>
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Total Amount</label>
            <p class="fw-bold text-pink fs-3 mb-0">Rs. {{ number_format((float) $order->total_amount, 0) }}</p>
        </div>
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Payment Method</label>
            <p class="fw-medium text-dark mb-0">{{ $order->paymentMethod?->name ?? ucfirst($order->payment_method_slug ?? '—') }}</p>
        </div>
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Payment Status</label>
            <span class="badge rounded-pill px-3 py-2 
                @if($order->payment_status === 'approved') bg-success
                @elseif($order->payment_status === 'pending') bg-warning text-dark
                @elseif($order->payment_status === 'failed') bg-danger
                @else bg-secondary @endif">
                {{ ucfirst($order->payment_status ?? 'pending') }}
            </span>
        </div>
        <div class="col-6">
            <label class="text-uppercase text-muted small fw-semibold mb-0">Transaction Ref</label>
            <p class="fw-medium text-dark text-break mb-0">{{ $order->transaction_reference ?? '—' }}</p>
        </div>
    </div>

    <hr class="my-4">

    <!-- Delivery Address -->
    <div class="mb-3">
        <label class="text-uppercase text-muted small fw-semibold mb-1">Delivery Address</label>
        <div class="bg-light p-3 rounded-3 border">
            <p class="fw-medium text-dark mb-0">{{ $order->delivery_address ?? '—' }}</p>
            <p class="text-muted mb-0">{{ $order->city ?? '' }}</p>
        </div>
    </div>

    <hr class="my-4">

    <!-- Rider Assignment -->
    <div class="mb-3">
        <label class="text-uppercase text-muted small fw-semibold mb-2">Rider Assignment</label>
        @if($order->rider)
            @php
                $statusClasses = [
                    'review'    => 'bg-warning bg-opacity-25 text-warning border-warning',
                    'preparing' => 'bg-info bg-opacity-25 text-info border-info',
                    'completed' => 'bg-success bg-opacity-25 text-success border-success',
                    'delivered' => 'bg-success bg-opacity-25 text-success border-success',
                ];
            @endphp
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-pink-light border border-pink-soft">
                @if($order->rider->image_url)
                    <img src="{{ $order->rider->image_url }}" alt="{{ $order->rider->name }}" 
                         class="rounded-circle border border-2 border-pink-soft" 
                         style="width:48px; height:48px; object-fit:cover;">
                @else
                    <div class="rounded-circle bg-pink-soft d-flex align-items-center justify-content-center text-pink fw-bold" 
                         style="width:48px; height:48px; font-size:18px;">
                        {{ strtoupper(substr($order->rider->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-grow-1">
                    <p class="fw-semibold text-dark mb-0">{{ $order->rider->name }}</p>
                    <small class="text-muted">
                        <i class="fa-solid fa-phone me-1"></i> {{ $order->rider->phone }} 
                        &middot; 
                        <i class="fa-solid fa-truck me-1"></i> {{ ucfirst($order->rider->vehicle_type) }}
                        @if($order->rider->vehicle_number)
                            - {{ $order->rider->vehicle_number }}
                        @endif
                    </small>
                </div>
                <div class="text-end">
                    <span class="badge rounded-pill px-3 py-2 border {{ $statusClasses[$order->status] ?? 'bg-secondary text-white border-secondary' }}">
                        <i class="fa-regular fa-clock me-1"></i>
                        {{ ucfirst($order->status ?? 'review') }}
                    </span>
                    @if($order->estimated_time)
                        <small class="text-muted d-block mt-1">
                            <i class="fa-regular fa-hourglass me-1"></i> {{ $order->estimated_time }}
                        </small>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-4 bg-light rounded-3 border">
                <i class="fa-solid fa-motorcycle text-pink fs-2 d-block mb-2"></i>
                <p class="text-muted mb-0">No rider assigned yet.</p>
                <small class="text-muted">Use the <i class="fa-solid fa-motorcycle text-pink"></i> button on the orders table to assign one.</small>
            </div>
        @endif
    </div>

    @if($order->paymentMethod && $order->paymentMethod->type !== 'cod')
        <hr class="my-4">

        <!-- Payment Details -->
        <div class="mb-3">
            <label class="text-uppercase text-muted small fw-semibold mb-2">Customer's Submitted Payment Details</label>
            <div class="row p-3 bg-light rounded-3 border">
                @if($order->bank_name)
                <div class="col-6">
                    <label class="text-muted small fw-semibold mb-0">Bank</label>
                    <p class="fw-medium text-dark mb-0">{{ $order->bank_name }}</p>
                </div>
                @endif
                <div class="col-6">
                    <label class="text-muted small fw-semibold mb-0">Account Title</label>
                    <p class="fw-medium text-dark mb-0">{{ $order->account_title ?? '—' }}</p>
                </div>
                <div class="col-12">
                    <label class="text-muted small fw-semibold mb-0">Account Number / IBAN</label>
                    <p class="fw-medium text-dark text-break mb-0">{{ $order->account_number ?? '—' }}</p>
                </div>
            </div>

            @if($order->payment_screenshot)
                <div class="mt-3">
                    <label class="text-muted small fw-semibold mb-1">Payment Screenshot</label>
                    <div class="position-relative">
                        <a href="{{ asset($order->payment_screenshot) }}" target="_blank" class="d-inline-block">
                            <img src="{{ asset($order->payment_screenshot) }}" alt="Payment proof"
                                 class="img-fluid rounded-3 border" 
                                 style="max-width:200px; max-height:200px; object-fit:cover;">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-0 hover-bg-opacity-50 transition-all rounded-3" 
                                 style="transition: all 0.3s ease;">
                                <i class="fa-solid fa-magnifying-glass-plus text-white opacity-0 hover-opacity-100" 
                                   style="font-size:24px; transition: all 0.3s ease;"></i>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <hr class="my-4">

    <!-- Order Items -->
    <div class="mb-2">
        <label class="text-uppercase text-muted small fw-semibold mb-2">Order Items</label>
        @if($order->items && $order->items->count())
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Product</th>
                            <th class="border-0 text-center">Quantity</th>
                            <th class="border-0 text-end">Price</th>
                            <th class="border-0 text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="fw-medium">{{ $item->product?->name ?? 'Product #'.$item->product_id }}</td>
                                <td class="text-center">× {{ $item->quantity }}</td>
                                <td class="text-end">Rs. {{ number_format((float) $item->unit_price, 0) }}</td>
                                <td class="text-end fw-semibold">Rs. {{ number_format((float) ($item->unit_price * $item->quantity), 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold text-pink">Rs. {{ number_format((float) $order->total_amount, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-3 text-muted">
                <i class="fa-solid fa-box-open fs-3 d-block mb-2"></i>
                No items found for this order.
            </div>
        @endif
    </div>
</div>

<style>
    .hover-bg-opacity-50:hover {
        background-color: rgba(0,0,0,0.3) !important;
    }
    .hover-opacity-100:hover {
        opacity: 1 !important;
    }
    .hover-opacity-100 {
        opacity: 0;
    }
    .text-pink {
        color: #ff2d7a !important;
    }
    .bg-pink-light {
        background-color: #fdf2f8 !important;
    }
    .border-pink-soft {
        border-color: #fbcfe8 !important;
    }
    .bg-pink-soft {
        background-color: #fbcfe8 !important;
    }
</style>
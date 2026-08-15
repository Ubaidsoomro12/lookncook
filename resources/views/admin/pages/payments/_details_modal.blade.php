<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Order #</p>
            <p class="font-medium text-gray-800">{{ $order->order_number ?? $order->id }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Date</p>
            <p class="font-medium text-gray-800">{{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Customer</p>
            <p class="font-medium text-gray-800">{{ $order->customer_name ?? $order->user?->name ?? 'Guest' }}</p>
            <p class="text-xs text-gray-500">{{ $order->customer_email ?? $order->user?->email ?? '' }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Phone</p>
            <p class="font-medium text-gray-800">{{ $order->customer_phone ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Total Amount</p>
            <p class="font-semibold text-gray-800 text-lg">Rs. {{ number_format((float) $order->total_amount, 0) }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Payment Method</p>
            <p class="font-medium text-gray-800">{{ $order->paymentMethod?->name ?? ucfirst($order->payment_method_slug ?? '—') }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Payment Status</p>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                @if($order->payment_status === 'approved') bg-green-50 text-green-600 border border-green-200
                @elseif($order->payment_status === 'pending') bg-yellow-50 text-yellow-600 border border-yellow-200
                @elseif($order->payment_status === 'failed') bg-red-50 text-red-600 border border-red-200
                @else bg-gray-100 text-gray-500 border border-gray-200 @endif">
                {{ ucfirst($order->payment_status ?? 'pending') }}
            </span>
        </div>
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400">Transaction Ref</p>
            <p class="font-medium text-gray-800 text-sm break-all">{{ $order->transaction_reference ?? '—' }}</p>
        </div>
    </div>

    <hr class="my-4">

    <div>
        <p class="text-xs uppercase tracking-wider text-gray-400 mb-2">Delivery Address</p>
        <p class="text-sm text-gray-700">{{ $order->delivery_address ?? '—' }}</p>
        <p class="text-sm text-gray-700">{{ $order->city ?? '' }}</p>
    </div>

    @if($order->paymentMethod && $order->paymentMethod->type !== 'cod')
        <hr class="my-4">
        <div>
            <p class="text-xs uppercase tracking-wider text-gray-400 mb-2">Customer's Submitted Payment Details</p>
            <div class="grid grid-cols-2 gap-3 text-sm bg-slate-50 border border-slate-200 rounded-xl p-4">
                @if($order->bank_name)
                <div>
                    <p class="text-[11px] text-gray-400">Bank</p>
                    <p class="font-medium text-gray-800">{{ $order->bank_name }}</p>
                </div>
                @endif
                <div>
                    <p class="text-[11px] text-gray-400">Account Title</p>
                    <p class="font-medium text-gray-800">{{ $order->account_title ?? '—' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-[11px] text-gray-400">Account Number / IBAN</p>
                    <p class="font-medium text-gray-800 break-all">{{ $order->account_number ?? '—' }}</p>
                </div>
            </div>

            @if($order->payment_screenshot)
                <div class="mt-3">
                    <p class="text-[11px] text-gray-400 mb-1.5">Payment Screenshot</p>
                    <a href="{{ asset($order->payment_screenshot) }}" target="_blank">
                        <img src="{{ asset($order->payment_screenshot) }}" alt="Payment proof"
                             class="w-full max-w-xs rounded-xl border border-gray-200 hover:opacity-90 transition-all">
                    </a>
                </div>
            @endif
        </div>
    @endif

    <hr class="my-4">

    <div>
        <p class="text-xs uppercase tracking-wider text-gray-400 mb-2">Order Items</p>
        @if($order->items && $order->items->count())
            <ul class="divide-y divide-gray-100 text-sm">
                @foreach($order->items as $item)
                    <li class="py-2 flex justify-between">
                        <span>{{ $item->product?->name ?? 'Product #'.$item->product_id }} × {{ $item->quantity }}</span>
                        <span class="font-medium">Rs. {{ number_format((float) ($item->unit_price * $item->quantity), 0) }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-400">No items</p>
        @endif
    </div>
</div>
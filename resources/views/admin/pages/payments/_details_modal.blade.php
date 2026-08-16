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

    <hr class="my-4">

    <div>
        <p class="text-xs uppercase tracking-wider text-gray-400 mb-2">Rider Assignment</p>
        @if($order->rider)
            @php
                $assignStatusColors = [
                    'review'    => 'bg-amber-50 text-amber-600 border-amber-200',
                    'preparing' => 'bg-blue-50 text-blue-600 border-blue-200',
                    'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                    'delivered' => 'bg-green-50 text-green-700 border-green-300',
                ];
            @endphp
            <div class="flex items-center gap-3 bg-pink-50/50 border border-pink-100 rounded-xl p-4">
                @if($order->rider->image_url)
                    <img src="{{ $order->rider->image_url }}" alt="{{ $order->rider->name }}" class="w-11 h-11 rounded-full object-cover border-2 border-pink-100">
                @else
                    <div class="w-11 h-11 rounded-full bg-pink-100 flex items-center justify-center text-[#ff2d7a] font-bold">
                        {{ strtoupper(substr($order->rider->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $order->rider->name }}</p>
                    <p class="text-xs text-gray-500">{{ $order->rider->phone }} &middot; {{ ucfirst($order->rider->vehicle_type) }}{{ $order->rider->vehicle_number ? ' - '.$order->rider->vehicle_number : '' }}</p>
                </div>
                <div class="text-right">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $assignStatusColors[$order->status] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                        {{ ucfirst($order->status ?? 'review') }}
                    </span>
                    @if($order->estimated_time)
                        <p class="text-xs text-gray-400 mt-1"><i class="fa-regular fa-clock"></i> {{ $order->estimated_time }}</p>
                    @endif
                </div>
            </div>
        @else
            <p class="text-sm text-gray-400">No rider assigned yet. Use the <i class="fa-solid fa-motorcycle text-[#ff2d7a]"></i> button on the orders table to assign one.</p>
        @endif
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
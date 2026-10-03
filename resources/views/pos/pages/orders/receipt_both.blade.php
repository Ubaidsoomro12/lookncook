<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Receipt {{ $order->order_number }}</title>
<style>
    @page { margin: 20px 25px; }
    * { box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 12px;
        color: #1f2937;
        margin: 0;
        padding: 0;
    }

    /* Each .receipt-page is a full A4 page */
    .receipt-page {
        page-break-after: always;
        padding: 0;
        margin: 0;
    }
    .receipt-page:last-child {
        page-break-after: auto;
    }

    .header { text-align: center; padding-bottom: 12px; border-bottom: 2px solid #ff2d7a; margin-bottom: 16px; }
    .header .logo { width: 70px; height: 70px; border-radius: 50%; border: 2px solid #ff2d7a; margin-bottom: 6px; }
    .header h1 { font-size: 24px; font-weight: bold; color: #ff2d7a; margin: 4px 0 2px 0; letter-spacing: 1px; }
    .header .tagline { font-size: 11px; color: #6b7280; margin: 0; letter-spacing: 2px; text-transform: uppercase; }
    .header .contact { font-size: 10px; color: #6b7280; margin-top: 4px; }

    .receipt-title {
        text-align: center; font-size: 14px; font-weight: bold; color: #111827;
        letter-spacing: 3px; margin-bottom: 14px; padding: 6px 0;
        background: #fff5f8; border-top: 1px dashed #ffd6e5; border-bottom: 1px dashed #ffd6e5;
    }
    .receipt-title.dept { background: #f0f9ff; border-top-color: #bae6fd; border-bottom-color: #bae6fd; color: #075985; }

    .info-table { width: 100%; margin-bottom: 16px; border-collapse: collapse; }
    .info-table td { padding: 4px 8px; font-size: 11px; vertical-align: top; width: 50%; }
    .info-table .label { color: #6b7280; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px; }
    .info-table .value { color: #111827; font-weight: bold; font-size: 12px; }

    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .items-table thead th {
        background: #ff2d7a; color: #fff; font-size: 10px; text-transform: uppercase;
        letter-spacing: 0.5px; padding: 8px 6px; text-align: left; border: none;
    }
    .items-table thead th.center { text-align: center; }
    .items-table thead th.right { text-align: right; }
    .items-table tbody td {
        padding: 8px 6px; border-bottom: 1px solid #f3f4f6; font-size: 11px; vertical-align: top;
    }
    .items-table tbody tr:last-child td { border-bottom: 2px solid #ffd6e5; }
    .items-table tbody .item-name { font-weight: bold; color: #111827; }
    .items-table tbody .item-cat { color: #6b7280; font-size: 10px; }
    .items-table tbody td.center { text-align: center; }
    .items-table tbody td.right { text-align: right; font-weight: bold; }

    .bill-wrap { width: 100%; margin-top: 8px; }
    .bill-table { width: 55%; margin-left: auto; border-collapse: collapse; }
    .bill-table td { padding: 6px 10px; font-size: 12px; }
    .bill-table .bill-label { color: #6b7280; text-align: left; }
    .bill-table .bill-value { color: #111827; text-align: right; font-weight: bold; }
    .bill-table tr.total-row td { border-top: 2px solid #111827; padding-top: 10px; font-size: 16px; font-weight: bold; }
    .bill-table tr.total-row .bill-label { color: #111827; }
    .bill-table tr.total-row .bill-value { color: #ff2d7a; font-size: 18px; }

    .notes-box {
        margin-top: 14px; padding: 10px 12px; background: #fffbeb;
        border-left: 3px solid #f59e0b; font-size: 11px; color: #78350f;
    }
    .notes-box .notes-title { font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; font-size: 10px; margin-bottom: 4px; }

    .footer { margin-top: 24px; padding-top: 14px; border-top: 2px dashed #ffd6e5; text-align: center; }
    .footer .thanks { font-size: 14px; font-weight: bold; color: #ff2d7a; margin-bottom: 4px; letter-spacing: 1px; }
    .footer .thanks.dept { color: #075985; }
    .footer .msg { font-size: 10px; color: #6b7280; margin: 0 0 8px 0; }
    .footer .divider { font-size: 10px; color: #9ca3af; margin-top: 8px; letter-spacing: 1px; }
</style>
</head>
<body>

    {{-- =====================================================
         PAGE 1: CUSTOMER RECEIPT
    ====================================================== --}}
    <div class="receipt-page">
        <div class="header">
            @if(file_exists(public_path('images/lock-logo.png')))
                <img class="logo" src="{{ public_path('images/lock-logo.png') }}" alt="Logo">
            @endif
            <h1>Look n Cook</h1>
            <p class="tagline">Fine Dining Restaurant</p>
            <p class="contact">Main Branch &bull; 0300-0000000 &bull; info@lookncook.com</p>
        </div>

        <div class="receipt-title">CUSTOMER RECEIPT</div>

        <table class="info-table">
            <tr>
                <td>
                    <span class="label">Order No.</span>
                    <span class="value">#{{ $order->order_number }}</span>
                </td>
                <td>
                    <span class="label">Date &amp; Time</span>
                    <span class="value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Table</span>
                    <span class="value">{{ $order->table?->table_number ?? '—' }}@if($order->table?->table_name) &mdash; {{ $order->table->table_name }}@endif</span>
                </td>
                <td>
                    <span class="label">Waiter</span>
                    <span class="value">{{ $order->waiter?->name ?? 'Unassigned' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Customer</span>
                    <span class="value">{{ $order->customer_name ?: 'Walk-in Customer' }}</span>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Item</th>
                    <th class="center" style="width: 12%;">Qty</th>
                    <th class="right" style="width: 20%;">Unit Price</th>
                    <th class="right" style="width: 23%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            @if($item->category)<div class="item-cat">{{ $item->category->name }}</div>@endif
                        </td>
                        <td class="center">{{ $item->quantity }}</td>
                        <td class="right">Rs {{ number_format($item->unit_price, 2) }}</td>
                        <td class="right">Rs {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="center" style="color: #9ca3af; padding: 20px;">No items</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="bill-wrap">
            <table class="bill-table">
                <tr><td class="bill-label">Subtotal</td><td class="bill-value">Rs {{ number_format($order->subtotal, 2) }}</td></tr>
                @if((float) $order->tax > 0)
                <tr>
                    <td class="bill-label">Tax ({{ rtrim(rtrim(number_format($order->tax, 2, '.', ''), '0'), '.') }}%)</td>
                    <td class="bill-value">Rs {{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                @endif
                @if((float) $order->discount > 0)
                <tr>
                    <td class="bill-label">Discount ({{ rtrim(rtrim(number_format($order->discount, 2, '.', ''), '0'), '.') }}%)</td>
                    <td class="bill-value">- Rs {{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="bill-label">Grand Total</td>
                    <td class="bill-value">Rs {{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($order->notes)
        <div class="notes-box">
            <div class="notes-title">Special Instructions</div>
            {{ $order->notes }}
        </div>
        @endif

        <div class="footer">
            <div class="thanks">THANK YOU FOR DINING WITH US!</div>
            <p class="msg">We hope you enjoyed your meal. Please visit us again.</p>
            <div class="divider">&mdash;&mdash;&mdash; Powered by Look n Cook POS &mdash;&mdash;&mdash;</div>
        </div>
    </div>

    {{-- =====================================================
         PAGE 2: DEPARTMENT / KITCHEN RECEIPT
    ====================================================== --}}
    <div class="receipt-page">
        <div class="header">
            @if(file_exists(public_path('images/lock-logo.png')))
                <img class="logo" src="{{ public_path('images/lock-logo.png') }}" alt="Logo">
            @endif
            <h1>Look n Cook</h1>
            <p class="tagline">Kitchen / Bar Department</p>
            <p class="contact">Main Branch &bull; 0300-0000000 &bull; info@lookncook.com</p>
        </div>

        <div class="receipt-title dept">DEPARTMENT RECEIPT</div>

        <table class="info-table">
            <tr>
                <td>
                    <span class="label">Order No.</span>
                    <span class="value">#{{ $order->order_number }}</span>
                </td>
                <td>
                    <span class="label">Date &amp; Time</span>
                    <span class="value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Table</span>
                    <span class="value">{{ $order->table?->table_number ?? '—' }}@if($order->table?->table_name) &mdash; {{ $order->table->table_name }}@endif</span>
                </td>
                <td>
                    <span class="label">Waiter</span>
                    <span class="value">{{ $order->waiter?->name ?? 'Unassigned' }}</span>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="center" style="width: 15%;">Qty</th>
                    <th style="width: 55%;">Item</th>
                    <th style="width: 30%;">Category</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td class="center" style="font-size: 14px; font-weight: bold;">{{ $item->quantity }}×</td>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            @if($item->notes)
                                <div class="item-cat" style="color: #dc2626; font-weight: 600;">Note: {{ $item->notes }}</div>
                            @endif
                        </td>
                        <td>{{ $item->category?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="center" style="color: #9ca3af; padding: 20px;">No items</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($order->notes)
        <div class="notes-box">
            <div class="notes-title">Special Instructions from Order</div>
            {{ $order->notes }}
        </div>
        @endif

        <div class="footer">
            <div class="thanks dept">PREPARE WITH CARE</div>
            <p class="msg">Please ensure all items are prepared as per our standards.</p>
            <div class="divider">&mdash;&mdash;&mdash; Kitchen / Bar Department Copy &mdash;&mdash;&mdash;</div>
        </div>
    </div>

</body>
</html>
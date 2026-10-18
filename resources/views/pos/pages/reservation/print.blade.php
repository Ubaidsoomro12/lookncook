<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }} - {{ $reservation->customer_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff2d7a;
            --primary-light: #fff5f8;
            --dark: #111827;
            --gray: #6b7280;
            --border: #e5e7eb;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f3f4f6; 
            padding: 40px 20px; 
            color: var(--dark); 
            -webkit-print-color-adjust: exact;
        }

        /* Actions Bar */
        .actions-bar { 
            max-width: 1200px; margin: 0 auto 20px auto; 
            display: flex; justify-content: flex-end; gap: 12px; 
        }
        .btn-action { 
            padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px; 
            cursor: pointer; border: none; display: flex; align-items: center; gap: 8px; transition: all .2s;
        }
        .btn-pdf { background: var(--dark); color: #fff; }
        .btn-pdf:hover { background: #000; transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(0,0,0,.3); }
        .btn-print { background: var(--primary); color: #fff; }
        .btn-print:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(255,45,122,.4); }

        /* Receipts Container */
        .receipts-wrapper { 
            max-width: 1200px; margin: auto; display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;
        }

        /* Invoice Container */
        .invoice-container { 
            flex: 1; min-width: 450px; max-width: 580px; background: #fff; 
            border-radius: 20px; box-shadow: 0 10px 30px -10px rgba(0,0,0,.1); 
            overflow: hidden; position: relative;
        }

        /* Top Header */
        .header { 
            background: linear-gradient(135deg, var(--dark) 0%, #1f2937 100%); 
            color: #fff; padding: 30px; display: flex; justify-content: space-between; align-items: flex-start;
            position: relative;
        }
        .header::after {
            content: ''; position: absolute; right: 0; bottom: 0; width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(255,45,122,0.15) 0%, transparent 70%);
            border-radius: 50%; transform: translate(30%, 30%);
        }
        .logo-area h1 { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 4px; }
        .logo-area p { color: #9ca3af; font-size: 12px; font-weight: 500; }
        
        .copy-badge {
            display: inline-block; padding: 4px 12px; background: rgba(255,255,255,0.1);
            border-radius: 99px; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 8px; border: 1px solid rgba(255,255,255,0.2);
        }

        .invoice-meta { text-align: right; z-index: 10; }
        .invoice-meta h2 { font-size: 16px; font-weight: 800; color: var(--primary); margin-bottom: 2px; }
        .invoice-meta p { font-size: 11px; color: #9ca3af; margin-bottom: 2px; }
        
        .status-badge { 
            display: inline-block; padding: 4px 14px; border-radius: 99px; font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px; margin-top: 8px;
        }
        .status-pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.5); }
        .status-confirmed { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.5); }
        .status-completed { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.5); }
        .status-cancelled { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.5); }

        /* Body */
        .body-content { padding: 30px; }

        /* Info Grid */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-block h3 { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gray); font-weight: 700; margin-bottom: 10px; border-bottom: 1px solid var(--border); padding-bottom: 6px; }
        .info-block p { font-size: 12px; margin-bottom: 4px; color: var(--dark); }
        .info-block p span { font-weight: 600; color: var(--gray); display: inline-block; width: 70px; }
        .info-block p strong { font-weight: 700; }

        /* Table */
        .items-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 20px; }
        .items-table th { 
            background: var(--primary-light); color: var(--primary); font-size: 11px; font-weight: 800; 
            text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 12px; text-align: left;
            border-bottom: 2px solid #ffd6e5;
        }
        .items-table th:first-child { border-top-left-radius: 8px; }
        .items-table th:last-child { border-top-right-radius: 8px; text-align: right; }
        .items-table td { padding: 10px 12px; font-size: 12px; border-bottom: 1px solid var(--border); }
        .items-table td:last-child { text-align: right; font-weight: 700; }
        .items-table tr:last-child td { border-bottom: none; }

        /* Bottom Section */
        .bottom-section { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; }
        
        .notes-area { flex: 1; max-width: 250px; }
        .notes-area h4 { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gray); font-weight: 700; margin-bottom: 6px; }
        .notes-area p { font-size: 11px; color: var(--dark); background: #f9fafb; padding: 12px; border-radius: 10px; border: 1px solid var(--border); line-height: 1.4; }

        .totals-area { width: 300px; background: var(--primary-light); border-radius: 12px; padding: 20px; border: 1px solid #ffd6e5; }
        .totals-row { display: flex; justify-content: space-between; align-items: flex-start; font-size: 12px; margin-bottom: 8px; color: var(--gray); font-weight: 500; }
        .totals-row span:last-child { font-weight: 700; color: var(--dark); white-space: nowrap; margin-left: 10px; }
        .totals-row.discount span:last-child { color: #ef4444; }
        
        .totals-divider { border-top: 1px dashed #ffd6e5; margin: 12px 0; }
        
        .grand-total-row { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; }
        .grand-total-row span:first-child { font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--dark); }
        .grand-total-row span:last-child { font-size: 22px; font-weight: 900; color: var(--primary); }

        .per-head-row { 
            display: flex; justify-content: space-between; margin-top: 12px; padding-top: 12px; border-top: 1px solid #ffd6e5;
            font-size: 11px; font-weight: 600; color: var(--gray);
        }
        .per-head-row span:last-child { font-size: 14px; font-weight: 800; color: #10b981; }

        /* Footer */
        .footer { 
            background: #f9fafb; padding: 16px 30px; text-align: center; font-size: 10px; color: var(--gray); 
            border-top: 1px solid var(--border);
        }
        .footer strong { color: var(--dark); }

        /* Print Overrides */
        @media print {
            body { background: #fff; padding: 0; }
            .actions-bar { display: none; }
            .receipts-wrapper { display: block; max-width: 100%; }
            .invoice-container { box-shadow: none; border-radius: 0; max-width: 100%; margin-bottom: 30px; page-break-after: always; border: 2px solid #000; }
        }
        
        @media (max-width: 900px) {
            .receipts-wrapper { flex-direction: column; align-items: center; }
            .invoice-container { min-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <button class="btn-action btn-pdf" onclick="downloadPDF()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download PDF
        </button>
        <button class="btn-action btn-print" onclick="window.print()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Only
        </button>
    </div>

    <div class="receipts-wrapper" id="invoiceContent">
        
        @php
            $statusClass = match($reservation->status) {
                'confirmed' => 'status-confirmed',
                'completed' => 'status-completed',
                'cancelled' => 'status-cancelled',
                default => 'status-pending',
            };
            
            // Calculate base amount
            $baseAmount = $reservation->food_subtotal + $reservation->hall_payment + $reservation->decoration_total;
            
            // If discount in DB is a percentage (<= 100) and greater than 0, calculate the real amount
            $discountPercent = 0;
            $discountAmount = $reservation->discount;
            
            if ($reservation->discount > 0 && $reservation->discount <= 100) {
                $discountPercent = $reservation->discount;
                $discountAmount = ($baseAmount * $discountPercent) / 100;
            }
            
            // Calculate dynamic tax
            $calcTax = ($baseAmount * $reservation->tax_percent) / 100;
            
            // Recalculate grand total dynamically in case the DB is out of sync
            $dynamicGrandTotal = max(0, $baseAmount + $calcTax - $discountAmount);
        @endphp

        <!-- COPY 1: CUSTOMER RECEIPT -->
        <div class="invoice-container">
            <div class="header">
                <div class="logo-area">
                    <div class="copy-badge">Customer Copy</div>
                    <h1>Look n Cook</h1>
                    <p>Premium Event Reservation</p>
                </div>
                <div class="invoice-meta">
                    <h2>BILL #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <p>Generated: {{ now()->format('d M Y') }}</p>
                    <div class="status-badge {{ $statusClass }}">{{ $reservation->status }}</div>
                </div>
            </div>

            <div class="body-content">
                <div class="info-grid">
                    <div class="info-block">
                        <h3>Customer Details</h3>
                        <p><span>Name:</span> <strong>{{ $reservation->customer_name }}</strong></p>
                        <p><span>Phone:</span> <strong>{{ $reservation->customer_phone }}</strong></p>
                        <p><span>Address:</span> {{ $reservation->address ?? 'N/A' }}</p>
                    </div>
                    <div class="info-block">
                        <h3>Event Details</h3>
                        <p><span>Hall:</span> <strong>{{ $reservation->hall_name }}</strong></p>
                        <p><span>Date:</span> <strong>{{ $reservation->event_date->format('d M Y') }}</strong></p>
                        <p><span>Guests:</span> <strong>{{ $reservation->total_guests }}</strong></p>
                        <p><span>Waiters:</span> {{ $reservation->waiters->pluck('name')->implode(', ') ?: 'None' }}</p>
                    </div>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservation->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="bottom-section">
                    <div class="notes-area">
                        @if($reservation->notes)
                            <h4>Special Notes</h4>
                            <p>{{ $reservation->notes }}</p>
                        @endif
                    </div>

                    <div class="totals-area">
                        <div class="totals-row">
                            <span>Food Subtotal</span>
                            <span>Rs {{ number_format($reservation->food_subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span>Hall Charges</span>
                            <span>Rs {{ number_format($reservation->hall_payment, 2) }}</span>
                        </div>
                        
                        @if($reservation->stage_decoration > 0)
                        <div class="totals-row">
                            <span>Stage Decoration</span>
                            <span>Rs {{ number_format($reservation->stage_decoration, 2) }}</span>
                        </div>
                        @endif

                        @if($reservation->front_decoration > 0)
                        <div class="totals-row">
                            <span>Front Decoration</span>
                            <span>Rs {{ number_format($reservation->front_decoration, 2) }}</span>
                        </div>
                        @endif

                        @if($reservation->overall_decoration > 0)
                        <div class="totals-row">
                            <span>Overall Decoration</span>
                            <span>Rs {{ number_format($reservation->overall_decoration, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="totals-divider"></div>
                        
                        <div class="totals-row">
                            <span>Tax ({{ $reservation->tax_percent }}% of Rs {{ number_format($baseAmount, 0) }})</span>
                            <span>Rs {{ number_format($calcTax, 2) }}</span>
                        </div>
                        <div class="totals-row discount">
                            <span>Discount {{ $discountPercent > 0 ? '('. $discountPercent .'% of Rs '. number_format($baseAmount, 0) .')' : '' }}</span>
                            <span>- Rs {{ number_format($discountAmount, 2) }}</span>
                        </div>
                        
                        <div class="totals-divider"></div>

                        <div class="grand-total-row">
                            <span>Grand Total</span>
                            <span>Rs {{ number_format($reservation->grand_total, 2) }}</span>
                        </div>
                        
                        <div class="per-head-row">
                            <span>Per Head ({{ $reservation->total_guests }})</span>
                            <span>Rs {{ number_format($reservation->per_head_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer">
                Thank you for choosing <strong>Look n Cook</strong>. We look forward to serving you!<br>
                This is a computer-generated document.
            </div>
        </div>


        <!-- COPY 2: HOTEL RECEIPT -->
        <div class="invoice-container">
            <div class="header">
                <div class="logo-area">
                    <div class="copy-badge">Hotel Copy</div>
                    <h1>Look n Cook</h1>
                    <p>Premium Event Reservation</p>
                </div>
                <div class="invoice-meta">
                    <h2>BILL #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <p>Generated: {{ now()->format('d M Y') }}</p>
                    <div class="status-badge {{ $statusClass }}">{{ $reservation->status }}</div>
                </div>
            </div>

            <div class="body-content">
                <div class="info-grid">
                    <div class="info-block">
                        <h3>Customer Details</h3>
                        <p><span>Name:</span> <strong>{{ $reservation->customer_name }}</strong></p>
                        <p><span>Phone:</span> <strong>{{ $reservation->customer_phone }}</strong></p>
                        <p><span>Address:</span> {{ $reservation->address ?? 'N/A' }}</p>
                    </div>
                    <div class="info-block">
                        <h3>Event Details</h3>
                        <p><span>Hall:</span> <strong>{{ $reservation->hall_name }}</strong></p>
                        <p><span>Date:</span> <strong>{{ $reservation->event_date->format('d M Y') }}</strong></p>
                        <p><span>Guests:</span> <strong>{{ $reservation->total_guests }}</strong></p>
                        <p><span>Waiters:</span> {{ $reservation->waiters->pluck('name')->implode(', ') ?: 'None' }}</p>
                    </div>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservation->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="bottom-section">
                    <div class="notes-area">
                        @if($reservation->notes)
                            <h4>Special Notes</h4>
                            <p>{{ $reservation->notes }}</p>
                        @endif
                        
                        <div style="margin-top: 15px;">
                            <h4>Prepared By</h4>
                            <p style="background:transparent; padding:0; border:none; font-weight: 600; font-size: 11px;">
                                {{ $reservation->creator->name ?? 'System' }} <br>
                                <span style="font-size:10px; color:#6b7280; font-weight:400;">
                                    {{ $reservation->created_at->format('d M Y, h:i A') }}
                                    @if($reservation->updater)
                                        <br>Edited: {{ $reservation->updater->name }}
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="totals-area">
                        <div class="totals-row">
                            <span>Food Subtotal</span>
                            <span>Rs {{ number_format($reservation->food_subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span>Hall Charges</span>
                            <span>Rs {{ number_format($reservation->hall_payment, 2) }}</span>
                        </div>
                        
                        @if($reservation->stage_decoration > 0)
                        <div class="totals-row">
                            <span>Stage Decoration</span>
                            <span>Rs {{ number_format($reservation->stage_decoration, 2) }}</span>
                        </div>
                        @endif

                        @if($reservation->front_decoration > 0)
                        <div class="totals-row">
                            <span>Front Decoration</span>
                            <span>Rs {{ number_format($reservation->front_decoration, 2) }}</span>
                        </div>
                        @endif

                        @if($reservation->overall_decoration > 0)
                        <div class="totals-row">
                            <span>Overall Decoration</span>
                            <span>Rs {{ number_format($reservation->overall_decoration, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="totals-divider"></div>
                        
                        <div class="totals-row">
                            <span>Tax ({{ $reservation->tax_percent }}% of Rs {{ number_format($baseAmount, 0) }})</span>
                            <span>Rs {{ number_format($calcTax, 2) }}</span>
                        </div>
                        <div class="totals-row discount">
                            <span>Discount {{ $discountPercent > 0 ? '('. $discountPercent .'% of Rs '. number_format($baseAmount, 0) .')' : '' }}</span>
                            <span>- Rs {{ number_format($discountAmount, 2) }}</span>
                        </div>
                        
                        <div class="totals-divider"></div>

                        <div class="grand-total-row">
                            <span>Grand Total</span>
                            <span>Rs {{ number_format($reservation->grand_total, 2) }}</span>
                        </div>
                        
                        <div class="per-head-row">
                            <span>Per Head ({{ $reservation->total_guests }})</span>
                            <span>Rs {{ number_format($reservation->per_head_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer">
                Thank you for choosing <strong>Look n Cook</strong>. We look forward to serving you!<br>
                This is a computer-generated document.
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('invoiceContent');
            const opt = {
                margin:       0.2,
                filename:     'Reservation_{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}_{{ Str::slug($reservation->customer_name) }}.pdf',
                image:        { type: 'jpeg', quality: 1 },
                html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
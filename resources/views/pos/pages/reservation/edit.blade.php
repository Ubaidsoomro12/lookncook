@extends('pos.layouts.pos_master')

@section('title', 'Edit Reservation')

@section('content')
<style>
    /* Form Styling */
    .form-section { background:#fff; border:1.5px solid #ffd6e5; border-radius:16px; padding:24px; margin-bottom:20px; box-shadow:0 4px 12px rgba(255,45,122,.04); }
    .section-title { font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#6b7280; margin-bottom:18px; display:flex; align-items:center; border-bottom:1px solid #f3f4f6; padding-bottom:12px; }
    .section-title i { color:#ff2d7a; margin-right:10px; font-size:14px; }
    .field-group { display:flex; flex-direction:column; gap:6px; margin-bottom:16px; }
    .field-group label { font-size:12px; font-weight:700; color:#374151; }
    .field-group input, .field-group select, .field-group textarea { border-radius:10px; border:1.5px solid #e5e7eb; font-size:13px; padding:10px 14px; transition:all .2s; background:#f9fafb; }
    .field-group input:focus, .field-group select:focus, .field-group textarea:focus { border-color:#ff2d7a; background:#fff; box-shadow:0 0 0 4px rgba(255,45,122,.1); outline:none; }
    
    /* Waiter Chips */
    .waiter-box { background:#f9fafb; border:2px dashed #e5e7eb; border-radius:12px; padding:12px; min-height:104px; display:flex; flex-wrap:wrap; gap:8px; align-content:flex-start; }
    .waiter-chip { background:linear-gradient(135deg,#ff2d7a,#ff4b91); color:#fff; padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; display:flex; align-items:center; gap:8px; box-shadow:0 4px 10px rgba(255,45,122,.3); animation:popIn .2s ease; }
    .waiter-chip i { cursor:pointer; opacity:.7; transition:opacity .2s; }
    .waiter-chip i:hover { opacity:1; }
    
    /* Categories & Foods */
    .cat-tile { cursor:pointer; border:2px solid #f3f4f6; border-radius:12px; padding:12px 8px; text-align:center; transition:all .2s; background:#fff; }
    .cat-tile:hover, .cat-tile.active { border-color:#ff2d7a; background:#fff5f8; transform:translateY(-2px); box-shadow:0 6px 16px rgba(255,45,122,.15); }
    .cat-tile img { width:48px; height:48px; border-radius:50%; object-fit:cover; margin-bottom:8px; }
    .cat-tile .name { font-size:11px; font-weight:700; color:#111827; line-height:1.2; }
    
    .food-tile { cursor:pointer; border:1px solid #e5e7eb; border-radius:12px; padding:12px; position:relative; transition:all .2s; background:#fff; }
    .food-tile:hover { border-color:#ff2d7a; box-shadow:0 8px 20px rgba(255,45,122,.15); transform:translateY(-3px); }
    .food-tile .f-img { width:100%; height:90px; object-fit:cover; border-radius:8px; margin-bottom:10px; background:#f3f4f6; display:block; }
    .food-tile .f-img-fallback { width:100%; height:90px; border-radius:8px; background:#f3f4f6; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:26px; margin-bottom:10px; }
    .food-tile .f-name { font-size:13px; font-weight:700; line-height:1.2; margin-bottom:4px; }
    .food-tile .f-price { font-size:13px; font-weight:800; color:#ff2d7a; }
    
    /* Right Summary Panel (Stylish) */
    .summary-panel { 
        background: linear-gradient(180deg, #111827 0%, #0f172a 100%); 
        color:#fff; border-radius:20px; padding:24px; 
        position:sticky; top:20px; 
        box-shadow: 0 20px 40px -10px rgba(0,0,0,.5);
        border: 1px solid #1f2937;
    }
    .summary-panel h6 { font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#6b7280; margin-bottom:16px; display:flex; align-items:center; }
    .summary-panel h6 i { color:#ff2d7a; margin-right:8px; font-size:14px; }
    
    .cart-item { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #1f2937; font-size:13px; }
    .cart-item:last-child { border-bottom:none; }
    .qty-btn { width:26px; height:26px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#fff; font-weight:700; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; }
    .qty-btn:hover { background:#ff2d7a; border-color:#ff2d7a; }
    
    .sum-row { display:flex; justify-content:space-between; align-items:center; font-size:13px; margin-bottom:12px; color:#9ca3af; font-weight:500; }
    .sum-row span:last-child { font-weight:700; color:#fff; font-size:14px; }
    
    .sum-input { width:110px; background:#1f2937; border:1px solid #374151; color:#fff; border-radius:8px; padding:6px 10px; text-align:right; font-size:13px; font-weight:600; transition:all .2s; }
    .sum-input:focus { outline:none; border-color:#ff2d7a; box-shadow:0 0 0 3px rgba(255,45,122,.2); }
    
    .sum-divider { border-top:1px dashed #374151; margin:16px 0; }
    
    .grand-total-box {
        background: linear-gradient(135deg, rgba(255,45,122,.15), rgba(255,45,122,.05));
        border: 1px solid rgba(255,45,122,.3);
        border-radius: 14px; padding: 16px; text-align: center; margin-bottom: 12px;
    }
    .grand-total-label { font-size: 10px; text-transform: uppercase; letter-spacing: .1em; color: #ff8fab; font-weight: 800; margin-bottom: 4px; }
    .grand-total-value { font-size: 28px; font-weight: 900; color: #ff2d7a; line-height: 1; text-shadow: 0 2px 10px rgba(255,45,122,.3); }
    
    .per-head-box {
        display: flex; justify-content: space-between; align-items: center;
        background: #1f2937; padding: 12px 16px; border-radius: 10px;
        font-size: 13px; font-weight: 600; color: #9ca3af;
    }
    .per-head-box span:last-child { color: #10b981; font-size: 16px; font-weight: 800; }
    
    /* Status Pills */
    .status-pill { display:inline-block; padding:8px 20px; border-radius:99px; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; border:2px solid #e5e7eb; background:#fff; color:#6b7280; margin-right:8px; }
    .status-pill:hover { border-color:#ffd6e5; background:#fff5f8; color:#ff2d7a; }
    .status-pill.active { background:linear-gradient(135deg,#ff2d7a,#ff4b91); color:#fff; border-color:#ff2d7a; box-shadow:0 4px 12px rgba(255,45,122,.3); }
    
    @keyframes popIn { 0% { transform:scale(0); } 100% { transform:scale(1); } }
    
    /* Scrollbar */
    ::-webkit-scrollbar { width:6px; }
    ::-webkit-scrollbar-track { background:#f1f1f1; border-radius:10px; }
    ::-webkit-scrollbar-thumb { background:#ffd6e5; border-radius:10px; }
    ::-webkit-scrollbar-thumb:hover { background:#ff2d7a; }
</style>

<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Edit Reservation #{{ $reservation->id }}</h4>
            <p class="text-muted small m-0">Created on {{ $reservation->created_at->format('d M Y') }} by {{ $reservation->creator->name ?? 'System' }}</p>
        </div>
        <a href="{{ route('pos.reservations.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    </div>

    <form id="reservationForm">
        <div class="row g-4">
            <!-- LEFT SIDE -->
            <div class="col-lg-8">
                
                <!-- Customer Section -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-user"></i>Customer Details</div>
                    <div class="row">
                        <div class="col-md-6 field-group">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <input type="text" id="custName" required value="{{ $reservation->customer_name }}">
                        </div>
                        <div class="col-md-6 field-group">
                            <label>Phone Number <span class="text-danger">*</span></label>
                            <input type="text" id="custPhone" required value="{{ $reservation->customer_phone }}">
                        </div>
                        <div class="col-md-6 field-group">
                            <label>Event Date <span class="text-danger">*</span></label>
                            <input type="date" id="eventDate" required value="{{ $reservation->event_date->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 field-group">
                            <label>Total Guests <span class="text-danger">*</span></label>
                            <input type="number" id="totalGuests" required min="1" value="{{ $reservation->total_guests }}">
                        </div>
                    </div>
                </div>

                <!-- Hall Section -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-building"></i>Hall Details</div>
                    <div class="row">
                        <div class="col-md-6 field-group">
                            <label>Hall Name <span class="text-danger">*</span></label>
                            <input type="text" id="hallName" required value="{{ $reservation->hall_name }}">
                        </div>
                        <div class="col-md-6 field-group">
                            <label>Hall Address</label>
                            <input type="text" id="address" value="{{ $reservation->address }}">
                        </div>
                        <div class="col-md-6 field-group">
                            <label>Hall Payment (Rs)</label>
                            <input type="number" id="hallPayment" class="form-control" value="{{ $reservation->hall_payment }}" min="0" step="0.01" oninput="calculateTotals()">
                        </div>
                    </div>
                </div>

                <!-- Waiter Section -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-users"></i>Assign Waiters</div>
                    <div class="row">
                        <div class="col-md-5 field-group">
                            <label>Select Waiter</label>
                            <select id="waiterSelect" class="form-select">
                                <option value="">-- Choose Waiter --</option>
                                @foreach($waiters as $w)
                                    <option value="{{ $w->id }}" data-name="{{ $w->name }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="small fw-bold text-muted mb-1">Assigned Waiters</label>
                            <div class="waiter-box" id="assignedWaiters"></div>
                        </div>
                    </div>
                </div>

                <!-- Food Section -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-utensils"></i>Food Menu</div>
                    <div class="row g-2 mb-3" id="categoriesWrap">
                        @foreach($categories as $c)
                            <div class="col-4 col-md-3 col-lg-2">
                                <div class="cat-tile" onclick="loadFoods({{ $c->id }}, this)">
                                    @if($c->image) <img src="{{ asset($c->image) }}" alt=""> @else <i class="fa-solid fa-utensils fa-2x text-muted mb-2"></i> @endif
                                    <div class="name">{{ $c->name }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row g-3" id="foodsContainer">
                        <div class="col-12 text-center text-muted small py-5">
                            <i class="fa-solid fa-hand-pointer fa-2x mb-2 d-block" style="color:#ffd6e5;"></i>
                            Select a category to view foods
                        </div>
                    </div>
                </div>

                <!-- Decoration Section -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-wand-magic-sparkles"></i>Decoration Details</div>
                    <div class="row">
                        <div class="col-md-4 field-group">
                            <label>Stage Decoration (Rs)</label>
                            <input type="number" id="stageDec" class="form-control" value="{{ $reservation->stage_decoration }}" min="0" step="0.01" oninput="calculateTotals()">
                        </div>
                        <div class="col-md-4 field-group">
                            <label>Front Decoration (Rs)</label>
                            <input type="number" id="frontDec" class="form-control" value="{{ $reservation->front_decoration }}" min="0" step="0.01" oninput="calculateTotals()">
                        </div>
                        <div class="col-md-4 field-group">
                            <label>Overall Decoration (Rs)</label>
                            <input type="number" id="overallDec" class="form-control" value="{{ $reservation->overall_decoration }}" min="0" step="0.01" oninput="calculateTotals()">
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="form-section">
                    <div class="section-title"><i class="fa-solid fa-flag"></i>Booking Status</div>
                    <input type="hidden" id="statusInput" value="{{ $reservation->status }}">
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <div class="status-pill {{ $reservation->status == 'pending' ? 'active' : '' }}" onclick="setStatus('pending', this)">Pending</div>
                        <div class="status-pill {{ $reservation->status == 'confirmed' ? 'active' : '' }}" onclick="setStatus('confirmed', this)">Confirmed</div>
                        <div class="status-pill {{ $reservation->status == 'completed' ? 'active' : '' }}" onclick="setStatus('completed', this)">Completed</div>
                        <div class="status-pill {{ $reservation->status == 'cancelled' ? 'active' : '' }}" onclick="setStatus('cancelled', this)">Cancelled</div>
                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE (Summary Panel) -->
            <div class="col-lg-4">
                <div class="summary-panel">
                    
                    <h6><i class="fa-solid fa-cart-shopping"></i>Selected Items</h6>
                    <div id="cartContainer" style="max-height:200px; overflow-y:auto; margin-bottom:20px; padding-right:4px;">
                        <div class="text-muted small text-center py-4" id="emptyCartMsg">No items added yet</div>
                    </div>

                    <div class="sum-divider"></div>

                    <h6><i class="fa-solid fa-receipt"></i>Bill Summary</h6>
                    
                    <div class="sum-row">
                        <span>Food Subtotal</span>
                        <span id="lblFoodSubtotal">Rs 0.00</span>
                    </div>
                    <div class="sum-row">
                        <span>Decoration Total</span>
                        <span id="lblDecTotal">Rs 0.00</span>
                    </div>
                    
                    <div class="sum-divider"></div>

                    <div class="sum-row">
                        <span>Tax (%)</span>
                        <input type="number" id="taxPercent" class="sum-input" value="{{ $reservation->tax_percent }}" min="0" step="0.1" oninput="calculateTotals()">
                    </div>
                    <div class="sum-row">
                        <span>Discount (Rs)</span>
                        <input type="number" id="discount" class="sum-input" value="{{ $reservation->discount }}" min="0" step="0.01" oninput="calculateTotals()">
                    </div>
                    
                    <div class="sum-divider"></div>

                    <div class="grand-total-box">
                        <div class="grand-total-label">Grand Total</div>
                        <div class="grand-total-value" id="lblGrandTotal">Rs 0.00</div>
                    </div>
                    
                    <div class="per-head-box">
                        <span>Per Head Amount</span>
                        <span id="lblPerHead">Rs 0.00</span>
                    </div>

                    <div class="mt-4">
                        <label class="small text-muted mb-2 fw-bold">Notes</label>
                        <textarea id="notes" class="form-control" rows="2" style="background:#1f2937; color:#fff; border-color:#374151; font-size:13px; border-radius:10px;">{{ $reservation->notes }}</textarea>
                    </div>

                    <button type="button" class="btn w-100 mt-4 py-3" style="background:linear-gradient(135deg,#ff2d7a,#ff4b91); color:#fff; font-weight:800; border-radius:12px; font-size:15px; box-shadow:0 10px 20px -5px rgba(255,45,122,.4);" onclick="updateReservation()">
                        <i class="fa-solid fa-save me-2"></i>Update Reservation
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const csrf = "{{ csrf_token() }}";
    const RES_ID = {{ $reservation->id }};
    
    let cart = {!! json_encode($reservation->items->map(function($item) { return ['id' => $item->product_id, 'name' => $item->product_name, 'price' => (float)$item->unit_price, 'qty' => $item->quantity]; })) !!};
    let selectedWaiters = {!! json_encode($reservation->waiters->pluck('id')->map(fn($id) => (string)$id)) !!};

    // ================= STATUS LOGIC =================
    function setStatus(status, el) {
        document.querySelectorAll('.status-pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('statusInput').value = status;
    }

    // ================= WAITER LOGIC =================
    const waiterSelect = document.getElementById('waiterSelect');
    const waiterBox = document.getElementById('assignedWaiters');

    waiterSelect.addEventListener('change', function() {
        const id = this.value;
        if (!id) return;
        if (!selectedWaiters.includes(id)) {
            selectedWaiters.push(id);
            renderWaiters();
        }
        this.value = ''; 
    });

    function renderWaiters() {
        waiterBox.innerHTML = '';
        selectedWaiters.forEach(id => {
            const option = waiterSelect.querySelector(`option[value="${id}"]`);
            const name = option ? option.getAttribute('data-name') : 'Waiter';
            if (option) option.style.display = 'none';

            const chip = document.createElement('div');
            chip.className = 'waiter-chip';
            chip.innerHTML = `${name} <i class="fa-solid fa-xmark" onclick="removeWaiter('${id}')"></i>`;
            waiterBox.appendChild(chip);
        });
    }

    window.removeWaiter = function(id) {
        selectedWaiters = selectedWaiters.filter(w => w !== id);
        const option = waiterSelect.querySelector(`option[value="${id}"]`);
        if (option) option.style.display = 'block'; 
        renderWaiters();
    };

    // ================= FOOD LOGIC =================
    async function loadFoods(catId, el) {
        document.querySelectorAll('.cat-tile').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        
        const container = document.getElementById('foodsContainer');
        container.innerHTML = '<div class="col-12 text-center py-4"><i class="fa-solid fa-spinner fa-spin fa-2x" style="color:#ffd6e5;"></i></div>';
        
        try {
            const res = await fetch(`{{ url('pos/reservations/ajax/foods') }}/${catId}`);
            const data = await res.json();
            container.innerHTML = '';
            if(data.foods.length === 0) {
                container.innerHTML = '<div class="col-12 text-center text-muted small py-5">No foods in this category</div>';
                return;
            }
            data.foods.forEach(f => {
                const div = document.createElement('div');
                div.className = 'col-6 col-md-4 col-xl-3';
                
                const imgHtml = f.image 
                    ? `<img src="${f.image}" class="f-img" alt="${f.name}">`
                    : `<div class="f-img-fallback"><i class="fa-solid fa-utensils"></i></div>`;

                div.innerHTML = `
                    <div class="food-tile" onclick="addToCart(${f.id}, '${f.name.replace(/'/g, "\\'")}', ${f.price})">
                        ${imgHtml}
                        <div class="f-name">${f.name}</div>
                        <div class="f-price">Rs ${f.price}</div>
                    </div>
                `;
                container.appendChild(div);
            });
        } catch (e) {
            container.innerHTML = '<div class="col-12 text-danger py-3">Failed to load foods</div>';
        }
    }

    window.addToCart = function(id, name, price) {
        const existing = cart.find(item => item.id === id);
        if (existing) { existing.qty++; } else { cart.push({ id, name, price, qty: 1 }); }
        renderCart();
    };

    window.updateQty = function(id, change) {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        item.qty += change;
        if (item.qty <= 0) { cart = cart.filter(i => i.id !== id); }
        renderCart();
    };

    window.removeCartItem = function(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    };

    function renderCart() {
        const container = document.getElementById('cartContainer');
        if (cart.length === 0) {
            container.innerHTML = '<div class="text-muted small text-center py-4" id="emptyCartMsg">No items added yet</div>';
            calculateTotals();
            return;
        }
        container.innerHTML = '';
        cart.forEach(item => {
            const div = document.createElement('div');
            div.className = 'cart-item';
            div.innerHTML = `
                <div style="flex:1;">
                    <div class="fw-bold text-white" style="font-size:13px;">${item.name}</div>
                    <div class="text-muted small" style="font-size:11px;">Rs ${item.price.toLocaleString()}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                    <span class="text-white fw-bold" style="font-size:13px;">${item.qty}</span>
                    <button type="button" class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    <button type="button" class="btn btn-sm text-danger p-0 ms-2" onclick="removeCartItem(${item.id})" style="opacity:.8;"><i class="fa-solid fa-trash"></i></button>
                </div>
            `;
            container.appendChild(div);
        });
        calculateTotals();
    }

    // ================= CALCULATION LOGIC =================
    function calculateTotals() {
        const foodSubtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        document.getElementById('lblFoodSubtotal').innerText = `Rs ${foodSubtotal.toLocaleString(undefined, {minimumFractionDigits:2})}`;

        const hall = parseFloat(document.getElementById('hallPayment').value) || 0;
        const stage = parseFloat(document.getElementById('stageDec').value) || 0;
        const front = parseFloat(document.getElementById('frontDec').value) || 0;
        const overall = parseFloat(document.getElementById('overallDec').value) || 0;
        const decTotal = stage + front + overall;
        
        document.getElementById('lblDecTotal').innerText = `Rs ${decTotal.toLocaleString(undefined, {minimumFractionDigits:2})}`;

        const baseAmount = foodSubtotal + hall + decTotal;

        const taxPercent = parseFloat(document.getElementById('taxPercent').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const taxAmount = baseAmount * (taxPercent / 100);

        const grandTotal = Math.max(0, baseAmount + taxAmount - discount);
        const guests = parseFloat(document.getElementById('totalGuests').value) || 1;
        const perHead = grandTotal / guests;

        document.getElementById('lblGrandTotal').innerText = `Rs ${grandTotal.toLocaleString(undefined, {minimumFractionDigits:2})}`;
        document.getElementById('lblPerHead').innerText = `Rs ${perHead.toLocaleString(undefined, {minimumFractionDigits:2})}`;
    }

    document.getElementById('totalGuests').addEventListener('input', calculateTotals);

    async function updateReservation() {
        // We calculate the exact amounts before sending to the controller
        const foodSubtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const hall = parseFloat(document.getElementById('hallPayment').value) || 0;
        const decTotal = (parseFloat(document.getElementById('stageDec').value)||0) + (parseFloat(document.getElementById('frontDec').value)||0) + (parseFloat(document.getElementById('overallDec').value)||0);
        const base = foodSubtotal + hall + decTotal;
        
        const taxPct = parseFloat(document.getElementById('taxPercent').value) || 0;
        const taxAmt = (base * taxPct) / 100;
        
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const grandTotal = Math.max(0, base + taxAmt - discount);

        const payload = {
            customer_name: document.getElementById('custName').value,
            customer_phone: document.getElementById('custPhone').value,
            hall_name: document.getElementById('hallName').value,
            address: document.getElementById('address').value,
            event_date: document.getElementById('eventDate').value,
            total_guests: document.getElementById('totalGuests').value,
            waiters: selectedWaiters,
            items: cart,
            status: document.getElementById('statusInput').value, 
            hall_payment: hall,
            stage_decoration: parseFloat(document.getElementById('stageDec').value) || 0,
            front_decoration: parseFloat(document.getElementById('frontDec').value) || 0,
            overall_decoration: parseFloat(document.getElementById('overallDec').value) || 0,
            decoration_total: decTotal,
            tax_percent: taxPct,
            tax_amount: taxAmt, // Sent correctly to DB
            discount: discount,
            notes: document.getElementById('notes').value,
            food_subtotal: foodSubtotal,
            grand_total: grandTotal,
            per_head_amount: (grandTotal / (parseFloat(document.getElementById('totalGuests').value) || 1))
        };

        if(!payload.customer_name || !payload.customer_phone || !payload.hall_name || !payload.event_date) {
            return Swal.fire('Missing Fields', 'Please fill all required customer and hall details.', 'warning');
        }
        if(cart.length === 0) {
            return Swal.fire('No Food Selected', 'Please add at least one food item.', 'warning');
        }

        try {
            const res = await fetch(`{{ url('pos/reservations') }}/${RES_ID}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Reservation Updated!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('pos.reservations.index') }}";
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to update', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Network or Server Error: ' + e.message, 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderWaiters();
        renderCart();
    });
</script>
@endsection
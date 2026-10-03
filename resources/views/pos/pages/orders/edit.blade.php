@extends('pos.layouts.pos_master')

@section('title', 'Edit Order ' . $order->order_number)

@section('content')
<style>
.card-clean{background:#fff;border:1.5px solid #ffd6e5;border-radius:16px;box-shadow:0 2px 8px rgba(255,45,122,.05);}
.card-clean .card-body{padding:18px 20px;}
@media (min-width: 768px){.card-clean .card-body{padding:22px 26px;}}
.section-title{
    font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;
    color:#6b7280;margin-bottom:14px;padding-left:10px;
    display:flex;align-items:center;min-height:20px;
}
.btn-pink{background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;font-weight:600;border-radius:10px;}
.btn-pink:hover{color:#fff;filter:brightness(1.05);}
.field-group{display:flex;flex-direction:column;gap:5px;}
.field-group .form-label{font-size:12px;font-weight:600;color:#374151;margin-bottom:0;}
.field-group .form-select,
.field-group .form-control{border-radius:10px;border:1.5px solid #e5e7eb;font-size:13px;min-height:42px;}
.field-group .form-select:focus,
.field-group .form-control:focus{border-color:#ff2d7a;box-shadow:0 0 0 3px rgba(255,45,122,.12);}
.field-group .form-select:disabled{background:#f9fafb;color:#6b7280;}
.categories-wrap{
    display:grid;grid-template-columns:repeat(auto-fill,minmax(88px,1fr));
    gap:8px;max-height:400px;overflow-y:auto;padding:4px 8px 4px 4px;
}
@media (min-width: 576px){
    .categories-wrap{grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:12px;}
}
.cat-tile{
    display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
    gap:6px;padding:10px 6px;min-height:100px;
    border:2px solid #f3f4f6;border-radius:14px;background:#fafafa;cursor:pointer;
    transition:all .25s cubic-bezier(.4,0,.2,1);box-sizing:border-box;
}
@media (min-width: 576px){
    .cat-tile{gap:8px;padding:12px 8px;min-height:112px;}
}
.cat-tile:hover{border-color:#ffd6e5;background:#fff5f8;transform:translateY(-2px);}
.cat-tile.active{border-color:#ff2d7a;background:#fff5f8;box-shadow:0 8px 20px -8px rgba(255,45,122,.4);}
.cat-tile .cat-img{
    width:46px;height:46px;border-radius:12px;overflow:hidden;
    background:#e5e7eb;display:flex;align-items:center;justify-content:center;
    color:#9ca3af;font-size:17px;flex-shrink:0;
}
@media (min-width: 576px){
    .cat-tile .cat-img{width:56px;height:56px;font-size:20px;}
}
.cat-tile .cat-img img{width:100%;height:100%;object-fit:cover;display:block;}
.cat-tile .cat-name{
    font-size:10px;font-weight:600;color:#111827;line-height:1.25;
    text-align:center;width:100%;height:28px;overflow:hidden;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;word-break:break-word;
}
@media (min-width: 576px){.cat-tile .cat-name{font-size:11px;}}
.foods-wrap{
    display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));
    gap:8px;max-height:400px;overflow-y:auto;padding:4px 8px 4px 4px;
}
@media (min-width: 576px){
    .foods-wrap{grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;}
}
.foods-empty{
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    height:400px;color:#9ca3af;text-align:center;padding:20px;
}
.foods-empty i{font-size:42px;color:#ffd6e5;margin-bottom:12px;}
.food-tile{
    position:relative;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;
    overflow:hidden;cursor:pointer;user-select:none;
    transition:all .25s cubic-bezier(.4,0,.2,1);
}
.food-tile:hover{border-color:#ff2d7a;transform:translateY(-3px);box-shadow:0 12px 24px -10px rgba(255,45,122,.4);}
.food-tile:active{transform:translateY(-1px) scale(.98);}
.food-tile .food-img{width:100%;height:80px;object-fit:cover;background:#f3f4f6;display:block;}
@media (min-width: 576px){.food-tile .food-img{height:100px;}}
.food-tile .food-img-fallback{
    width:100%;height:80px;background:#f3f4f6;
    display:flex;align-items:center;justify-content:center;color:#d1d5db;font-size:26px;
}
@media (min-width: 576px){.food-tile .food-img-fallback{height:100px;font-size:32px;}}
.food-tile .food-info{padding:8px 10px;}
.food-tile .food-name{
    font-size:11px;font-weight:600;color:#111827;line-height:1.25;
    height:30px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
}
@media (min-width: 576px){.food-tile .food-name{font-size:12px;}}
.food-tile .food-price{font-size:12px;font-weight:700;color:#ff2d7a;margin-top:4px;}
@media (min-width: 576px){.food-tile .food-price{font-size:13px;margin-top:6px;}}
.food-tile .food-add{
    position:absolute;top:8px;right:8px;width:26px;height:26px;border-radius:50%;
    background:#ff2d7a;color:#fff;border:none;
    display:flex;align-items:center;justify-content:center;font-size:11px;
    box-shadow:0 4px 10px rgba(255,45,122,.4);transition:all .2s ease;
}
@media (min-width: 576px){.food-tile .food-add{width:28px;height:28px;font-size:12px;}}
.food-tile:hover .food-add{transform:scale(1.1);}
.food-tile .food-counter{
    position:absolute;top:8px;left:8px;background:#111827;color:#fff;
    font-size:10px;font-weight:700;padding:3px 8px;border-radius:999px;
    box-shadow:0 4px 10px rgba(0,0,0,.3);display:none;z-index:2;animation:popIn .25s ease;
}
@media (min-width: 576px){.food-tile .food-counter{font-size:11px;}}
@keyframes popIn{0%{transform:scale(0);}60%{transform:scale(1.2);}100%{transform:scale(1);}}
.receipt-card{
    background:#fff;border:1.5px solid #ffd6e5;border-radius:16px;
    overflow:hidden;box-shadow:0 12px 30px -12px rgba(255,45,122,.18);
}
.receipt-header{
    background:linear-gradient(135deg,#ff2d7a,#ff4b91);
    color:#fff;padding:16px 20px;
    display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;
}
@media (min-width: 768px){.receipt-header{padding:20px 26px;}}
.receipt-header h5{margin:0;font-weight:700;font-size:15px;}
@media (min-width: 576px){.receipt-header h5{font-size:16px;}}
.receipt-header small{opacity:.85;font-size:11px;}
.receipt-body{padding:16px 18px;}
@media (min-width: 768px){.receipt-body{padding:22px 26px;}}
.receipt-item{
    display:flex;justify-content:space-between;align-items:center;
    padding:10px 0;border-bottom:1px dashed #e5e7eb;gap:10px;flex-wrap:wrap;
}
.receipt-item:last-child{border-bottom:none;}
.receipt-item .ri-info{flex:1;min-width:140px;}
.receipt-item .ri-name{font-size:12px;font-weight:600;color:#111827;word-break:break-word;}
@media (min-width: 576px){.receipt-item .ri-name{font-size:13px;}}
.receipt-item .ri-meta{font-size:10px;color:#6b7280;margin-top:2px;}
@media (min-width: 576px){.receipt-item .ri-meta{font-size:11px;}}
.receipt-item .ri-total{font-size:12px;font-weight:700;color:#111827;min-width:75px;text-align:right;}
@media (min-width: 576px){.receipt-item .ri-total{font-size:13px;min-width:80px;}}
.receipt-item .ri-remove{background:transparent;border:none;color:#ef4444;cursor:pointer;padding:6px 8px;border-radius:8px;transition:background .2s;}
.receipt-item .ri-remove:hover{background:#fef2f2;}
.qty-control{
    display:flex;align-items:center;gap:2px;
    background:#f9fafb;border-radius:10px;padding:3px;border:1px solid #f3f4f6;flex-shrink:0;
}
.qty-btn{
    width:26px;height:26px;border:none;background:#fff;color:#374151;
    border-radius:8px;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    font-size:12px;font-weight:700;transition:all .15s;
    box-shadow:0 1px 3px rgba(0,0,0,.06);
}
@media (min-width: 576px){.qty-btn{width:28px;height:28px;font-size:13px;}}
.qty-btn:hover:not(:disabled){background:#ff2d7a;color:#fff;}
.qty-btn:active:not(:disabled){transform:scale(.94);}
.qty-btn:disabled{opacity:.35;cursor:not-allowed;}
.qty-value{min-width:28px;text-align:center;font-size:12px;font-weight:700;color:#111827;}
@media (min-width: 576px){.qty-value{min-width:32px;font-size:13px;}}
.receipt-items-scroll{max-height:260px;overflow-y:auto;padding-right:4px;}
.bill-panel{background:#111827;color:#f3f4f6;border-radius:14px;padding:16px;margin-top:14px;}
@media (min-width: 768px){.bill-panel{padding:18px;}}
.bill-row{display:flex;justify-content:space-between;font-size:12px;padding:4px 0;opacity:.9;}
@media (min-width: 576px){.bill-row{font-size:13px;}}
.bill-grand{
    display:flex;justify-content:space-between;font-size:20px;font-weight:700;color:#ff2d7a;
    border-top:1px dashed #374151;padding-top:12px;margin-top:10px;
}
@media (min-width: 576px){.bill-grand{font-size:22px;}}
.status-row{display:flex;flex-wrap:wrap;gap:8px;}
@media (min-width: 576px){.status-row{gap:10px;}}
.status-btn{
    display:inline-flex;align-items:center;gap:6px;
    padding:8px 14px;background:#f9fafb;border:1.5px solid #e5e7eb;
    border-radius:12px;font-size:12px;font-weight:600;color:#4b5563;
    cursor:pointer;transition:all .2s ease;user-select:none;
}
@media (min-width: 576px){.status-btn{padding:10px 20px;gap:8px;font-size:13px;}}
.status-btn i{font-size:11px;}
@media (min-width: 576px){.status-btn i{font-size:13px;}}
.status-btn:hover{border-color:#ffd6e5;background:#fff5f8;color:#ff2d7a;}
.status-btn.active{
    background:linear-gradient(135deg,#ff2d7a,#ff4b91);
    border-color:#ff2d7a;color:#fff;
    box-shadow:0 8px 20px -8px rgba(255,45,122,.5);
}
.status-btn.active i{color:#fff;}
.status-btn.cancelled-btn{border-color:#fecaca;color:#dc2626;}
.status-btn.cancelled-btn:hover{border-color:#ef4444;background:#fef2f2;color:#dc2626;}
.status-btn.cancelled-btn.active{
    background:linear-gradient(135deg,#dc2626,#ef4444);
    border-color:#dc2626;color:#fff;
    box-shadow:0 8px 20px -8px rgba(220,38,38,.5);
}
.status-btn.cancelled-btn.active i{color:#fff;}
.categories-wrap::-webkit-scrollbar,
.foods-wrap::-webkit-scrollbar,
.receipt-items-scroll::-webkit-scrollbar{width:6px;}
.categories-wrap::-webkit-scrollbar-track,
.foods-wrap::-webkit-scrollbar-track,
.receipt-items-scroll::-webkit-scrollbar-track{background:#f9fafb;border-radius:6px;}
.categories-wrap::-webkit-scrollbar-thumb,
.foods-wrap::-webkit-scrollbar-thumb,
.receipt-items-scroll::-webkit-scrollbar-thumb{background:#ffd6e5;border-radius:6px;}
.categories-wrap::-webkit-scrollbar-thumb:hover,
.foods-wrap::-webkit-scrollbar-thumb:hover,
.receipt-items-scroll::-webkit-scrollbar-thumb:hover{background:#ff2d7a;}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 mb-md-4">
        <div class="min-w-0">
            <h4 class="fw-bold mb-1 fs-5 fs-md-4">Edit Order #{{ $order->order_number }}</h4>
            <p class="text-muted small m-0">
                Table: <strong>{{ $order->table?->table_number ?? '—' }}</strong> •
                Waiter: <strong>{{ $order->waiter?->name ?? 'Unassigned' }}</strong>
            </p>
        </div>
        <a href="{{ route('pos.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    <div class="row g-2 g-md-3 mb-3">
        <div class="col-12">
            <div class="card-clean h-100">
                <div class="card-body">
                    <div class="section-title">
                        <i class="fa-solid fa-user-tie me-2" style="color:#ff2d7a;"></i>Assignment & Notes
                    </div>
                    <div class="row g-2 g-md-3">
                        <div class="col-12 col-sm-4">
                            <div class="field-group">
                                <label class="form-label">Waiter</label>
                                <select id="waiterSelect" class="form-select">
                                    <option value="">-- Select waiter --</option>
                                    @foreach($waiters as $w)
                                        <option value="{{ $w->id }}" @selected($order->waiter_id==$w->id)>{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="field-group">
                                <label class="form-label">Table</label>
                                <select id="tableSelect" class="form-select" disabled>
                                    @foreach($tables as $t)
                                        <option value="{{ $t->id }}" @selected($order->pos_table_id==$t->id)>
                                            {{ $t->table_number }}{{ $t->table_name ? ' - '.$t->table_name : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="field-group">
                                <label class="form-label">Customer</label>
                                <input type="text" id="customerName" class="form-control"
                                       value="{{ $order->customer_name }}" placeholder="Optional">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-group">
                                <label class="form-label">Kitchen Notes</label>
                                <input type="text" id="orderNotes" class="form-control"
                                       value="{{ $order->notes }}" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 g-md-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="card-clean" style="height:auto;min-height:440px;">
                <div class="card-body d-flex flex-column">
                    <div class="section-title">
                        <i class="fa-solid fa-layer-group me-2" style="color:#ff2d7a;"></i>Categories
                    </div>
                    <div class="categories-wrap" id="categoriesWrap" style="flex:1;">
                        @foreach($categories as $c)
                            <div class="cat-tile" data-cat-id="{{ $c->id }}" data-cat-name="{{ $c->name }}">
                                <div class="cat-img">
                                    @if($c->image)
                                        <img src="{{ asset($c->image) }}" alt="{{ $c->name }}"
                                             onerror="this.parentElement.innerHTML='<i class=\'fa-solid fa-utensils\'></i>';">
                                    @else
                                        <i class="fa-solid fa-utensils"></i>
                                    @endif
                                </div>
                                <div class="cat-name">{{ $c->name }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card-clean" style="height:auto;min-height:440px;">
                <div class="card-body d-flex flex-column">
                    <div class="section-title" id="foodsTitle">
                        <i class="fa-solid fa-utensils me-2" style="color:#ff2d7a;"></i>Foods
                    </div>
                    <div id="foodsContainer" style="flex:1;overflow:hidden;">
                        <div class="foods-empty">
                            <i class="fa-solid fa-hand-pointer"></i>
                            <div style="font-size:13px;">Select a category to view foods</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="receipt-card mb-3" id="receiptCard">
        <div class="receipt-header">
            <div>
                <h5><i class="fa-solid fa-receipt me-2"></i>Order Confirmation</h5>
                <small>#{{ $order->order_number }} • {{ $order->table?->table_number ?? '—' }}</small>
            </div>
            <div style="text-align:right;">
                <small>Waiter</small>
                <div style="font-weight:700;">{{ $order->waiter?->name ?? 'Unassigned' }}</div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="row g-3 g-md-4">
                <div class="col-12 col-lg-7">
                    <div class="section-title" style="padding-left:0;">
                        <i class="fa-solid fa-bowl-food me-2" style="color:#ff2d7a;"></i>Items
                        <span id="itemsCount" class="ms-2" style="color:#9ca3af;font-weight:500;text-transform:none;letter-spacing:0;">(0)</span>
                    </div>
                    <div class="receipt-items-scroll" id="itemsList">
                        <div class="text-center py-5 text-muted small">
                            <i class="fa-solid fa-bowl-food fa-2x mb-2 d-block" style="color:#ffd6e5;"></i>
                            No items yet.
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="bill-panel">
                        <h6 class="text-uppercase small mb-3" style="opacity:.7;letter-spacing:.06em;">Bill Summary</h6>
                        <div class="bill-row"><span>Subtotal</span><span id="billSubtotal">{{ number_format($order->subtotal, 2) }}</span></div>

                        <div class="row g-2 mt-2 mb-2">
                            <div class="col-6">
                                <div class="field-group">
                                    <label class="form-label" style="font-size:11px;color:#9ca3af;">Tax</label>
                                    <input type="number" id="taxInput" class="form-control form-control-sm" style="background:#1f2937;color:#fff;border-color:#374151;min-height:32px;font-size:12px;"
                                           value="{{ $order->tax }}" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="field-group">
                                    <label class="form-label" style="font-size:11px;color:#9ca3af;">Discount</label>
                                    <input type="number" id="discountInput" class="form-control form-control-sm" style="background:#1f2937;color:#fff;border-color:#374151;min-height:32px;font-size:12px;"
                                           value="{{ $order->discount }}" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="bill-row"><span>Tax Amount</span><span id="billTax">{{ number_format($order->tax_amount ?? $order->tax, 2) }}</span></div>
                        <div class="bill-row"><span>Discount Amount</span><span id="billDiscount">{{ number_format($order->discount_amount ?? $order->discount, 2) }}</span></div>

                        <div class="bill-grand">
                            <span>Total</span>
                            <span id="billGrandTotal">{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-clean mb-3">
        <div class="card-body">
            <div class="section-title">
                <i class="fa-solid fa-flag me-2" style="color:#ff2d7a;"></i>Order Status
            </div>
            <div class="status-row" id="statusRow">
                <button type="button" class="status-btn" data-status="pending">
                    <i class="fa-solid fa-hourglass-half"></i>Pending
                </button>
                <button type="button" class="status-btn" data-status="preparing">
                    <i class="fa-solid fa-fire-burner"></i>Preparing
                </button>
                <button type="button" class="status-btn" data-status="served">
                    <i class="fa-solid fa-bell-concierge"></i>Served
                </button>
                <button type="button" class="status-btn cancelled-btn" data-status="cancelled">
                    <i class="fa-solid fa-ban"></i>Cancelled
                </button>
            </div>
        </div>
    </div>

    <button id="saveBtn" class="btn btn-pink w-100 py-2" style="font-size:14px;">
        <i class="fa-solid fa-floppy-disk me-2"></i>Save &amp; Update
    </button>

</div>
@endsection

@section('scripts')
<script>
window.POS_BASE  = "{{ url('pos/orders') }}";
window.POS_FOODS = "{{ url('pos/ajax/foods') }}";
window.POS_INDEX = "{{ route('pos.orders.index') }}";
window.ORDER_ID  = {{ $order->id }};
window.ORDER_STATUS = "{{ $order->status }}";
</script>

<script>
(function(){
    'use strict';

    const csrf     = document.querySelector('meta[name="csrf-token"]')?.content;
    const BASE     = window.POS_BASE;
    const FOODS    = window.POS_FOODS;
    const INDEX    = window.POS_INDEX;
    const ORDER_ID = window.ORDER_ID;
    const $  = s => document.querySelector(s);
    const $$ = s => Array.from(document.querySelectorAll(s));
    let foodCache = {};
    let currentItems = [];

    const money = n => 'Rs ' + (Number(n)||0).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
    const esc   = s => !s ? '' : String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    function getSelectedStatus(){
        return document.querySelector('.status-btn.active')?.dataset.status || 'pending';
    }
    function setSelectedStatus(status){
        $$('.status-btn').forEach(b => b.classList.toggle('active', b.dataset.status === status));
    }

    function toast(msg, type='success'){
        const c = {success:'#10b981', error:'#ef4444'}[type] || '#10b981';
        const el = document.createElement('div');
        el.style.cssText = `position:fixed;top:20px;right:20px;z-index:99999;background:#fff;padding:14px 18px;border-radius:12px;box-shadow:0 12px 28px rgba(0,0,0,.15);border-left:4px solid ${c};font-size:14px;color:#111827;font-weight:500;transform:translateX(120%);transition:all .35s ease;max-width:calc(100vw - 40px);`;
        el.textContent = msg;
        document.body.appendChild(el);
        requestAnimationFrame(() => el.style.transform = 'translateX(0)');
        setTimeout(() => { el.style.transform = 'translateX(120%)'; setTimeout(() => el.remove(), 400); }, 2600);
    }

    async function ajax(url, opt={}){
        const r = await fetch(url, {
            ...opt,
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...(opt.headers || {})
            }
        });
        return r.json();
    }

    function updateTileCounters(items){
        const counts = {};
        items.forEach(it => { if(it.product_id) counts[it.product_id] = (counts[it.product_id] || 0) + it.quantity; });
        $$('.food-tile').forEach(tile => {
            const pid = tile.dataset.productId;
            const badge = tile.querySelector('.food-counter');
            if(!badge) return;
            const c = counts[pid] || 0;
            if(c > 0){ badge.textContent = 'x' + c; badge.style.display = 'block'; }
            else { badge.style.display = 'none'; }
        });
    }

    function buildItemRow(it){
        const row = document.createElement('div');
        row.className = 'receipt-item';
        row.dataset.itemId = it.id;
        row.innerHTML = `
            <div class="ri-info">
                <div class="ri-name">${esc(it.product_name)}</div>
                <div class="ri-meta">${it.category ? esc(it.category)+' • ' : ''}${money(it.unit_price)}</div>
            </div>
            <div class="qty-control">
                <button class="qty-btn qty-minus" title="Decrease"><i class="fa-solid fa-minus"></i></button>
                <span class="qty-value">${it.quantity}</span>
                <button class="qty-btn qty-plus" title="Increase"><i class="fa-solid fa-plus"></i></button>
            </div>
            <div class="ri-total">${money(it.total_price)}</div>
            <button class="ri-remove remove-item" title="Remove"><i class="fa-solid fa-trash"></i></button>`;

        const minusBtn = row.querySelector('.qty-minus');
        const plusBtn  = row.querySelector('.qty-plus');
        if(it.quantity <= 1) minusBtn.disabled = true;

        minusBtn.addEventListener('click', () => {
            const newQty = it.quantity - 1;
            if(newQty < 1) return;
            updateQty(it.id, newQty);
        });
        plusBtn.addEventListener('click', () => updateQty(it.id, it.quantity + 1));
        row.querySelector('.remove-item').addEventListener('click', () => removeItem(it.id));
        return row;
    }

    function renderItems(items){
        currentItems = items || [];
        const list = $('#itemsList');
        list.innerHTML = '';
        if(!items.length){
            list.innerHTML = '<div class="text-center py-5 text-muted small"><i class="fa-solid fa-bowl-food fa-2x mb-2 d-block" style="color:#ffd6e5;"></i>No items yet.</div>';
        } else {
            items.forEach(it => list.appendChild(buildItemRow(it)));
        }
        $('#itemsCount').textContent = `(${items.length})`;
        updateTileCounters(currentItems);
    }

    function updateBill(o){
        $('#billSubtotal').textContent   = money(o.subtotal);
        $('#billTax').textContent        = money(o.tax_amount ?? o.tax);
        $('#billDiscount').textContent   = money(o.discount_amount ?? o.discount);
        $('#billGrandTotal').textContent = money(o.total_amount);
    }

    function selectCategory(catId, catName){
        $$('.cat-tile').forEach(t => t.classList.toggle('active', String(t.dataset.catId) === String(catId)));
        $('#foodsTitle').innerHTML = `<i class="fa-solid fa-utensils me-2" style="color:#ff2d7a;"></i>${esc(catName)} — Foods`;
        loadFoods(catId);
    }

    async function loadFoods(catId){
        const container = $('#foodsContainer');
        if(foodCache[catId]){ renderFoods(foodCache[catId]); return; }
        container.innerHTML = '<div class="foods-empty"><i class="fa-solid fa-spinner fa-spin"></i><div style="font-size:13px;">Loading...</div></div>';
        const data = await ajax(`${FOODS}/${catId}`);
        if(data.success){
            foodCache[catId] = data.foods;
            renderFoods(data.foods);
        } else {
            container.innerHTML = '<div class="foods-empty"><i class="fa-solid fa-exclamation-triangle"></i><div style="font-size:13px;">Failed to load foods</div></div>';
        }
    }

    function renderFoods(foods){
        const container = $('#foodsContainer');
        if(!foods.length){
            container.innerHTML = '<div class="foods-empty"><i class="fa-solid fa-bowl-food"></i><div style="font-size:13px;">No foods in this category</div></div>';
            return;
        }
        container.innerHTML = `<div class="foods-wrap" id="foodsWrap"></div>`;
        const wrap = $('#foodsWrap');
        foods.forEach(f => {
            const tile = document.createElement('div');
            tile.className = 'food-tile';
            tile.dataset.productId = f.id;
            const imgHtml = f.image
                ? `<img class="food-img" src="${f.image}" alt="${esc(f.name)}" onerror="this.outerHTML='<div class=\\'food-img-fallback\\'><i class=\\'fa-solid fa-utensils\\'></i></div>';" />`
                : `<div class="food-img-fallback"><i class="fa-solid fa-utensils"></i></div>`;
            tile.innerHTML = `
                <div class="food-counter"></div>
                ${imgHtml}
                <button class="food-add" title="Add"><i class="fa-solid fa-plus"></i></button>
                <div class="food-info">
                    <div class="food-name">${esc(f.name)}</div>
                    <div class="food-price">${money(f.price)}</div>
                </div>`;
            tile.addEventListener('click', () => quickAdd(f));
            wrap.appendChild(tile);
        });
        updateTileCounters(currentItems);
    }

    async function quickAdd(food){
        const tile = document.querySelector(`.food-tile[data-product-id="${food.id}"]`);
        if(tile){
            const badge = tile.querySelector('.food-counter');
            const current = parseInt((badge.textContent || 'x0').replace('x','')) || 0;
            badge.textContent = 'x' + (current + 1);
            badge.style.display = 'block';
            badge.style.animation = 'none';
            void badge.offsetWidth;
            badge.style.animation = 'popIn .25s ease';
        }
        const data = await ajax(`${BASE}/${ORDER_ID}/items`, {
            method: 'POST',
            body: JSON.stringify({ product_id: food.id, quantity: 1 })
        });
        if(data.success){
            renderItems(data.order.items || []);
            updateBill(data.order);
        } else {
            toast(data.message || 'Failed', 'error');
            updateTileCounters(currentItems);
        }
    }

    async function updateQty(itemId, qty){
        const data = await ajax(`${BASE}/${ORDER_ID}/items/${itemId}`, {
            method: 'PUT', body: JSON.stringify({ quantity: qty })
        });
        if(data.success){ renderItems(data.order.items || []); updateBill(data.order); }
    }

    async function removeItem(itemId){
        const data = await ajax(`${BASE}/${ORDER_ID}/items/${itemId}`, { method: 'DELETE' });
        if(data.success){ renderItems(data.order.items || []); updateBill(data.order); toast('Removed'); }
    }

    async function applyCharges(){
        const data = await ajax(`${BASE}/${ORDER_ID}/charges`, {
            method: 'POST',
            body: JSON.stringify({
                tax: parseFloat($('#taxInput').value) || 0,
                discount: parseFloat($('#discountInput').value) || 0,
            })
        });
        if(data.success) updateBill(data.order);
    }

    /* ================================================================
       SAVE & UPDATE
       - Saves the order
       - Redirects back to orders list
       (Complete & Print has been removed from this page)
    ================================================================ */
    async function saveAll(){
        const status = getSelectedStatus();
        const btn = $('#saveBtn');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        const data = await ajax(`${BASE}/${ORDER_ID}`, {
            method: 'PUT',
            body: JSON.stringify({
                status: status,
                waiter_id: $('#waiterSelect').value || null,
                customer_name: $('#customerName').value || null,
                notes: $('#orderNotes').value || null,
            })
        });

        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if(!data.success){
            return toast('Failed to save', 'error');
        }

        toast('Saved');
        setTimeout(() => location.href = INDEX, 700);
    }

    document.addEventListener('DOMContentLoaded', () => {
        setSelectedStatus(window.ORDER_STATUS || 'pending');

        $$('.status-btn').forEach(btn => {
            btn.addEventListener('click', () => setSelectedStatus(btn.dataset.status));
        });

        $$('.cat-tile').forEach(tile => {
            tile.addEventListener('click', () => selectCategory(tile.dataset.catId, tile.dataset.catName));
        });

        // Load initial items
        currentItems = [];
        (async () => {
            try {
                const res = await fetch(`${BASE}/${ORDER_ID}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                const data = await res.json();
                if(data.success){ renderItems(data.order.items || []); updateBill(data.order); }
            } catch(e) {}
        })();

        $('#taxInput')?.addEventListener('change', applyCharges);
        $('#discountInput')?.addEventListener('change', applyCharges);
        $('#saveBtn')?.addEventListener('click', saveAll);
    });
})();
</script>
@endsection
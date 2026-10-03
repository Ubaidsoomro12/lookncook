@extends('pos.layouts.pos_master')

@section('title', 'Orders')

@section('content')
<style>
.pos-order-card{
    background:#fff;border:1.5px solid #ffd6e5;border-radius:16px;padding:16px;
    cursor:pointer;position:relative;overflow:hidden;height:100%;
    box-shadow:0 2px 8px rgba(255,45,122,.05);
    transition:transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s ease, border-color .35s ease, background .35s ease;
}
.pos-order-card:hover{
    transform:translateY(-4px);border-color:#ff2d7a;
    background:linear-gradient(135deg,#ffffff 0%,#fff5f8 100%);
    box-shadow:0 22px 45px -12px rgba(255,45,122,.35), 0 0 0 4px rgba(255,45,122,.06);
}
.oc-status-dot{width:8px;height:8px;border-radius:50%;display:inline-block;flex-shrink:0;}
.oc-status-dot.pending{background:#f59e0b;} .oc-status-dot.preparing{background:#3b82f6;} .oc-status-dot.served{background:#10b981;}
.oc-number{font-weight:700;color:#111827;font-size:15px;word-break:break-all;}
.oc-table{font-size:12px;color:#6b7280;}
.oc-total{font-weight:700;color:#ff2d7a;font-size:18px;}
#orderModal .modal-dialog{
    transform:scale(.85) translateY(30px);opacity:0;
    transition:transform .35s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
}
#orderModal.show .modal-dialog{transform:scale(1) translateY(0);opacity:1;}
#orderModal .modal-content{border:none;border-radius:20px;overflow:hidden;box-shadow:0 30px 60px -15px rgba(0,0,0,.35);}
#orderModal .modal-header{background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;padding:16px 20px;}
#orderModal .modal-header .btn-close{filter:invert(1) brightness(2);}
#orderModal .modal-body{padding:16px 18px;background:#f9fafb;max-height:75vh;overflow-y:auto;}
@media (min-width: 768px){
    #orderModal .modal-header{padding:18px 24px;}
    #orderModal .modal-body{padding:20px 24px;}
}
#orderModal .card-body{padding:18px 20px;}
.sec-title{
    font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;
    color:#6b7280;margin-bottom:12px;padding-left:10px;
    display:flex;align-items:center;min-height:18px;
}
.field-group{display:flex;flex-direction:column;gap:5px;}
.field-group .form-label{font-size:12px;font-weight:600;color:#374151;margin-bottom:0;}
.field-group .form-select,
.field-group .form-control{border-radius:10px;border:1.5px solid #e5e7eb;font-size:13px;min-height:42px;min-width:0;width:100%;}
.field-group .form-select:focus,
.field-group .form-control:focus{border-color:#ff2d7a;box-shadow:0 0 0 3px rgba(255,45,122,.12);}
.field-group .form-select:disabled{background:#f9fafb;color:#6b7280;}
.categories-wrap{
    display:grid;grid-template-columns:repeat(auto-fill,minmax(78px,1fr));
    gap:8px;max-height:320px;overflow-y:auto;padding:4px 6px 4px 4px;
}
@media (min-width: 576px){
    .categories-wrap{grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:10px;}
}
.cat-tile{
    display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
    gap:5px;padding:8px 5px;min-height:88px;
    border:2px solid #f3f4f6;border-radius:12px;background:#fafafa;cursor:pointer;
    transition:all .25s cubic-bezier(.4,0,.2,1);text-align:center;box-sizing:border-box;
}
.cat-tile:hover{border-color:#ffd6e5;background:#fff5f8;transform:translateY(-2px);}
.cat-tile.active{border-color:#ff2d7a;background:#fff5f8;box-shadow:0 6px 16px -6px rgba(255,45,122,.4);}
.cat-tile .cat-img{
    width:42px;height:42px;border-radius:10px;overflow:hidden;
    background:#e5e7eb;display:flex;align-items:center;justify-content:center;
    color:#9ca3af;font-size:15px;flex-shrink:0;
}
@media (min-width: 576px){
    .cat-tile .cat-img{width:48px;height:48px;font-size:16px;}
}
.cat-tile .cat-img img{width:100%;height:100%;object-fit:cover;display:block;}
.cat-tile .cat-name{
    font-size:10px;font-weight:600;color:#111827;line-height:1.2;
    text-align:center;width:100%;height:24px;overflow:hidden;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;word-break:break-word;
}
.foods-wrap{
    display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));
    gap:8px;max-height:320px;overflow-y:auto;padding:4px 6px 4px 4px;
}
@media (min-width: 576px){
    .foods-wrap{grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;}
}
.foods-empty{
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    height:320px;color:#9ca3af;text-align:center;padding:20px;
}
.foods-empty i{font-size:34px;color:#ffd6e5;margin-bottom:10px;}
.food-tile{
    position:relative;background:#fff;border:1.5px solid #e5e7eb;border-radius:12px;
    overflow:hidden;cursor:pointer;user-select:none;
    transition:all .25s cubic-bezier(.4,0,.2,1);
}
.food-tile:hover{border-color:#ff2d7a;transform:translateY(-3px);box-shadow:0 12px 24px -10px rgba(255,45,122,.4);}
.food-tile:active{transform:translateY(-1px) scale(.98);}
.food-tile .food-img{width:100%;height:72px;object-fit:cover;background:#f3f4f6;display:block;}
@media (min-width: 576px){
    .food-tile .food-img{height:80px;}
}
.food-tile .food-img-fallback{
    width:100%;height:72px;background:#f3f4f6;
    display:flex;align-items:center;justify-content:center;color:#d1d5db;font-size:24px;
}
@media (min-width: 576px){
    .food-tile .food-img-fallback{height:80px;font-size:26px;}
}
.food-tile .food-info{padding:7px 9px;}
.food-tile .food-name{
    font-size:11px;font-weight:600;color:#111827;line-height:1.25;
    height:28px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
}
.food-tile .food-price{font-size:12px;font-weight:700;color:#ff2d7a;margin-top:4px;}
.food-tile .food-add{
    position:absolute;top:6px;right:6px;width:24px;height:24px;border-radius:50%;
    background:#ff2d7a;color:#fff;border:none;
    display:flex;align-items:center;justify-content:center;font-size:10px;
    box-shadow:0 4px 10px rgba(255,45,122,.4);transition:all .2s ease;
}
.food-tile:hover .food-add{transform:scale(1.1);}
.food-tile .food-counter{
    position:absolute;top:6px;left:6px;background:#111827;color:#fff;
    font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px;
    box-shadow:0 4px 10px rgba(0,0,0,.3);display:none;z-index:2;animation:popIn .25s ease;
}
@keyframes popIn{0%{transform:scale(0);}60%{transform:scale(1.2);}100%{transform:scale(1);}}
.receipt-items-scroll{max-height:260px;overflow-y:auto;padding-right:4px;}
.receipt-item{
    display:flex;justify-content:space-between;align-items:center;
    padding:10px 0;border-bottom:1px dashed #e5e7eb;gap:8px;flex-wrap:wrap;
}
.receipt-item:last-child{border-bottom:none;}
.receipt-item .ri-info{flex:1;min-width:120px;}
.receipt-item .ri-name{font-size:12px;font-weight:600;color:#111827;word-break:break-word;}
.receipt-item .ri-meta{font-size:10px;color:#6b7280;margin-top:2px;}
.receipt-item .ri-total{font-size:12px;font-weight:700;color:#111827;min-width:65px;text-align:right;}
.receipt-item .ri-remove{background:transparent;border:none;color:#ef4444;cursor:pointer;padding:5px 7px;border-radius:6px;transition:background .2s;}
.receipt-item .ri-remove:hover{background:#fef2f2;}
.qty-control{display:flex;align-items:center;gap:2px;background:#f9fafb;border-radius:8px;padding:2px;border:1px solid #f3f4f6;flex-shrink:0;}
.qty-btn{
    width:24px;height:24px;border:none;background:#fff;color:#374151;
    border-radius:6px;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:700;transition:all .15s;
    box-shadow:0 1px 3px rgba(0,0,0,.06);
}
.qty-btn:hover:not(:disabled){background:#ff2d7a;color:#fff;}
.qty-btn:active:not(:disabled){transform:scale(.94);}
.qty-btn:disabled{opacity:.35;cursor:not-allowed;}
.qty-value{min-width:26px;text-align:center;font-size:12px;font-weight:700;color:#111827;}
.bill-panel{background:#111827;color:#f3f4f6;border-radius:14px;padding:16px;}
.bill-row{display:flex;justify-content:space-between;font-size:12px;padding:3px 0;opacity:.9;}
.bill-grand{
    display:flex;justify-content:space-between;font-size:20px;font-weight:700;color:#ff2d7a;
    border-top:1px dashed #374151;padding-top:10px;margin-top:8px;
}
.status-row{display:flex;flex-wrap:wrap;gap:8px;}
.status-btn{
    display:inline-flex;align-items:center;gap:6px;
    padding:8px 14px;background:#f9fafb;border:1.5px solid #e5e7eb;
    border-radius:10px;font-size:12px;font-weight:600;color:#4b5563;
    cursor:pointer;transition:all .2s ease;user-select:none;
}
.status-btn i{font-size:11px;}
.status-btn:hover{border-color:#ffd6e5;background:#fff5f8;color:#ff2d7a;}
.status-btn.active{
    background:linear-gradient(135deg,#ff2d7a,#ff4b91);
    border-color:#ff2d7a;color:#fff;
    box-shadow:0 6px 14px -6px rgba(255,45,122,.4);
}
.status-btn.active i{color:#fff;}

.status-btn.cancelled-btn{border-color:#fecaca;color:#dc2626;}
.status-btn.cancelled-btn:hover{border-color:#ef4444;background:#fef2f2;color:#dc2626;}
.status-btn.cancelled-btn.active{
    background:linear-gradient(135deg,#dc2626,#ef4444);
    border-color:#dc2626;color:#fff;
    box-shadow:0 6px 14px -6px rgba(220,38,38,.4);
}
.status-btn.cancelled-btn.active i{color:#fff;}

.status-btn.complete-print-btn{border-color:#ffd6e5;color:#ff2d7a;background:#fff5f8;}
.status-btn.complete-print-btn:hover{border-color:#ff2d7a;background:#ffeaf2;color:#ff2d7a;}
.status-btn.complete-print-btn.active{
    background:linear-gradient(135deg,#ff2d7a,#ff4b91);
    border-color:#ff2d7a;color:#fff;
    box-shadow:0 6px 14px -6px rgba(255,45,122,.4);
}
.status-btn.complete-print-btn.active i{color:#fff;}

.status-btn.print-btn{
    border-color:#111827;color:#111827;background:#fff;
}
.status-btn.print-btn:hover{
    background:#111827;color:#fff;border-color:#111827;
}
.status-btn.print-btn i{font-size:12px;}

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

/* ✅ GLOBAL FIX — prevent horizontal overflow on tiny screens */
html, body{
    overflow-x: hidden;
    max-width: 100vw;
}
.container-fluid{
    max-width: 100%;
    overflow-x: hidden;
}

/* ✅ FIX FOR 320px — stat cards + status buttons */
@media (max-width: 400px){
    .container-fluid{
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    /* --- Stat cards (Pending / Preparing / Served / Today) --- */
    .row.g-2.g-md-3 > [class*="col-"]{
        padding-left: 5px;
        padding-right: 5px;
    }
    .row.g-2{
        --bs-gutter-x: 10px;
        --bs-gutter-y: 10px;
    }
    .card .card-body.d-flex.align-items-center{
        padding: 10px !important;
        gap: 8px !important;
        min-width: 0;
    }
    .card .card-body.d-flex.align-items-center > div:first-child{
        width: 34px !important;
        height: 34px !important;
        font-size: 13px;
        border-radius: 9px !important;
    }
    .card .card-body.d-flex.align-items-center .text-muted.small{
        font-size: 11px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .card .card-body.d-flex.align-items-center .fw-bold.fs-5{
        font-size: 15px !important;
        line-height: 1.2;
    }
    .card .card-body.d-flex.align-items-center > div:last-child{
        min-width: 0;
        overflow: hidden;
    }

    /* --- Header "New Order" button --- */
    .d-flex.flex-wrap.align-items-center.justify-content-between.gap-3.mb-3 > a.btn{
        width: 100%;
        text-align: center;
        justify-content: center;
    }

    /* --- Status buttons inside modal --- */
    .status-row{
        flex-wrap: wrap;
        gap: 6px;
    }
    .status-btn{
        flex: 1 1 auto;
        min-width: 0;
        padding: 7px 10px;
        font-size: 11px;
        gap: 4px;
        justify-content: center;
    }
    .status-btn i{
        font-size: 10px;
    }
    .card-body .d-flex.justify-content-between.align-items-center.flex-wrap.gap-2 > .d-flex.gap-2{
        flex-wrap: wrap;
        width: 100%;
        min-width: 0;
    }
    .card-body .d-flex.justify-content-between.align-items-center.flex-wrap.gap-2 > .d-flex.gap-2 .status-btn{
        flex: 1 1 auto;
        min-width: 0;
        justify-content: center;
        padding: 7px 8px;
        font-size: 11px;
    }
    .bill-panel .row.g-2.mt-2.mb-2 .col-6{
        padding-left: 4px;
        padding-right: 4px;
    }
    .bill-panel .form-control-sm{
        font-size: 11px;
        padding: 4px 6px;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 mb-md-4">
        <div>
            <h4 class="fw-bold mb-1 fs-5 fs-md-4">Order Management</h4>
            <p class="text-muted small m-0">Live orders from all tables</p>
        </div>
        <a href="{{ route('pos.orders.create') }}" class="btn px-3 px-md-4"
           style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;">
            <i class="fa-solid fa-plus me-2"></i>New Order
        </a>
    </div>

    @php
        $statCards = [
            ['label'=>'Pending','value'=>$stats['pending'],'icon'=>'fa-hourglass-half','color'=>'#f59e0b'],
            ['label'=>'Preparing','value'=>$stats['preparing'],'icon'=>'fa-fire-burner','color'=>'#3b82f6'],
            ['label'=>'Served','value'=>$stats['served'],'icon'=>'fa-bell-concierge','color'=>'#10b981'],
            ['label'=>'Today','value'=>$stats['today'],'icon'=>'fa-calendar-day','color'=>'#ff2d7a'],
        ];
    @endphp
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        @foreach($statCards as $s)
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                    <div style="width:42px;height:42px;border-radius:12px;background:{{ $s['color'] }}1a;color:{{ $s['color'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid {{ $s['icon'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-muted small">{{ $s['label'] }}</div>
                        <div class="fw-bold fs-5">{{ $s['value'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 border" style="border-style:dashed !important;border-color:#ffd6e5 !important;">
            <i class="fa-solid fa-receipt fa-3x mb-3" style="color:#ffd6e5;"></i>
            <h5 class="text-muted mb-1">No active orders</h5>
            <p class="text-muted small mb-0">Click <strong>"New Order"</strong> to create one.</p>
        </div>
    @else
        <div class="row g-2 g-md-3">
            @foreach($orders as $order)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="pos-order-card" data-order-id="{{ $order->id }}" role="button">
                    <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                        <div class="min-w-0">
                            <div class="oc-number">#{{ $order->order_number }}</div>
                            <div class="oc-table">
                                <i class="fa-solid fa-chair me-1"></i>{{ $order->table?->table_number ?? '—' }}
                                @if($order->table?->table_name) • {{ $order->table->table_name }} @endif
                            </div>
                        </div>
                        <span class="oc-status-dot {{ $order->status }}"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small text-muted mb-2 gap-2">
                        <div class="text-truncate"><i class="fa-solid fa-user-tie me-1"></i>{{ $order->waiter?->name ?? 'Unassigned' }}</div>
                        <div class="flex-shrink-0"><i class="fa-regular fa-clock me-1"></i>{{ $order->created_at->diffForHumans(null, true) }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end pt-2" style="border-top:1px dashed #ffd6e5;">
                        <span class="badge" style="background:#fff5f8;color:#ff2d7a;">{{ $order->items->count() }} items</span>
                        <div class="oc-total">{{ number_format($order->total_amount, 2) }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ============ ORDER MODAL ============ --}}
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-semibold fs-6" id="orderModalTitle"><i class="fa-solid fa-receipt me-2"></i>Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-2 g-md-3 mb-3">
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <div class="sec-title"><i class="fa-solid fa-user-tie me-2" style="color:#ff2d7a;"></i>Assignment & Notes</div>
                <div class="row g-2 g-md-3">
                  <div class="col-12 col-sm-4">
                    <div class="field-group">
                      <label class="form-label">Waiter</label>
                      <select id="waiterSelect" class="form-select">
                        <option value="">-- Select waiter --</option>
                        @foreach($waiters as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="field-group">
                      <label class="form-label">Table</label>
                      <select id="tableSelect" class="form-select" disabled>
                        <option value="">--</option>
                        @foreach($tables as $t)
                          <option value="{{ $t->id }}">{{ $t->table_number }}{{ $t->table_name ? ' - '.$t->table_name : '' }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-sm-4">
                    <div class="field-group">
                      <label class="form-label">Customer</label>
                      <input type="text" id="customerName" class="form-control" placeholder="Optional">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="field-group">
                      <label class="form-label">Kitchen Notes</label>
                      <input type="text" id="orderNotes" class="form-control" placeholder="Optional">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-2 g-md-3 mb-3">
          <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm" style="height:auto;min-height:340px;">
              <div class="card-body d-flex flex-column">
                <div class="sec-title"><i class="fa-solid fa-layer-group me-2" style="color:#ff2d7a;"></i>Categories</div>
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
            <div class="card border-0 shadow-sm" style="height:auto;min-height:340px;">
              <div class="card-body d-flex flex-column">
                <div class="sec-title" id="foodsTitle"><i class="fa-solid fa-utensils me-2" style="color:#ff2d7a;"></i>Foods</div>
                <div id="foodsContainer" style="flex:1;overflow:hidden;">
                  <div class="foods-empty">
                    <i class="fa-solid fa-hand-pointer"></i>
                    <div style="font-size:12px;">Select a category to view foods</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-2 g-md-3 mb-3">
          <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body">
                <div class="sec-title" style="padding-left:0;">
                    <i class="fa-solid fa-bowl-food me-2" style="color:#ff2d7a;"></i>Order Items
                    <span id="itemsCount" style="color:#9ca3af;font-weight:500;text-transform:none;letter-spacing:0;">(0)</span>
                </div>
                <div class="receipt-items-scroll" id="itemsList">
                  <div class="text-center py-4 text-muted small">No items yet.</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-5">
            <div class="bill-panel">
              <div class="sec-title" style="color:#9ca3af;padding-left:0;">Bill Summary</div>
              <div class="bill-row"><span>Subtotal</span><span id="billSubtotal">Rs 0.00</span></div>

              <div class="row g-2 mt-2 mb-2">
                  <div class="col-6">
                      <div class="field-group">
                          <label class="form-label" style="font-size:11px;color:#9ca3af;">Tax</label>
                          <input type="number" id="taxInput" class="form-control form-control-sm" style="background:#1f2937;color:#fff;border-color:#374151;min-height:32px;font-size:12px;" value="0" min="0" step="0.01">
                      </div>
                  </div>
                  <div class="col-6">
                      <div class="field-group">
                          <label class="form-label" style="font-size:11px;color:#9ca3af;">Discount</label>
                          <input type="number" id="discountInput" class="form-control form-control-sm" style="background:#1f2937;color:#fff;border-color:#374151;min-height:32px;font-size:12px;" value="0" min="0" step="0.01">
                      </div>
                  </div>
              </div>
              <div class="bill-row"><span>Tax Amount</span><span id="billTax">Rs 0.00</span></div>
              <div class="bill-row"><span>Discount Amount</span><span id="billDiscount">Rs 0.00</span></div>

              <div class="bill-grand">
                <span>Total</span><span id="billGrandTotal">Rs 0.00</span>
              </div>
            </div>
            <a id="editPageLink" href="#" class="btn w-100 mt-3" style="background:#111827;color:#fff;border:none;border-radius:10px;">
              <i class="fa-solid fa-pen me-2"></i>Open Full Edit Page
            </a>
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="sec-title"><i class="fa-solid fa-flag me-2" style="color:#ff2d7a;"></i>Order Status</div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
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
                <div class="d-flex gap-2">
                    <button type="button" class="status-btn complete-print-btn" data-status="completed">
                        <i class="fa-solid fa-check"></i>Complete &amp; Save
                    </button>
                    <button type="button" class="status-btn print-btn" id="printBtn">
                        <i class="fa-solid fa-print"></i>Print
                    </button>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-white border-top flex-wrap gap-2">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>Close</button>
        <button type="button" class="btn d-none" id="saveStatusBtn"
                style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;border-radius:10px;">
          <i class="fa-solid fa-floppy-disk me-1"></i>Save &amp; Update
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ⭐ RECEIPT MODAL — same page, no reload --}}
@include('pos.partials.receipt_modal')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
window.POS_BASE  = "{{ url('pos/orders') }}";
window.POS_FOODS = "{{ url('pos/ajax/foods') }}";
</script>

<script>
(function(){
    'use strict';

    const csrf  = document.querySelector('meta[name="csrf-token"]')?.content;
    const BASE  = window.POS_BASE;
    const FOODS = window.POS_FOODS;
    const $  = s => document.querySelector(s);
    const $$ = s => Array.from(document.querySelectorAll(s));
    let modal = null;
    let currentId = null;
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

    function ensureModal(){ if(!modal) modal = new bootstrap.Modal(document.getElementById('orderModal'), {backdrop:'static'}); }

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

    function resetModal(){
        currentItems = [];
        $('#itemsList').innerHTML = '<div class="text-center py-4 text-muted small">No items yet.</div>';
        $('#itemsCount').textContent = '(0)';
        ['billSubtotal','billTax','billDiscount','billGrandTotal'].forEach(id => $('#'+id).textContent = money(0));
        $('#customerName').value = '';
        $('#orderNotes').value = '';
        $('#taxInput').value = 0;
        $('#discountInput').value = 0;
        setSelectedStatus('pending');
        $$('.cat-tile').forEach(t => t.classList.remove('active'));
        $('#foodsTitle').innerHTML = '<i class="fa-solid fa-utensils me-2" style="color:#ff2d7a;"></i>Foods';
        $('#foodsContainer').innerHTML = '<div class="foods-empty"><i class="fa-solid fa-hand-pointer"></i><div style="font-size:12px;">Select a category to view foods</div></div>';
        $('#saveStatusBtn').classList.add('d-none');
    }

    async function openExisting(id){
        try {
            const data = await ajax(`${BASE}/${id}`);
            if(!data.success) return toast(data.message || 'Failed to load', 'error');
            ensureModal();
            resetModal();
            currentId = id;
            const o = data.order;
            $('#orderModalTitle').innerHTML = `<i class="fa-solid fa-receipt me-2"></i>Order ${esc(o.order_number)}`;
            $('#waiterSelect').value   = o.waiter_id || '';
            $('#tableSelect').value    = o.table_id || '';
            $('#customerName').value   = o.customer_name || '';
            $('#orderNotes').value     = o.notes || '';
            $('#taxInput').value       = o.tax || 0;
            $('#discountInput').value  = o.discount || 0;
            setSelectedStatus(o.status || 'pending');
            renderItems(o.items || []);
            updateBill(o);
            $('#saveStatusBtn').classList.remove('d-none');
            $('#editPageLink').href = `${BASE}/${id}/edit`;
            modal.show();
        } catch (e) {
            console.error(e);
            toast('Failed to load: ' + e.message, 'error');
        }
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
            list.innerHTML = '<div class="text-center py-4 text-muted small">No items yet.</div>';
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
        container.innerHTML = '<div class="foods-empty"><i class="fa-solid fa-spinner fa-spin"></i><div style="font-size:12px;">Loading...</div></div>';
        const data = await ajax(`${FOODS}/${catId}`);
        if(data.success){
            foodCache[catId] = data.foods;
            renderFoods(data.foods);
        } else {
            container.innerHTML = '<div class="foods-empty"><i class="fa-solid fa-exclamation-triangle"></i><div style="font-size:12px;">Failed to load</div></div>';
        }
    }

    function renderFoods(foods){
        const container = $('#foodsContainer');
        if(!foods.length){
            container.innerHTML = '<div class="foods-empty"><i class="fa-solid fa-bowl-food"></i><div style="font-size:12px;">No foods</div></div>';
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
        if(!currentId) return;
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
        const data = await ajax(`${BASE}/${currentId}/items`, {
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
        const data = await ajax(`${BASE}/${currentId}/items/${itemId}`, {
            method: 'PUT', body: JSON.stringify({ quantity: qty })
        });
        if(data.success){ renderItems(data.order.items || []); updateBill(data.order); }
    }

    async function removeItem(itemId){
        const data = await ajax(`${BASE}/${currentId}/items/${itemId}`, { method: 'DELETE' });
        if(data.success){ renderItems(data.order.items || []); updateBill(data.order); toast('Removed'); }
    }

    async function applyCharges(){
        if(!currentId) return;
        const data = await ajax(`${BASE}/${currentId}/charges`, {
            method: 'POST',
            body: JSON.stringify({
                tax: parseFloat($('#taxInput').value) || 0,
                discount: parseFloat($('#discountInput').value) || 0,
            })
        });
        if(data.success) updateBill(data.order);
    }

    function printReceipt(){
        if(!currentId){
            toast('No order loaded', 'error');
            return;
        }
        const url = `/pos/orders/${currentId}/print-receipt`;
        window.open(url, '_blank', 'width=900,height=800');
    }

    async function saveStatus(){
        if(!currentId) return;

        const status = getSelectedStatus();
        const btn = $('#saveStatusBtn');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        const data = await ajax(`${BASE}/${currentId}`, {
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

        if(status === 'completed'){
            try {
                const om = document.getElementById('orderModal');
                const inst = bootstrap.Modal.getInstance(om);
                if (inst) inst.hide();
            } catch(e) {}

            setTimeout(() => {
                if (window.POSReceipt && typeof window.POSReceipt.show === 'function') {
                    window.POSReceipt.show(data.order);
                } else {
                    console.error('POSReceipt module not loaded');
                    toast('Receipt module missing', 'error');
                }
            }, 400);
        } else {
            toast('Saved');
            setTimeout(() => location.reload(), 700);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        $$('.pos-order-card').forEach(c => c.addEventListener('click', () => openExisting(c.dataset.orderId)));

        $$('.cat-tile').forEach(tile => {
            tile.addEventListener('click', () => selectCategory(tile.dataset.catId, tile.dataset.catName));
        });

        $$('.status-btn').forEach(btn => {
            if (btn.classList.contains('print-btn')) return;
            btn.addEventListener('click', () => setSelectedStatus(btn.dataset.status));
        });

        const prBtn = document.getElementById('printBtn');
        if(prBtn) prBtn.addEventListener('click', printReceipt);

        $('#saveStatusBtn')?.addEventListener('click', saveStatus);
        $('#taxInput')?.addEventListener('change', applyCharges);
        $('#discountInput')?.addEventListener('change', applyCharges);

        $('#waiterSelect')?.addEventListener('change', () => {
            if(currentId) ajax(`${BASE}/${currentId}`, {method:'PUT', body:JSON.stringify({waiter_id: $('#waiterSelect').value})});
        });
    });
})();
</script>
@endsection
@extends('pos.layouts.pos_master')

@section('title', 'New Order')

@section('content')
<style>
.card-clean{
    background:#fff;
    border:1.5px solid #ffd6e5;
    border-radius:16px;
    box-shadow:0 2px 8px rgba(255,45,122,.05);
}
.card-clean .card-body{padding:24px;}
.section-title{
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.06em;
    color:#6b7280;
    margin-bottom:16px;
}
.btn-pink{
    background:linear-gradient(135deg,#ff2d7a,#ff4b91);
    color:#fff;
    border:none;
    font-weight:600;
    border-radius:10px;
}
.btn-pink:hover{color:#fff;filter:brightness(1.05);}

/* ✅ FIX: prevent selects/inputs from overflowing on tiny screens */
.form-select, .form-control{
    min-width:0;
    width:100%;
    text-overflow:ellipsis;
}

@media (max-width: 400px){
    .container-fluid{
        padding-left:12px !important;
        padding-right:12px !important;
    }
    .card-clean .card-body{
        padding:16px;
    }
    .form-select, .form-control{
        font-size:13px;
        padding:6px 10px;
    }
    .btn-pink, .btn-outline-secondary{
        width:100%;
        justify-content:center;
    }
    .d-flex.gap-2.pt-2{
        flex-direction:column;
    }
}
</style>

<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">New Order</h4>
            <p class="text-muted small m-0">Assign waiter and table to start an order</p>
        </div>
        <a href="{{ route('pos.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pos.orders.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-lg-6">
                <div class="card-clean h-100">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="fa-solid fa-user-tie me-2" style="color:#ff2d7a;"></i>Assignment
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Waiter <span class="text-danger">*</span></label>
                            <select name="waiter_id" class="form-select @error('waiter_id') is-invalid @enderror" required>
                                <option value="">-- Select waiter --</option>
                                @foreach($waiters as $w)
                                    <option value="{{ $w->id }}" @selected(old('waiter_id')==$w->id)>{{ $w->name }}</option>
                                @endforeach
                            </select>
                            @error('waiter_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Table <span class="text-danger">*</span></label>
                            <select name="pos_table_id" class="form-select @error('pos_table_id') is-invalid @enderror" required>
                                <option value="">-- Select table --</option>
                                @foreach($tables as $t)
                                    <option value="{{ $t->id }}" @selected(old('pos_table_id')==$t->id)>
                                        {{ $t->table_number }} — {{ $t->table_name ?? 'Table' }} ({{ ucfirst($t->status) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pos_table_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-clean h-100">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="fa-solid fa-user me-2" style="color:#ff2d7a;"></i>Customer Info
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control"
                                   value="{{ old('customer_name') }}" placeholder="Optional">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="Any notes for kitchen...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-pink px-5">
                        <i class="fa-solid fa-check me-2"></i>Create Order
                    </button>
                    <a href="{{ route('pos.orders.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
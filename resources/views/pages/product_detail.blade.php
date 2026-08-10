@extends('layouts.master') {{-- Make sure this matches your layout file name --}}

@section('content')
<div class="container my-5" style="padding-top: 5%;">
    <!-- Product Detail Header Split -->
    <div class="row g-5">
        <!-- Left Side: Product Image Container -->
        <div class="col-md-6">
            <div class="product-image-wrapper p-3 bg-light rounded shadow-sm text-center">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 450px; width: 100%; object-fit: cover;">
                @else
                    <img src="{{ asset('assets/images/default-food.png') }}" alt="Default Food" class="img-fluid rounded" style="max-height: 450px; width: 100%; object-fit: cover;">
                @endif
            </div>
        </div>

        <!-- Right Side: Content Details -->
        <div class="col-md-6 d-flex flex-column justify-content-between">
            <div class="product-info">
                <span class="badge bg-warning text-dark mb-2 px-3 py-2 text-uppercase font-weight-bold">
                    {{ $product->category->name ?? 'Menu Item' }}
                </span>
                <h1 class="display-5 fw-bold text-dark mb-3">{{ $product->name }}</h1>

                <!-- Dynamic Price Box -->
                <div class="price-box mb-4">
                    <span class="h2 text-danger fw-bold me-3" id="display-sale-price">Rs. {{ number_format($product->sale_price, 2) }}</span>
                    @if($product->price > $product->sale_price)
                        <span class="text-muted text-decoration-line-through h4" id="display-old-price">Rs. {{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <hr class="text-muted mb-4">

                <!-- Dynamic Weight / Variations Selector -->
                <div class="variants-selector mb-4">
                    <label class="form-label fw-bold text-secondary mb-2">Select Portion / Weight:</label>
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Base Weight Option -->
                        <input type="radio" class="btn-check product-variant-input" name="product_portion" id="variant_base"
                               data-price="{{ $product->price }}" data-sale-price="{{ $product->sale_price }}" data-weight="{{ $product->weight }}" checked>
                        <label class="btn btn-outline-danger px-3" for="variant_base">{{ $product->weight }}</label>

                        <!-- Extra Dynamic Variations from Admin -->
                        @if($product->variation && is_array($product->variation))
                            @foreach($product->variation as $index => $variant)
                                @if(!empty($variant['weight']) && isset($variant['price']))
                                    <input type="radio" class="btn-check product-variant-input" name="product_portion" id="variant_{{ $index }}"
                                           data-price="{{ $variant['old_price'] ?? $variant['price'] }}"
                                           data-sale-price="{{ $variant['price'] }}"
                                           data-weight="{{ $variant['weight'] }}">
                                    <label class="btn btn-outline-danger px-3" for="variant_{{ $index }}">{{ $variant['weight'] }}</label>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Quantity and Add to Cart Section -->
                <div class="d-flex align-items-center gap-3 my-4">
                    <div class="input-group" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" id="btn-minus">-</button>
                        <input type="text" class="form-control text-center fw-bold" id="cart-quantity" value="1" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="btn-plus">+</button>
                    </div>
                    <button class="btn btn-danger btn-lg px-5 py-2 fw-bold text-uppercase shadow-sm" id="add-to-cart-btn">
                        <i class="fa-solid fa-cart-shopping me-2"></i> Add To Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Down Side: Complete Description Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold border-bottom pb-2 mb-3 text-dark">Description</h3>
            <p class="text-secondary lh-lg fs-5" style="white-space: pre-line;">
                {{ $product->description }}
            </p>
        </div>
    </div>

    <!-- Down Bottom: Similar Products Grid -->
    @if($similarProducts->count() > 0)
        <div class="row mt-5 pt-4">
            <div class="col-12">
                <h3 class="fw-bold mb-4 text-dark text-center text-md-start">Similar Delicious Items</h3>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach($similarProducts as $similar)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 position-relative menu-card">
                            <div class="position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2 shadow-sm eye-box" style="z-index: 10;">
                                <a href="{{ route('product.details', $similar->id) }}">
                                    <i class="fa-solid fa-eye fs-5"></i>
                                </a>
                            </div>
                            @if($similar->image)
                                <img src="{{ asset($similar->image) }}" class="card-img-top rounded-top" alt="{{ $similar->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/default-food.png') }}" class="card-img-top rounded-top" alt="Default Food" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title fw-bold text-dark">{{ $similar->name }}</h5>
                                    <p class="card-text text-muted small text-truncate">{{ $similar->description }}</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fw-bold text-danger">Rs. {{ number_format($similar->sale_price, 2) }}</span>
                                    <a href="{{ route('product.details', $similar->id) }}" class="btn btn-sm btn-outline-danger px-3">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Variant Price Switching, Quantity, and Add to Cart Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ---------- Price Update on Variant Switch ----------
        const variantInputs = document.querySelectorAll('.product-variant-input');
        const displaySalePrice = document.getElementById('display-sale-price');
        const displayOldPrice = document.getElementById('display-old-price');

        variantInputs.forEach(input => {
            input.addEventListener('change', function () {
                const salePrice = parseFloat(this.getAttribute('data-sale-price')).toFixed(2);
                const oldPrice = parseFloat(this.getAttribute('data-price')).toFixed(2);

                displaySalePrice.textContent = 'Rs. ' + Number(salePrice).toLocaleString();
                if (displayOldPrice) {
                    if (parseFloat(oldPrice) > parseFloat(salePrice)) {
                        displayOldPrice.style.display = 'inline';
                        displayOldPrice.textContent = 'Rs. ' + Number(oldPrice).toLocaleString();
                    } else {
                        displayOldPrice.style.display = 'none';
                    }
                }
            });
        });

        // ---------- Quantity Controls ----------
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const quantityInput = document.getElementById('cart-quantity');

        btnMinus.addEventListener('click', function() {
            let currentVal = parseInt(quantityInput.value);
            if(currentVal > 1) {
                quantityInput.value = currentVal - 1;
            }
        });

        btnPlus.addEventListener('click', function() {
            let currentVal = parseInt(quantityInput.value);
            quantityInput.value = currentVal + 1;
        });

        // ---------- Add to Cart Functionality ----------
        // Uses the global addToCart() function defined in the navbar partial,
        // so the navbar cart badge, sidebar, and toast all update automatically.
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        addToCartBtn.addEventListener('click', function() {
            const productName = "{{ addslashes($product->name) }}";
            const productImage = "{{ asset($product->image ?? 'assets/images/default-food.png') }}";

            // Get selected variant (radio checked)
            const selectedVariant = document.querySelector('input[name="product_portion"]:checked');
            const salePrice = parseFloat(selectedVariant.getAttribute('data-sale-price'));
            const weight = selectedVariant.getAttribute('data-weight') || "{{ $product->weight }}";

            // Get quantity
            const quantity = parseInt(document.getElementById('cart-quantity').value) || 1;

            // Match the naming convention used on the menu page so items merge correctly
            const finalName = weight ? `${productName} (${weight})` : productName;

            if (typeof addToCart === 'function') {
                addToCart(finalName, salePrice, productImage, quantity);
            } else {
                console.error('addToCart function is not defined globally.');
            }
        });
    });
</script>
@endsection
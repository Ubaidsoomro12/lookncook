@extends('admin.layouts.master')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
    </div>

    @if($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category_id" required
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('category_id') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all">
                    <option value="" disabled>Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all"
                       placeholder="e.g. Zinger Burger">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('description') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all"
                          placeholder="Short description about this product (min 10 characters)...">{{ old('description', $product->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Old Price (PKR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('price') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. 600">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Sale Price (PKR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('sale_price') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. 500">
                    @error('sale_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Weight / Portion <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="weight" value="{{ old('weight', $product->weight) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('weight') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. Regular, 1 plate">
                    @error('weight')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Dynamic Variations Section --}}
            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Dynamic Variants</h3>
                        <p class="text-xs text-gray-400">Optional. Add custom sizes like 500g, 1kg — each with its own old price and sale price.</p>
                    </div>
                    <button type="button" id="add-variant-btn"
                            class="text-xs font-semibold px-3 py-1.5 bg-[#ff2d7a]/10 text-[#ff2d7a] rounded-lg hover:bg-[#ff2d7a]/20 transition-all">
                        + Add Variant Option
                    </button>
                </div>

                <div id="variants-container" class="space-y-2">
                    @php
                        // Prioritize old input validation data fallback, otherwise load relation records from database
                        $variantsData = old('variants', $product->variants ?? []);
                    @endphp

                    @foreach($variantsData as $index => $variant)
                        @php
                            // Handle both array data structures from old() or Model relationship properties
                            $vWeight = is_array($variant) ? ($variant['weight'] ?? '') : $variant->weight;
                            $vOldPrice = is_array($variant) ? ($variant['old_price'] ?? '') : $variant->old_price;
                            $vPrice = is_array($variant) ? ($variant['price'] ?? '') : $variant->price;
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-[1.2fr_1fr_1fr_auto] gap-2 items-center variant-row">
                            <input type="text" name="variants[{{ $index }}][weight]" value="{{ $vWeight }}" required
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a]"
                                   placeholder="Weight (e.g. 1kg)">
                            <input type="number" step="0.01" name="variants[{{ $index }}][old_price]" value="{{ $vOldPrice }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a]"
                                   placeholder="Old Price (optional)">
                            <input type="number" step="0.01" name="variants[{{ $index }}][price]" value="{{ $vPrice }}" required
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a]"
                                   placeholder="Price (PKR)">
                            <button type="button" class="remove-variant-btn px-2.5 py-2 text-sm font-medium bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-all">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Product Image
                </label>
                
                @if($product->image)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl max-w-xs">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Current Product Image" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                        <div>
                            <p class="text-xs font-medium text-gray-500">Current Image File</p>
                            <p class="text-[11px] text-gray-400 truncate max-w-[180px]">{{ basename($product->image) }}</p>
                        </div>
                    </div>
                @endif

                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-[#ff2d7a]/10 file:text-[#ff2d7a] file:font-medium hover:file:bg-[#ff2d7a]/20 transition-all">
                <p class="text-xs text-gray-400 mt-1">Leave empty to keep current image. JPG, PNG or WEBP. Max 4MB.</p>
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" required
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('status') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a] transition-all">
                    <option value="" disabled>Select status</option>
                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91] text-white font-medium px-6 py-2.5 rounded-xl shadow-md shadow-[#ff2d7a]/20 hover:opacity-90 transition-all">
                    <i class="fa-solid fa-check"></i> Update Product
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('variants-container');
        const addButton = document.getElementById('add-variant-btn');
        let variantIndex = {{ count($variantsData) }};

        addButton.addEventListener('click', function() {
            const html = `
                <div class="grid grid-cols-1 sm:grid-cols-[1.2fr_1fr_1fr_auto] gap-2 items-center variant-row animate-fadeIn">
                    <input type="text" name="variants[${variantIndex}][weight]" required
                           class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a]"
                           placeholder="Weight (e.g. 500g)">
                    <input type="number" step="0.01" name="variants[${variantIndex}][old_price]"
                           class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a]"
                           placeholder="Old Price (optional)">
                    <input type="number" step="0.01" name="variants[${variantIndex}][price]" required
                           class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/30 focus:border-[#ff2d7a]"
                           placeholder="Price (PKR)">
                    <button type="button" class="remove-variant-btn px-2.5 py-2 text-sm font-medium bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-all">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            variantIndex++;
        });

        container.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.remove-variant-btn');
            if (deleteBtn) {
                deleteBtn.closest('.variant-row').remove();
            }
        });
    });
</script>
@endsection
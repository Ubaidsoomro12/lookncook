@extends('admin.layouts.master')
@section('title', 'Edit | Products')

@section('content')
<style>
  .prod-edit-back {
    width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 12px; background: #f3f4f6; color: #6b7280; transition: all .2s;
  }
  .prod-edit-back:hover { background: #e5e7eb; color: #6b7280; }

  .prod-edit-card {
    border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px; background: #fff;
  }
  .prod-edit-label { display: block; font-weight: 500; font-size: 14px; color: #374151; margin-bottom: 6px; }
  .prod-edit-input {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none;
  }
  .prod-edit-input:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.3); }
  .prod-edit-input-error { border-color: #fca5a5; }
  .prod-edit-textarea {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; resize: vertical; min-height: 100px;
  }
  .prod-edit-textarea:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.3); }
  .prod-edit-select {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; background: #fff;
  }
  .prod-edit-select:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.3); }
  .prod-edit-file {
    font-size: 14px; padding: 6px 12px; border-radius: 12px; border: 1px solid #e5e7eb; width: 100%;
  }
  .prod-edit-file::-webkit-file-upload-button {
    background: rgba(255,45,122,0.1); color: #ff2d7a; border: none;
    padding: 8px 16px; border-radius: 12px; font-weight: 500; font-size: 14px;
    margin-right: 12px; cursor: pointer; transition: all .2s;
  }
  .prod-edit-file::-webkit-file-upload-button:hover { background: rgba(255,45,122,0.2); }
  .prod-edit-hint { font-size: 12px; color: #9ca3af; margin-top: 4px; }
  .prod-edit-error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
  .prod-edit-error-box {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    padding: 12px 16px; border-radius: 12px; font-size: 14px; margin-bottom: 20px;
  }
  .prod-edit-error-box ul { list-style: disc; padding-left: 20px; margin: 0; }
  .prod-edit-error-box ul li { margin-bottom: 2px; }
  .prod-edit-submit {
    background: linear-gradient(to right, #ff2d7a, #ff4b91); color: #fff; font-weight: 500;
    padding: 10px 24px; border-radius: 12px; border: none;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.2); transition: all .2s;
  }
  .prod-edit-submit:hover { opacity: .9; color: #fff; }
  .prod-edit-cancel {
    padding: 10px 24px; border-radius: 12px; border: 1px solid #e5e7eb;
    color: #6b7280; font-weight: 500; background: #fff; transition: all .2s;
  }
  .prod-edit-cancel:hover { background: #f9fafb; color: #6b7280; }

  .prod-edit-img-preview {
    display: flex; align-items: center; gap: 12px;
    padding: 12px; background: #f9fafb; border: 1px solid #f3f4f6;
    border-radius: 12px; max-width: 320px; margin-bottom: 12px;
  }
  .prod-edit-img-preview img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; }
  .prod-edit-img-preview .info p { font-size: 12px; font-weight: 500; color: #6b7280; margin: 0; }
  .prod-edit-img-preview .info .filename { font-size: 11px; color: #9ca3af; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

  .variant-row { display: grid; grid-template-columns: 1.2fr 1fr 1fr auto; gap: 8px; align-items: center; }
  @media (max-width: 640px) { .variant-row { grid-template-columns: 1fr; } }
  .variant-input {
    padding: 8px 12px; font-size: 14px; border: 1px solid #e5e7eb; border-radius: 12px;
    outline: none; transition: all .2s;
  }
  .variant-input:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.3); }
  .variant-remove-btn {
    padding: 8px 10px; font-size: 14px; font-weight: 500;
    background: #fef2f2; color: #dc2626; border: none; border-radius: 12px;
    transition: all .2s; cursor: pointer;
  }
  .variant-remove-btn:hover { background: #fee2e2; }

  .tw-max-w-2xl { max-width: 672px; margin: 0 auto; }
  .tw-space-y-5 > * + * { margin-top: 20px; }
  .tw-grid-cols-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
  @media (max-width: 768px) { .tw-grid-cols-3 { grid-template-columns: 1fr; } }
</style>

<div class="tw-max-w-2xl px-3 px-sm-4">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.products.index') }}" class="prod-edit-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0" style="font-size:24px;">Edit Product</h1>
  </div>

  @if($errors->any())
    <div class="prod-edit-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="prod-edit-card">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="tw-space-y-5">
      @csrf
      @method('PUT')

      <div>
        <label class="prod-edit-label">Category <span class="text-danger">*</span></label>
        <select name="category_id" required class="prod-edit-select {{ $errors->has('category_id') ? 'prod-edit-input-error' : '' }}">
          <option value="" disabled>Select a category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('category_id')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="prod-edit-label">Product Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
               class="prod-edit-input {{ $errors->has('name') ? 'prod-edit-input-error' : '' }}"
               placeholder="e.g. Zinger Burger">
        @error('name')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="prod-edit-label">Description <span class="text-danger">*</span></label>
        <textarea name="description" rows="4" required
                  class="prod-edit-textarea {{ $errors->has('description') ? 'prod-edit-input-error' : '' }}"
                  placeholder="Short description about this product (min 10 characters)...">{{ old('description', $product->description) }}</textarea>
        @error('description')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="tw-grid-cols-3">
        <div>
          <label class="prod-edit-label">Old Price (PKR) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                 class="prod-edit-input {{ $errors->has('price') ? 'prod-edit-input-error' : '' }}"
                 placeholder="e.g. 600">
          @error('price')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="prod-edit-label">Sale Price (PKR) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" required
                 class="prod-edit-input {{ $errors->has('sale_price') ? 'prod-edit-input-error' : '' }}"
                 placeholder="e.g. 500">
          @error('sale_price')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="prod-edit-label">Weight / Portion <span class="text-danger">*</span></label>
          <input type="text" name="weight" value="{{ old('weight', $product->weight) }}" required
                 class="prod-edit-input {{ $errors->has('weight') ? 'prod-edit-input-error' : '' }}"
                 placeholder="e.g. Regular, 1 plate">
          @error('weight')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
        </div>
      </div>

      <!-- Dynamic Variations -->
      <div style="border:1px solid #e5e7eb; border-radius:12px; padding:16px; background:#f9fafb;">
        <div class="d-flex align-items-center justify-between mb-3">
          <div>
            <h3 style="font-size:14px; font-weight:600; color:#1f2937; margin:0;">Dynamic Variants</h3>
            <p style="font-size:12px; color:#9ca3af; margin:0;">Optional. Add custom sizes like 500g, 1kg — each with its own old price and sale price.</p>
          </div>
          <button type="button" id="add-variant-btn"
                  style="font-size:12px; font-weight:600; padding:6px 12px; background:rgba(255,45,122,0.1); color:#ff2d7a; border:none; border-radius:8px; transition:all .2s; cursor:pointer;">
            + Add Variant Option
          </button>
        </div>

        <div id="variants-container" style="display:flex; flex-direction:column; gap:8px;">
          @php
            $variantsData = old('variants', $product->variants ?? []);
          @endphp
          @foreach($variantsData as $index => $variant)
            @php
              $vWeight = is_array($variant) ? ($variant['weight'] ?? '') : $variant->weight;
              $vOldPrice = is_array($variant) ? ($variant['old_price'] ?? '') : $variant->old_price;
              $vPrice = is_array($variant) ? ($variant['price'] ?? '') : $variant->price;
            @endphp
            <div class="variant-row">
              <input type="text" name="variants[{{ $index }}][weight]" value="{{ $vWeight }}" required
                     class="variant-input" placeholder="Weight (e.g. 1kg)">
              <input type="number" step="0.01" name="variants[{{ $index }}][old_price]" value="{{ $vOldPrice }}"
                     class="variant-input" placeholder="Old Price (optional)">
              <input type="number" step="0.01" name="variants[{{ $index }}][price]" value="{{ $vPrice }}" required
                     class="variant-input" placeholder="Price (PKR)">
              <button type="button" class="variant-remove-btn">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            </div>
          @endforeach
        </div>
      </div>

      <div>
        <label class="prod-edit-label">Product Image</label>
        @if($product->image)
          <div class="prod-edit-img-preview">
            <img src="{{ asset('storage/' . $product->image) }}" alt="Current Product Image">
            <div class="info">
              <p>Current Image File</p>
              <p class="filename">{{ basename($product->image) }}</p>
            </div>
          </div>
        @endif
        <input type="file" name="image" accept="image/*"
               class="prod-edit-file {{ $errors->has('image') ? 'prod-edit-input-error' : '' }}">
        <p class="prod-edit-hint">Leave empty to keep current image. JPG, PNG or WEBP. Max 4MB.</p>
        @error('image')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="prod-edit-label">Status <span class="text-danger">*</span></label>
        <select name="status" required class="prod-edit-select {{ $errors->has('status') ? 'prod-edit-input-error' : '' }}">
          <option value="" disabled>Select status</option>
          <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<p class="prod-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="d-flex gap-3 pt-2">
        <button type="submit" class="prod-edit-submit">
          <i class="fa-solid fa-check"></i> Update Product
        </button>
        <a href="{{ route('admin.products.index') }}" class="prod-edit-cancel">Cancel</a>
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
            <div class="variant-row">
                <input type="text" name="variants[${variantIndex}][weight]" required
                       class="variant-input" placeholder="Weight (e.g. 500g)">
                <input type="number" step="0.01" name="variants[${variantIndex}][old_price]"
                       class="variant-input" placeholder="Old Price (optional)">
                <input type="number" step="0.01" name="variants[${variantIndex}][price]" required
                       class="variant-input" placeholder="Price (PKR)">
                <button type="button" class="variant-remove-btn">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    container.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.variant-remove-btn');
        if (deleteBtn) {
            deleteBtn.closest('.variant-row').remove();
        }
    });
});
</script>
@endsection
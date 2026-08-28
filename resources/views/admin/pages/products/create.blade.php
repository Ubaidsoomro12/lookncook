@extends('admin.layouts.master')
@section('title', 'Create | Products')

@section('content')
<style>
  .prod-form-back {
    width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 12px; background: #f3f4f6; color: #6b7280; transition: all .2s;
  }
  .prod-form-back:hover { background: #e5e7eb; color: #6b7280; }

  .prod-form-card {
    border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px; background: #fff;
  }
  .prod-form-label { display: block; font-weight: 500; font-size: 14px; color: #374151; margin-bottom: 6px; }
  .prod-form-input {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none;
  }
  .prod-form-input:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.3); }
  .prod-form-input-error { border-color: #fca5a5; }
  .prod-form-textarea {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; resize: vertical; min-height: 100px;
  }
  .prod-form-textarea:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.3); }
  .prod-form-select {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; background: #fff;
  }
  .prod-form-select:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.3); }
  .prod-form-file {
    font-size: 14px; padding: 6px 12px; border-radius: 12px; border: 1px solid #e5e7eb; width: 100%;
  }
  .prod-form-file::-webkit-file-upload-button {
    background: rgba(255,45,122,0.1); color: #ff2d7a; border: none;
    padding: 8px 16px; border-radius: 12px; font-weight: 500; font-size: 14px;
    margin-right: 12px; cursor: pointer; transition: all .2s;
  }
  .prod-form-file::-webkit-file-upload-button:hover { background: rgba(255,45,122,0.2); }
  .prod-form-hint { font-size: 12px; color: #9ca3af; margin-top: 4px; }
  .prod-form-error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
  .prod-form-error-box {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    padding: 12px 16px; border-radius: 12px; font-size: 14px; margin-bottom: 20px;
  }
  .prod-form-error-box ul { list-style: disc; padding-left: 20px; margin: 0; }
  .prod-form-error-box ul li { margin-bottom: 2px; }
  .prod-form-submit {
    background: linear-gradient(to right, #ff2d7a, #ff4b91); color: #fff; font-weight: 500;
    padding: 10px 24px; border-radius: 12px; border: none;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.2); transition: all .2s;
  }
  .prod-form-submit:hover { opacity: .9; color: #fff; }
  .prod-form-cancel {
    padding: 10px 24px; border-radius: 12px; border: 1px solid #e5e7eb;
    color: #6b7280; font-weight: 500; background: #fff; transition: all .2s;
  }
  .prod-form-cancel:hover { background: #f9fafb; color: #6b7280; }

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
    <a href="{{ route('admin.products.index') }}" class="prod-form-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0" style="font-size:24px;">Add New Product</h1>
  </div>

  @if($errors->any())
    <div class="prod-form-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="prod-form-card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="tw-space-y-5">
      @csrf

      <div>
        <label class="prod-form-label">Category <span class="text-danger">*</span></label>
        <select name="category_id" required class="prod-form-select {{ $errors->has('category_id') ? 'prod-form-input-error' : '' }}">
          <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select a category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('category_id')<p class="prod-form-error-text">{{ $message }}</p>@enderror
        @if($categories->isEmpty())
          <p class="text-xs" style="color:#d97706; margin-top:4px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            No active categories found. <a href="{{ route('admin.categories.create') }}" class="underline font-medium">Add one first</a>.
          </p>
        @endif
      </div>

      <div>
        <label class="prod-form-label">Product Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="prod-form-input {{ $errors->has('name') ? 'prod-form-input-error' : '' }}"
               placeholder="e.g. Zinger Burger">
        @error('name')<p class="prod-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="prod-form-label">Description <span class="text-danger">*</span></label>
        <textarea name="description" rows="4" required
                  class="prod-form-textarea {{ $errors->has('description') ? 'prod-form-input-error' : '' }}"
                  placeholder="Short description about this product (min 10 characters)...">{{ old('description') }}</textarea>
        @error('description')<p class="prod-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="tw-grid-cols-3">
        <div>
          <label class="prod-form-label">Old Price (PKR) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                 class="prod-form-input {{ $errors->has('price') ? 'prod-form-input-error' : '' }}"
                 placeholder="e.g. 600">
          @error('price')<p class="prod-form-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="prod-form-label">Sale Price (PKR) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" required
                 class="prod-form-input {{ $errors->has('sale_price') ? 'prod-form-input-error' : '' }}"
                 placeholder="e.g. 500">
          @error('sale_price')<p class="prod-form-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="prod-form-label">Weight / Portion <span class="text-danger">*</span></label>
          <input type="text" name="weight" value="{{ old('weight') }}" required
                 class="prod-form-input {{ $errors->has('weight') ? 'prod-form-input-error' : '' }}"
                 placeholder="e.g. Regular, 1 plate">
          @error('weight')<p class="prod-form-error-text">{{ $message }}</p>@enderror
        </div>
      </div>

      <!-- Dynamic Variations -->
      <div style="border:1px solid #e5e7eb; border-radius:12px; padding:16px; background:#f9fafb;">
        <div class="d-flex align-items-center justify-content-between mb-3">
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
          @if(is_array(old('variants')))
            @foreach(old('variants') as $index => $variant)
              <div class="variant-row">
                <input type="text" name="variants[{{ $index }}][weight]" value="{{ $variant['weight'] ?? '' }}" required
                       class="variant-input" placeholder="Weight (e.g. 1kg)">
                <input type="number" step="0.01" name="variants[{{ $index }}][old_price]" value="{{ $variant['old_price'] ?? '' }}"
                       class="variant-input" placeholder="Old Price (optional)">
                <input type="number" step="0.01" name="variants[{{ $index }}][price]" value="{{ $variant['price'] ?? '' }}" required
                       class="variant-input" placeholder="Price (PKR)">
                <button type="button" class="variant-remove-btn">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </div>
            @endforeach
          @endif
        </div>
      </div>

      <div>
        <label class="prod-form-label">Product Image <span class="text-danger">*</span></label>
        <input type="file" name="image" accept="image/*" required
               class="prod-form-file {{ $errors->has('image') ? 'prod-form-input-error' : '' }}">
        <p class="prod-form-hint">JPG, PNG or WEBP. Max 4MB.</p>
        @error('image')<p class="prod-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="prod-form-label">Status <span class="text-danger">*</span></label>
        <select name="status" required class="prod-form-select {{ $errors->has('status') ? 'prod-form-input-error' : '' }}">
          <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select status</option>
          <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<p class="prod-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="d-flex gap-3 pt-2">
        <button type="submit" class="prod-form-submit">
          <i class="fa-solid fa-check"></i> Save Product
        </button>
        <a href="{{ route('admin.products.index') }}" class="prod-form-cancel">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('variants-container');
    const addButton = document.getElementById('add-variant-btn');
    let variantIndex = {{ is_array(old('variants')) ? count(old('variants')) : 0 }};

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
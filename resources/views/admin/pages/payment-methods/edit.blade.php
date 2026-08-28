@extends('admin.layouts.master')
@section('title', 'Edit | Payments')

@section('content')
<style>
  .payment-edit-back {
    width: 2.25rem;
    height: 2.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    background: #f3f4f6;
    color: #6b7280;
    transition: all 0.2s;
  }
  .payment-edit-back:hover {
    background: #e5e7eb;
    color: #6b7280;
  }
  .payment-edit-card {
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 1.5rem;
    background: #fff;
  }
  .payment-edit-label {
    display: block;
    font-weight: 500;
    font-size: 0.875rem;
    color: #374151;
    margin-bottom: 0.375rem;
  }
  .payment-edit-input {
    width: 100%;
    padding: 0.625rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
    transition: all 0.2s;
    outline: none;
  }
  .payment-edit-input:focus {
    border-color: #334155;
    box-shadow: 0 0 0 3px rgba(51,65,85,0.2);
  }
  .payment-edit-input-error {
    border-color: #fca5a5;
  }
  .payment-edit-select {
    width: 100%;
    padding: 0.625rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
    transition: all 0.2s;
    outline: none;
    background: #fff;
  }
  .payment-edit-select:focus {
    border-color: #334155;
    box-shadow: 0 0 0 3px rgba(51,65,85,0.2);
  }
  .payment-edit-file {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    width: 100%;
  }
  .payment-edit-file::-webkit-file-upload-button {
    background: #334155;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-weight: 500;
    font-size: 0.875rem;
    margin-right: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
  }
  .payment-edit-file::-webkit-file-upload-button:hover {
    background: #1e293b;
  }
  .payment-edit-hint {
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 0.375rem;
  }
  .payment-edit-error-text {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
  }
  .payment-edit-error-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    margin-bottom: 1.25rem;
  }
  .payment-edit-error-box ul {
    list-style: disc;
    padding-left: 1.25rem;
    margin: 0;
  }
  .payment-edit-error-box ul li {
    margin-bottom: 0.125rem;
  }
  .payment-edit-submit {
    background: linear-gradient(to right, #1e293b, #334155);
    color: #fff;
    font-weight: 500;
    padding: 0.625rem 1.5rem;
    border-radius: 0.75rem;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(30,41,59,0.2);
    transition: all 0.2s;
  }
  .payment-edit-submit:hover {
    opacity: 0.9;
    color: #fff;
  }
  .payment-edit-cancel {
    padding: 0.625rem 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    font-weight: 500;
    background: #fff;
    transition: all 0.2s;
  }
  .payment-edit-cancel:hover {
    background: #f9fafb;
    color: #6b7280;
  }
  .payment-edit-check {
    width: 1rem;
    height: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.25rem;
    accent-color: #334155;
  }
  .payment-edit-preview {
    width: 4rem;
    height: 4rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
  }
  .payment-edit-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  .payment-edit-preview i {
    font-size: 1.25rem;
    color: #d1d5db;
    display: none;
  }
  .payment-edit-divider {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 1rem 0;
  }
  .payment-edit-section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }
  .tw-max-w-2xl {
    max-width: 42rem;
    margin: 0 auto;
  }
  .tw-space-y-4 > * + * {
    margin-top: 1rem;
  }
  .tw-space-y-5 > * + * {
    margin-top: 1.25rem;
  }
  .tw-grid-cols-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }
  @media (max-width: 768px) {
    .tw-grid-cols-2 {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="tw-max-w-2xl px-3 px-sm-4">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.payment-methods.index') }}" class="payment-edit-back">
      <i class="fa-solid fa-arrow-left" style="font-size:0.875rem;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0" style="font-size:1.5rem;">Edit Payment Method</h1>
  </div>

  @if($errors->any())
    <div class="payment-edit-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="payment-edit-card">
    <form action="{{ route('admin.payment-methods.update', $method->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Type Selection -->
      <div>
        <label class="payment-edit-label">Select Payment Type <span class="text-danger">*</span></label>
        <select name="type" id="typeSelect" required class="payment-edit-select {{ $errors->has('type') ? 'payment-edit-input-error' : '' }}">
          <option value="">-- Choose a type --</option>
          <option value="cod" {{ old('type', $method->type) === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
          <option value="mobile_wallet" {{ old('type', $method->type) === 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
          <option value="bank" {{ old('type', $method->type) === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
        </select>
        @error('type')<p class="payment-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <!-- Additional Fields -->
      <div id="additionalFields" style="display: {{ old('type', $method->type) ? 'block' : 'none' }};" class="tw-space-y-5">

        <!-- Name -->
        <div>
          <label class="payment-edit-label">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name', $method->name) }}" required
                 class="payment-edit-input {{ $errors->has('name') ? 'payment-edit-input-error' : '' }}"
                 placeholder="e.g. EasyPaisa, Meezan Bank">
          @error('name')<p class="payment-edit-error-text">{{ $message }}</p>@enderror
        </div>

        <!-- Logo -->
        <div>
          <label class="payment-edit-label">Logo <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
          <div class="d-flex align-items-center gap-3">
            <div class="payment-edit-preview">
              @if($method->logo)
                <img id="logoPreviewImg" src="{{ asset($method->logo) }}" alt="{{ $method->name }} logo">
                <i id="logoPreviewIcon" class="fa-solid fa-image"></i>
              @else
                <img id="logoPreviewImg" src="" alt="Logo preview" style="display:none;">
                <i id="logoPreviewIcon" class="fa-solid fa-image" style="display:block;"></i>
              @endif
            </div>
            <div class="flex-grow-1">
              <input type="file" name="logo" id="logoInput" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                     class="payment-edit-file {{ $errors->has('logo') ? 'payment-edit-input-error' : '' }}">
              <p class="payment-edit-hint">PNG, JPG, WEBP or SVG. Square images look best. Max 2MB. Uploading a new file will replace the current logo.</p>
              @if($method->logo)
                <div class="form-check mt-2">
                  <input type="checkbox" name="remove_logo" value="1" class="payment-edit-check" id="removeLogo">
                  <label for="removeLogo" class="text-danger small" style="font-size:0.75rem; margin-left:0.25rem;">Remove current logo</label>
                </div>
              @endif
            </div>
          </div>
          @error('logo')<p class="payment-edit-error-text">{{ $message }}</p>@enderror
        </div>

        <!-- Conditional Fields -->
        <div id="conditionalFields">

          <!-- Bank Fields -->
          <div id="bankFields" style="display: {{ old('type', $method->type) === 'bank' ? 'block' : 'none' }};">
            <hr class="payment-edit-divider">
            <h3 class="payment-edit-section-title">Bank Details</h3>
            <div class="tw-space-y-4 mt-2">
              <div class="tw-grid-cols-2">
                <div>
                  <label class="payment-edit-label">Account Holder</label>
                  <input type="text" name="account_title" value="{{ old('account_title', $method->account_title) }}"
                         class="payment-edit-input" placeholder="e.g. Look n Cook">
                </div>
                <div>
                  <label class="payment-edit-label">Account Number</label>
                  <input type="text" name="account_number" value="{{ old('account_number', $method->account_number) }}"
                         class="payment-edit-input" placeholder="e.g. 03493568403">
                </div>
                <div class="tw-grid-cols-2" style="grid-column: span 2;">
                  <label class="payment-edit-label">IBAN <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
                  <input type="text" name="iban" value="{{ old('iban', $method->iban) }}"
                         class="payment-edit-input" placeholder="e.g. PK48MEZNO0003OO112913065">
                </div>
              </div>
            </div>
          </div>

          <!-- Wallet Fields -->
          <div id="walletFields" style="display: {{ old('type', $method->type) === 'mobile_wallet' ? 'block' : 'none' }};">
            <hr class="payment-edit-divider">
            <h3 class="payment-edit-section-title">Mobile Wallet Details</h3>
            <div class="tw-space-y-4 mt-2">
              <div class="tw-grid-cols-2">
                <div>
                  <label class="payment-edit-label">Account Holder</label>
                  <input type="text" name="account_title" value="{{ old('account_title', $method->account_title) }}"
                         class="payment-edit-input" placeholder="e.g. Tasliman">
                </div>
                <div>
                  <label class="payment-edit-label">Account Number</label>
                  <input type="text" name="account_number" value="{{ old('account_number', $method->account_number) }}"
                         class="payment-edit-input" placeholder="e.g. 03493568403">
                </div>
                <div class="tw-grid-cols-2" style="grid-column: span 2;">
                  <label class="payment-edit-label">Deep Link <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
                  <input type="url" name="deep_link" value="{{ old('deep_link', $method->deep_link) }}"
                         class="payment-edit-input" placeholder="https://jazzcash.com.pk/pay/...">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Active -->
        <div class="d-flex align-items-center gap-2">
          <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $method->is_active) ? 'checked' : '' }} class="payment-edit-check">
          <label for="is_active" class="payment-edit-label m-0" style="font-weight:500;">Active</label>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-3 pt-2">
          <button type="submit" class="payment-edit-submit">
            <i class="fa-solid fa-check"></i> Update Method
          </button>
          <a href="{{ route('admin.payment-methods.index') }}" class="payment-edit-cancel">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const typeSelect = document.getElementById('typeSelect');
  const additionalFields = document.getElementById('additionalFields');
  const bankFields = document.getElementById('bankFields');
  const walletFields = document.getElementById('walletFields');

  function toggleFields() {
    const type = typeSelect.value;
    if (type) {
      additionalFields.style.display = 'block';
    } else {
      additionalFields.style.display = 'none';
      return;
    }
    bankFields.style.display = (type === 'bank') ? 'block' : 'none';
    walletFields.style.display = (type === 'mobile_wallet') ? 'block' : 'none';
  }

  typeSelect.addEventListener('change', toggleFields);
  toggleFields();

  const logoInput = document.getElementById('logoInput');
  const logoPreviewImg = document.getElementById('logoPreviewImg');
  const logoPreviewIcon = document.getElementById('logoPreviewIcon');

  logoInput.addEventListener('change', function() {
    const file = this.files && this.files[0];
    if (!file) {
      if (!logoPreviewImg.src || logoPreviewImg.src === '') {
        logoPreviewImg.style.display = 'none';
        logoPreviewIcon.style.display = 'block';
      }
      return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
      logoPreviewImg.src = e.target.result;
      logoPreviewImg.style.display = 'block';
      logoPreviewIcon.style.display = 'none';
    };
    reader.readAsDataURL(file);
  });
});
</script>
@endsection
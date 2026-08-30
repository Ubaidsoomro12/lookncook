@extends('admin.layouts.master')
@section('title', 'Create | Payments')

@section('content')
<style>
  .payment-form-back {
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
  .payment-form-back:hover {
    background: #e5e7eb;
    color: #6b7280;
  }
  .payment-form-card {
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 1.5rem;
    background: #fff;
  }
  .payment-form-label {
    display: block;
    font-weight: 500;
    font-size: 0.875rem;
    color: #374151;
    margin-bottom: 0.375rem;
  }
  .payment-form-input {
    width: 100%;
    padding: 0.625rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
    transition: all 0.2s;
    outline: none;
  }
  .payment-form-input:focus {
    border-color: #334155;
    box-shadow: 0 0 0 3px rgba(51,65,85,0.2);
  }
  .payment-form-input-error {
    border-color: #fca5a5;
  }
  .payment-form-select {
    width: 100%;
    padding: 0.625rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
    transition: all 0.2s;
    outline: none;
    background: #fff;
  }
  .payment-form-select:focus {
    border-color: #334155;
    box-shadow: 0 0 0 3px rgba(51,65,85,0.2);
  }
  .payment-form-file {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    width: 100%;
  }
  .payment-form-file::-webkit-file-upload-button {
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
  .payment-form-file::-webkit-file-upload-button:hover {
    background: #1e293b;
  }
  .payment-form-hint {
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 0.375rem;
  }
  .payment-form-error-text {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
  }
  .payment-form-error-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    margin-bottom: 1.25rem;
  }
  .payment-form-error-box ul {
    list-style: disc;
    padding-left: 1.25rem;
    margin: 0;
  }
  .payment-form-error-box ul li {
    margin-bottom: 0.125rem;
  }
  .payment-form-submit {
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
  .payment-form-submit:hover {
    opacity: 0.9;
    color: #fff;
  }
  .payment-form-cancel {
    padding: 0.625rem 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    font-weight: 500;
    background: #fff;
    transition: all 0.2s;
  }
  .payment-form-cancel:hover {
    background: #f9fafb;
    color: #6b7280;
  }
  .payment-form-check {
    width: 1rem;
    height: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.25rem;
    accent-color: #334155;
  }
  .payment-form-preview {
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
  .payment-form-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: none;
  }
  .payment-form-preview i {
    font-size: 1.25rem;
    color: #d1d5db;
  }
  .payment-form-divider {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 1rem 0;
  }
  .payment-form-section-title {
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
    <a href="{{ route('admin.payment-methods.index') }}" class="payment-form-back">
      <i class="fa-solid fa-arrow-left" style="font-size:0.875rem;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0" style="font-size:1.5rem;">Add Payment Method</h1>
  </div>

  @if($errors->any())
    <div class="payment-form-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="payment-form-card">
    <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Type Selection -->
      <div>
        <label class="payment-form-label">Select Payment Type <span class="text-danger">*</span></label>
        <select name="type" id="typeSelect" required class="payment-form-select {{ $errors->has('type') ? 'payment-form-input-error' : '' }}">
          <option value="">-- Choose a type --</option>
          <option value="cod">Cash on Delivery</option>
          <option value="mobile_wallet">Mobile Wallet</option>
          <option value="bank">Bank Transfer</option>
        </select>
        @error('type')<p class="payment-form-error-text">{{ $message }}</p>@enderror
      </div>

      <!-- Additional Fields -->
      <div id="additionalFields" style="display: none;" class="tw-space-y-5">

        <!-- Name -->
        <div>
          <label class="payment-form-label">Bank Name <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 class="payment-form-input {{ $errors->has('name') ? 'payment-form-input-error' : '' }}"
                 placeholder="e.g. EasyPaisa, Meezan Bank">
          @error('name')<p class="payment-form-error-text">{{ $message }}</p>@enderror
        </div>

        <!-- Logo -->
        <div>
          <label class="payment-form-label">Logo <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
          <div class="d-flex align-items-center gap-3">
            <div class="payment-form-preview">
              <img id="logoPreviewImg" src="" alt="Logo preview">
              <i id="logoPreviewIcon" class="fa-solid fa-image"></i>
            </div>
            <div class="flex-grow-1">
              <input type="file" name="logo" id="logoInput" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                     class="payment-form-file {{ $errors->has('logo') ? 'payment-form-input-error' : '' }}">
              <p class="payment-form-hint">PNG, JPG, WEBP or SVG. Square images look best. Max 2MB.</p>
            </div>
          </div>
          @error('logo')<p class="payment-form-error-text">{{ $message }}</p>@enderror
        </div>

        <!-- Conditional Fields -->
        <div id="conditionalFields">

          <!-- Bank Fields -->
          <div id="bankFields" style="display: none;">
            <hr class="payment-form-divider">
            <h3 class="payment-form-section-title">Bank Details</h3>
            <div class="tw-space-y-4 mt-2">
              <div class="tw-grid-cols-2">
                <div>
                  <label class="payment-form-label">Account Holder</label>
                  <input type="text" name="account_title" value="{{ old('account_title') }}"
                         class="payment-form-input" placeholder="e.g. Look n Cook">
                </div>
                <div>
                  <label class="payment-form-label">Account Number</label>
                  <input type="text" name="account_number" value="{{ old('account_number') }}"
                         class="payment-form-input" placeholder="e.g. 03493568403">
                </div>
                <div class="tw-grid-cols-2" style="grid-column: span 2;">
                  <label class="payment-form-label">IBAN <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
                  <input type="text" name="iban" value="{{ old('iban') }}"
                         class="payment-form-input" placeholder="e.g. PK48MEZNO0003OO112913065">
                </div>
              </div>
            </div>
          </div>

          <!-- Wallet Fields -->
          <div id="walletFields" style="display: none;">
            <hr class="payment-form-divider">
            <h3 class="payment-form-section-title">Mobile Wallet Details</h3>
            <div class="tw-space-y-4 mt-2">
              <div class="tw-grid-cols-2">
                <div>
                  <label class="payment-form-label">Account Holder</label>
                  <input type="text" name="account_title" value="{{ old('account_title') }}"
                         class="payment-form-input" placeholder="e.g. Tasliman">
                </div>
                <div>
                  <label class="payment-form-label">Account Number</label>
                  <input type="text" name="account_number" value="{{ old('account_number') }}"
                         class="payment-form-input" placeholder="e.g. 03493568403">
                </div>
                <div class="tw-grid-cols-2" style="grid-column: span 2;">
                  <label class="payment-form-label">Deep Link <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
                  <input type="url" name="deep_link" value="{{ old('deep_link') }}"
                         class="payment-form-input" placeholder="https://jazzcash.com.pk/pay/...">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Active -->
        <div class="d-flex align-items-center gap-2">
          <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="payment-form-check">
          <label for="is_active" class="payment-form-label m-0" style="font-weight:500;">Active</label>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-3 pt-2">
          <button type="submit" class="payment-form-submit">
            <i class="fa-solid fa-check"></i> Save Method
          </button>
          <a href="{{ route('admin.payment-methods.index') }}" class="payment-form-cancel">Cancel</a>
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
      logoPreviewImg.style.display = 'none';
      logoPreviewIcon.style.display = 'block';
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
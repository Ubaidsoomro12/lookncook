{{-- FILE: resources/views/admin/pages/riders/create.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Create | Riders')

@section('content')
<style>
  .rider-form-back {
    width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 12px; background: #fdf2f8; color: #ff2d7a; transition: all .2s;
  }
  .rider-form-back:hover { background: #fce7f3; color: #ff2d7a; }

  .rider-form-card {
    border: 1px solid #fce7f3; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px; background: #fff;
  }
  .rider-form-label { display: block; font-weight: 500; font-size: 14px; color: #374151; margin-bottom: 6px; }
  .rider-form-input {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none;
  }
  .rider-form-input:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .rider-form-input-error { border-color: #fca5a5; }
  .rider-form-textarea {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; resize: vertical; min-height: 80px;
  }
  .rider-form-textarea:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .rider-form-select {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; background: #fff;
  }
  .rider-form-select:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .rider-form-file {
    font-size: 14px; padding: 6px 12px; border-radius: 12px; border: 1px solid #e5e7eb; width: 100%;
  }
  .rider-form-file::-webkit-file-upload-button {
    background: #ff2d7a; color: #fff; border: none;
    padding: 8px 16px; border-radius: 12px; font-weight: 500; font-size: 14px;
    margin-right: 12px; cursor: pointer; transition: all .2s;
  }
  .rider-form-file::-webkit-file-upload-button:hover { background: #e01d65; }
  .rider-form-hint { font-size: 12px; color: #9ca3af; margin-top: 4px; }
  .rider-form-error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
  .rider-form-error-box {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    padding: 12px 16px; border-radius: 12px; font-size: 14px; margin-bottom: 20px;
  }
  .rider-form-error-box ul { list-style: disc; padding-left: 20px; margin: 0; }
  .rider-form-error-box ul li { margin-bottom: 2px; }
  .rider-form-submit {
    background: linear-gradient(to right, #ff2d7a, #ff6fa5); color: #fff; font-weight: 500;
    padding: 10px 24px; border-radius: 12px; border: none;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.2); transition: all .2s;
  }
  .rider-form-submit:hover { opacity: .9; color: #fff; }
  .rider-form-cancel {
    padding: 10px 24px; border-radius: 12px; border: 1px solid #e5e7eb;
    color: #6b7280; font-weight: 500; background: #fff; transition: all .2s;
  }
  .rider-form-cancel:hover { background: #f9fafb; color: #6b7280; }

  .rider-form-preview {
    width: 80px; height: 80px; border-radius: 50%; border: 2px solid #fbcfe8;
    background: #fdf2f8; display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
  }
  .rider-form-preview img { width: 100%; height: 100%; object-fit: cover; display: none; }
  .rider-form-preview i { font-size: 24px; color: #fbcfe8; }

  .rider-form-divider { border: none; border-top: 1px solid #fdf2f8; margin: 20px 0; }
  .rider-form-section-title {
    font-size: 13px; font-weight: 600; color: #ff2d7a; text-transform: uppercase; letter-spacing: 0.05em;
  }

  .tw-max-w-3xl { max-width: 768px; margin: 0 auto; }
  .tw-space-y-5 > * + * { margin-top: 20px; }
  .tw-grid-cols-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  @media (max-width: 768px) { .tw-grid-cols-2 { grid-template-columns: 1fr; } }

  .rider-form-check { width: 16px; height: 16px; border: 1px solid #e5e7eb; border-radius: 4px; accent-color: #ff2d7a; }
</style>

<div class="tw-max-w-3xl px-3 px-sm-4">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.riders.index') }}" class="rider-form-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0" style="font-size:24px;">Add Rider</h1>
  </div>

  @if($errors->any())
    <div class="rider-form-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="rider-form-card">
    <form action="{{ route('admin.riders.store') }}" method="POST" enctype="multipart/form-data" class="tw-space-y-5">
      @csrf

      <!-- Photo -->
      <div>
        <label class="rider-form-label">Rider Photo <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
        <div class="d-flex align-items-center gap-3">
          <div class="rider-form-preview">
            <img id="imagePreviewImg" src="" alt="Preview">
            <i id="imagePreviewIcon" class="fa-solid fa-user"></i>
          </div>
          <div class="flex-grow-1">
            <input type="file" name="image" id="imageInput" accept="image/png,image/jpeg,image/jpg,image/webp"
                   class="rider-form-file {{ $errors->has('image') ? 'rider-form-input-error' : '' }}">
            <p class="rider-form-hint">PNG, JPG or WEBP. Square photo looks best. Max 2MB.</p>
          </div>
        </div>
        @error('image')<p class="rider-form-error-text">{{ $message }}</p>@enderror
      </div>

      <hr class="rider-form-divider">
      <h3 class="rider-form-section-title">Basic Information</h3>

      <div class="tw-grid-cols-2">
        <div>
          <label class="rider-form-label">Full Name <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 class="rider-form-input {{ $errors->has('name') ? 'rider-form-input-error' : '' }}"
                 placeholder="e.g. Ahmed Khan">
          @error('name')<p class="rider-form-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-form-label">Phone Number <span class="text-danger">*</span></label>
          <input type="text" name="phone" value="{{ old('phone') }}" required
                 class="rider-form-input {{ $errors->has('phone') ? 'rider-form-input-error' : '' }}"
                 placeholder="e.g. 03001234567">
          @error('phone')<p class="rider-form-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-form-label">Email <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
          <input type="email" name="email" value="{{ old('email') }}"
                 class="rider-form-input {{ $errors->has('email') ? 'rider-form-input-error' : '' }}"
                 placeholder="e.g. ahmed@example.com">
          @error('email')<p class="rider-form-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-form-label">City</label>
          <input type="text" name="city" value="{{ old('city') }}"
                 class="rider-form-input" placeholder="e.g. Karachi">
        </div>
        <div style="grid-column: span 2;">
          <label class="rider-form-label">Address <span class="text-danger">*</span></label>
          <textarea name="address" required rows="2"
                    class="rider-form-textarea {{ $errors->has('address') ? 'rider-form-input-error' : '' }}"
                    placeholder="Enter full address">{{ old('address') }}</textarea>
          @error('address')<p class="rider-form-error-text">{{ $message }}</p>@enderror
        </div>
      </div>

      <hr class="rider-form-divider">
      <h3 class="rider-form-section-title">Vehicle Details</h3>

      <div class="tw-grid-cols-2">
        <div>
          <label class="rider-form-label">Vehicle Type <span class="text-danger">*</span></label>
          <select name="vehicle_type" required class="rider-form-select {{ $errors->has('vehicle_type') ? 'rider-form-input-error' : '' }}">
            <option value="bike" {{ old('vehicle_type') === 'bike' ? 'selected' : '' }}>🏍️ Bike</option>
            <option value="car" {{ old('vehicle_type') === 'car' ? 'selected' : '' }}>🚗 Car</option>
            <option value="van" {{ old('vehicle_type') === 'van' ? 'selected' : '' }}>🚐 Van</option>
            <option value="bicycle" {{ old('vehicle_type') === 'bicycle' ? 'selected' : '' }}>🚲 Bicycle</option>
          </select>
          @error('vehicle_type')<p class="rider-form-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-form-label">Vehicle Number / Plate</label>
          <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}"
                 class="rider-form-input" placeholder="e.g. KHI-1234">
        </div>
        <div>
          <label class="rider-form-label">License Number</label>
          <input type="text" name="license_number" value="{{ old('license_number') }}"
                 class="rider-form-input" placeholder="Driving license #">
        </div>
        <div>
          <label class="rider-form-label">CNIC</label>
          <input type="text" name="cnic" value="{{ old('cnic') }}"
                 class="rider-form-input" placeholder="e.g. 42101-1234567-1">
        </div>
      </div>

      <hr class="rider-form-divider">
      <h3 class="rider-form-section-title">Other Details</h3>

      <div class="tw-grid-cols-2">
        <div>
          <label class="rider-form-label">Emergency Contact</label>
          <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                 class="rider-form-input" placeholder="Alternate contact number">
        </div>
        <div>
          <label class="rider-form-label">Joining Date</label>
          <input type="date" name="joining_date" value="{{ old('joining_date') }}"
                 class="rider-form-input">
        </div>
        <div style="grid-column: span 2;">
          <label class="rider-form-label">Notes</label>
          <textarea name="notes" rows="3"
                    class="rider-form-textarea"
                    placeholder="Any additional notes about this rider">{{ old('notes') }}</textarea>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rider-form-check">
        <label for="is_active" class="rider-form-label m-0" style="font-weight:500;">Active</label>
      </div>

      <div class="d-flex gap-3 pt-2">
        <button type="submit" class="rider-form-submit">
          <i class="fa-solid fa-check"></i> Save Rider
        </button>
        <a href="{{ route('admin.riders.index') }}" class="rider-form-cancel">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imagePreviewImg = document.getElementById('imagePreviewImg');
    const imagePreviewIcon = document.getElementById('imagePreviewIcon');

    imageInput.addEventListener('change', function() {
        const file = this.files && this.files[0];
        if (!file) {
            imagePreviewImg.style.display = 'none';
            imagePreviewIcon.style.display = 'block';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreviewImg.src = e.target.result;
            imagePreviewImg.style.display = 'block';
            imagePreviewIcon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
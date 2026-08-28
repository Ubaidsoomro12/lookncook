{{-- FILE: resources/views/admin/pages/riders/edit.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Edit | Riders')

@section('content')
<style>
  .rider-edit-back {
    width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 12px; background: #fdf2f8; color: #ff2d7a; transition: all .2s;
  }
  .rider-edit-back:hover { background: #fce7f3; color: #ff2d7a; }

  .rider-edit-card {
    border: 1px solid #fce7f3; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px; background: #fff;
  }
  .rider-edit-label { display: block; font-weight: 500; font-size: 14px; color: #374151; margin-bottom: 6px; }
  .rider-edit-input {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none;
  }
  .rider-edit-input:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .rider-edit-input-error { border-color: #fca5a5; }
  .rider-edit-textarea {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; resize: vertical; min-height: 80px;
  }
  .rider-edit-textarea:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .rider-edit-select {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; background: #fff;
  }
  .rider-edit-select:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .rider-edit-file {
    font-size: 14px; padding: 6px 12px; border-radius: 12px; border: 1px solid #e5e7eb; width: 100%;
  }
  .rider-edit-file::-webkit-file-upload-button {
    background: #ff2d7a; color: #fff; border: none;
    padding: 8px 16px; border-radius: 12px; font-weight: 500; font-size: 14px;
    margin-right: 12px; cursor: pointer; transition: all .2s;
  }
  .rider-edit-file::-webkit-file-upload-button:hover { background: #e01d65; }
  .rider-edit-hint { font-size: 12px; color: #9ca3af; margin-top: 4px; }
  .rider-edit-error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
  .rider-edit-error-box {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    padding: 12px 16px; border-radius: 12px; font-size: 14px; margin-bottom: 20px;
  }
  .rider-edit-error-box ul { list-style: disc; padding-left: 20px; margin: 0; }
  .rider-edit-error-box ul li { margin-bottom: 2px; }
  .rider-edit-submit {
    background: linear-gradient(to right, #ff2d7a, #ff6fa5); color: #fff; font-weight: 500;
    padding: 10px 24px; border-radius: 12px; border: none;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.2); transition: all .2s;
  }
  .rider-edit-submit:hover { opacity: .9; color: #fff; }
  .rider-edit-cancel {
    padding: 10px 24px; border-radius: 12px; border: 1px solid #e5e7eb;
    color: #6b7280; font-weight: 500; background: #fff; transition: all .2s;
  }
  .rider-edit-cancel:hover { background: #f9fafb; color: #6b7280; }

  .rider-edit-preview {
    width: 80px; height: 80px; border-radius: 50%; border: 2px solid #fbcfe8;
    background: #fdf2f8; display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
  }
  .rider-edit-preview img { width: 100%; height: 100%; object-fit: cover; }
  .rider-edit-preview i { font-size: 24px; color: #fbcfe8; display: none; }

  .rider-edit-divider { border: none; border-top: 1px solid #fdf2f8; margin: 20px 0; }
  .rider-edit-section-title {
    font-size: 13px; font-weight: 600; color: #ff2d7a; text-transform: uppercase; letter-spacing: 0.05em;
  }
  .rider-edit-check { width: 16px; height: 16px; border: 1px solid #e5e7eb; border-radius: 4px; accent-color: #ff2d7a; }

  .tw-max-w-3xl { max-width: 768px; margin: 0 auto; }
  .tw-space-y-5 > * + * { margin-top: 20px; }
  .tw-grid-cols-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  @media (max-width: 768px) { .tw-grid-cols-2 { grid-template-columns: 1fr; } }
</style>

<div class="tw-max-w-3xl px-3 px-sm-4">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.riders.index') }}" class="rider-edit-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0" style="font-size:24px;">Edit Rider</h1>
  </div>

  @if($errors->any())
    <div class="rider-edit-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="rider-edit-card">
    <form action="{{ route('admin.riders.update', $rider->id) }}" method="POST" enctype="multipart/form-data" class="tw-space-y-5">
      @csrf
      @method('PUT')

      <!-- Photo -->
      <div>
        <label class="rider-edit-label">Rider Photo <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
        <div class="d-flex align-items-center gap-3">
          <div class="rider-edit-preview">
            @if($rider->image)
              <img id="imagePreviewImg" src="{{ asset($rider->image) }}" alt="{{ $rider->name }}">
              <i id="imagePreviewIcon" class="fa-solid fa-user"></i>
            @else
              <img id="imagePreviewImg" src="" alt="Preview" style="display:none;">
              <i id="imagePreviewIcon" class="fa-solid fa-user" style="display:block;"></i>
            @endif
          </div>
          <div class="flex-grow-1">
            <input type="file" name="image" id="imageInput" accept="image/png,image/jpeg,image/jpg,image/webp"
                   class="rider-edit-file {{ $errors->has('image') ? 'rider-edit-input-error' : '' }}">
            <p class="rider-edit-hint">
              PNG, JPG or WEBP. Max 2MB.
              @if($rider->image) Uploading a new file will replace the current photo. @endif
            </p>
            @if($rider->image)
              <div class="form-check mt-2">
                <input type="checkbox" name="remove_image" value="1" class="rider-edit-check" id="removeImage">
                <label for="removeImage" class="text-danger small" style="font-size:12px; margin-left:4px;">Remove current photo</label>
              </div>
            @endif
          </div>
        </div>
        @error('image')<p class="rider-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <hr class="rider-edit-divider">
      <h3 class="rider-edit-section-title">Basic Information</h3>

      <div class="tw-grid-cols-2">
        <div>
          <label class="rider-edit-label">Full Name <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name', $rider->name) }}" required
                 class="rider-edit-input {{ $errors->has('name') ? 'rider-edit-input-error' : '' }}">
          @error('name')<p class="rider-edit-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-edit-label">Phone Number <span class="text-danger">*</span></label>
          <input type="text" name="phone" value="{{ old('phone', $rider->phone) }}" required
                 class="rider-edit-input {{ $errors->has('phone') ? 'rider-edit-input-error' : '' }}">
          @error('phone')<p class="rider-edit-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-edit-label">Email <span class="text-secondary" style="font-weight:400;">(optional)</span></label>
          <input type="email" name="email" value="{{ old('email', $rider->email) }}"
                 class="rider-edit-input {{ $errors->has('email') ? 'rider-edit-input-error' : '' }}">
          @error('email')<p class="rider-edit-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-edit-label">City</label>
          <input type="text" name="city" value="{{ old('city', $rider->city) }}"
                 class="rider-edit-input">
        </div>
        <div style="grid-column: span 2;">
          <label class="rider-edit-label">Address <span class="text-danger">*</span></label>
          <textarea name="address" required rows="2"
                    class="rider-edit-textarea {{ $errors->has('address') ? 'rider-edit-input-error' : '' }}">{{ old('address', $rider->address) }}</textarea>
          @error('address')<p class="rider-edit-error-text">{{ $message }}</p>@enderror
        </div>
      </div>

      <hr class="rider-edit-divider">
      <h3 class="rider-edit-section-title">Vehicle Details</h3>

      <div class="tw-grid-cols-2">
        <div>
          <label class="rider-edit-label">Vehicle Type <span class="text-danger">*</span></label>
          <select name="vehicle_type" required class="rider-edit-select {{ $errors->has('vehicle_type') ? 'rider-edit-input-error' : '' }}">
            <option value="bike" {{ old('vehicle_type', $rider->vehicle_type) === 'bike' ? 'selected' : '' }}>🏍️ Bike</option>
            <option value="car" {{ old('vehicle_type', $rider->vehicle_type) === 'car' ? 'selected' : '' }}>🚗 Car</option>
            <option value="van" {{ old('vehicle_type', $rider->vehicle_type) === 'van' ? 'selected' : '' }}>🚐 Van</option>
            <option value="bicycle" {{ old('vehicle_type', $rider->vehicle_type) === 'bicycle' ? 'selected' : '' }}>🚲 Bicycle</option>
          </select>
          @error('vehicle_type')<p class="rider-edit-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="rider-edit-label">Vehicle Number / Plate</label>
          <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $rider->vehicle_number) }}"
                 class="rider-edit-input">
        </div>
        <div>
          <label class="rider-edit-label">License Number</label>
          <input type="text" name="license_number" value="{{ old('license_number', $rider->license_number) }}"
                 class="rider-edit-input">
        </div>
        <div>
          <label class="rider-edit-label">CNIC</label>
          <input type="text" name="cnic" value="{{ old('cnic', $rider->cnic) }}"
                 class="rider-edit-input">
        </div>
      </div>

      <hr class="rider-edit-divider">
      <h3 class="rider-edit-section-title">Other Details</h3>

      <div class="tw-grid-cols-2">
        <div>
          <label class="rider-edit-label">Emergency Contact</label>
          <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $rider->emergency_contact) }}"
                 class="rider-edit-input">
        </div>
        <div>
          <label class="rider-edit-label">Joining Date</label>
          <input type="date" name="joining_date" value="{{ old('joining_date', $rider->joining_date?->format('Y-m-d')) }}"
                 class="rider-edit-input">
        </div>
        <div style="grid-column: span 2;">
          <label class="rider-edit-label">Notes</label>
          <textarea name="notes" rows="3"
                    class="rider-edit-textarea">{{ old('notes', $rider->notes) }}</textarea>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $rider->is_active) ? 'checked' : '' }} class="rider-edit-check">
        <label for="is_active" class="rider-edit-label m-0" style="font-weight:500;">Active</label>
      </div>

      <div class="d-flex gap-3 pt-2">
        <button type="submit" class="rider-edit-submit">
          <i class="fa-solid fa-check"></i> Update Rider
        </button>
        <a href="{{ route('admin.riders.index') }}" class="rider-edit-cancel">Cancel</a>
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
            if (!imagePreviewImg.src || imagePreviewImg.src === '') {
                imagePreviewImg.style.display = 'none';
                imagePreviewIcon.style.display = 'block';
            }
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
@extends('admin.layouts.master')

@section('content')
<style>
  .banner-form-back {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #fdf2f8;
    color: #ff2d7a;
    transition: all 0.2s;
  }
  .banner-form-back:hover {
    background: #fce7f3;
    color: #ff2d7a;
  }
  .banner-form-card {
    border: 1px solid #fce7f3;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px;
    background: #fff;
  }
  .banner-form-label {
    display: block;
    font-weight: 500;
    font-size: 14px;
    color: #374151;
    margin-bottom: 6px;
  }
  .banner-form-input {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
  }
  .banner-form-input:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.2);
  }
  .banner-form-select {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
    background: #fff;
  }
  .banner-form-select:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.2);
  }
  .banner-form-textarea {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
    resize: vertical;
    min-height: 80px;
  }
  .banner-form-textarea:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.2);
  }
  .banner-form-preview {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    border: 2px dashed #fce7e8;
    background: #fdf2f8;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
  }
  .banner-form-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: none;
  }
  .banner-form-file {
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    width: 100%;
  }
  .banner-form-file::-webkit-file-upload-button {
    background: #ff2d7a;
    color: #fff;
    border: none;
    padding: 6px 16px;
    border-radius: 12px;
    font-weight: 500;
    font-size: 14px;
    margin-right: 12px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .banner-form-file::-webkit-file-upload-button:hover {
    background: #e01d65;
  }
  .banner-form-hint {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 6px;
  }
  .banner-form-check {
    width: 16px;
    height: 16px;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    accent-color: #ff2d7a;
  }
  .banner-form-submit {
    background: linear-gradient(to right, #ff2d7a, #ff4b91);
    color: #fff;
    font-weight: 500;
    padding: 10px 24px;
    border-radius: 12px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.2);
    transition: all 0.2s;
  }
  .banner-form-submit:hover {
    opacity: 0.9;
    color: #fff;
  }
  .banner-form-cancel {
    padding: 10px 24px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    font-weight: 500;
    background: #fff;
    transition: all 0.2s;
  }
  .banner-form-cancel:hover {
    background: #f9fafb;
    color: #6b7280;
  }
  .banner-form-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    margin-bottom: 20px;
  }
  .banner-form-error ul {
    list-style: disc;
    padding-left: 20px;
    margin: 0;
  }
</style>

<div class="container-fluid" style="max-width:672px; margin:0 auto;">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.banners.index') }}" class="banner-form-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0">Add Banner</h1>
  </div>

  @if($errors->any())
    <div class="banner-form-error">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="banner-form-card">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="banner-form-label">Section <span class="text-danger">*</span></label>
        <select name="section" required class="banner-form-select">
          <option value="hero" {{ old('section') == 'hero' ? 'selected' : '' }}>Hero (Top)</option>
          <option value="section_banner" {{ old('section') == 'section_banner' ? 'selected' : '' }}>Section Banner (Quality)</option>
          <option value="footer" {{ old('section') == 'footer' ? 'selected' : '' }}>Footer</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="banner-form-label">Title</label>
        <input type="text" name="title" value="{{ old('title') }}" class="banner-form-input" placeholder="Enter banner title">
      </div>

      <div class="mb-3">
        <label class="banner-form-label">Subtitle</label>
        <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="banner-form-input" placeholder="Enter subtitle">
      </div>

      <div class="mb-3">
        <label class="banner-form-label">Description</label>
        <textarea name="description" rows="3" class="banner-form-textarea">{{ old('description') }}</textarea>
      </div>

      <div class="mb-3">
        <label class="banner-form-label">Link (optional)</label>
        <input type="url" name="link" value="{{ old('link') }}" class="banner-form-input" placeholder="https://example.com">
      </div>

      <div class="mb-3">
        <label class="banner-form-label">Button Text</label>
        <input type="text" name="button_text" value="{{ old('button_text') }}" class="banner-form-input" placeholder="e.g. Order Now">
      </div>

      <div class="mb-3">
        <label class="banner-form-label">Image <span class="text-danger">*</span></label>
        <div class="d-flex align-items-center gap-3">
          <div class="banner-form-preview">
            <img id="imagePreviewImg" src="" alt="Preview">
            <i id="imagePreviewIcon" class="fa-solid fa-image" style="font-size:24px; color:#fbcfe8;"></i>
          </div>
          <div class="flex-grow-1">
            <input type="file" name="image" id="imageInput" accept="image/*" required class="banner-form-file">
            <p class="banner-form-hint">JPG, PNG, WEBP. Max 42MB.</p>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label class="banner-form-label">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="banner-form-input">
      </div>

      <div class="mb-3 d-flex align-items-center gap-2">
        <input type="checkbox" name="status" id="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="banner-form-check">
        <label for="status" class="banner-form-label m-0">Active</label>
      </div>

      <div class="d-flex gap-3 pt-2">
        <button type="submit" class="banner-form-submit">
          <i class="fa-solid fa-check"></i> Create Banner
        </button>
        <a href="{{ route('admin.banners.index') }}" class="banner-form-cancel">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const imageInput = document.getElementById('imageInput');
  const previewImg = document.getElementById('imagePreviewImg');
  const previewIcon = document.getElementById('imagePreviewIcon');

  imageInput.addEventListener('change', function() {
    const file = this.files && this.files[0];
    if (!file) {
      previewImg.style.display = 'none';
      previewIcon.style.display = 'block';
      return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
      previewImg.src = e.target.result;
      previewImg.style.display = 'block';
      previewIcon.style.display = 'none';
    };
    reader.readAsDataURL(file);
  });
});
</script>
@endsection
@extends('admin.layouts.master')
@section('title', 'Create | Categories')

@section('content')
<style>
  .cat-form-back {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #f3f4f6;
    color: #6b7280;
    transition: all 0.2s;
  }
  .cat-form-back:hover {
    background: #e5e7eb;
    color: #6b7280;
  }
  .cat-form-card {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px;
    background: #fff;
  }
  .cat-form-label {
    display: block;
    font-weight: 500;
    font-size: 14px;
    color: #374151;
    margin-bottom: 6px;
  }
  .cat-form-input {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
  }
  .cat-form-input:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.3);
  }
  .cat-form-input-error {
    border-color: #fca5a5;
  }
  .cat-form-textarea {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
    resize: vertical;
    min-height: 100px;
  }
  .cat-form-textarea:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.3);
  }
  .cat-form-select {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
    background: #fff;
  }
  .cat-form-select:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.3);
  }
  .cat-form-file {
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    width: 100%;
  }
  .cat-form-file::-webkit-file-upload-button {
    background: rgba(255,45,122,0.1);
    color: #ff2d7a;
    border: none;
    padding: 8px 16px;
    border-radius: 12px;
    font-weight: 500;
    font-size: 14px;
    margin-right: 12px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .cat-form-file::-webkit-file-upload-button:hover {
    background: rgba(255,45,122,0.2);
  }
  .cat-form-hint {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
  }
  .cat-form-error-text {
    color: #dc2626;
    font-size: 12px;
    margin-top: 4px;
  }
  .cat-form-error-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    margin-bottom: 20px;
  }
  .cat-form-error-box ul {
    list-style: disc;
    padding-left: 20px;
    margin: 0;
  }
  .cat-form-submit {
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
  .cat-form-submit:hover {
    opacity: 0.9;
    color: #fff;
  }
  .cat-form-cancel {
    padding: 10px 24px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    font-weight: 500;
    background: #fff;
    transition: all 0.2s;
  }
  .cat-form-cancel:hover {
    background: #f9fafb;
    color: #6b7280;
  }
</style>

<div class="container-fluid" style="max-width:672px; margin:0 auto;">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.categories.index') }}" class="cat-form-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0">Add New Category</h1>
  </div>

  @if($errors->any())
    <div class="cat-form-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="cat-form-card">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="cat-form-label">Category Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="cat-form-input {{ $errors->has('name') ? 'cat-form-input-error' : '' }}"
               placeholder="e.g. Fast Food">
        @error('name')<p class="cat-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="mb-3">
        <label class="cat-form-label">Description <span class="text-danger">*</span></label>
        <textarea name="description" rows="4" required
                  class="cat-form-textarea {{ $errors->has('description') ? 'cat-form-input-error' : '' }}"
                  placeholder="Short description about this category (min 10 characters)...">{{ old('description') }}</textarea>
        @error('description')<p class="cat-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="mb-3">
        <label class="cat-form-label">Category Image <span class="text-danger">*</span></label>
        <input type="file" name="image" accept="image/*" required class="cat-form-file">
        <p class="cat-form-hint">JPG, PNG or WEBP. Max 4MB.</p>
        @error('image')<p class="cat-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="mb-3">
        <label class="cat-form-label">Status <span class="text-danger">*</span></label>
        <select name="status" required class="cat-form-select {{ $errors->has('status') ? 'cat-form-input-error' : '' }}">
          <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select status</option>
          <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<p class="cat-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="d-flex gap-3 pt-2">
        <button type="submit" class="cat-form-submit">
          <i class="fa-solid fa-check"></i> Save Category
        </button>
        <a href="{{ route('admin.categories.index') }}" class="cat-form-cancel">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
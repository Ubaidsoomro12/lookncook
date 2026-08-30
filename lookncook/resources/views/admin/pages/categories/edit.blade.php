@extends('admin.layouts.master')
@section('title', 'Edit | Categories')

@section('content')
<style>
  .cat-edit-back {
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
  .cat-edit-back:hover {
    background: #e5e7eb;
    color: #6b7280;
  }
  .cat-edit-card {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px;
    background: #fff;
  }
  .cat-edit-label {
    display: block;
    font-weight: 500;
    font-size: 14px;
    color: #374151;
    margin-bottom: 6px;
  }
  .cat-edit-input {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
  }
  .cat-edit-input:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.3);
  }
  .cat-edit-input-error {
    border-color: #fca5a5;
  }
  .cat-edit-textarea {
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
  .cat-edit-textarea:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.3);
  }
  .cat-edit-select {
    width: 100%;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
    background: #fff;
  }
  .cat-edit-select:focus {
    border-color: #ff2d7a;
    box-shadow: 0 0 0 3px rgba(255,45,122,0.3);
  }
  .cat-edit-file {
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    width: 100%;
  }
  .cat-edit-file::-webkit-file-upload-button {
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
  .cat-edit-file::-webkit-file-upload-button:hover {
    background: rgba(255,45,122,0.2);
  }
  .cat-edit-hint {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
  }
  .cat-edit-error-text {
    color: #dc2626;
    font-size: 12px;
    margin-top: 4px;
  }
  .cat-edit-error-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    margin-bottom: 20px;
  }
  .cat-edit-error-box ul {
    list-style: disc;
    padding-left: 20px;
    margin: 0;
  }
  .cat-edit-img-preview {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
    margin-bottom: 12px;
  }
  .cat-edit-submit {
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
  .cat-edit-submit:hover {
    opacity: 0.9;
    color: #fff;
  }
  .cat-edit-cancel {
    padding: 10px 24px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    font-weight: 500;
    background: #fff;
    transition: all 0.2s;
  }
  .cat-edit-cancel:hover {
    background: #f9fafb;
    color: #6b7280;
  }
</style>

<div class="container-fluid" style="max-width:672px; margin:0 auto;">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.categories.index') }}" class="cat-edit-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0">Edit Category</h1>
  </div>

  @if($errors->any())
    <div class="cat-edit-error-box">
      <p class="fw-semibold mb-1">Please fix the following:</p>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="cat-edit-card">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="cat-edit-label">Category Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
               class="cat-edit-input {{ $errors->has('name') ? 'cat-edit-input-error' : '' }}">
        @error('name')<p class="cat-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="mb-3">
        <label class="cat-edit-label">Description <span class="text-danger">*</span></label>
        <textarea name="description" rows="4" required
                  class="cat-edit-textarea {{ $errors->has('description') ? 'cat-edit-input-error' : '' }}">{{ old('description', $category->description) }}</textarea>
        @error('description')<p class="cat-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="mb-3">
        <label class="cat-edit-label">Category Image</label>
        @if($category->image)
          <div>
            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="cat-edit-img-preview">
          </div>
        @endif
        <input type="file" name="image" accept="image/*" class="cat-edit-file">
        <p class="cat-edit-hint">Leave empty to keep the current image.</p>
        @error('image')<p class="cat-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="mb-3">
        <label class="cat-edit-label">Status <span class="text-danger">*</span></label>
        <select name="status" required class="cat-edit-select {{ $errors->has('status') ? 'cat-edit-input-error' : '' }}">
          <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<p class="cat-edit-error-text">{{ $message }}</p>@enderror
      </div>

      <div class="d-flex gap-3 pt-2">
        <button type="submit" class="cat-edit-submit">
          <i class="fa-solid fa-check"></i> Update Category
        </button>
        <a href="{{ route('admin.categories.index') }}" class="cat-edit-cancel">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
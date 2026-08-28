@extends('admin.layouts.master')
@section('title', 'Create Users')

@section('content')
<style>
  .user-form-back {
    width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 12px; background: #fdf2f8; color: #ff2d7a; transition: all .2s;
  }
  .user-form-back:hover { background: #fce7f3; color: #ff2d7a; }

  .user-form-card {
    border: 1px solid #fce7f3; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px; background: #fff;
  }
  .user-form-label { display: block; font-weight: 500; font-size: 14px; color: #374151; margin-bottom: 6px; }
  .user-form-input {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none;
  }
  .user-form-input:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .user-form-select {
    width: 100%; padding: 10px 16px; border-radius: 12px; border: 1px solid #e5e7eb;
    font-size: 14px; transition: all .2s; outline: none; background: #fff;
  }
  .user-form-select:focus { border-color: #ff2d7a; box-shadow: 0 0 0 3px rgba(255,45,122,0.2); }
  .user-form-error-text { color: #dc2626; font-size: 12px; margin-top: 4px; }
  .user-form-error-box {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    padding: 12px 16px; border-radius: 12px; font-size: 14px; margin-bottom: 20px;
  }
  .user-form-error-box ul { list-style: disc; padding-left: 20px; margin: 0; }
  .user-form-error-box ul li { margin-bottom: 2px; }
  .user-form-submit {
    background: linear-gradient(to right, #ff2d7a, #ff4b91); color: #fff; font-weight: 500;
    padding: 10px 24px; border-radius: 12px; border: none;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 12px rgba(255,45,122,0.2); transition: all .2s;
  }
  .user-form-submit:hover { opacity: .9; color: #fff; }
  .user-form-cancel {
    padding: 10px 24px; border-radius: 12px; border: 1px solid #e5e7eb;
    color: #6b7280; font-weight: 500; background: #fff; transition: all .2s;
  }
  .user-form-cancel:hover { background: #f9fafb; color: #6b7280; }

  .tw-max-w-2xl { max-width: 672px; margin: 0 auto; }
  .tw-grid-cols-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  @media (max-width: 768px) { .tw-grid-cols-2 { grid-template-columns: 1fr; } }
</style>

<div class="tw-max-w-2xl px-3 px-sm-4">

  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}" class="user-form-back">
      <i class="fa-solid fa-arrow-left" style="font-size:14px;"></i>
    </a>
    <h1 class="h3 fw-bold text-dark m-0" style="font-size:24px;">Add User</h1>
  </div>

  @if($errors->any())
    <div class="user-form-error-box">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="user-form-card">
    <form action="{{ route('admin.users.store') }}" method="POST" class="tw-grid-cols-2">
      @csrf

      <div>
        <label class="user-form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" required class="user-form-input">
        @error('name')<p class="user-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="user-form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" value="{{ old('email') }}" required class="user-form-input">
        @error('email')<p class="user-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="user-form-label">Phone <span class="text-danger">*</span></label>
        <input type="text" name="phone" value="{{ old('phone') }}" required class="user-form-input">
        @error('phone')<p class="user-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="user-form-label">City</label>
        <input type="text" name="city" value="{{ old('city') }}" class="user-form-input">
      </div>

      <div>
        <label class="user-form-label">Role <span class="text-danger">*</span></label>
        <select name="role_id" required class="user-form-select">
          <option value="">Select Role</option>
          <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Admin</option>
          <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>User</option>
          <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Manager</option>
        </select>
        @error('role_id')<p class="user-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="user-form-label">Password <span class="text-danger">*</span></label>
        <input type="password" name="password" required class="user-form-input">
        @error('password')<p class="user-form-error-text">{{ $message }}</p>@enderror
      </div>

      <div style="grid-column: span 2;">
        <label class="user-form-label">Confirm Password <span class="text-danger">*</span></label>
        <input type="password" name="password_confirmation" required class="user-form-input">
      </div>

      <div style="grid-column: span 2;" class="d-flex gap-3 pt-2">
        <button type="submit" class="user-form-submit">
          <i class="fa-solid fa-check"></i> Create User
        </button>
        <a href="{{ route('admin.users.index') }}" class="user-form-cancel">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
@extends('layouts.master')
@section('title', 'My Profile')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Welcome Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <span class="avatar-text">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            @php
                                $roleNames = [
                                    1 => 'Admin',
                                    2 => 'User',
                                    3 => 'Manager',
                                    4 => 'Waiter',
                                    5 => 'Chef',
                                    6 => 'Cashier',
                                    7 => 'Cleaner',
                                    8 => 'Delivery Rider'
                                ];
                                $userRole = $roleNames[Auth::user()->role_id] ?? 'Guest';
                            @endphp
                            <h4 class="mb-0">Welcome, {{ Auth::user()->name }}!</h4>
                            <small class="text-light">
                                <i class="fas fa-user-tag me-1"></i>
                                {{ $userRole }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alert sections with auto-dismiss -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show auto-dismiss" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-user me-1 text-primary"></i> Full Name
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', Auth::user()->name) }}" 
                                       placeholder="Enter your full name">
                                <small class="text-muted">Leave blank to keep current</small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-1 text-primary"></i> Email Address
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', Auth::user()->email) }}" 
                                       placeholder="Enter your email">
                                <small class="text-muted">Leave blank to keep current</small>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold">
                                    <i class="fas fa-phone me-1 text-primary"></i> Phone Number
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', Auth::user()->phone) }}" 
                                       placeholder="Enter your phone number">
                                <small class="text-muted">Leave blank to keep current</small>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- City -->
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label fw-bold">
                                    <i class="fas fa-city me-1 text-primary"></i> City
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('city') is-invalid @enderror" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', Auth::user()->city) }}" 
                                       placeholder="Enter your city">
                                <small class="text-muted">Leave blank to keep current</small>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-1 text-primary"></i> New Password
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter new password (optional)">
                                <small class="text-muted">Leave blank to keep current password</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">
                                    <i class="fas fa-check-circle me-1 text-primary"></i> Confirm Password
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirm new password">
                                <small class="text-muted">Re-enter password to confirm</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i> Update Profile
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="info-box">
                                <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                <h6>Member Since</h6>
                                <p class="text-muted">{{ Auth::user()->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <i class="fas fa-user-shield fa-2x text-primary mb-2"></i>
                                <h6>Role</h6>
                                <p class="text-muted">{{ $userRole }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                <h6>Last Updated</h6>
                                <p class="text-muted">{{ Auth::user()->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #ff2d7a;
        --primary-dark: #e61a66;
        --primary-light: #ff6b9d;
        --primary-gradient: linear-gradient(135deg, #ff2d7a 0%, #e61a66 100%);
        --primary-gradient-light: linear-gradient(135deg, #ff6b9d 0%, #ff2d7a 100%);
    }

    .bg-gradient-primary {
        background: var(--primary-gradient) !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .avatar-circle {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .avatar-text {
        color: white;
        font-weight: bold;
        font-size: 20px;
    }

    .info-box {
        padding: 15px;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .info-box:hover {
        background: #fff0f5;
        transform: translateY(-3px);
    }

    .form-control-lg {
        border-radius: 10px;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 45, 122, 0.25);
    }

    .form-label .text-primary {
        color: var(--primary-color) !important;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--primary-gradient-light);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 45, 122, 0.4);
    }

    .btn-outline-secondary {
        border-color: #e0e0e0;
        color: #666;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #f8f8f8;
        border-color: #ccc;
        transform: translateY(-2px);
    }

    /* Alert styling */
    .alert-success {
        background-color: #f0fff4;
        border-color: #b2f5b2;
        color: #22543d;
        border-radius: 10px;
    }

    .alert-info {
        background-color: #fff5f8;
        border-color: #ffb3c9;
        color: #9b2c50;
        border-radius: 10px;
    }

    .alert-danger {
        background-color: #fff5f5;
        border-color: #feb2b2;
        color: #9b2c2c;
        border-radius: 10px;
    }

    /* Card styling */
    .card {
        border-radius: 15px !important;
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }

    /* Additional Info Card icons */
    .info-box i.text-primary {
        color: var(--primary-color) !important;
    }
</style>

<!-- JavaScript at the bottom -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto dismiss all alerts with class 'auto-dismiss'
        const alerts = document.querySelectorAll('.auto-dismiss');
        
        alerts.forEach(function(alert) {
            setTimeout(function() {
                // Fade out animation
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                
                // Remove after fade out
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 3000); // Show for 3 seconds
        });
    });
</script>
@endsection
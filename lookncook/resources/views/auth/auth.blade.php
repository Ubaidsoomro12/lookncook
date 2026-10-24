<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Look n Cook - Authentication & Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- <nav class="navbar navbar-expand-lg custom-navbar py-3 mb-4">
    <div class="container">
        <a class="navbar-brand logo-text" href="{{ url('/') }}">
            <i class="bi bi-egg-fried me-2 text-primary-color"></i>Look <span class="text-secondary-color">n</span> Cook
        </a>
        
        <div class="ms-auto d-flex align-items-center">
            @auth
                <div class="dropdown">
                    <button class="btn user-profile-dropdown dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2 text-primary-color"></i>Hi, {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu" aria-labelledby="userMenuButton">
                        <li class="dropdown-header text-muted">
                            <i class="bi bi-geo-alt me-1"></i> Location: {{ Auth::user()->city }}
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger logout-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout Account
                            </a>
                        </li>
                    </ul>
                </div>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <span class="badge guest-status-badge">
                    <i class="bi bi-shield-lock-fill me-1"></i> Secure Guest Mode
                </span>
            @endauth
        </div>
    </div>
</nav> -->

<div class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
                
                @auth
                    <div class="auth-card text-center py-5 animate-fade-in">
                        <div class="success-icon-wrapper mb-4">
                            <i class="bi bi-check-all"></i>
                        </div>
                        <h2 class="form-title mb-2">Access Granted!</h2>
                        <p class="form-subtitle mb-4">Welcome to your personalized Look n Cook space.</p>
                        
                        <div class="user-meta-card text-start mb-4">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="text-muted"><i class="bi bi-person me-2"></i> Account Name:</span>
                                <strong class="text-dark">{{ Auth::user()->name }}</strong>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="text-muted"><i class="bi bi-envelope me-2"></i> Email Contact:</span>
                                <strong class="text-dark">{{ Auth::user()->email }}</strong>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="text-muted"><i class="bi bi-telephone me-2"></i> Mobile Code:</span>
                                <strong class="text-dark">{{ Auth::user()->phone }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-geo-alt me-2"></i> Active City:</span>
                                <strong class="text-dark">{{ Auth::user()->city }}</strong>
                            </div>
                        </div>

                        <p class="text-muted small mb-4">Your dynamic session identifier is verified. Use the top menu profiles element to log out safely at any time.</p>
                        
                        <a href="{{ url('/') }}" class="btn-auth-action text-decoration-none d-inline-block">
                            Browse Cook Menus <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                @else
                    <div class="auth-card">
                        
                        <div class="auth-tabs" id="authTabsHeader">
                            <button class="auth-tab-btn active" id="loginTabBtn" onclick="switchAuthMode('login')">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                            <button class="auth-tab-btn" id="registerTabBtn" onclick="switchAuthMode('register')">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                            <div class="tab-slider"></div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 custom-alert mb-4">
                                <ul class="mb-0 px-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success border-0 custom-alert mb-4" style="background-color: #f0fdf4; color: #16a34a;">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div id="loginFormContainer" class="auth-form-block active">
                            <div class="text-center mb-4">
                                <h3 class="form-title">Welcome Back</h3>
                                <p class="form-subtitle">Enter your details to access your dashboard</p>
                            </div>

                            <form action="{{ route('login.submit') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="custom-input-label">Email Address</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control custom-form-control" placeholder="example@mail.com" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="custom-input-label">Password</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control custom-form-control" placeholder="••••••••" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="rememberMe" name="remember">
                                        <label class="form-check-label check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <a href="#" class="auth-link" onclick="switchAuthMode('forgot')">Forgot Password?</a>
                                </div>

                                <button type="submit" class="btn-auth-action">
                                    Sign In <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </form>
                        </div>

                        <div id="registerFormContainer" class="auth-form-block">
                            <div class="text-center mb-4">
                                <h3 class="form-title">Join Look n Cook</h3>
                                <p class="form-subtitle">Register to verify your account and start ordering</p>
                            </div>

                            <form action="{{ route('register.otp') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="custom-input-label">Full Name</label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" name="name" class="form-control custom-form-control" value="{{ old('name') }}" placeholder="John Doe" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="custom-input-label">Email Address</label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" name="email" class="form-control custom-form-control" value="{{ old('email') }}" placeholder="john@example.com" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="custom-input-label">Phone Number</label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                            <input type="text" name="phone" class="form-control custom-form-control" value="{{ old('phone') }}" placeholder="03001234567" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="custom-input-label">City</label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                            <input type="text" name="city" class="form-control custom-form-control" value="{{ old('city') }}" placeholder="Hyderabad" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="custom-input-label">Password</label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                            <input type="password" name="password" class="form-control custom-form-control" placeholder="••••••••" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="custom-input-label">Confirm Password</label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                            <input type="password" name="password_confirmation" class="form-control custom-form-control" placeholder="••••••••" required>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-action">
                                    Get Verification OTP <i class="bi bi-envelope-check ms-2"></i>
                                </button>
                            </form>
                        </div>

                        <div id="otpFormContainer" class="auth-form-block">
                            <div class="text-center mb-4">
                                <h3 class="form-title">Verify Your Email</h3>
                                <p class="form-subtitle">We have sent a 6-digit verification code to your email address.</p>
                            </div>

                            <form action="{{ route('register.submit') }}" method="POST">
                                @csrf <div class="mb-4">
                                    <label class="custom-input-label d-block text-center">Enter 6-Digit OTP Code</label>
                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <input type="text" name="otp" class="form-control text-center custom-form-control" style="max-width: 220px; font-size: 24px; letter-spacing: 6px; background: #fafafa !important; border: 1px solid #e2e8f0 !important; border-radius: 14px;" maxlength="6" placeholder="000000" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-action mb-3">
                                    Confirm & Create Account <i class="bi bi-check-circle ms-2"></i>
                                </button>
                                
                                <div class="text-center">
                                    <a href="#" class="auth-link text-muted small" onclick="location.reload();">Cancel and Go Back</a>
                                </div>
                            </form>
                        </div>

                        <div id="forgotFormContainer" class="auth-form-block">
                            <div class="text-center mb-4">
                                <h3 class="form-title">Recover Password</h3>
                                <p class="form-subtitle">Enter your email and we will send you a secure 6-digit access OTP code.</p>
                            </div>

                            <form action="{{ route('password.forgot.send') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="custom-input-label">Your Registered Email Address</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                        <input type="email" name="email" class="form-control custom-form-control" placeholder="yourmail@example.com" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-action mb-3">
                                    Send Recovery Code <i class="bi bi-send ms-2"></i>
                                </button>

                                <div class="text-center">
                                    <a href="#" class="auth-link text-muted small" onclick="switchAuthMode('login')">Return to Login Screen</a>
                                </div>
                            </form>
                        </div>

                        <div id="resetFormContainer" class="auth-form-block">
                            <div class="text-center mb-4">
                                <h3 class="form-title">Create New Password</h3>
                                <p class="form-subtitle">Provide the authorization key sent to your inbox and confirm your new secure password.</p>
                            </div>

                            <form action="{{ route('password.forgot.submit') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="custom-input-label d-block text-center">Enter 6-Digit Password Reset OTP</label>
                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <input type="text" name="otp" class="form-control text-center custom-form-control" style="max-width: 220px; font-size: 24px; letter-spacing: 6px; background: #fafafa !important; border: 1px solid #e2e8f0 !important; border-radius: 14px;" maxlength="6" placeholder="000000" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="custom-input-label">New Account Password</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                                        <input type="password" name="password" class="form-control custom-form-control" placeholder="••••••••" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="custom-input-label">Confirm New Password</label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control custom-form-control" placeholder="••••••••" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-action mb-3">
                                    Update Password <i class="bi bi-arrow-right-circle ms-2"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                @endauth

            </div>
        </div>
    </div>
</div>

<style>
/* Core Brand Variables */
:root {
    --primary-action: #ff2d7a;
    --primary-dark: #111111;
    --light-bg-gradient-1: #fff1f6;
    --light-bg-gradient-2: #fff7fb;
    --border-color-soft: #e2e8f0;
}

body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: radial-gradient(circle at top right, var(--light-bg-gradient-1) 0%, var(--light-bg-gradient-2) 100%);
}

.auth-wrapper {
    padding: 20px 0 60px 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Custom Navbar Style Layout */
.custom-navbar {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 45, 122, 0.06);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
}

.logo-text {
    font-weight: 800;
    font-size: 22px;
    letter-spacing: -0.5px;
    color: var(--primary-dark) !important;
}

.text-primary-color { color: var(--primary-action); }
.text-secondary-color { color: #6c757d; }

/* Profile Dropdown Component Elements */
.user-profile-dropdown {
    background: #ffffff;
    border: 1px solid var(--border-color-soft);
    padding: 10px 18px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 14px;
    color: var(--primary-dark);
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    transition: 0.2s ease-in-out;
}

.user-profile-dropdown:hover, .user-profile-dropdown:focus {
    border-color: var(--primary-action);
    background: #ffffff;
}

.custom-dropdown-menu {
    border-radius: 16px;
    padding: 10px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    min-width: 200px;
}

.logout-item {
    font-weight: 600;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 14px;
}

.logout-item:hover {
    background: #fff5f5;
}

.guest-status-badge {
    background: rgba(108, 117, 125, 0.08);
    color: #5a626a;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
}

/* Auth Card Layout Panel */
.auth-card {
    background: #ffffff;
    border-radius: 32px;
    box-shadow: 0 20px 50px rgba(255, 45, 122, 0.06), 0 4px 12px rgba(0, 0, 0, 0.02);
    padding: 45px;
    border: 1px solid rgba(255, 45, 122, 0.05);
    position: relative;
    overflow: hidden;
}

.success-icon-wrapper {
    width: 72px;
    height: 72px;
    background: #f0fdf4;
    color: #16a34a;
    font-size: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border-radius: 50%;
    box-shadow: 0 8px 20px rgba(22, 163, 74, 0.15);
}

.user-meta-card {
    background: #fafafa;
    border: 1px solid var(--border-color-soft);
    border-radius: 20px;
    padding: 20px;
    font-size: 14px;
}

/* Tab Switcher Styling */
.auth-tabs {
    display: flex;
    background: #f6f6f9;
    padding: 6px;
    border-radius: 18px;
    margin-bottom: 40px;
    position: relative;
    z-index: 1;
}

.auth-tab-btn {
    flex: 1;
    border: none;
    background: transparent;
    padding: 12px;
    font-weight: 700;
    font-size: 15px;
    color: #6c757d;
    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 14px;
    position: relative;
    z-index: 2;
    cursor: pointer;
}

.auth-tab-btn.active {
    color: #ffffff;
}

.tab-slider {
    position: absolute;
    top: 6px;
    left: 6px;
    width: calc(50% - 6px);
    height: calc(100% - 12px);
    background: var(--primary-action);
    border-radius: 14px;
    box-shadow: 0 6px 15px rgba(255, 45, 122, 0.3);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
}

/* Layout State Animation Rules */
.auth-form-block {
    display: none;
    opacity: 0;
    transform: translateY(15px);
    transition: opacity 0.4s ease, transform 0.4s ease;
}

.auth-form-block.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.form-title {
    font-weight: 800;
    font-size: 28px;
    color: var(--primary-dark);
    letter-spacing: -0.5px;
}

.form-subtitle {
    color: #7d7d8e;
    font-size: 14px;
}

/* Form Components Elements */
.custom-input-label {
    font-size: 14px;
    font-weight: 700;
    color: #2d2d3f;
    margin-bottom: 8px;
}

.custom-input-group {
    background: #fafafa;
    border: 1px solid var(--border-color-soft);
    border-radius: 14px;
    transition: 0.3s;
    overflow: hidden;
}

.custom-input-group:focus-within {
    border-color: var(--primary-action);
    box-shadow: 0 0 0 4px rgba(255, 45, 122, 0.1);
    background: #ffffff;
}

.custom-input-group .input-group-text {
    background: transparent;
    border: none;
    color: #a0aec0;
    padding-left: 18px;
    font-size: 18px;
}

.custom-form-control {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 14px 18px 14px 10px;
    font-size: 15px;
    color: #1a202c;
    font-weight: 500;
}

.custom-form-control::placeholder {
    color: #cbd5e0;
}

.custom-checkbox {
    border-radius: 6px;
    border-color: #cbd5e0;
}

.custom-checkbox:checked {
    background-color: var(--primary-action);
    border-color: var(--primary-action);
}

.check-label {
    font-size: 14px;
    font-weight: 600;
    color: #4a5568;
}

.auth-link {
    color: var(--primary-action);
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    transition: 0.2s;
}

.auth-link:hover {
    color: var(--primary-dark);
}

.btn-auth-action {
    width: 100%;
    background: var(--primary-action);
    color: #ffffff;
    border: none;
    padding: 16px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 8px 24px rgba(255, 45, 122, 0.2);
    transition: 0.3s;
    cursor: pointer;
    text-align: center;
}

.btn-auth-action:hover {
    background: var(--primary-dark);
    box-shadow: 0 8px 24px rgba(17, 17, 17, 0.2);
    color: #ffffff;
}

.custom-alert {
    background-color: #fff5f5;
    color: #e53e3e;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 600;
}

#registerFormContainer, #otpFormContainer, #forgotFormContainer, #resetFormContainer {
    display: none;
}

.animate-fade-in {
    animation: fadeInEffect 0.5s ease-out forwards;
}

@keyframes fadeInEffect {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media(max-width: 576px) {
    .auth-card {
        padding: 25px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function switchAuthMode(mode) {
    const loginBtn = document.getElementById('loginTabBtn');
    const registerBtn = document.getElementById('registerTabBtn');
    const slider = document.querySelector('.tab-slider');
    const loginForm = document.getElementById('loginFormContainer');
    const registerForm = document.getElementById('registerFormContainer');
    const otpForm = document.getElementById('otpFormContainer');
    const forgotForm = document.getElementById('forgotFormContainer');
    const resetForm = document.getElementById('resetFormContainer');
    const tabsHeader = document.getElementById('authTabsHeader');

    if(!tabsHeader) return; 

    // Hide everything first
    tabsHeader.style.display = 'none';
    loginForm.style.display = 'none';
    registerForm.style.display = 'none';
    otpForm.style.display = 'none';
    forgotForm.style.display = 'none';
    resetForm.style.display = 'none';
    
    loginForm.classList.remove('active');
    registerForm.classList.remove('active');
    otpForm.classList.remove('active');
    forgotForm.classList.remove('active');
    resetForm.classList.remove('active');

    if (mode === 'login') {
        tabsHeader.style.display = 'flex';
        loginBtn.classList.add('active');
        registerBtn.classList.remove('active');
        slider.style.transform = 'translateX(0)';
        
        loginForm.style.display = 'block';
        setTimeout(() => loginForm.classList.add('active'), 20);
    } else if (mode === 'register') {
        tabsHeader.style.display = 'flex';
        registerBtn.classList.add('active');
        loginBtn.classList.remove('active');
        slider.style.transform = 'translateX(100%)';
        slider.style.left = '0px'; 
        
        registerForm.style.display = 'block';
        setTimeout(() => registerForm.classList.add('active'), 20);
    } else if (mode === 'otp') {
        otpForm.style.display = 'block';
        setTimeout(() => otpForm.classList.add('active'), 20);
    } else if (mode === 'forgot') {
        forgotForm.style.display = 'block';
        setTimeout(() => forgotForm.classList.add('active'), 20);
    } else if (mode === 'reset') {
        resetForm.style.display = 'block';
        setTimeout(() => resetForm.classList.add('active'), 20);
    }
}

// Automatically navigate forms based on dynamic backend flags
@if(session('otp_sent'))
    document.addEventListener("DOMContentLoaded", function() {
        switchAuthMode('otp');
    });
@elseif(session('forgot_otp_sent'))
    document.addEventListener("DOMContentLoaded", function() {
        switchAuthMode('reset');
    });
@elseif($errors->has('name') || $errors->has('phone') || $errors->has('city') || old('name'))
    document.addEventListener("DOMContentLoaded", function() {
        switchAuthMode('register');
    });
@endif
</script>

</body>
</html>
<nav style="background:#fff; border-radius:12px; padding:12px 24px; box-shadow:0 2px 6px rgba(0,0,0,0.05); margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
    <div style="display:flex; align-items:center; gap:12px;">
        <!-- Toggle button for mobile -->
        <button id="mobileMenuToggle" class="btn btn-light" style="padding:6px 10px; display: none;" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h5 class="mb-0" style="font-weight:600;">@yield('page-title', 'Staff Dashboard')</h5>
    </div>

    <!-- User Dropdown Block (matching admin panel) -->
    <div class="dash-user-dropdown">
        <button id="profileDropdownBtn" class="dash-profile-btn">
            <div class="dash-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="dash-welcome-text">
                Welcome, {{ auth()->user()->name }}
                <small style="font-weight:400; color:#6b7280;">
                    @php
                        $roleLabels = [1=>'Admin',2=>'User',3=>'Manager',4=>'Waiter',5=>'Chef',6=>'Cashier',7=>'Cleaner',8=>'Delivery Rider'];
                        $roleName = $roleLabels[auth()->user()->role_id] ?? 'Staff';
                    @endphp
                    ({{ $roleName }})
                </small>
            </span>
            <i class="fa-solid fa-chevron-down dash-chevron"></i>
        </button>

        <!-- Profile Dropdown overlay card -->
        <div id="profileMenu" class="dash-dropdown-menu">
            <a href="#" class="dash-dropdown-item">
                <i class="fa-regular fa-user"></i> My Profile
            </a>
            <a href="/" class="dash-dropdown-item">
                <i class="fa-solid fa-globe"></i> Go to Website
            </a>

            {{-- ✅ Staff & Customer Reviews (only for staff roles 3–8) --}}
            @php
                $staffRoles = [3, 4, 5, 6, 7, 8];
                $isStaff    = in_array(auth()->user()->role_id, $staffRoles);
            @endphp
            @if($isStaff)
                <a href="{{ route('staff.customer.reviews') }}" class="dash-dropdown-item dash-dropdown-reviews">
                    <i class="fa-solid fa-star"></i>
                    <span>Staff &amp; Customer Reviews</span>
                </a>
            @endif

            <hr class="dash-dropdown-divider">
            <form action="{{ route('logout') }}" method="POST" class="w-100">
                @csrf
                <button type="submit" class="dash-dropdown-item dash-dropdown-signout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    .dash-user-dropdown {
        position: relative;
    }
    .dash-profile-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: none;
        padding: 6px 12px;
        border-radius: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .dash-profile-btn:hover {
        background: #f9fafb;
    }
    .dash-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff1f6;
        color: #ff2d7a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border: 1px solid rgba(255,45,122,0.2);
        font-size: 14px;
        flex-shrink: 0;
    }
    .dash-welcome-text {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        display: none;
        text-align: left;
        line-height: 1.2;
    }
    .dash-chevron {
        font-size: 12px;
        color: #9ca3af;
    }

    .dash-dropdown-menu {
        position: absolute;
        right: 0;
        margin-top: 8px;
        width: 192px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        padding: 8px 0;
        display: none;
        z-index: 50;
    }
    .dash-dropdown-menu.show {
        display: block;
    }
    .dash-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        font-size: 14px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
        background: transparent;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    .dash-dropdown-item:hover {
        background: #f9fafb;
        text-decoration: none;
        color: #374151;
    }
    .dash-dropdown-item i {
        color: #9ca3af;
        width: 16px;
    }
    .dash-dropdown-divider {
        border: none;
        border-top: 1px solid #f3f4f6;
        margin: 4px 0;
    }
    .dash-dropdown-signout {
        color: #dc2626;
        font-weight: 500;
    }
    .dash-dropdown-signout:hover {
        background: #fef2f2;
        color: #dc2626;
    }
    .dash-dropdown-signout i {
        color: #dc2626;
    }

    /* ✅ Stylish bold look for Staff & Customer Reviews */
    .dash-dropdown-reviews {
        font-weight: 700;
        color: #ff2d7a;
        letter-spacing: 0.2px;
    }
    .dash-dropdown-reviews i {
        color: #ff2d7a;
    }
    .dash-dropdown-reviews:hover {
        background: rgba(255, 45, 122, 0.08);
        color: #e91e63;
    }
    .dash-dropdown-reviews:hover i {
        color: #e91e63;
    }

    /* Responsive */
    @media (min-width: 640px) {
        .dash-welcome-text {
            display: inline-block;
        }
    }
</style>

<script>
    // Show mobile toggle button on small screens
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('mobileMenuToggle');
        if (window.innerWidth < 1024) {
            toggleBtn.style.display = 'inline-block';
        }
        window.addEventListener('resize', function() {
            if (window.innerWidth < 1024) {
                toggleBtn.style.display = 'inline-block';
            } else {
                toggleBtn.style.display = 'none';
            }
        });

        // Profile Dropdown Toggle
        const profileBtn = document.getElementById('profileDropdownBtn');
        const profileMenu = document.getElementById('profileMenu');

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.remove('show');
                }
            });
        }
    });
</script>
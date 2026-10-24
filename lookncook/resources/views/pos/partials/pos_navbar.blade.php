{{-- FILE: resources/views/pos/layouts/header.blade.php --}}
<header class="pos-header">
    <div class="pos-header-inner">
        <div class="pos-header-left">
            <!-- Hamburger Menu button visible on smaller viewports -->
            <button id="mobileMenuBtn" class="pos-mobile-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="pos-header-title">System Dashboard Workspace Profile</span>
        </div>

        <!-- User Dropdown Block exactly matching image 1 layout panel -->
        <div class="pos-user-dropdown">
            <button id="profileDropdownBtn" class="pos-profile-btn">
                <div class="pos-avatar">A</div>
                <span class="pos-welcome-text">Welcome, Manager</span>
                <i class="fa-solid fa-chevron-down pos-chevron"></i>
            </button>

            <!-- Profile Dropdown overlay card -->
            <div id="profileMenu" class="pos-dropdown-menu">
                <a href="#" class="pos-dropdown-item">
                    <i class="fa-regular fa-user"></i> My Profile
                </a>
                <a href="/" class="pos-dropdown-item">
                    <i class="fa-solid fa-globe"></i> Go to Website
                </a>
                <hr class="pos-dropdown-divider">
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="pos-dropdown-item pos-dropdown-signout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    .pos-header {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        height: 64px;
        position: sticky;
        top: 0;
        z-index: 30;
        display: flex;
        align-items: center;
        padding: 0 24px;
    }
    .pos-header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .pos-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .pos-mobile-btn {
        padding: 8px;
        border-radius: 8px;
        color: #6b7280;
        background: transparent;
        border: none;
        display: block;
    }
    .pos-mobile-btn:hover {
        background: #f3f4f6;
    }
    .pos-mobile-btn i {
        font-size: 20px;
    }
    .pos-header-title {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        display: none;
    }

    .pos-user-dropdown {
        position: relative;
    }
    .pos-profile-btn {
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
    .pos-profile-btn:hover {
        background: #f9fafb;
    }
    .pos-avatar {
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
    }
    .pos-welcome-text {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        display: none;
    }
    .pos-chevron {
        font-size: 12px;
        color: #9ca3af;
    }

    .pos-dropdown-menu {
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
    .pos-dropdown-menu.show {
        display: block;
    }
    .pos-dropdown-item {
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
    .pos-dropdown-item:hover {
        background: #f9fafb;
        text-decoration: none;
        color: #374151;
    }
    .pos-dropdown-item i {
        color: #9ca3af;
        width: 16px;
    }
    .pos-dropdown-divider {
        border: none;
        border-top: 1px solid #f3f4f6;
        margin: 4px 0;
    }
    .pos-dropdown-signout {
        color: #dc2626;
        font-weight: 500;
    }
    .pos-dropdown-signout:hover {
        background: #fef2f2;
        color: #dc2626;
    }
    .pos-dropdown-signout i {
        color: #dc2626;
    }

    /* Responsive */
    @media (min-width: 640px) {
        .pos-header-title {
            display: inline-block;
        }
        .pos-welcome-text {
            display: inline-block;
        }
    }
    @media (min-width: 1024px) {
        .pos-mobile-btn {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Mobile Menu Button (for sidebar toggle if needed)
        const mobileBtn = document.getElementById('mobileMenuBtn');
        if (mobileBtn) {
            mobileBtn.addEventListener('click', function() {
                // Add your sidebar toggle logic here
                // Example: document.getElementById('sidebar').classList.toggle('show');
            });
        }
    });
</script>
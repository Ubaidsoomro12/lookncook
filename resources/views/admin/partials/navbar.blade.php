<header class="dash-header">
    <div class="dash-header-inner">
        <div class="dash-header-left">
            <!-- Hamburger Menu button visible on smaller viewports -->
            <button id="mobileMenuBtn" class="dash-mobile-btn" onclick="toggleSidebar()">
                <i id="burgerIcon" class="fa-solid fa-bars"></i>
            </button>
            <span class="dash-header-title">System Dashboard Workspace Profile</span>
        </div>

        <!-- User Dropdown Block exactly matching image 1 layout panel -->
        <div class="dash-user-dropdown">
            <button id="profileDropdownBtn" class="dash-profile-btn">
                <div class="dash-avatar">A</div>
                <span class="dash-welcome-text">Welcome, admin</span>
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
                <hr class="dash-dropdown-divider">
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="dash-dropdown-item dash-dropdown-signout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    .dash-header {
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
    .dash-header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .dash-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .dash-mobile-btn {
        padding: 8px;
        border-radius: 8px;
        color: #6b7280;
        background: transparent;
        border: none;
        display: block;
        cursor: pointer;
    }
    .dash-mobile-btn:hover {
        background: #f3f4f6;
    }
    .dash-mobile-btn i {
        font-size: 20px;
    }
    .dash-header-title {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        display: none;
    }

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
    }
    .dash-welcome-text {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        display: none;
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

    /* Responsive */
    @media (min-width: 640px) {
        .dash-header-title {
            display: inline-block;
        }
        .dash-welcome-text {
            display: inline-block;
        }
    }
    @media (min-width: 1024px) {
        .dash-mobile-btn {
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
    });
</script>
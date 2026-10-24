{{-- FILE: resources/views/admin/partials/sidebar.blade.php --}}
<!-- Sidebar Overlay for mobile -->
<div id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside id="adminSidebar"
    style="position:fixed; inset-y:0; left:0; z-index:40; width:256px; background:#111217; color:#9ca3af; border-right:1px solid #1f2937; transform:translateX(-100%); transition:transform 0.3s ease-in-out; display:flex; flex-direction:column; justify-content:space-between;">

    <!-- ===== SCROLLABLE CONTENT AREA ===== -->
    <!-- Natural scrolling - works with mouse wheel, touch, and trackpad -->
    <div style="overflow-y:auto; flex:1; padding:16px 0; overscroll-behavior:contain; -webkit-overflow-scrolling:touch;"
         class="sidebar-scrollable">

        <!-- Application App Branding Context Logo Area -->
        <div style="padding:0 24px; margin-bottom:32px; display:flex; align-items:center; gap:12px; position:relative;">
            <div style="width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #fff; box-shadow:0 4px 12px rgba(255,45,122,0.2); flex-shrink:0; overflow:hidden; background:#fff;">
                <img src="{{ asset('images/lock-logo.png') }}" alt="Look n Cook" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div>
                <h1 style="color:#fff; font-weight:700; font-size:18px; letter-spacing:0.025em;">Look n Cook</h1>
                <p style="font-size:11px; color:#ff2d7a; font-weight:600; letter-spacing:0.1em; text-transform:uppercase;">Admin Panel</p>
            </div>

            <!-- Mobile Dedicated Close Button inside the Sidebar Header -->
            <button id="closeMobileSidebarBtn"
                style="display:block; position:absolute; right:8px; top:50%; transform:translateY(-50%); color:#9ca3af; background:#1f2937; border:1px solid #374151; border-radius:8px; padding:6px; transition:all 0.2s; cursor:pointer;">
                <i class="fa-solid fa-xmark" style="font-size:18px;"></i>
            </button>
        </div>

        <!-- Main Categories Menu Cluster Mapping -->
        <div style="padding:0 16px;">

            <!-- ===== MAIN SECTION ===== -->
            <p style="font-size:11px; font-weight:700; color:#4b5563; text-transform:uppercase; letter-spacing:0.05em; padding:0 8px; margin-bottom:8px;">Main</p>

            <!-- Dashboard Menu Item -->
            <a href="{{ route('admin.dashboard') }}"
                style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; margin-bottom:24px; {{ request()->routeIs('admin.dashboard') ? 'background:linear-gradient(to right, #ff2d7a, #ff4b91); color:#fff; box-shadow:0 4px 12px rgba(255,45,122,0.2);' : 'color:#9ca3af;' }}">
                <i class="fa-solid fa-chart-pie" style="font-size:18px;"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                <i class="fa-solid fa-users-gear" style="font-size:14px;"></i>
                <span>Users Management</span>
            </a>

            <a href="{{ route('admin.staff.index') }}"
                style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                <i class="fa-solid fa-users" style="font-size:14px;"></i>
                 <span>Staff Management</span>
            </a>

            <!-- ===== MANAGEMENT SECTION ===== -->
            <p style="font-size:11px; font-weight:700; color:#4b5563; text-transform:uppercase; letter-spacing:0.05em; padding:0 8px; margin-top:16px; margin-bottom:8px;">Management</p>

            <div style="display:flex; flex-direction:column; gap:4px;">
                <a href="{{ route('admin.banners.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-images" style="font-size:14px;"></i>
                    <span>Banner Management</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-utensils" style="font-size:14px;"></i>
                    <span>Product Management</span>
                </a>

                <a href="{{ route('admin.payment-methods.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-credit-card" style="font-size:14px;"></i>
                    <span>Payment Methods</span>
                </a>

                <a href="{{ route('admin.payments.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; {{ request()->routeIs('admin.payments.*') ? 'background:#1f2937; color:#fff;' : 'color:#9ca3af;' }}">
                    <i class="fa-solid fa-shopping-bag" style="font-size:14px;"></i>
                    <span>Order Management</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; {{ request()->routeIs('admin.reviews*') ? 'background:rgba(255,45,122,0.1); color:#fff; border:1px solid rgba(255,45,122,0.3);' : 'color:#9ca3af;' }}">
                    <i class="fa-solid fa-star" style="font-size:14px; {{ request()->routeIs('admin.reviews*') ? 'color:#ff2d7a;' : '' }}"></i>
                    <span>Reviews Management</span>
                </a>

                <a href="<a href="{{ route('admin.reviews.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; {{ request()->routeIs('admin.reviews*') ? 'background:rgba(255,45,122,0.1); color:#fff; border:1px solid rgba(255,45,122,0.3);' : 'color:#9ca3af;' }}">
                    <i class="fa-solid fa-star" style="font-size:14px; {{ request()->routeIs('admin.reviews*') ? 'color:#ff2d7a;' : '' }}"></i>
                    <span>Reviews Management</span>
                </a>

                <a href="{{ route('admin.branches.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-user-shield" style="font-size:14px;"></i>
                    <span>Branches Management</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; {{ request()->routeIs('admin.categories.*') ? 'background:#1f2937; color:#fff;' : 'color:#9ca3af;' }}">
                    <i class="fa-solid fa-folder-tree" style="font-size:14px;"></i>
                    <span>Categories Management</span>
                </a>

                <a href="{{ route('admin.gallery.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-images" style="font-size:14px;"></i>
                    <span>Gallery Management</span>
                </a>

                <a href="{{ route('admin.riders.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-motorcycle" style="font-size:14px;"></i>
                    <span>Riders Management</span>
                </a>

                <a href="{{ route('admin.about.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-circle-info" style="font-size:14px;"></i>
                    <span>About Us</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Footer Context Inside Side Nav -->
    <div style="padding:16px; border-top:1px solid #1f2937; text-align:center; font-size:11px; color:#4b5563; font-weight:500; letter-spacing:0.025em;">
        &copy; 2026 Look n Cook Core Engine
    </div>
</aside>

<script>
    // Sidebar toggle functions - GLOBALLY accessible
    function openSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const burgerIcon = document.getElementById('burgerIcon');
        
        if (sidebar) {
            if (window.innerWidth < 1024) {
                sidebar.style.transform = 'translateX(0)';
                sidebar.classList.add('open');
            }
        }
        if (overlay) overlay.classList.add('active');
        if (burgerIcon) burgerIcon.style.display = 'none';
        
        const closeIcon = document.getElementById('closeIcon');
        if (closeIcon) closeIcon.style.display = 'block';
    }

    function closeSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const burgerIcon = document.getElementById('burgerIcon');
        
        if (sidebar) {
            if (window.innerWidth < 1024) {
                sidebar.style.transform = 'translateX(-100%)';
                sidebar.classList.remove('open');
            }
        }
        if (overlay) overlay.classList.remove('active');
        if (burgerIcon) burgerIcon.style.display = 'block';
        
        const closeIcon = document.getElementById('closeIcon');
        if (closeIcon) closeIcon.style.display = 'none';
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        if (!sidebar) return;
        
        if (window.innerWidth >= 1024) {
            return;
        }
        
        const isOpen = sidebar.style.transform === 'translateX(0px)' || sidebar.classList.contains('open');
        
        if (isOpen) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.getElementById('closeMobileSidebarBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeSidebar);
        }

        const profileBtn = document.getElementById('profileDropdownBtn');
        const profileMenu = document.getElementById('profileMenu');

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.style.display = 'none';
                }
            });
        }

        const overlay = document.getElementById('sidebarOverlay');
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        function handleResize() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth >= 1024) {
                if (sidebar) {
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.classList.add('open');
                }
                if (overlay) overlay.classList.remove('active');
                
                const burgerIcon = document.getElementById('burgerIcon');
                const closeIcon = document.getElementById('closeIcon');
                if (burgerIcon) burgerIcon.style.display = 'block';
                if (closeIcon) closeIcon.style.display = 'none';
            } else {
                if (sidebar && !sidebar.classList.contains('open')) {
                    sidebar.style.transform = 'translateX(-100%)';
                }
            }
        }

        handleResize();
        window.addEventListener('resize', handleResize);

        if (window.innerWidth < 1024) {
            const sidebar = document.getElementById('adminSidebar');
            if (sidebar) {
                sidebar.style.transform = 'translateX(-100%)';
                sidebar.classList.remove('open');
            }
        }
    });
</script>

<style>
    /* ===== SIDEBAR VISIBILITY ===== */
    @media (min-width: 1024px) {
        #adminSidebar {
            transform: translateX(0) !important;
        }
        #closeMobileSidebarBtn {
            display: none !important;
        }
        #sidebarOverlay {
            display: none !important;
        }
    }

    @media (max-width: 1023px) {
        #adminSidebar {
            transform: translateX(-100%);
        }
        #adminSidebar.open {
            transform: translateX(0) !important;
        }
    }

    /* ===== HOVER EFFECTS ===== */
    .sidebar-link:hover {
        background: #1f2937;
        color: #fff;
    }

    /* ===== NATURAL SCROLLING - NO VISIBLE SCROLLBAR ===== */
    .sidebar-scrollable {
        /* Enable scrolling */
        overflow-y: auto;
        
        /* Smooth scrolling on all devices */
        -webkit-overflow-scrolling: touch;
        
        /* Prevent scroll chaining (prevents page from scrolling when sidebar reaches end) */
        overscroll-behavior: contain;
        
        /* Hide scrollbar in Firefox */
        scrollbar-width: none;
        
        /* Hide scrollbar in IE/Edge */
        -ms-overflow-style: none;
    }
    
    /* Hide scrollbar in WebKit browsers (Chrome, Safari, Edge) */
    .sidebar-scrollable::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    /* ===== SIDEBAR OVERLAY ===== */
    #sidebarOverlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 39;
        display: none;
    }
    #sidebarOverlay.active {
        display: block;
    }
</style>
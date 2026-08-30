{{-- FILE: resources/views/pos/partials/pos_sidebar.blade.php --}}
<!-- Sidebar Overlay for mobile -->
<div id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside id="adminSidebar"
    style="position:fixed; inset:0; left:0; z-index:40; width:256px; background:#111217; color:#9ca3af; border-right:1px solid #1f2937; transform:translateX(-100%); transition:all 0.3s ease-in-out; display:flex; flex-direction:column; justify-content:space-between;">

    <div style="overflow-y:auto; flex:1; padding:16px 0;">

        <!-- Brand Logo -->
        <div style="padding:0 24px; margin-bottom:32px; display:flex; align-items:center; gap:12px; position:relative;">
            <div style="width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #fff; box-shadow:0 4px 12px rgba(255,45,122,0.2); flex-shrink:0; overflow:hidden; background:#fff;">
                <img src="{{ asset('images/lock-logo.png') }}" alt="Look n Cook" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div>
                <h1 style="color:#fff; font-weight:700; font-size:18px; letter-spacing:0.025em;">Look n Cook</h1>
                <p style="font-size:11px; color:#ff2d7a; font-weight:600; letter-spacing:0.1em; text-transform:uppercase;">POS System</p>
            </div>
            <button id="closeMobileSidebarBtn"
                style="display:block; position:absolute; right:8px; top:50%; transform:translateY(-50%); color:#9ca3af; background:#1f2937; border:1px solid #374151; border-radius:8px; padding:6px; transition:all 0.2s; cursor:pointer;">
                <i class="fa-solid fa-xmark" style="font-size:18px;"></i>
            </button>
        </div>

        <!-- Navigation -->
        <div style="padding:0 16px;">
            <p style="font-size:11px; font-weight:700; color:#4b5563; text-transform:uppercase; letter-spacing:0.05em; padding:0 8px; margin-bottom:8px;">Main</p>

            <!-- POS Dashboard -->
            <a href="{{ route('pos.dashboard') }}"
                style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; margin-bottom:24px; {{ request()->routeIs('pos.*') ? 'background:linear-gradient(to right, #ff2d7a, #ff4b91); color:#fff; box-shadow:0 4px 12px rgba(255,45,122,0.2);' : 'color:#9ca3af;' }}">
                <i class="fa-solid fa-chart-pie" style="font-size:18px;"></i>
                <span>POS Dashboard</span>
            </a>

            <p style="font-size:11px; font-weight:700; color:#4b5563; text-transform:uppercase; letter-spacing:0.05em; padding:0 8px; margin-bottom:8px;">POS Operations</p>

            <div style="display:flex; flex-direction:column; gap:4px;">
                <a href="#"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-shopping-cart" style="font-size:14px;"></i>
                    <span>New Order</span>
                </a>
                <a href="#"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-list" style="font-size:14px;"></i>
                    <span>All Orders</span>
                </a>
                <a href="#"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-utensils" style="font-size:14px;"></i>
                    <span>Products</span>
                </a>
                <a href="#"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-users" style="font-size:14px;"></i>
                    <span>Customers</span>
                </a>
                <!-- TABLES LINK -->
                <a href="{{ route('admin.tables.index') }}"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; {{ request()->routeIs('admin.tables.*') ? 'background:linear-gradient(to right, #ff2d7a, #ff4b91); color:#fff; box-shadow:0 4px 12px rgba(255,45,122,0.2);' : 'color:#9ca3af;' }}">
                    <i class="fa-solid fa-table-cells" style="font-size:14px;"></i>
                    <span>Tables</span>
                    @if(request()->routeIs('admin.tables.*'))
                        <span style="margin-left:auto; font-size:11px; background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:9999px;">Active</span>
                    @endif
                </a>
            </div>

            <p style="font-size:11px; font-weight:700; color:#4b5563; text-transform:uppercase; letter-spacing:0.05em; padding:0 8px; margin-top:16px; margin-bottom:8px;">Reports</p>

            <div style="display:flex; flex-direction:column; gap:4px;">
                <a href="#"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-chart-bar" style="font-size:14px;"></i>
                    <span>Sales Reports</span>
                </a>
                <a href="#"
                    style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; transition:all 0.2s; text-decoration:none; color:#9ca3af;">
                    <i class="fa-solid fa-boxes" style="font-size:14px;"></i>
                    <span>Inventory Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="padding:16px; border-top:1px solid #1f2937; text-align:center; font-size:11px; color:#4b5563; font-weight:500; letter-spacing:0.025em;">
        &copy; 2026 Look n Cook POS
    </div>
</aside>

<style>
    /* Sidebar visible on large screens */
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

    /* Sidebar hidden on mobile */
    @media (max-width: 1023px) {
        #adminSidebar {
            transform: translateX(-100%);
        }
        #adminSidebar.open {
            transform: translateX(0) !important;
        }
    }

    /* Hover effects for sidebar links */
    .sidebar-link:hover {
        background: #1f2937;
        color: #fff;
    }

    /* Custom scrollbar for sidebar */
    #adminSidebar > div:first-child::-webkit-scrollbar {
        width: 4px;
    }
    #adminSidebar > div:first-child::-webkit-scrollbar-track {
        background: #111217;
    }
    #adminSidebar > div:first-child::-webkit-scrollbar-thumb {
        background: #ff2d7a;
        border-radius: 2px;
    }

    /* Sidebar overlay */
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
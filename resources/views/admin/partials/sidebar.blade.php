<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-[#111217] text-gray-400 border-r border-gray-800 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col justify-between">
    
    <div class="overflow-y-auto flex-1 py-4 custom-scrollbar">
        <!-- Application App Branding Context Logo Area -->
        <div class="px-6 mb-8 flex items-center gap-3 relative">
            <div class="w-12 h-12 bg-[#ff2d7a] rounded-full flex items-center justify-center text-white font-black text-xl border-2 border-white shadow-lg shadow-[#ff2d7a]/20 shrink-0">
                LC
            </div>
            <div>
                <h1 class="text-white font-bold tracking-wide text-lg">Look n Cook</h1>
                <p class="text-xs text-[#ff2d7a] font-semibold tracking-widest uppercase">Admin Panel</p>
            </div>

            <!-- Mobile Dedicated Close Button inside the Sidebar Header -->
            <button id="closeMobileSidebarBtn" class="lg:hidden absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#ff2d7a] focus:outline-none p-1.5 rounded-lg bg-gray-900 border border-gray-800 transition-all">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Main Categories Menu Cluster Mapping -->
        <div class="px-4">
            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider px-2 mb-2">Main</p>
            
            <!-- Dashboard Menu Item with Theme Gradient Matching Image 1 Selection -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91] text-white font-medium shadow-md shadow-[#ff2d7a]/20 mb-6 transition-all">
                <i class="fa-solid fa-chart-pie text-lg"></i>
                <span>Dashboard</span>
            </a>

            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider px-2 mb-2">Management</p>
            <nav class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-images group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Banner Management</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-utensils group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Product Management</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-rectangle-list group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Order Management</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-star group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Reviews Management</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-user-shield group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Role Management</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-folder-tree group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Categories Management</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-motorcycle group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Riders Management</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Quick Footer Context Inside Side Nav -->
    <div class="p-4 border-t border-gray-800 text-center text-[11px] text-gray-600 font-medium tracking-wide">
        &copy; 2026 Look n Cook Core Engine
    </div>
</aside>
<aside id="adminSidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-[#111217] text-gray-400 border-r border-gray-800 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col justify-between">

    <div class="overflow-y-auto flex-1 py-4 custom-scrollbar">
        <!-- Brand Logo -->
        <div class="px-6 mb-8 flex items-center gap-3 relative">
            <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 border-white shadow-lg shadow-[#ff2d7a]/20 shrink-0 overflow-hidden bg-white">
        <img src="{{ asset('images/lock-logo.png') }}" alt="Look n Cook" class="w-full h-full object-cover">
    </div>
            <div>
                <h1 class="text-white font-bold tracking-wide text-lg">Look n Cook</h1>
                <p class="text-xs text-[#ff2d7a] font-semibold tracking-widest uppercase">POS System</p>
            </div>
            <button id="closeMobileSidebarBtn"
                class="lg:hidden absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#ff2d7a] focus:outline-none p-1.5 rounded-lg bg-gray-900 border border-gray-800 transition-all">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation -->
        <div class="px-4">
            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider px-2 mb-2">Main</p>

            <!-- POS Dashboard -->
            <a href="{{ route('pos.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('pos.*') ? 'bg-gradient-to-r from-[#ff2d7a] to-[#ff4b91] text-white shadow-md shadow-[#ff2d7a]/20' : 'hover:bg-gray-950 hover:text-white' }} transition-all mb-6">
                <i class="fa-solid fa-chart-pie text-lg"></i>
                <span>POS Dashboard</span>
            </a>

            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider px-2 mb-2">POS Operations</p>
            <nav class="space-y-1">
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-shopping-cart group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>New Order</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-list group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>All Orders</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-utensils group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Products</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-users group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Customers</span>
                </a>
            </nav>

            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider px-2 mb-2 mt-4">Reports</p>
            <nav class="space-y-1">
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-chart-bar group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Sales Reports</span>
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-950 hover:text-white transition-all group">
                    <i class="fa-solid fa-boxes group-hover:text-[#ff2d7a] text-sm transition-colors"></i>
                    <span>Inventory Reports</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-800 text-center text-[11px] text-gray-600 font-medium tracking-wide">
        &copy; 2026 Look n Cook POS
    </div>
</aside>
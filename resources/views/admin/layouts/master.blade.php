<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>
<body class="bg-[#f8f9fa] text-gray-800 antialiased font-sans">

    <!-- Sidebar Include Component -->
    @include('admin.partials.sidebar')

    <!-- Layout Base Panel Content Dynamic Insertion -->
    <div class="lg:pl-64 min-h-screen flex flex-col">
        
        <!-- Top Navbar Row Component -->
        <header class="bg-white border-b border-gray-200 h-16 sticky top-0 z-30 flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <!-- FIXED: Added relative, sizing, and flex styles to keep icons perfectly centered when toggled -->
                <button id="mobileMenuBtn" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 lg:hidden focus:outline-none relative w-10 h-10 flex items-center justify-center">
                    <i id="burgerIcon" class="fa-solid fa-bars text-xl block"></i>
                    <i id="closeIcon" class="fa-solid fa-xmark text-xl hidden text-[#ff2d7a]"></i>
                </button>
                <span class="text-sm font-semibold text-gray-500 hidden sm:inline-block">System Dashboard Workspace Profile</span>
            </div>

            <!-- Profile Interactive Dropdown Anchor Context Trigger Grid -->
            <div class="relative">
                <button id="profileDropdownBtn" class="flex items-center gap-2 focus:outline-none py-1.5 px-3 rounded-xl hover:bg-gray-50 transition-all">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-700 border border-gray-300">A</div>
                    <span class="text-sm font-medium text-gray-700 hidden sm:inline-block">Welcome, admin</span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                </button>

                <!-- Floating Dropdown Modal Block Panel Wrapper -->
                <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-2xl shadow-xl py-2 hidden z-50">
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-all">
                        <i class="fa-regular fa-user text-gray-400"></i> My Profile
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-all">
                        <i class="fa-solid fa-globe text-gray-400"></i> Go to Website
                    </a>
                    <hr class="border-gray-100 my-1">
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium text-left transition-all">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Dynamic Workspace Content Section Body Component Wrapper Injection Layout Context Grid Box -->
        <main class="flex-1 p-6">
            @yield('admin_content')
        </main>

    </div>

    <!-- Scripts Context Scripts Component Include -->
    @include('admin.partials.script')
</body>
</html>
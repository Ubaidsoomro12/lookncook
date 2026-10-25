{{-- FILE: resources/views/admin/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>
<body style="background:#f8f9fa; color:#1f2937; font-family: system-ui, -apple-system, sans-serif;">

    <!-- Sidebar Include Component -->
    @include('admin.partials.sidebar')

    <!-- Layout Base Panel Content Dynamic Insertion -->
    <div id="mainContent" style="padding-left:0; min-height:100vh; display:flex; flex-direction:column; transition:padding-left 0.3s ease-in-out;">

        <!-- Top Navbar Row Component -->
        @include('admin.partials.navbar')

        <!-- Content Area -->
        <main style="flex:1; padding:24px;">
            @yield('content')
        </main>

        <!-- ✅ New Professional Footer -->
        <footer style="background:#ffffff; border-top:1px solid #e5e7eb; padding:20px 30px; margin-top:auto;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                
                <!-- Left Side: Brand & Copyright -->
                <div class="d-flex align-items-center gap-2">
                    <div style="width:28px; height:28px; background:#fff0f6; border-radius:6px; display:flex; align-items:center; justify-content:center; border:1px solid #ffd6e5;">
                        <i class="fa-solid fa-utensils" style="color:#ff2d7a; font-size:12px;"></i>
                    </div>
                    <p class="mb-0" style="font-size:13px; color:#6b7280; font-weight:500;">
                        &copy; {{ date('Y') }} <span style="color:#ff2d7a; font-weight:700;">Look n Cook</span>. All rights reserved.
                    </p>
                </div>

                <!-- Right Side: Links & Version -->
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex gap-3">
                        <a href="#" style="font-size:12px; color:#9ca3af; text-decoration:none; font-weight:500; transition:color 0.2s;" onmouseover="this.style.color='#ff2d7a'" onmouseout="this.style.color='#9ca3af'">Privacy Policy</a>
                        <a href="#" style="font-size:12px; color:#9ca3af; text-decoration:none; font-weight:500; transition:color 0.2s;" onmouseover="this.style.color='#ff2d7a'" onmouseout="this.style.color='#9ca3af'">Terms of Service</a>
                    </div>
                    <div style="width:1px; height:14px; background:#e5e7eb;"></div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size:11px; color:#9ca3af; font-weight:600;">v1.0.0</span>
                        <span style="display:inline-block; width:6px; height:6px; background:#10b981; border-radius:50%;"></span>
                    </div>
                </div>

            </div>
        </footer>

    </div>

    {{-- ⭐ PAGE-LEVEL SCRIPTS (from @section('scripts') and @push('scripts')) --}}
    @yield('scripts')
    @stack('scripts')


    <!-- Scripts Context Scripts Component Include -->
    @include('admin.partials.script')
</body>
</html>

<style>
    /* Desktop: sidebar visible and content shifted */
    @media (min-width: 1024px) {
        #mainContent {
            padding-left: 256px !important;
        }
    }

    /* Mobile: overlay styles */
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
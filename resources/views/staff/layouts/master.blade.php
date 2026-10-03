<!DOCTYPE html>
<html lang="en">
<head>
    @include('staff.partials.staff_head')
</head>
<body style="background:#f9fafb; font-family: system-ui, -apple-system, sans-serif;">

    <div style="display:flex; height:100vh; overflow:hidden;">
        <!-- Sidebar -->
        @include('staff.partials.staff_sidebar')

        <!-- Main Content Wrapper -->
        <div id="mainContentWrapper" style="flex:1; display:flex; flex-direction:column; overflow:hidden; transition:margin-left 0.3s ease-in-out;">
            <!-- Navbar -->
            @include('staff.partials.staff_navbar')

            <!-- Page Content -->
            <main style="flex:1; overflow-y:auto; padding:24px; background:#f9fafb;">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('staff.partials.staff_footer')
        </div>
    </div>

    <!-- Scripts -->
    @include('staff.partials.staff_script')

    <!-- Additional Page Specific Scripts -->
    @stack('scripts')
</body>
</html>

<style>
    /* Desktop: sidebar visible, content shifted */
    @media (min-width: 1024px) {
        #mainContentWrapper {
            margin-left: 256px !important;
        }
        #staffSidebar {
            transform: translateX(0) !important;
        }
        #closeMobileSidebarBtn {
            display: none !important;
        }
    }

    /* Mobile: sidebar hidden by default */
    @media (max-width: 1023px) {
        #staffSidebar {
            transform: translateX(-100%);
        }
        #staffSidebar.open {
            transform: translateX(0) !important;
        }
        #mainContentWrapper {
            margin-left: 0 !important;
        }
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

    .sidebar-transition {
        transition: all 0.3s ease-in-out;
    }
</style>
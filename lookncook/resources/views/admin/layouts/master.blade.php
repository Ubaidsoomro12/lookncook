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

    </div>

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
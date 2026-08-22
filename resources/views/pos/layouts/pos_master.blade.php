<!DOCTYPE html>
<html lang="en">
<head>
    @include('pos.partials.pos_head')
</head>
<body class="bg-gray-50 antialiased">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('pos.partials.pos_sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <!-- Navbar -->
            @include('pos.partials.pos_navbar')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('pos.partials.pos_footer')
        </div>
    </div>

    <!-- Scripts -->
    @include('pos.partials.pos_script')
    
    <!-- Additional Page Specific Scripts -->
    @yield('scripts')
</body>
</html>
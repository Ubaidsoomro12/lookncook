<aside id="staffSidebar" class="sidebar-transition" style="
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 256px;
    background: var(--sidebar-bg);
    color: #fff;
    z-index: 40;
    overflow-y: auto;
    transform: translateX(-100%);
">
    <div class="sidebar-brand">
        <i class="fa-solid fa-utensils"></i> Look n Cook
    </div>
    
    <ul class="nav flex-column sidebar-nav mt-3">
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </li>

        <!-- My Shifts -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-clock"></i> My Shifts
            </a>
        </li>

        <!-- My Attendance -->
        <li class="nav-item">
            <a href="{{ route('staff.attendance') }}" class="nav-link {{ request()->routeIs('staff.attendance') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> My Attendance
            </a>
        </li>

        <!-- Payroll -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-file-invoice"></i> Payroll
            </a>
        </li>

        <!-- Messages -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-message"></i> Messages
            </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>

<!-- Overlay -->
<div id="sidebarOverlay" class="sidebar-transition" onclick="toggleSidebar()"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('staffSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    }

    // Close sidebar when clicking a nav link on mobile
    document.querySelectorAll('#staffSidebar .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.innerWidth < 1024) {
                const sidebar = document.getElementById('staffSidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        });
    });
</script>
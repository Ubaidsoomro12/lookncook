<nav style="background:#fff; border-radius:12px; padding:12px 24px; box-shadow:0 2px 6px rgba(0,0,0,0.05); margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
    <div style="display:flex; align-items:center; gap:12px;">
        <!-- Toggle button for mobile -->
        <button id="mobileMenuToggle" class="btn btn-light" style="padding:6px 10px; display: none;" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h5 class="mb-0" style="font-weight:600;">@yield('page-title', 'Staff Dashboard')</h5>
    </div>
    <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
        <span style="font-weight:600; color:#1f2937;">
            <i class="fa-regular fa-circle-user me-2"></i>
            {{ auth()->user()->name }}
            <small style="font-weight:400; color:#6b7280;">
                @php
                    $roleLabels = [1=>'Admin',2=>'User',3=>'Manager',4=>'Waiter',5=>'Chef',6=>'Cashier',7=>'Cleaner',8=>'Delivery Rider'];
                    $roleName = $roleLabels[auth()->user()->role_id] ?? 'Staff';
                @endphp
                ({{ $roleName }})
            </small>
        </span>
        <a href="#" class="btn btn-sm btn-outline-secondary" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</nav>

<script>
    // Show mobile toggle button on small screens
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('mobileMenuToggle');
        if (window.innerWidth < 1024) {
            toggleBtn.style.display = 'inline-block';
        }
        window.addEventListener('resize', function() {
            if (window.innerWidth < 1024) {
                toggleBtn.style.display = 'inline-block';
            } else {
                toggleBtn.style.display = 'none';
            }
        });
    });
</script>
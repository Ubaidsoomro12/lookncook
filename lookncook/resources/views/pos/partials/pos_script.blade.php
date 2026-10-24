<!-- Chart.js for Sales and Order charts display -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Responsive Mobile Sidebar Toggle Functions
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileSidebarBtn = document.getElementById('closeMobileSidebarBtn'); // FIXED: New inner element link
    const sidebarElement = document.getElementById('adminSidebar');
    const burgerIcon = document.getElementById('burgerIcon');
    const closeIcon = document.getElementById('closeIcon');

    // Helper function to handle classes sync smoothly
    function toggleSidebarState() {
        sidebarElement.classList.toggle('-translate-x-full');
        
        if (sidebarElement.classList.contains('-translate-x-full')) {
            // Sidebar is now closed
            if (burgerIcon) { burgerIcon.classList.remove('hidden'); burgerIcon.classList.add('block'); }
            if (closeIcon) { closeIcon.classList.remove('block'); closeIcon.classList.add('hidden'); }
        } else {
            // Sidebar is now open
            if (burgerIcon) { burgerIcon.classList.remove('block'); burgerIcon.classList.add('hidden'); }
            if (closeIcon) { closeIcon.classList.remove('hidden'); closeIcon.classList.add('block'); }
        }
    }

    // Trigger on main navbar hamburger click
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleSidebarState);
    }

    // FIXED: Trigger toggle on sidebar inner cross button click
    if (closeMobileSidebarBtn) {
        closeMobileSidebarBtn.addEventListener('click', toggleSidebarState);
    }

    // Profile Dropdown Handling Toggle
    const profileDropdownBtn = document.getElementById('profileDropdownBtn');
    const profileMenu = document.getElementById('profileMenu');

    if (profileDropdownBtn && profileMenu) {
        profileDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
    }

    // Close dropdown on clicking outside windows area
    window.addEventListener('click', () => {
        if (profileMenu && !profileMenu.classList.contains('hidden')) {
            profileMenu.classList.add('hidden');
        }
    });
</script>
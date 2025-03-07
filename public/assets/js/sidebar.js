document.addEventListener('DOMContentLoaded', function () {
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');

    // Initially hide the close button when sidebar is closed
    if (!sidebar.classList.contains('open')) {
        closeSidebarBtn.style.display = 'none';
    }

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.classList.add('sidebar-open');
        closeSidebarBtn.style.display = 'flex';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
        setTimeout(() => {
            closeSidebarBtn.style.display = 'none';
        }, 300); // Same duration as the sidebar transition (0.3s)
    }

    toggleSidebarBtn.addEventListener('click', openSidebar);
    closeSidebarBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    // Handle keyboard events (Escape to close sidebar)
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            closeSidebar();
        }
    });
});
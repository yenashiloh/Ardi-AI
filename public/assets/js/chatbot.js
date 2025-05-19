document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle functionality
    const themeOptions = document.querySelectorAll('.theme-option');
    const body = document.documentElement;
    const logoImages = document.querySelectorAll('.logo-icon img, .message-avatar img');
    
    // Function to update logo images based on theme
    function updateLogoImages(isDarkMode) {
        logoImages.forEach(img => {
            let currentSrc = img.getAttribute('src');
            if (isDarkMode) {
                img.setAttribute('src', currentSrc.replace('Ardi-Logo.svg', 'Ardi-Logo-White.svg'));
            } else {
                img.setAttribute('src', currentSrc.replace('Ardi-Logo-White.svg', 'Ardi-Logo.svg'));
            }
        });
    }
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        themeOptions[1].classList.add('active');
        themeOptions[0].classList.remove('active');
        updateLogoImages(true);
    }
    
    // Theme toggle listeners
    themeOptions.forEach((option, index) => {
        option.addEventListener('click', function() {
            themeOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            if (index === 1) { // Dark mode
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
                updateLogoImages(true);
            } else { // Light mode
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
                updateLogoImages(false);
            }
        });
    });
    
    // ======= SIDEBAR FUNCTIONALITY =======
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    
    // Initialize sidebar based on device
    function initSidebar() {
        const isMobile = window.innerWidth <= 768;
        
        // On mobile, start with collapsed sidebar
        if (isMobile) {
            sidebar.classList.remove('active');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        } else {
            // On desktop, check saved preference
            const sidebarState = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarState) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
        }
    }
    
    // Initialize on page load
    initSidebar();
    
    // Update on window resize
    window.addEventListener('resize', function() {
        initSidebar();
    });
    
    // Handle toggle button clicks
    const toggleButtons = document.querySelectorAll('.sidebar-toggle-mobile');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const isMobile = window.innerWidth <= 768;
            
            if (isMobile) {
                // On mobile, toggle between collapsed and expanded states
                sidebar.classList.toggle('active');
                
                // Toggle overlay when sidebar is active
                if (sidebar.classList.contains('active')) {
                    if (sidebarOverlay) sidebarOverlay.classList.add('active');
                } else {
                    if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                }
            } else {
                // On desktop, toggle collapsed state
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        });
    });
    
    // Handle overlay click to close sidebar
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            this.classList.remove('active');
        });
    }
});
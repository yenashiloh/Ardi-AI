document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle functionality
    const themeOptions = document.querySelectorAll('.theme-option');
    const body = document.documentElement; // Use documentElement to apply class to :root
    const logoImages = document.querySelectorAll('.logo-icon img, .message-avatar img');
    
    // Function to update logo images based on theme
    function updateLogoImages(isDarkMode) {
        logoImages.forEach(img => {
            let currentSrc = img.getAttribute('src');
            if (isDarkMode) {
                // Replace normal logo with white logo
                img.setAttribute('src', currentSrc.replace('Ardi-Logo.svg', 'Ardi-Logo-White.svg'));
            } else {
                // Replace white logo with normal logo
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
    
    // Add click event to theme options
    themeOptions.forEach((option, index) => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            themeOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Toggle dark mode
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
    
    // Sidebar collapse functionality
    const sidebar = document.querySelector('.sidebar');
    

    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    
    // Check for saved sidebar state
    const sidebarState = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarState) {
        sidebar.classList.add('collapsed');
    }
    
    // Toggle sidebar on button click
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }
    
    // Handle mobile overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            this.classList.remove('active');
        });
    }
    
    // Reuse the sidebar-toggle-mobile for all screens
    const toggleButtons = document.querySelectorAll('.sidebar-toggle-mobile');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            
            // For mobile view also handle the active class
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('active');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.toggle('active');
                }
            }
        });
    });
});
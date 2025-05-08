/**
 * Toggle Sidebar
 */
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    // Check if elements exist
    if (!sidebar || !mainContent || !sidebarToggle) {
        console.error('Required elements not found');
        return;
    }
    
    // Function to check if device is small (mobile)
    function isSmallDevice() {
        return window.innerWidth < 768; // Adjust breakpoint as needed
    }
    
    // Function to hide sidebar completely
    function hideSidebar() {
        sidebar.classList.add('sidebar-hidden');
        sidebar.classList.remove('sidebar-overlay');
        sidebar.classList.remove('sidebar-collapsed');
        mainContent.style.marginLeft = '0';
        mainContent.classList.add('main-content-full');
        document.body.classList.remove('content-blurred');
    }
    
    // Function to show sidebar (either collapsed or expanded)
    function showSidebar(collapsed) {
        sidebar.classList.remove('sidebar-hidden');
        
        if (isSmallDevice()) {
            // On small devices, show as overlay
            sidebar.classList.add('sidebar-overlay');
            sidebar.classList.remove('sidebar-collapsed');
            mainContent.style.marginLeft = '0';
            document.body.classList.add('content-blurred');
        } else {
            // On larger devices, show as normal or collapsed
            sidebar.classList.remove('sidebar-overlay');
            document.body.classList.remove('content-blurred');
            
            if (collapsed) {
                sidebar.classList.add('sidebar-collapsed');
                mainContent.style.marginLeft = '80px';
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                mainContent.style.marginLeft = '280px';
            }
        }
    }
    
    // Apply initial state immediately on page load (before images and other resources)
    function applyInitialState() {
        const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (sidebarHidden || isSmallDevice()) {
            hideSidebar();
        } else {
            showSidebar(sidebarCollapsed);
        }
    }
    
    // Apply initial state as early as possible
    applyInitialState();
    
    // Toggle sidebar when button is clicked
    sidebarToggle.addEventListener('click', function() {
        const isHidden = sidebar.classList.contains('sidebar-hidden');
        
        if (isHidden) {
            // Show sidebar (as overlay on small devices, or in saved state on larger devices)
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            showSidebar(sidebarCollapsed);
            localStorage.setItem('sidebarHidden', 'false');
        } else {
            // Hide sidebar
            hideSidebar();
            localStorage.setItem('sidebarHidden', 'true');
        }
    });
    
    // Handle collapsing the sidebar on larger screens (separate toggle function)
    function toggleSidebarCollapse() {
        if (!isSmallDevice() && !sidebar.classList.contains('sidebar-hidden')) {
            const willBeCollapsed = !sidebar.classList.contains('sidebar-collapsed');
            
            if (willBeCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                mainContent.style.marginLeft = '80px';
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                mainContent.style.marginLeft = '280px';
            }
            
            localStorage.setItem('sidebarCollapsed', willBeCollapsed);
        }
    }
    
    // Add this if you have a separate toggle for collapsing (not hiding) the sidebar
    // const collapseToggle = document.getElementById('collapseToggle');
    // if (collapseToggle) {
    //     collapseToggle.addEventListener('click', toggleSidebarCollapse);
    // }
    
    // Close sidebar when clicking outside on small devices
    document.addEventListener('click', function(event) {
        if (isSmallDevice() && 
            !sidebar.classList.contains('sidebar-hidden') && 
            !sidebar.contains(event.target) && 
            event.target !== sidebarToggle) {
            hideSidebar();
            localStorage.setItem('sidebarHidden', 'true');
        }
    });
    
    // Update sidebar state on window resize
    window.addEventListener('resize', function() {
        const wasSmallDevice = window.innerWidth < 768;
        
        // Small delay to ensure accurate reading after resize completes
        setTimeout(function() {
            const isSmallNow = isSmallDevice();
            
            // If device size category changed
            if (wasSmallDevice !== isSmallNow) {
                applyInitialState();
            }
        }, 100);
    });
});

/**
 * Dark Mode Toggle
 */
const darkModeToggle = document.getElementById('darkModeToggle');

darkModeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    updateCharts();
});

// Update charts when dark mode changes
function updateCharts() {
    const isDarkMode = document.body.classList.contains('dark-mode');

    // Update Revenue Chart
    revenueChart.options.scales.y.grid.color = isDarkMode 
        ? 'rgba(255, 255, 255, 0.1)' 
        : 'rgba(0, 0, 0, 0.05)';
    revenueChart.update();
}

// Add some animations for cards
const cards = document.querySelectorAll('.stat-card, .chart-card');

function animateCards() {
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
}

// Initialize animations
window.addEventListener('load', () => {
    animateCards();
});


/**
 * Header Dropdown Profile
 */
document.addEventListener('DOMContentLoaded', function() {
    const initUserDropdown = () => {
        const dropdownTrigger = document.querySelector('.user-dropdown-trigger');
        const dropdown = document.querySelector('.user-dropdown');

        if (!dropdownTrigger || !dropdown) {
            console.warn('User dropdown elements not found');
            return;
        }

        const toggleDropdown = (event) => {
            event.stopPropagation();
            dropdownTrigger.classList.toggle('active');
            dropdown.classList.toggle('active');
        };

        const closeDropdown = () => {
            dropdownTrigger.classList.remove('active');
            dropdown.classList.remove('active');
        };

        // Toggle dropdown on trigger click
        dropdownTrigger.addEventListener('click', toggleDropdown);

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!dropdownTrigger.contains(event.target) && 
                !dropdown.contains(event.target)) {
                closeDropdown();
            }
        });
    };

    // Try to initialize immediately
    initUserDropdown();

    // Also use MutationObserver to handle dynamic content
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'childList') {
                initUserDropdown();
            }
        });
    });

    // Observe the entire document for changes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

/**
 * Logout
 */
document.addEventListener('DOMContentLoaded', function() {
    // Modal
    const logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        logoutModal.addEventListener('show.bs.modal', function (event) {
        });
    }
});

/**
 * Success Message
 */

function fadeOutEffect(element) {
    let opacity = 1;  // Initial opacity
    let fadeInterval = setInterval(function () {
        if (opacity <= 0) {
            clearInterval(fadeInterval);
            element.style.display = 'none';
        } else {
            opacity -= 0.1;  // Reduce opacity
            element.style.opacity = opacity;
        }
    }, 100); // Adjust fade speed
}

const successMessage = document.getElementById('successMessage');
if (successMessage) {
    setTimeout(function () {
        fadeOutEffect(successMessage);
    }, 5000); // Hide after 5 seconds
}

const errorMessage = document.getElementById('errorMessage');
if (errorMessage) {
    setTimeout(function () {
        fadeOutEffect(errorMessage);
    }, 5000); // Hide after 5 seconds
}

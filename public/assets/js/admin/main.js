// Sidebar toggle
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');

sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('mobile-open');
});

// Dark mode toggle
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
 * Expand
 */


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


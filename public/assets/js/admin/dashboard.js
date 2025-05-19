/**
 * Dashboard.js - Handles interactive functionality for the admin dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the date filter dropdown
    initializeDropdowns();
    
    // Handle date filter changes
    setupDateFilters();
    
    // Update real-time stats periodically
    setupStatsRefresh();
    
    // Set up chart interactivity
    setupChartInteractions();
});

/**
 * Initialize all dropdowns on the page
 */
function initializeDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        // Toggle dropdown menu
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            menu.classList.toggle('show');
        });
        
        // Close dropdown when clicking elsewhere
        document.addEventListener('click', function() {
            menu.classList.remove('show');
        });
        
        // Prevent menu from closing when clicking inside it
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Handle dropdown item selection
        const items = menu.querySelectorAll('.dropdown-item');
        items.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update button text
                const filterType = this.textContent;
                button.innerHTML = 'Filter By: ' + filterType + ' <i class="fas fa-chevron-down"></i>';
                
                // Handle filter selection
                const filterValue = this.getAttribute('data-filter');
                applyDateFilter(filterValue);
                
                // Close the dropdown
                menu.classList.remove('show');
            });
        });
    });
}

/**
 * Setup date filter functionality
 */
function setupDateFilters() {
    // Apply default filter (last 7 days)
    applyDateFilter('7days');
}

/**
 * Apply date filter to dashboard data
 */
function applyDateFilter(filter) {
    // Show loading state
    const refreshButton = document.getElementById('refresh-data');
    refreshButton.disabled = true;
    refreshButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    
    // Calculate date range based on filter
    let startDate, endDate;
    const now = new Date();
    
    switch(filter) {
        case '7days':
            startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            endDate = now;
            break;
        case '30days':
            startDate = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
            endDate = now;
            break;
        case '3months':
            startDate = new Date(now.getFullYear(), now.getMonth() - 3, now.getDate());
            endDate = now;
            break;
        case 'year':
            startDate = new Date(now.getFullYear() - 1, now.getMonth(), now.getDate());
            endDate = now;
            break;
        default:
            startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            endDate = now;
    }
    
    // Format dates for API
    const formattedStartDate = formatDate(startDate);
    const formattedEndDate = formatDate(endDate);
    
    // Make API call to get filtered data
    fetch(`/admin/dashboard/stats?start_date=${formattedStartDate}&end_date=${formattedEndDate}`)
        .then(response => {
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Expected JSON response but got a different content type');
            }
            return response.json();
        })
        .then(data => {
            // Update dashboard with new data
            updateDashboardStats(data);
            
            // Restore button state
            refreshButton.disabled = false;
            refreshButton.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
            
            // Show success notification
            showNotification('Dashboard data updated successfully', 'success');
        })
        .catch(error => {
            console.error('Error fetching filtered data:', error);
            
            // Restore button state
            refreshButton.disabled = false;
            refreshButton.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
            
            // Show error notification
            showNotification('Error updating dashboard data: ' + error.message, 'error');
            
            // If the route is not found, suggest checking the routes
            if (error.message.includes('404') || error.message.includes('not found')) {
                showNotification('The dashboard stats endpoint may not be registered. Check your routes file.', 'warning');
            }
        });
}

/**
 * Format date as YYYY-MM-DD
 */
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

/**
 * Update all dashboard statistics with new data
 */
function updateDashboardStats(data) {
    // Check if data is valid before updating
    if (!data || typeof data !== 'object') {
        console.error('Invalid data received:', data);
        showNotification('Invalid data received from server', 'error');
        return;
    }

    try {
        // Update card values if they exist
        const totalUsersEl = document.querySelector('.stat-card:nth-child(1) .value');
        if (totalUsersEl && data.totalUsers !== undefined) {
            totalUsersEl.textContent = numberFormat(data.totalUsers);
        }
        
        const activeUsersEl = document.querySelector('.stat-card:nth-child(2) .value');
        if (activeUsersEl && data.activeUsers !== undefined) {
            activeUsersEl.textContent = numberFormat(data.activeUsers);
        }
        
        const dailyActiveUsersEl = document.querySelector('.stat-card:nth-child(3) .value');
        if (dailyActiveUsersEl && data.dailyActiveUsers !== undefined) {
            dailyActiveUsersEl.textContent = numberFormat(data.dailyActiveUsers);
        }
        
        const messagesExchangedEl = document.querySelector('.stat-card:nth-child(4) .value');
        if (messagesExchangedEl && data.messagesExchanged !== undefined) {
            messagesExchangedEl.textContent = numberFormat(data.messagesExchanged);
        }
        
        // Update user growth chart if it exists
        const userGrowthChart = Chart.getChart('userGrowthChart');
        if (userGrowthChart && data.userGrowthData) {
            userGrowthChart.data.labels = data.userGrowthData.map(item => item.month);
            userGrowthChart.data.datasets[0].data = data.userGrowthData.map(item => item.users);
            userGrowthChart.update();
        }
        
        // Update message activity chart if it exists
        const messageActivityChart = Chart.getChart('messageActivityChart');
        if (messageActivityChart && data.messageActivityData) {
            messageActivityChart.data.labels = data.messageActivityData.map(item => item.day);
            messageActivityChart.data.datasets[0].data = data.messageActivityData.map(item => item.messages);
            messageActivityChart.update();
        }
        
        // Update percentage changes if they exist
        if (data.percentageChanges) {
            updatePercentageChanges(data.percentageChanges);
        }
        
        // Update the messages today count if it exists
        if (data.messageActivityData && data.messageActivityData.length > 0) {
            const messagesElement = document.getElementById('messages-today');
            if (messagesElement) {
                messagesElement.textContent = data.messageActivityData[data.messageActivityData.length - 1].messages;
            }
        }
    } catch (error) {
        console.error('Error updating dashboard stats:', error);
        showNotification('Error updating dashboard display: ' + error.message, 'error');
    }
}

/**
 * Update percentage changes in stat cards
 */
function updatePercentageChanges(changes) {
    // Update users percentage
    const usersChange = document.getElementById('users-change');
    if (usersChange) {
        if (changes.users >= 0) {
            usersChange.className = 'change positive';
            usersChange.innerHTML = `<i class="fas fa-arrow-up"></i> <span id="users-percent">${changes.users.toFixed(1)}%</span> This Month`;
        } else {
            usersChange.className = 'change negative';
            usersChange.innerHTML = `<i class="fas fa-arrow-down"></i> <span id="users-percent">${Math.abs(changes.users).toFixed(1)}%</span> This Month`;
        }
    }
    
    // Update active users percentage
    const activeUsersChange = document.getElementById('active-users-change');
    if (activeUsersChange) {
        if (changes.activeUsers >= 0) {
            activeUsersChange.className = 'change positive';
            activeUsersChange.innerHTML = `<i class="fas fa-arrow-up"></i> <span id="active-users-percent">${changes.activeUsers.toFixed(1)}%</span> This Month`;
        } else {
            activeUsersChange.className = 'change negative';
            activeUsersChange.innerHTML = `<i class="fas fa-arrow-down"></i> <span id="active-users-percent">${Math.abs(changes.activeUsers).toFixed(1)}%</span> This Month`;
        }
    }
    
    // Update daily active users percentage
    const dailyUsersChange = document.getElementById('daily-users-change');
    if (dailyUsersChange) {
        if (changes.dailyActiveUsers >= 0) {
            dailyUsersChange.className = 'change positive';
            dailyUsersChange.innerHTML = `<i class="fas fa-arrow-up"></i> <span id="daily-users-percent">${changes.dailyActiveUsers.toFixed(1)}%</span> vs Yesterday`;
        } else {
            dailyUsersChange.className = 'change negative';
            dailyUsersChange.innerHTML = `<i class="fas fa-arrow-down"></i> <span id="daily-users-percent">${Math.abs(changes.dailyActiveUsers).toFixed(1)}%</span> vs Yesterday`;
        }
    }
    
    // Update messages percentage
    const messagesChange = document.getElementById('messages-change');
    if (messagesChange) {
        if (changes.messages >= 0) {
            messagesChange.className = 'change positive';
            messagesChange.innerHTML = `<i class="fas fa-arrow-up"></i> <span id="messages-percent">${changes.messages.toFixed(1)}%</span> This Month`;
        } else {
            messagesChange.className = 'change negative';
            messagesChange.innerHTML = `<i class="fas fa-arrow-down"></i> <span id="messages-percent">${Math.abs(changes.messages).toFixed(1)}%</span> This Month`;
        }
    }
}

/**
 * Set up periodic stats refresh
 */
function setupStatsRefresh() {
    // Refresh stats every 5 minutes (300000ms)
    setInterval(function() {
        // Get current filter from dropdown button text
        const filterButton = document.querySelector('.dropdown-toggle');
        if (!filterButton) return;
        
        const filterText = filterButton.textContent.trim().replace('Filter By: ', '');
        
        // Determine filter value
        let filterValue = '7days';
        
        if (filterText.includes('30')) {
            filterValue = '30days';
        } else if (filterText.includes('3')) {
            filterValue = '3months';
        } else if (filterText.includes('Year')) {
            filterValue = 'year';
        }
        
        // Apply the filter to refresh data
        applyDateFilter(filterValue);
    }, 300000);
}

/**
 * Set up chart interactions
 */
function setupChartInteractions() {
    // Get charts (wait for them to be initialized)
    setTimeout(() => {
        try {
            const userGrowthChart = Chart.getChart('userGrowthChart');
            const messageActivityChart = Chart.getChart('messageActivityChart');
            
            if (userGrowthChart) {
                // Handle resize
                window.addEventListener('resize', function() {
                    userGrowthChart.resize();
                });
            }
            
            if (messageActivityChart) {
                // Handle resize
                window.addEventListener('resize', function() {
                    messageActivityChart.resize();
                });
            }
        } catch (error) {
            console.error('Error setting up chart interactions:', error);
        }
    }, 1000);
}

/**
 * Format number with thousands separators
 */
function numberFormat(number) {
    return new Intl.NumberFormat().format(number);
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    // Add to container (create if it doesn't exist)
    let container = document.querySelector('.notification-container');
    
    if (!container) {
        container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
    }
    
    container.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
    
    // Close button
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.remove();
    });
}
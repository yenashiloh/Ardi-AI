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
    
    // Initialize dashboard charts
    initializeCharts();
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
 * Initialize dashboard charts based on data from the page
 */
function initializeCharts() {
    try {
        // Get chart data from HTML elements (inserted by the blade template)
        const userGrowthDataElem = document.getElementById('userGrowthData');
        const messageActivityDataElem = document.getElementById('messageActivityData');
        
        if (!userGrowthDataElem || !messageActivityDataElem) {
            console.error('Chart data elements not found. Make sure your blade template includes these hidden inputs.');
            return;
        }
        
        // Parse JSON data
        const userGrowthData = JSON.parse(userGrowthDataElem.value);
        const messageActivityData = JSON.parse(messageActivityDataElem.value);
        
        // Extract labels and data points
        const growthLabels = userGrowthData.map(item => item.month);
        const growthValues = userGrowthData.map(item => item.users);
        
        const activityLabels = messageActivityData.map(item => item.day);
        const activityValues = messageActivityData.map(item => item.messages);
        
        // Create User Growth Chart
        const userGrowthCtx = document.getElementById('userGrowthChart');
        if (userGrowthCtx) {
            const userGrowthChart = new Chart(userGrowthCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: growthLabels,
                    datasets: [{
                        label: 'New Users',
                        data: growthValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
            
            // Set up chart type toggle
            const toggleBtn = document.getElementById('growth-type-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const currentType = userGrowthChart.config.type;
                    
                    if (currentType === 'line') {
                        userGrowthChart.config.type = 'bar';
                        this.querySelector('span').innerText = 'Bar Chart';
                        this.querySelector('i').className = 'fas fa-chart-bar';
                    } else {
                        userGrowthChart.config.type = 'line';
                        this.querySelector('span').innerText = 'Line Chart';
                        this.querySelector('i').className = 'fas fa-chart-line';
                    }
                    
                    userGrowthChart.update();
                });
            }
        }
        
        // Create Message Activity Chart
        const messageActivityCtx = document.getElementById('messageActivityChart');
        if (messageActivityCtx) {
            const messageActivityChart = new Chart(messageActivityCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: activityLabels,
                    datasets: [{
                        label: 'Messages',
                        data: activityValues,
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        // Set up refresh button functionality
        const refreshBtn = document.getElementById('refresh-data');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
                
                fetch('/admin/dashboard/stats')
                    .then(response => response.json())
                    .then(data => {
                        // Update cards
                        document.querySelector('.stat-card:nth-child(1) .value').innerText = numberFormat(data.totalUsers);
                        document.querySelector('.stat-card:nth-child(2) .value').innerText = numberFormat(data.activeUsers);
                        document.querySelector('.stat-card:nth-child(3) .value').innerText = numberFormat(data.dailyActiveUsers);
                        document.querySelector('.stat-card:nth-child(4) .value').innerText = numberFormat(data.messagesExchanged);
                        
                        // Update charts
                        const userGrowthChart = Chart.getChart('userGrowthChart');
                        if (userGrowthChart) {
                            userGrowthChart.data.labels = data.userGrowthData.map(item => item.month);
                            userGrowthChart.data.datasets[0].data = data.userGrowthData.map(item => item.users);
                            userGrowthChart.update();
                        }
                        
                        const messageActivityChart = Chart.getChart('messageActivityChart');
                        if (messageActivityChart) {
                            messageActivityChart.data.labels = data.messageActivityData.map(item => item.day);
                            messageActivityChart.data.datasets[0].data = data.messageActivityData.map(item => item.messages);
                            messageActivityChart.update();
                        }
                        
                        // Update messages today count
                        if (data.messageActivityData && data.messageActivityData.length > 0) {
                            const messagesElement = document.getElementById('messages-today');
                            if (messagesElement) {
                                messagesElement.textContent = data.messageActivityData[data.messageActivityData.length - 1].messages;
                            }
                        }
                        
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                        
                        // Show success notification
                        showNotification('Dashboard data updated successfully', 'success');
                    })
                    .catch(error => {
                        console.error('Error refreshing data:', error);
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                        
                        // Show error notification
                        showNotification('Error refreshing data', 'error');
                    });
            });
        }
        
        // Set up export button functionality
        const exportBtn = document.getElementById('download-report');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                window.location.href = '/admin/dashboard/export';
            });
        }
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
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
        .then(response => response.json())
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
            showNotification('Error updating dashboard data', 'error');
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
    // Update card values
    document.querySelector('.stat-card:nth-child(1) .value').textContent = numberFormat(data.totalUsers);
    document.querySelector('.stat-card:nth-child(2) .value').textContent = numberFormat(data.activeUsers);
    document.querySelector('.stat-card:nth-child(3) .value').textContent = numberFormat(data.dailyActiveUsers);
    document.querySelector('.stat-card:nth-child(4) .value').textContent = numberFormat(data.messagesExchanged);
    
    // Update user growth chart
    const userGrowthChart = Chart.getChart('userGrowthChart');
    if (userGrowthChart) {
        userGrowthChart.data.labels = data.userGrowthData.map(item => item.month);
        userGrowthChart.data.datasets[0].data = data.userGrowthData.map(item => item.users);
        userGrowthChart.update();
    }
    
    // Update message activity chart
    const messageActivityChart = Chart.getChart('messageActivityChart');
    if (messageActivityChart) {
        messageActivityChart.data.labels = data.messageActivityData.map(item => item.day);
        messageActivityChart.data.datasets[0].data = data.messageActivityData.map(item => item.messages);
        messageActivityChart.update();
    }
    
    // Update percentage changes
    if (data.percentageChanges) {
        updatePercentageChanges(data.percentageChanges);
    }
    
    // Update the messages today count
    if (data.messageActivityData && data.messageActivityData.length > 0) {
        const messagesElement = document.getElementById('messages-today');
        if (messagesElement) {
            messagesElement.textContent = data.messageActivityData[data.messageActivityData.length - 1].messages;
        }
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
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
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
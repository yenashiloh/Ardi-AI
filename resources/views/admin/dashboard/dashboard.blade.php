<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.header')
    <title>Dashboard</title>
</head>

<body>
    @include('admin.partials.sidebar')
    <!-- Header -->
    <div class="header">
        <div class="greeting">
            <h1>Hello, {{ $firstName }}</h1>
            <p>Dashboard Summary</p>
        </div>
        <div class="header-controls">
            {{-- <div class="dropdown">
                <button class="header-button primary-button dropdown-toggle" id="date-filter">
                    Filter By 
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" data-filter="7days">Last 7 Days</a>
                    <a class="dropdown-item" href="#" data-filter="30days">Last 30 Days</a>
                    <a class="dropdown-item" href="#" data-filter="3months">Last 3 Months</a>
                    <a class="dropdown-item" href="#" data-filter="year">Last Year</a>
                </div>
            </div>
            <button class="header-button" id="download-report">
            <i class="fas fa-download"></i> Export
        </button> --}}
            <button class="header-button primary-button" id="refresh-data">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12 animated-card">
            <div class="stat-card pulse-animation">
                <div class="icon blue">
                    <i class="fas fa-user"></i>
                </div>
                <div class="title">Total Users</div>
                <div class="value blue">{{ number_format($totalUsers) }}</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 animated-card">
            <div class="stat-card pulse-animation">
                <div class="icon purple">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="title">Active Users</div>
                <div class="value purple">{{ number_format($activeUsers) }}</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 animated-card">
            <div class="stat-card pulse-animation">
                <div class="icon green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="title">Daily Active Users</div>
                <div class="value green">{{ number_format($dailyActiveUsers) }}</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 animated-card">
            <div class="stat-card pulse-animation">
                <div class="icon pink">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="title">Messages Exchanged</div>
                <div class="value pink">{{ number_format($messagesExchanged) }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <!-- User Growth Chart -->
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>User Growth</h5>
                    <div class="card-actions">
                        <button class="btn btn-sm" id="growth-type-toggle">
                            <i class="fas fa-chart-line"></i> <span>Line Chart</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Message Activity Chart -->
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daily Message Activity</h5>
                </div>
                <div class="card-body">
                    <canvas id="messageActivityChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Queries and Usage Statistics -->
    <div class="row mt-4">
        <!-- Top Queries -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Top Queries</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Query</th>
                                    <th>Count</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topQueries as $query)
                                    <tr>
                                        <td>{{ \Illuminate\Support\Str::limit($query['question'], 50) }}</td>
                                        <td>{{ $query['count'] }}</td>
                                        <td>{{ round(($query['count'] / max(1, $messagesExchanged)) * 100, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Usage Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="metric">
                                <div class="metric-title">Avg. Messages per User</div>
                                <div class="metric-value">
                                    {{ $totalUsers > 0 ? round($messagesExchanged / $totalUsers, 1) : 0 }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="metric">
                                <div class="metric-title">Retention Rate</div>
                                <div class="metric-value">
                                    {{ $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0 }}%</div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4">
                            <div class="metric">
                                <div class="metric-title">Daily Engagement</div>
                                <div class="metric-value">
                                    {{ $activeUsers > 0 ? round(($dailyActiveUsers / $activeUsers) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4">
                            <div class="metric">
                                <div class="metric-title">Messages Today</div>
                                <div class="metric-value" id="messages-today">
                                    {{ isset($messageActivityData[6]['messages']) ? $messageActivityData[6]['messages'] : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Parse the data
            const userGrowthData = JSON.parse('{!! $userGrowthData !!}');
            const messageActivityData = JSON.parse('{!! $messageActivityData !!}');

            // Extract labels and data points
            const growthLabels = userGrowthData.map(item => item.month);
            const growthValues = userGrowthData.map(item => item.users);

            const activityLabels = messageActivityData.map(item => item.day);
            const activityValues = messageActivityData.map(item => item.messages);

            // Create User Growth Chart
            const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
            const userGrowthChart = new Chart(userGrowthCtx, {
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

            // Create Message Activity Chart
            const messageActivityCtx = document.getElementById('messageActivityChart').getContext('2d');
            const messageActivityChart = new Chart(messageActivityCtx, {
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

            // Toggle between bar and line chart
            document.getElementById('growth-type-toggle').addEventListener('click', function() {
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

            // Refresh data
            document.getElementById('refresh-data').addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';

                fetch('/admin/dashboard/stats')
                    .then(response => response.json())
                    .then(data => {
                        // Update cards
                        document.querySelector('.stat-card:nth-child(1) .value').innerText =
                            numberFormat(data.totalUsers);
                        document.querySelector('.stat-card:nth-child(2) .value').innerText =
                            numberFormat(data.activeUsers);
                        document.querySelector('.stat-card:nth-child(3) .value').innerText =
                            numberFormat(data.dailyActiveUsers);
                        document.querySelector('.stat-card:nth-child(4) .value').innerText =
                            numberFormat(data.messagesExchanged);

                        // Update charts
                        userGrowthChart.data.labels = data.userGrowthData.map(item => item.month);
                        userGrowthChart.data.datasets[0].data = data.userGrowthData.map(item => item
                            .users);
                        userGrowthChart.update();

                        messageActivityChart.data.labels = data.messageActivityData.map(item => item
                            .day);
                        messageActivityChart.data.datasets[0].data = data.messageActivityData.map(
                            item => item.messages);
                        messageActivityChart.update();

                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                    })
                    .catch(error => {
                        console.error('Error refreshing data:', error);
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';

                        // Show error notification
                        showNotification('Error refreshing data', 'error');
                    });
            });

            // Export data
            document.getElementById('download-report').addEventListener('click', function() {
                // Create a PDF report or CSV export
                window.location.href = '/admin/dashboard/export';
            });

            // Helper function to format numbers
            function numberFormat(number) {
                return new Intl.NumberFormat().format(number);
            }

            // Helper function to show notifications
            function showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `
                <div class="notification-content">
                    <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
                <button class="notification-close"><i class="fas fa-times"></i></button>
            `;

                // Add to container
                const container = document.querySelector('.notification-container') || (() => {
                    const cont = document.createElement('div');
                    cont.className = 'notification-container';
                    document.body.appendChild(cont);
                    return cont;
                })();

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
        });
    </script>
    @include('admin.partials.footer')
    <script src="../../assets/js/admin/dashboard.js"></script>
</body>

</html>

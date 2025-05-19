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

    <input type="hidden" id="userGrowthData" value='{!! $userGrowthData !!}'>
    <input type="hidden" id="messageActivityData" value='{!! $messageActivityData !!}'>

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


    @include('admin.partials.footer')
    <script src="../../assets/js/admin/dashboard.js"></script>
</body>

</html>

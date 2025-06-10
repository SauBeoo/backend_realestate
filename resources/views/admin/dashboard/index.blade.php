@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-tachometer-alt me-2"></i>
            Dashboard Overview
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh-dashboard">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Analytics Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Properties
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($analytics['total_properties'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Available Properties
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($analytics['available_properties'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($analytics['total_users'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Average Price
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($analytics['average_price'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Property Statistics Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Property Listings Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="#">View Details</a>
                            <a class="dropdown-item" href="#">Export Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="propertyChart" height="320"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Type Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Property Types</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="propertyTypeChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @if(isset($propertyStats['by_type']))
                            @foreach($propertyStats['by_type'] as $type => $count)
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i> {{ ucfirst($type) }}: {{ $count }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Top Properties Row -->
    <div class="row mb-4">
        <!-- Recent Activities -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    @if($activity['action'] === 'Sold')
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-check text-white"></i>
                                        </div>
                                    @elseif($activity['action'] === 'Listed')
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-plus text-white"></i>
                                        </div>
                                    @else
                                        <div class="icon-circle bg-info">
                                            <i class="fas fa-edit text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-gray-500">{{ $activity['timestamp']->diffForHumans() }}</div>
                                    <strong>{{ $activity['title'] }}</strong> - {{ $activity['action'] }}
                                    <div class="small">by {{ $activity['owner'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No recent activities found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Properties -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Properties</h6>
                </div>
                <div class="card-body">
                    @if(isset($topProperties) && count($topProperties) > 0)
                        @foreach($topProperties as $property)
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <div class="bg-light rounded p-2">
                                        <i class="fas fa-home text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>{{ $property->title }}</strong>
                                    <div class="small text-gray-500">
                                        {{ ucfirst($property->type) }} • {{ $property->area }}m²
                                    </div>
                                    <div class="text-success font-weight-bold">
                                        ${{ number_format($property->price, 2) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No top properties data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Metrics & AI Insights Row -->
    <div class="row mb-4">
        <!-- Financial Metrics -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Financial Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-success">Total Sales</h5>
                                    <h3 class="card-text">${{ number_format($financialMetrics['total_sales_value'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-info">Total Rentals</h5>
                                    <h3 class="card-text">${{ number_format($financialMetrics['total_rental_value'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-warning">Avg Sale Price</h5>
                                    <h3 class="card-text">${{ number_format($financialMetrics['average_sale_price'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-primary">Portfolio Value</h5>
                                    <h3 class="card-text">${{ number_format($financialMetrics['total_portfolio_value'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Insights -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">AI Insights</h6>
                </div>
                <div class="card-body">
                    @if(isset($aiInsights['insights']) && count($aiInsights['insights']) > 0)
                        @foreach($aiInsights['insights'] as $insight)
                            <div class="alert alert-{{ $insight['priority'] === 'high' ? 'warning' : 'info' }} mb-3">
                                <h6 class="alert-heading">{{ $insight['title'] }}</h6>
                                <p class="mb-1">{{ $insight['message'] }}</p>
                                <hr>
                                <p class="mb-0 small">{{ $insight['action'] }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <h6>Market Sentiment</h6>
                            <p class="mb-0">{{ $aiInsights['market_sentiment'] ?? 'Stable' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Market Trends -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Market Trends & Performance</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <h5>Market Velocity</h5>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" style="width: {{ ($marketTrends['market_velocity']['market_activity'] ?? 0) }}%"></div>
                                </div>
                                <small class="text-muted">{{ $marketTrends['market_velocity']['market_activity'] ?? 0 }}% activity rate</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <h5>Avg Time to Sale</h5>
                                <h3 class="text-primary">{{ $marketTrends['market_velocity']['avg_time_to_sale'] ?? 0 }} days</h3>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <h5>Avg Time to Rent</h5>
                                <h3 class="text-info">{{ $marketTrends['market_velocity']['avg_time_to_rent'] ?? 0 }} days</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Dashboard Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.analytics.export') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <select name="format" class="form-select">
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select name="report_type" class="form-select">
                            <option value="overview">Overview</option>
                            <option value="properties">Properties</option>
                            <option value="users">Users</option>
                            <option value="financial">Financial</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ now()->subDays(30)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Property Chart
    const propertyCtx = document.getElementById('propertyChart').getContext('2d');
    new Chart(propertyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Listings',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Sales',
                data: [8, 11, 13, 15, 16, 18],
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Property Type Chart
    const typeCtx = document.getElementById('propertyTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Apartments', 'Houses', 'Villas', 'Land'],
            datasets: [{
                data: [40, 30, 20, 10],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '80%'
        }
    });

    // Refresh Dashboard
    document.getElementById('refresh-dashboard').addEventListener('click', function() {
        window.location.reload();
    });
});
</script>
@endsection

@section('styles')
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }

.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-area { position: relative; height: 320px; }
.chart-pie { position: relative; height: 245px; }
</style>
@endsection
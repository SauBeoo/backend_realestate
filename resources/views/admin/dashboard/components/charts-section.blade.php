<!-- Charts Section Component -->
<div class="row g-4 mb-4">
    <!-- Property Listings Chart -->
    <div class="col-xl-8">
        <div class="card dashboard-card shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-1">Property Listings Overview</h5>
                    <p class="text-muted small mb-0">Monthly trends and performance</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar me-1"></i> Last 6 months
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-period="3">Last 3 months</a></li>
                        <li><a class="dropdown-item" href="#" data-period="6">Last 6 months</a></li>
                        <li><a class="dropdown-item" href="#" data-period="12">Last year</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="propertyChart" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Property Type Distribution -->
    <div class="col-xl-4">
        <div class="card dashboard-card shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-1">Property Types</h5>
                <p class="text-muted small mb-0">Distribution by category</p>
            </div>
            <div class="card-body">
                <div class="chart-container mb-3">
                    <canvas id="propertyTypeChart" height="200"></canvas>
                </div>
                <div class="property-types-legend">
                    @if(isset($propertyStats['by_type']))
                        @foreach($propertyStats['by_type'] as $type => $count)
                            <div class="legend-item d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="legend-color bg-{{ $loop->index % 4 == 0 ? 'primary' : ($loop->index % 4 == 1 ? 'success' : ($loop->index % 4 == 2 ? 'info' : 'warning')) }}"></div>
                                    <span class="legend-label">{{ ucfirst($type) }}</span>
                                </div>
                                <span class="legend-value fw-bold">{{ $count }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-pie text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">No data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

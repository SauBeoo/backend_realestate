<!-- Market Trends Component -->
<div class="card dashboard-card shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="card-title mb-1">Market Trends & Performance</h5>
        <p class="text-muted small mb-0">Real-time market analysis and velocity metrics</p>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Market Activity -->
            <div class="col-md-4">
                <div class="trend-metric text-center">
                    <h6 class="trend-label text-muted mb-3">Market Velocity</h6>
                    <div class="progress-circle-container mb-3">
                        <div class="progress-circle" data-percentage="{{ ($marketTrends['market_velocity']['market_activity'] ?? 0) }}">
                            <span class="progress-value">{{ $marketTrends['market_velocity']['market_activity'] ?? 0 }}%</span>
                        </div>
                    </div>
                    <p class="trend-description text-muted small mb-0">Market activity rate</p>
                </div>
            </div>
            
            <!-- Average Time to Sale -->
            <div class="col-md-4">
                <div class="trend-metric text-center">
                    <h6 class="trend-label text-muted mb-3">Avg Time to Sale</h6>
                    <div class="trend-value-large text-primary mb-2">
                        {{ $marketTrends['market_velocity']['avg_time_to_sale'] ?? 0 }}
                    </div>
                    <p class="trend-unit text-muted">days</p>
                    <div class="trend-indicator">
                        <span class="badge bg-success-subtle text-success">
                            <i class="fas fa-arrow-down fa-xs me-1"></i>5% faster
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Average Time to Rent -->
            <div class="col-md-4">
                <div class="trend-metric text-center">
                    <h6 class="trend-label text-muted mb-3">Avg Time to Rent</h6>
                    <div class="trend-value-large text-info mb-2">
                        {{ $marketTrends['market_velocity']['avg_time_to_rent'] ?? 0 }}
                    </div>
                    <p class="trend-unit text-muted">days</p>
                    <div class="trend-indicator">
                        <span class="badge bg-info-subtle text-info">
                            <i class="fas fa-arrow-up fa-xs me-1"></i>2% slower
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Market Insights -->
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="market-insight-card bg-light rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="insight-label small text-muted mb-1">Price Growth</div>
                            <div class="insight-value text-success fw-bold">+7.2%</div>
                        </div>
                        <i class="fas fa-trending-up text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="market-insight-card bg-light rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="insight-label small text-muted mb-1">Demand Index</div>
                            <div class="insight-value text-primary fw-bold">High</div>
                        </div>
                        <i class="fas fa-fire text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

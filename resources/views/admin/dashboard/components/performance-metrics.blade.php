<!-- Performance Metrics Component -->
<div class="card dashboard-card shadow-sm">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-1">
                <i class="fas fa-tachometer-alt text-info me-2"></i>Performance Metrics
            </h5>
            <p class="text-muted small mb-0">System and business performance indicators</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-clock me-1"></i> Last 24h
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-period="24h">Last 24 hours</a></li>
                <li><a class="dropdown-item" href="#" data-period="7d">Last 7 days</a></li>
                <li><a class="dropdown-item" href="#" data-period="30d">Last 30 days</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Response Time -->
            <div class="col-md-3">
                <div class="performance-metric text-center">
                    <div class="metric-icon bg-primary-subtle text-primary mb-2">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <div class="metric-value text-primary fw-bold h5">247ms</div>
                    <div class="metric-label text-muted small">Avg Response Time</div>
                    <div class="metric-trend">
                        <span class="badge bg-success-subtle text-success">
                            <i class="fas fa-arrow-down fa-xs me-1"></i>12ms faster
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Conversion Rate -->
            <div class="col-md-3">
                <div class="performance-metric text-center">
                    <div class="metric-icon bg-success-subtle text-success mb-2">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="metric-value text-success fw-bold h5">3.2%</div>
                    <div class="metric-label text-muted small">Conversion Rate</div>
                    <div class="metric-trend">
                        <span class="badge bg-success-subtle text-success">
                            <i class="fas fa-arrow-up fa-xs me-1"></i>0.3% increase
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Page Views -->
            <div class="col-md-3">
                <div class="performance-metric text-center">
                    <div class="metric-icon bg-info-subtle text-info mb-2">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="metric-value text-info fw-bold h5">12.4K</div>
                    <div class="metric-label text-muted small">Page Views</div>
                    <div class="metric-trend">
                        <span class="badge bg-info-subtle text-info">
                            <i class="fas fa-arrow-up fa-xs me-1"></i>8% increase
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- System Health -->
            <div class="col-md-3">
                <div class="performance-metric text-center">
                    <div class="metric-icon bg-warning-subtle text-warning mb-2">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="metric-value text-warning fw-bold h5">99.8%</div>
                    <div class="metric-label text-muted small">System Uptime</div>
                    <div class="metric-trend">
                        <span class="badge bg-success-subtle text-success">
                            <i class="fas fa-check fa-xs me-1"></i>Excellent
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Performance Chart -->
        <div class="mt-4">
            <h6 class="text-muted mb-3">Performance Trend</h6>
            <div class="performance-chart-container">
                <canvas id="performanceChart" height="100"></canvas>
            </div>
        </div>
        
        <!-- Quick Performance Actions -->
        <div class="mt-4 pt-3 border-top">
            <div class="row g-2">
                <div class="col-6">
                    <button class="btn btn-outline-primary btn-sm w-100" onclick="optimizePerformance()">
                        <i class="fas fa-rocket me-1"></i> Optimize
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-info btn-sm w-100" onclick="viewDetailedMetrics()">
                        <i class="fas fa-chart-line me-1"></i> Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

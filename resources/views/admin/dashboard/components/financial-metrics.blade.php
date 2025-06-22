<!-- Financial Metrics Component -->
<div class="card dashboard-card shadow-sm">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-1">Financial Overview</h5>
            <p class="text-muted small mb-0">Revenue and financial metrics</p>
        </div>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#financialDetailsModal">
            <i class="fas fa-chart-line me-1"></i> Details
        </button>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="metric-card bg-success-subtle rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-label text-success-emphasis small mb-1">Total Sales</div>
                            <div class="metric-value text-success fw-bold h4 mb-0">
                                ${{ number_format($financialMetrics['total_sales_value'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="metric-icon text-success">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-card bg-info-subtle rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-label text-info-emphasis small mb-1">Total Rentals</div>
                            <div class="metric-value text-info fw-bold h4 mb-0">
                                ${{ number_format($financialMetrics['total_rental_value'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="metric-icon text-info">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-card bg-warning-subtle rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-label text-warning-emphasis small mb-1">Avg Sale Price</div>
                            <div class="metric-value text-warning-emphasis fw-bold h4 mb-0">
                                ${{ number_format($financialMetrics['average_sale_price'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="metric-icon text-warning">
                            <i class="fas fa-calculator fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-card bg-primary-subtle rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-label text-primary-emphasis small mb-1">Portfolio Value</div>
                            <div class="metric-value text-primary fw-bold h4 mb-0">
                                ${{ number_format($financialMetrics['total_portfolio_value'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="metric-icon text-primary">
                            <i class="fas fa-briefcase fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

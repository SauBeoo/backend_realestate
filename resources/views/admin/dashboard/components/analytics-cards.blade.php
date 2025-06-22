<!-- Analytics Cards Component -->
<div class="row g-4 mb-4">
    <!-- Total Properties Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-label text-muted mb-1">Total Properties</div>
                        <div class="stats-value text-dark">
                            {{ number_format($analytics['total_properties'] ?? 0) }}
                        </div>
                        <div class="stats-trend text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            +12% from last month
                        </div>
                    </div>
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Properties Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-label text-muted mb-1">Available Properties</div>
                        <div class="stats-value text-dark">
                            {{ number_format($analytics['available_properties'] ?? 0) }}
                        </div>
                        <div class="stats-trend text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            +8% from last month
                        </div>
                    </div>
                    <div class="stats-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-label text-muted mb-1">Total Users</div>
                        <div class="stats-value text-dark">
                            {{ number_format($analytics['total_users'] ?? 0) }}
                        </div>
                        <div class="stats-trend text-info">
                            <i class="fas fa-arrow-up me-1"></i>
                            +5% from last month
                        </div>
                    </div>
                    <div class="stats-icon bg-info">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Average Price Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stats-label text-muted mb-1">Average Price</div>
                        <div class="stats-value text-dark">
                            ${{ number_format($analytics['average_price'] ?? 0, 2) }}
                        </div>
                        <div class="stats-trend text-warning">
                            <i class="fas fa-arrow-up me-1"></i>
                            +3% from last month
                        </div>
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

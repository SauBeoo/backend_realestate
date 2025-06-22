<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card fade-in h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-title">Total Customers</div>
                        <div class="stat-value">{{ number_format($statistics['total_users']) }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-chart-line me-1"></i>
                            All registered users
                        </div>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-success fade-in h-100" style="animation-delay: 0.1s">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-title">New This Month</div>
                        <div class="stat-value">{{ number_format($statistics['new_this_month']) }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-calendar-plus me-1"></i>
                            Recent registrations
                        </div>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-info fade-in h-100" style="animation-delay: 0.2s">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-title">Active Users</div>
                        <div class="stat-value">{{ number_format($statistics['active_users']) }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-heartbeat me-1"></i>
                            Currently active
                        </div>
                    </div>
                    <div class="icon-circle bg-info">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-warning fade-in h-100" style="animation-delay: 0.3s">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-title">With Properties</div>
                        <div class="stat-value">{{ number_format($statistics['users_with_properties']) }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-building me-1"></i>
                            Property owners
                        </div>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- Quick Actions Component -->
<div class="card dashboard-card shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="card-title mb-1">
            <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
        </h5>
        <p class="text-muted small mb-0">Frequently used operations</p>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('admin.properties.create') }}" class="btn btn-outline-primary w-100 quick-action-btn">
                    <i class="fas fa-plus me-2"></i>
                    <div>
                        <div class="fw-semibold">Add Property</div>
                        <small class="text-muted">Create new listing</small>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info w-100 quick-action-btn">
                    <i class="fas fa-users me-2"></i>
                    <div>
                        <div class="fw-semibold">Manage Users</div>
                        <small class="text-muted">User administration</small>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-outline-success w-100 quick-action-btn" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                    <i class="fas fa-tasks me-2"></i>
                    <div>
                        <div class="fw-semibold">Bulk Actions</div>
                        <small class="text-muted">Mass operations</small>
                    </div>
                </button>
            </div>
            <div class="col-md-6">
                <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-warning w-100 quick-action-btn">
                    <i class="fas fa-chart-bar me-2"></i>
                    <div>
                        <div class="fw-semibold">Full Analytics</div>
                        <small class="text-muted">Detailed reports</small>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Recent Shortcuts -->
        <div class="mt-4 pt-3 border-top">
            <h6 class="text-muted mb-3">Recent Shortcuts</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-sm btn-light">
                    <i class="fas fa-search me-1"></i> Search Properties
                </a>
                <a href="#" class="btn btn-sm btn-light">
                    <i class="fas fa-file-export me-1"></i> Export Data
                </a>
                <a href="#" class="btn btn-sm btn-light">
                    <i class="fas fa-cog me-1"></i> Settings
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tasks me-2"></i>Bulk Actions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-edit text-primary me-3"></i>
                            <div>
                                <div class="fw-semibold">Update Property Status</div>
                                <small class="text-muted">Change status for multiple properties</small>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-info me-3"></i>
                            <div>
                                <div class="fw-semibold">Send Notifications</div>
                                <small class="text-muted">Notify multiple users</small>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-archive text-warning me-3"></i>
                            <div>
                                <div class="fw-semibold">Archive Properties</div>
                                <small class="text-muted">Archive old listings</small>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-download text-success me-3"></i>
                            <div>
                                <div class="fw-semibold">Bulk Export</div>
                                <small class="text-muted">Export selected data</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

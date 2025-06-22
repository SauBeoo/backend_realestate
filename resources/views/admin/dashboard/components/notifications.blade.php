<!-- Dashboard Loading Component -->
<div class="dashboard-loading d-none" id="dashboardLoading">
    <div class="loading-overlay">
        <div class="loading-content text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>Loading Dashboard...</h5>
            <p class="text-muted">Fetching the latest data</p>
        </div>
    </div>
</div>

<!-- Error Alert Component -->
<div class="alert alert-danger alert-dismissible fade" id="dashboardError" role="alert" style="display: none;">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <div>
            <strong>Dashboard Error</strong>
            <div class="error-message">Unable to load dashboard data</div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Success Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span class="toast-message">Operation completed successfully</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Info Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="top: 60px !important;">
    <div id="infoToast" class="toast align-items-center text-white bg-info border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-info-circle me-2"></i>
                <span class="toast-message">Information updated</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Warning Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="top: 120px !important;">
    <div id="warningToast" class="toast align-items-center text-white bg-warning border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span class="toast-message">Warning message</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Offline Indicator -->
<div class="alert alert-warning position-fixed bottom-0 start-50 translate-middle-x mb-3 d-none" id="offlineIndicator">
    <i class="fas fa-wifi me-2"></i>
    <strong>Connection Lost</strong> - Dashboard will update when connection is restored
</div>

<!-- Real-time Status Indicator -->
<div class="real-time-status position-fixed bottom-0 end-0 m-3" id="realTimeStatus">
    <div class="status-indicator bg-success rounded-pill px-3 py-2 text-white small">
        <i class="fas fa-circle fa-xs me-2"></i>
        <span class="status-text">Live</span>
    </div>
</div>

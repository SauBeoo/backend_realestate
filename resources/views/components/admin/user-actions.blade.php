<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="bulkActionModalLabel">
                    <i class="fas fa-tasks me-2"></i>Bulk Actions
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($bulkActionRoute) }}" method="POST" id="bulkActionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-list me-1"></i>Select Action
                        </label>
                        <select name="action" class="form-select" required>
                            <option value="">Choose action...</option>
                            <option value="activate">
                                <i class="fas fa-check-circle"></i> Activate Selected Customers
                            </option>
                            <option value="deactivate">
                                <i class="fas fa-pause-circle"></i> Deactivate Selected Customers
                            </option>
                            <option value="suspend">
                                <i class="fas fa-ban"></i> Suspend Selected Customers
                            </option>
                            <option value="unsuspend">
                                <i class="fas fa-play-circle"></i> Unsuspend Selected Customers
                            </option>
                            <option value="delete" class="text-danger">
                                <i class="fas fa-trash"></i> Delete Selected Customers
                            </option>
                        </select>
                    </div>
                    <div class="alert alert-info border-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>Selected customers: <span id="selectedCount" class="text-primary">0</span></strong>
                                <br><small class="text-muted">Please select at least one customer to perform bulk actions.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="executeActionBtn" disabled>
                        <i class="fas fa-play me-1"></i>Execute Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-download me-2"></i>Export Customers
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($exportRoute) }}" method="GET" id="exportForm">
                <input type="hidden" name="report_type" value="users">
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-file-export me-1"></i>Export Format
                        </label>
                        <select name="format" class="form-select">
                            <option value="excel">
                                <i class="fas fa-file-excel"></i> Excel (.xlsx)
                            </option>
                            <option value="csv">
                                <i class="fas fa-file-csv"></i> CSV (.csv)
                            </option>
                            <option value="pdf">
                                <i class="fas fa-file-pdf"></i> PDF (.pdf)
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>From Date
                            </label>
                            <input type="date" name="date_from" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>To Date
                            </label>
                            <input type="date" name="date_to" class="form-control">
                        </div>
                    </div>
                    <div class="alert alert-warning border-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <small>If no date range is selected, all customers will be exported.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download me-1"></i>Export Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 
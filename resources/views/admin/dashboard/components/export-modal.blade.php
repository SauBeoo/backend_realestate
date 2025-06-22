<!-- Export Modal Component -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-download me-2"></i>Export Dashboard Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.analytics.export') }}" method="GET" id="exportForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Export Format</label>
                            <select name="format" class="form-select">
                                <option value="excel">
                                    <i class="fas fa-file-excel"></i> Excel Spreadsheet
                                </option>
                                <option value="pdf">
                                    <i class="fas fa-file-pdf"></i> PDF Document
                                </option>
                                <option value="csv">
                                    <i class="fas fa-file-csv"></i> CSV File
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Report Type</label>
                            <select name="report_type" class="form-select">
                                <option value="overview">Dashboard Overview</option>
                                <option value="properties">Property Analytics</option>
                                <option value="users">User Statistics</option>
                                <option value="financial">Financial Report</option>
                                <option value="market">Market Analysis</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="date_from" class="form-control" 
                                   value="{{ now()->subDays(30)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="date_to" class="form-control" 
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Additional Options</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="include_charts" id="includeCharts" checked>
                                        <label class="form-check-label" for="includeCharts">
                                            Include Charts
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="include_summary" id="includeSummary" checked>
                                        <label class="form-check-label" for="includeSummary">
                                            Include Summary
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="detailed_breakdown" id="detailedBreakdown">
                                        <label class="form-check-label" for="detailedBreakdown">
                                            Detailed Breakdown
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="submit" form="exportForm" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Export Data
                </button>
            </div>
        </div>
    </div>
</div>

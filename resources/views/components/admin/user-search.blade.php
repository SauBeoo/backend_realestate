<div class="card search-card fade-in border-0" style="animation-delay: 0.4s">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="icon-circle bg-light me-3">
                <i class="fas fa-search"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold">Search & Filter Customers</h5>
                <p class="text-muted mb-0 small">Find customers by name, email, or date range</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route($routeName) }}" class="search-form">
            <div class="row g-4">
                <div class="col-md-5">
                    <label class="form-label fw-bold">
                        <i class="fas fa-search me-2 text-primary"></i>Search Customers
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by name, email, or phone..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2 text-success"></i>From Date
                    </label>
                    <input type="date" 
                           name="date_from" 
                           class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-check me-2 text-info"></i>To Date
                    </label>
                    <input type="date" 
                           name="date_to" 
                           class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request()->hasAny(['search', 'date_from', 'date_to']))
                            <a href="{{ route($routeName) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Advanced Filters Toggle --}}
            <div class="mt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="advancedFilters">
                        <label class="form-check-label fw-bold text-muted" for="advancedFilters">
                            <i class="fas fa-filter me-1"></i>Advanced Filters
                        </label>
                    </div>
                    @if(request()->hasAny(['search', 'date_from', 'date_to']))
                        <div class="badge bg-primary-subtle">
                            <i class="fas fa-filter me-1"></i>
                            {{ collect(request()->only(['search', 'date_from', 'date_to']))->filter()->count() }} filters active
                        </div>
                    @endif
                </div>
                
                <div id="advancedFiltersContent" class="collapse mt-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">User Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">User Type</label>
                            <select name="user_type" class="form-select">
                                <option value="">All Types</option>
                                <option value="buyer">Buyer</option>
                                <option value="seller">Seller</option>
                                <option value="agent">Agent</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Verification Status</label>
                            <select name="verified" class="form-select">
                                <option value="">All Users</option>
                                <option value="1">Verified Only</option>
                                <option value="0">Unverified Only</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div> 
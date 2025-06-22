<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stats-value">{{ $totalCount ?? $statistics['total'] ?? 0 }}</div>
                <div class="stats-label">Total Properties</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-value">{{ $statistics['available'] ?? 0 }}</div>
                <div class="stats-label">Available</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-value">{{ $statistics['pending'] ?? 0 }}</div>
                <div class="stats-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-icon bg-danger">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stats-value">{{ $statistics['sold'] ?? 0 }}</div>
                <div class="stats-label">Sold</div>
            </div>
        </div>
    </div>
</div>

@if(isset($statistics['for_sale']) || isset($statistics['for_rent']))
<div class="row mb-4">
    <div class="col-lg-6 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="stats-value">{{ $statistics['for_sale'] ?? 0 }}</div>
                <div class="stats-label">For Sale</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="stats-icon bg-info">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stats-value">{{ $statistics['for_rent'] ?? 0 }}</div>
                <div class="stats-label">For Rent</div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.stats-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    cursor: pointer;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.stats-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin: 0 auto 1rem;
}

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stats-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}
</style>
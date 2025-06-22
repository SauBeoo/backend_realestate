<!-- Top Properties Component -->
<div class="card dashboard-card shadow-sm h-100">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-1">Top Properties</h5>
            <p class="text-muted small mb-0">Highest valued properties</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-filter="price">By Price</a></li>
                <li><a class="dropdown-item" href="#" data-filter="area">By Area</a></li>
                <li><a class="dropdown-item" href="#" data-filter="recent">Most Recent</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body p-0">
        @if(isset($topProperties) && count($topProperties) > 0)
            <div class="property-list">
                @foreach($topProperties as $property)
                    <div class="property-item d-flex align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="property-image me-3">
                            <div class="image-placeholder bg-light rounded d-flex align-items-center justify-content-center">
                                <i class="fas fa-home text-muted"></i>
                            </div>
                        </div>
                        <div class="property-info flex-grow-1">
                            <div class="property-title fw-semibold mb-1">{{ $property->title }}</div>
                            <div class="property-meta d-flex align-items-center mb-2">
                                <span class="property-type badge bg-light text-dark me-2">
                                    {{ ucfirst($property->propertyType?->name ?? 'Unknown') }}
                                </span>
                                <span class="property-area text-muted small">
                                    <i class="fas fa-expand-arrows-alt me-1"></i>{{ $property->area }}m²
                                </span>
                            </div>
                            <div class="property-price text-success fw-bold">
                                ${{ number_format($property->price, 2) }}
                            </div>
                        </div>
                        <div class="property-actions">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state text-center py-5">
                <i class="fas fa-building text-muted fa-3x mb-3"></i>
                <h6 class="text-muted">No Properties Found</h6>
                <p class="text-muted small mb-0">Top properties will appear here</p>
            </div>
        @endif
    </div>
</div>

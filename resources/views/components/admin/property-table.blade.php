<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Properties List
        </h5>
        <span class="badge bg-primary">
            @if(method_exists($properties, 'total'))
                {{ $properties->total() }}
            @else
                {{ $properties->count() }}
            @endif
            Properties
        </span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Property</th>
                        <th style="width: 120px;">Type</th>
                        <th style="width: 140px;">Price</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 180px;">Owner</th>
                        <th style="width: 160px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($properties as $property)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark">#{{ $property->id }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-home"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $property->title }}</h6>
                                    @if(isset($property->address) && $property->address)
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $property->address }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ ucfirst($property->propertyType?->name ?? 'Unknown') }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-success">
                                ${{ number_format($property->price, 0) }}
                            </strong>
                        </td>
                        <td>
                            @php
                                $statusKey = $property->propertyStatus?->key ?? 'unknown';
                                $statusClass = match ($statusKey) {
                                    'for_sale' => 'bg-success',
                                    'for_rent' => 'bg-primary',
                                    'sold' => 'bg-danger',
                                    'rented' => 'bg-info',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ $property->propertyStatus?->name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            @if(isset($property->owner) && $property->owner)
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($property->owner->name) }}&size=32&background=4f46e5&color=fff" 
                                         class="rounded-circle me-2" width="32" height="32" alt="Owner">
                                    <div>
                                        <div class="small fw-medium">{{ $property->owner->name }}</div>
                                        @if(isset($property->owner->email))
                                            <div class="small text-muted">{{ $property->owner->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-user-slash me-1"></i>
                                    No Owner
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <x-admin.property-actions :property="$property" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-home fa-3x mb-3"></i>
                                <h5>No Properties Found</h5>
                                <p>There are no properties matching your search criteria.</p>
                                <a href="{{ route('admin.properties.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Add First Property
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if(method_exists($properties, 'hasPages') && $properties->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="text-muted small">
                    @if(method_exists($properties, 'firstItem') && $properties->firstItem())
                        Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} of {{ $properties->total() }} results
                    @else
                        Showing results
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                {{ $properties->appends(request()->query())->links('custom-pagination') }}
            </div>
        </div>
    </div>
    @endif
</div>
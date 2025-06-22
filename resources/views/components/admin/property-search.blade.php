<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-search me-2"></i>
            Search Properties
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.properties.index') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Search by title, description, address..." 
                           value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type->key }}" 
                                {{ ($filters['type'] ?? '') == $type->key ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        @foreach($propertyStatuses as $status)
                            <option value="{{ $status->key }}" 
                                {{ ($filters['status'] ?? '') == $status->key ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="min_price" class="form-label">Min Price</label>
                    <input type="number" class="form-control" id="min_price" name="min_price" 
                           placeholder="0" value="{{ $filters['min_price'] ?? '' }}">
                </div>
                <div class="col-md-2 mb-3 d-grid">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        Search
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="max_price" class="form-label">Max Price</label>
                    <input type="number" class="form-control" id="max_price" name="max_price" 
                           placeholder="999999999" value="{{ $filters['max_price'] ?? '' }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="bedrooms" class="form-label">Bedrooms</label>
                    <select class="form-select" id="bedrooms" name="bedrooms">
                        <option value="">Any</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" 
                                {{ ($filters['bedrooms'] ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }}{{ $i == 5 ? '+' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-8 mb-3 d-grid align-self-end">
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>
                        Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
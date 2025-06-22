@extends('admin.layouts.app')

@section('title', 'Property Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Property Details: {{ $property->title }}</h1>
        <div>
            <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
            <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Property Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">{{ $property->title }}</h6>
                            <p class="text-muted">{{ $property->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Price:</strong></td>
                                    <td class="text-success">${{ number_format($property->price, 0) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $property->propertyType?->name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statusClass = match ($property->propertyStatus?->key ?? 'unknown') {
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
                                </tr>
                                <tr>
                                    <td><strong>Area:</strong></td>
                                    <td>{{ $property->area }} m²</td>
                                </tr>
                                <tr>
                                    <td><strong>Bedrooms:</strong></td>
                                    <td>{{ $property->bedrooms }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bathrooms:</strong></td>
                                    <td>{{ $property->bathrooms }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6><strong>Address:</strong></h6>
                        <p>{{ $property->address }}</p>
                        @if($property->latitude && $property->longitude)
                            <p><small class="text-muted">Coordinates: {{ $property->latitude }}, {{ $property->longitude }}</small></p>
                        @endif
                    </div>
                    
                    @if($property->features && count($property->features) > 0)
                    <div class="mb-3">
                        <h6><strong>Features:</strong></h6>
                        <div class="row">
                            @foreach($property->features as $feature)
                                <div class="col-md-6 mb-2">
                                    <i class="fas fa-check text-success me-2"></i>{{ $feature }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            @if($property->owner)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Owner Information</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($property->owner->name) }}&size=80&background=4f46e5&color=fff" 
                         class="rounded-circle mb-3" width="80" height="80" alt="Owner">
                    <h6>{{ $property->owner->name }}</h6>
                    <p class="text-muted">{{ $property->owner->email }}</p>
                    @if(isset($property->owner->phone))
                        <p class="text-muted">{{ $property->owner->phone }}</p>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Property
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $property->id }}, '{{ $property->title }}')">
                            <i class="fas fa-trash me-2"></i>Delete Property
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id, title) {
    if (confirm(`Are you sure you want to delete the property "${title}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/properties/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush 
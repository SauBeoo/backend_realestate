@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Property: {{ $property->title }}</h1>
        <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.properties.update', $property->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $property->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $property->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $property->price) }}" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="area" class="form-label">Area (sqft/sqm) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="area" name="area" value="{{ old('area', $property->area) }}" step="0.01" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bedrooms" class="form-label">Bedrooms <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="bedrooms" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bathrooms" class="form-label">Bathrooms <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="bathrooms" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $property->address) }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $property->latitude) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $property->longitude) }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="features_text" class="form-label">Features (comma-separated)</label>
                            <input type="text" class="form-control" id="features_text" name="features_text" value="{{ old('features_text', is_array($property->features) ? implode(', ', $property->features) : $property->features) }}">
                            <small class="form-text text-muted">Convert this to a proper array input later.</small>
                            <input type="hidden" name="features[]" value=""> 
                        </div>

                        <div class="mb-3">
                            <label for="images_text" class="form-label">Image URLs (comma-separated)</label>
                            <input type="text" class="form-control" id="images_text" name="images_text" value="{{ old('images_text', is_array($property->images) ? implode(', ', $property->images) : $property->images) }}">
                            <small class="form-text text-muted">Convert this to a proper array input later, and consider file uploads.</small>
                            <input type="hidden" name="images[]" value=""> 
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="apartment" {{ old('type', $property->type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="house" {{ old('type', $property->type) == 'house' ? 'selected' : '' }}>House</option>
                                <option value="land" {{ old('type', $property->type) == 'land' ? 'selected' : '' }}>Land</option>
                                <option value="villa" {{ old('type', $property->type) == 'villa' ? 'selected' : '' }}>Villa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="for_sale" {{ old('status', $property->status) == 'for_sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="for_rent" {{ old('status', $property->status) == 'for_rent' ? 'selected' : '' }}>For Rent</option>
                                <option value="sold" {{ old('status', $property->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="owner_id" class="form-label">Owner ID <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="owner_id" name="owner_id" value="{{ old('owner_id', $property->owner_id) }}" required>
                            <small class="form-text text-muted">Ideally, this should be a select dropdown of users.</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary w-100">Update Property</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const featuresText = document.getElementById('features_text');
            if (featuresText && featuresText.value.trim() !== '') {
                const featuresArray = featuresText.value.split(',').map(item => item.trim()).filter(item => item);
                document.querySelectorAll('input[name="features[]"]').forEach(inp => inp.remove());
                featuresArray.forEach(feature => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'features[]';
                    input.value = feature;
                    form.appendChild(input);
                });
            } else {
                const existingFeatures = document.querySelector('input[name="features[]"]');
                if (existingFeatures && existingFeatures.value === '') {}
                else if (!existingFeatures) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'features[]';
                    input.value = '';
                    form.appendChild(input);
                }
            }

            const imagesText = document.getElementById('images_text');
            if (imagesText && imagesText.value.trim() !== '') {
                const imagesArray = imagesText.value.split(',').map(item => item.trim()).filter(item => item && item.startsWith('http'));
                document.querySelectorAll('input[name="images[]"]').forEach(inp => inp.remove());
                imagesArray.forEach(image => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'images[]';
                    input.value = image;
                    form.appendChild(input);
                });
            } else {
                 const existingImages = document.querySelector('input[name="images[]"]');
                 if (existingImages && existingImages.value === '') {}
                 else if (!existingImages) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'images[]';
                    input.value = '';
                    form.appendChild(input);
                 }
            }
        });
    }
});
</script>
@endpush 
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Properties</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.properties.create') }}" class="btn btn-sm btn-primary">
                Create New Property
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Owner</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($properties as $property)
                <tr>
                    <td>{{ $property->id }}</td>
                    <td>{{ $property->title }}</td>
                    <td>{{ ucfirst($property->type) }}</td>
                    <td>${{ number_format($property->price, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $property->status)) }}</td>
                    <td>{{ $property->owner->name ?? 'N/A' }}</td> {{-- Assuming User model has 'name' --}}
                    <td>
                        <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this property?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No properties found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($properties->hasPages())
    <div class="mt-3">
        {{ $properties->links() }} 
    </div>
    @endif
</div>
@endsection 
<div class="btn-group btn-group-sm" role="group">
    <a href="#" class="btn btn-outline-info" title="View" data-bs-toggle="tooltip">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.properties.edit', $property->id) }}" 
       class="btn btn-outline-warning" title="Edit" data-bs-toggle="tooltip">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" 
            class="btn btn-outline-danger delete-btn" 
            data-id="{{ $property->id }}"
            data-title="{{ $property->title }}"
            title="Delete" 
            data-bs-toggle="tooltip">
        <i class="fas fa-trash"></i>
    </button>
</div>
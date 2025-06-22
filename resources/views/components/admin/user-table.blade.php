<div class="card table-card fade-in border-0" style="animation-delay: 0.5s">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="icon-circle bg-light me-3">
                    <i class="fas fa-table"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Customers Directory</h5>
                    <p class="text-muted mb-0 small">Manage and view all customer information</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary">
                    <i class="fas fa-users me-1"></i>
                    {{ number_format($users->total()) }} customers
                </span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if(count($users) > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="usersTable">
                    <thead>
                        <tr>
                            <th class="ps-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                    <label class="form-check-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none d-flex align-items-center">
                                    <span class="me-1">ID</span>
                                    @if(request('sort_by') === 'id')
                                        <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @else
                                        <i class="fas fa-sort text-muted opacity-50"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'first_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none d-flex align-items-center">
                                    <span class="me-1">Customer</span>
                                    @if(request('sort_by') === 'first_name')
                                        <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @else
                                        <i class="fas fa-sort text-muted opacity-50"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Email & Contact</th>
                            <th>Properties</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none d-flex align-items-center">
                                    <span class="me-1">Joined</span>
                                    @if(request('sort_by') === 'created_at')
                                        <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @else
                                        <i class="fas fa-sort text-muted opacity-50"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Status</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="user-row">
                                <td class="ps-4">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               name="user_ids[]" 
                                               value="{{ $user->id }}" 
                                               class="form-check-input user-checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light fw-bold">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <div class="avatar-initial">
                                                {{ $user->initials }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->full_name }}</div>
                                            @if($user->user_type && $user->user_type !== 'buyer')
                                                <div class="badge bg-info-subtle mt-1">
                                                    <i class="fas fa-tag me-1"></i>{{ ucfirst($user->user_type) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $user->email ?? 'No email' }}</div>
                                        @if(isset($user->phone) && $user->phone)
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                                            </small>
                                        @elseif(isset($user->created_at))
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>{{ $user->created_at->format('M d') }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $propertiesCount = $user->properties ? $user->properties->count() : 0;
                                    @endphp
                                    @if($propertiesCount > 0)
                                        <span class="badge bg-success-subtle">
                                            <i class="fas fa-home me-1"></i>{{ $propertiesCount }}
                                        </span>
                                    @else
                                        <span class="badge bg-light">
                                            <i class="fas fa-minus me-1"></i>None
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $user->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($user->status === 'active')
                                            <span class="badge bg-success-subtle">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                        @elseif($user->status === 'inactive')
                                            <span class="badge bg-secondary-subtle">
                                                <i class="fas fa-pause-circle me-1"></i>Inactive
                                            </span>
                                        @elseif($user->status === 'suspended')
                                            <span class="badge bg-danger-subtle">
                                                <i class="fas fa-ban me-1"></i>Suspended
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                        @if($user->is_verified ?? false)
                                            <span class="badge bg-primary-subtle">
                                                <i class="fas fa-shield-check me-1"></i>Verified
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="View Details"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit User"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" 
                                              method="POST" 
                                              style="display: inline;" 
                                               onsubmit="return confirmDelete('{{ str_replace("'", "\\'", $user->full_name) }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete User"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Enhanced Pagination --}}
            @if(method_exists($users, 'hasPages') && $users->hasPages())
                <div class="card-footer bg-white border-top">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                @if(method_exists($users, 'firstItem') && $users->firstItem())
                                    Showing <strong>{{ $users->firstItem() }}</strong> to <strong>{{ $users->lastItem() }}</strong> 
                                    of <strong>{{ number_format($users->total()) }}</strong> results
                                @else
                                    Showing results
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            {{ $users->appends(request()->query())->links('custom-pagination') }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="icon-circle mx-auto mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="text-muted mb-3">No customers found</h4>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'date_from', 'date_to']))
                        No customers match your search criteria. Try adjusting your filters.
                    @else
                        You haven't added any customers yet. Get started by adding your first customer.
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    @if(request()->hasAny(['search', 'date_from', 'date_to']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-refresh me-2"></i>Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Customer
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

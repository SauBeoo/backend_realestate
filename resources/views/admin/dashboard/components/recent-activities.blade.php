<!-- Recent Activities Component -->
<div class="card dashboard-card shadow-sm h-100">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-1">Recent Activities</h5>
            <p class="text-muted small mb-0">Latest property and user activities</p>
        </div>
        <a href="#" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-eye me-1"></i> View All
        </a>
    </div>
    <div class="card-body p-0">
        @if(isset($recentActivities) && count($recentActivities) > 0)
            <div class="activity-list">
                @foreach($recentActivities as $activity)
                    <div class="activity-item d-flex align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="activity-icon me-3">
                            @if($activity['action'] === 'Sold')
                                <div class="icon-wrapper bg-success-subtle text-success">
                                    <i class="fas fa-check"></i>
                                </div>
                            @elseif($activity['action'] === 'Listed')
                                <div class="icon-wrapper bg-primary-subtle text-primary">
                                    <i class="fas fa-plus"></i>
                                </div>
                            @else
                                <div class="icon-wrapper bg-info-subtle text-info">
                                    <i class="fas fa-edit"></i>
                                </div>
                            @endif
                        </div>
                        <div class="activity-content flex-grow-1">
                            <div class="activity-title fw-semibold mb-1">{{ $activity['title'] }}</div>
                            <div class="activity-meta d-flex align-items-center">
                                <span class="activity-action badge bg-{{ $activity['action'] === 'Sold' ? 'success' : ($activity['action'] === 'Listed' ? 'primary' : 'info') }}">
                                    {{ $activity['action'] }}
                                </span>
                                <span class="activity-owner text-muted ms-2">by {{ $activity['owner'] }}</span>
                            </div>
                        </div>
                        <div class="activity-time text-muted small">
                            {{ $activity['timestamp']->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state text-center py-5">
                <i class="fas fa-history text-muted fa-3x mb-3"></i>
                <h6 class="text-muted">No Recent Activities</h6>
                <p class="text-muted small mb-0">Activities will appear here as they happen</p>
            </div>
        @endif
    </div>
</div>

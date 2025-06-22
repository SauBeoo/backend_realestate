<!-- AI Insights Component -->
<div class="card dashboard-card shadow-sm">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-1">
                <i class="fas fa-robot text-primary me-2"></i>AI Insights
            </h5>
            <p class="text-muted small mb-0">Intelligent market analysis</p>
        </div>
        <div class="status-indicator">
            <span class="badge bg-success">
                <i class="fas fa-circle fa-xs me-1"></i>Active
            </span>
        </div>
    </div>
    <div class="card-body">
        @if(isset($aiInsights['insights']) && count($aiInsights['insights']) > 0)
            <div class="insights-list">
                @foreach($aiInsights['insights'] as $insight)
                    <div class="insight-item mb-3">
                        <div class="alert alert-{{ $insight['priority'] === 'high' ? 'warning' : 'info' }} border-0 mb-0">
                            <div class="d-flex align-items-start">
                                <div class="insight-icon me-3">
                                    <i class="fas fa-{{ $insight['priority'] === 'high' ? 'exclamation-triangle' : 'lightbulb' }}"></i>
                                </div>
                                <div class="insight-content flex-grow-1">
                                    <h6 class="insight-title mb-2">{{ $insight['title'] }}</h6>
                                    <p class="insight-message mb-2">{{ $insight['message'] }}</p>
                                    <div class="insight-action">
                                        <small class="text-muted">
                                            <i class="fas fa-arrow-right me-1"></i>{{ $insight['action'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="insight-item">
                <div class="alert alert-info border-0 mb-0">
                    <div class="d-flex align-items-center">
                        <div class="insight-icon me-3">
                            <i class="fas fa-chart-trending-up"></i>
                        </div>
                        <div class="insight-content">
                            <h6 class="mb-1">Market Sentiment</h6>
                            <p class="mb-0">{{ $aiInsights['market_sentiment'] ?? 'Stable market conditions with moderate activity' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="ai-actions mt-3 pt-3 border-top">
            <div class="row g-2">
                <div class="col-6">
                    <button class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-sync me-1"></i> Refresh Analysis
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-cog me-1"></i> Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

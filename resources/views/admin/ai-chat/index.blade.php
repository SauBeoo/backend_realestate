@extends('admin.layouts.app')

@section('title', 'AI Chat Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-robot me-2"></i>
            AI Chat Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.ai-chat.services') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-cogs"></i> AI Services
                </a>
                <a href="{{ route('admin.ai-chat.analytics') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-chart-bar"></i> Analytics
                </a>
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#testServiceModal">
                    <i class="fas fa-play"></i> Test AI Service
                </button>
            </div>
        </div>
    </div>

    <!-- Analytics Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sessions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['chat_statistics']['total_sessions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['chat_statistics']['active_sessions_today']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-dots fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">User Satisfaction</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analytics['chat_statistics']['user_satisfaction'] }}/5</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Monthly Cost</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($analytics['chat_statistics']['total_cost_this_month'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Services Overview & Recent Chats Row -->
    <div class="row mb-4">
        <!-- AI Services Usage -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">AI Services Usage</h6>
                    <a href="{{ route('admin.ai-chat.services') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-cogs"></i> Manage Services
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Legal Support -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-gradient-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Hỗ trợ pháp lý</h5>
                                            <h3 class="mb-0">{{ $analytics['service_usage']['legal_support']['count'] }}</h3>
                                            <small>{{ $analytics['service_usage']['legal_support']['percentage'] }}% of total</small>
                                        </div>
                                        <div>
                                            <i class="fas fa-gavel fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Valuation -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-gradient-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Định giá BĐS</h5>
                                            <h3 class="mb-0">{{ $analytics['service_usage']['property_valuation']['count'] }}</h3>
                                            <small>{{ $analytics['service_usage']['property_valuation']['percentage'] }}% of total</small>
                                        </div>
                                        <div>
                                            <i class="fas fa-calculator fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Negotiation Consulting -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-gradient-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">Tư vấn đàm phán</h5>
                                            <h3 class="mb-0">{{ $analytics['service_usage']['negotiation_consulting']['count'] }}</h3>
                                            <small>{{ $analytics['service_usage']['negotiation_consulting']['percentage'] }}% of total</small>
                                        </div>
                                        <div>
                                            <i class="fas fa-handshake fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="row mt-4">
                        <div class="col-md-3 text-center">
                            <h5>Response Time</h5>
                            <h3 class="text-success">{{ $analytics['response_performance']['average_response_time'] }}s</h3>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5>Success Rate</h5>
                            <h3 class="text-info">{{ $analytics['response_performance']['successful_responses'] }}%</h3>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5>Error Rate</h5>
                            <h3 class="text-warning">{{ $analytics['response_performance']['error_rate'] }}%</h3>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5>Timeout Rate</h5>
                            <h3 class="text-danger">{{ $analytics['response_performance']['timeout_rate'] }}%</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Chat Sessions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Chat Sessions</h6>
                    <a href="{{ route('admin.ai-chat.sessions') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if(count($recentChats) > 0)
                        @foreach($recentChats as $chat)
                            <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                <div class="mr-3">
                                    @if($chat['status'] === 'active')
                                        <div class="bg-success rounded-circle" style="width: 10px; height: 10px;"></div>
                                    @else
                                        <div class="bg-gray-400 rounded-circle" style="width: 10px; height: 10px;"></div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-gray-500">{{ $chat['started_at']->diffForHumans() }}</div>
                                    <strong>{{ $chat['service'] }}</strong>
                                    <div class="small">User ID: {{ $chat['user_id'] }} • {{ $chat['messages_count'] }} messages</div>
                                    <span class="badge badge-{{ $chat['status'] === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($chat['status']) }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ route('admin.ai-chat.sessions.show', $chat['id']) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center">No recent chat sessions</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- User Satisfaction & Popular Queries Row -->
    <div class="row mb-4">
        <!-- User Satisfaction Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Satisfaction Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="satisfactionChart"></canvas>
                    </div>
                    <div class="mt-4">
                        @foreach($analytics['user_satisfaction'] as $level => $percentage)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ ucfirst(str_replace('_', ' ', $level)) }}</span>
                                <span class="font-weight-bold">{{ $percentage }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Queries -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Popular Queries</h6>
                </div>
                <div class="card-body">
                    @if(count($analytics['popular_queries']) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($analytics['popular_queries'] as $index => $query)
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">#{{ $index + 1 }}</div>
                                        <p class="mb-1">{{ $query }}</p>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">Frequent</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center">No popular queries data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- AI Services Configuration Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">AI Services Configuration</h6>
                    <a href="{{ route('admin.ai-chat.services') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-cogs"></i> Configure Services
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h5>Total Services</h5>
                            <h3 class="text-primary">{{ $aiServices['total_services'] }}</h3>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5>Active Services</h5>
                            <h3 class="text-success">{{ $aiServices['active_services'] }}</h3>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5>API Calls Today</h5>
                            <h3 class="text-info">{{ $aiServices['total_api_calls_today'] }}</h3>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5>Avg Cost/Call</h5>
                            <h3 class="text-warning">${{ number_format($aiServices['average_cost_per_call'], 3) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Service Modal -->
<div class="modal fade" id="testServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test AI Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="testServiceForm">
                    <div class="mb-3">
                        <label class="form-label">AI Service</label>
                        <select name="service_type" class="form-select" required>
                            <option value="">Select a service...</option>
                            <option value="legal_support">Legal Support (Hỗ trợ pháp lý)</option>
                            <option value="property_valuation">Property Valuation (Định giá BĐS)</option>
                            <option value="negotiation_consulting">Negotiation Consulting (Tư vấn đàm phán)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Test Query</label>
                        <textarea name="test_query" class="form-control" rows="4" placeholder="Enter your test query here..." required></textarea>
                    </div>
                    <div id="testResult" class="alert alert-info d-none">
                        <h6>Test Result:</h6>
                        <div id="testResponse"></div>
                        <hr>
                        <small>
                            <strong>Response Time:</strong> <span id="responseTime"></span>ms |
                            <strong>Tokens Used:</strong> <span id="tokensUsed"></span> |
                            <strong>Cost:</strong> $<span id="costEstimate"></span>
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="testServiceBtn">
                    <i class="fas fa-play"></i> Test Service
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Satisfaction Chart
    const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
    const satisfactionData = @json($analytics['user_satisfaction']);
    
    new Chart(satisfactionCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(satisfactionData).map(key => key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())),
            datasets: [{
                data: Object.values(satisfactionData),
                backgroundColor: ['#28a745', '#6f42c1', '#17a2b8', '#ffc107', '#dc3545']
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '60%'
        }
    });

    // Test Service functionality
    document.getElementById('testServiceBtn').addEventListener('click', function() {
        const form = document.getElementById('testServiceForm');
        const formData = new FormData(form);
        const resultDiv = document.getElementById('testResult');
        
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
        
        fetch('{{ route("admin.ai-chat.test-service") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('testResponse').textContent = data.response.content;
                document.getElementById('responseTime').textContent = data.metrics.response_time;
                document.getElementById('tokensUsed').textContent = data.metrics.tokens_used;
                document.getElementById('costEstimate').textContent = data.metrics.cost_estimate;
                resultDiv.classList.remove('d-none');
            } else {
                alert('Test failed: ' + data.error);
            }
        })
        .catch(error => {
            alert('Test failed: ' + error.message);
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-play"></i> Test Service';
        });
    });
});
</script>
@endsection

@section('styles')
<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a, #17a673);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #36b9cc, #2c9faf);
}

.opacity-75 {
    opacity: 0.75;
}

.chart-pie {
    position: relative;
    height: 245px;
}

.badge-success { background-color: #1cc88a; }
.badge-secondary { background-color: #6c757d; }
</style>
@endsection
@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Enhanced Page Header -->
        <div class="dashboard-header shadow-sm">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="dashboard-title mb-0">
                            <i class="fas fa-tachometer-alt me-3"></i>
                            Dashboard Overview
                        </h1>
                        <p class="dashboard-subtitle mb-0">
                            Real-time insights and analytics for your real estate business
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-light btn-lg" id="refresh-dashboard" 
                                    data-bs-toggle="tooltip" title="Refresh Dashboard">
                                <i class="fas fa-sync-alt me-2"></i> Refresh
                            </button>
                            <button type="button" class="btn btn-light btn-lg" 
                                    data-bs-toggle="modal" data-bs-target="#exportModal"
                                    data-bs-toggle="tooltip" title="Export Data">
                                <i class="fas fa-download me-2"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>        <!-- Analytics Cards -->
        @include('admin.dashboard.components.analytics-cards')

        <!-- Charts Section -->
        @include('admin.dashboard.components.charts-section')

        <!-- Activities & Properties Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                @include('admin.dashboard.components.recent-activities')
            </div>
            <div class="col-lg-6">
                @include('admin.dashboard.components.top-properties')
            </div>
        </div>        <!-- Financial & AI Insights Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                @include('admin.dashboard.components.financial-metrics')
            </div>
            <div class="col-lg-4">
                @include('admin.dashboard.components.ai-insights')
            </div>
        </div>

        <!-- Performance & Quick Actions Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                @include('admin.dashboard.components.performance-metrics')
            </div>
            <div class="col-lg-4">
                @include('admin.dashboard.components.quick-actions')
            </div>
        </div>

        <!-- Market Trends -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                @include('admin.dashboard.components.market-trends')
            </div>
        </div>
    </div>
</div>

<!-- Notifications & Status Indicators -->
@include('admin.dashboard.components.notifications')

<!-- Modals -->
@include('admin.dashboard.components.export-modal')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
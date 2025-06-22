@extends('admin.layouts.app')

@section('title', 'User Management')

@push('styles')
<link href="{{ asset('css/admin/users.css') }}" rel="stylesheet">
@endpush

@section('content')
{{-- Modern Page Header --}}
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-users me-3"></i>
                    Customer Management
                </h1>
                <p class="mb-0 opacity-90">
                    Manage and monitor your customer base with powerful tools
                </p>
            </div>
            <div class="d-flex gap-3">
                <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                    <i class="fas fa-tasks me-2"></i>Bulk Actions
                </button>
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-download me-2"></i>Export Data
                </button>
                <a href="{{ route('admin.users.create') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-plus me-2"></i>Add Customer
                </a>
            </div>
        </div>
        
        {{-- Quick Stats Bar --}}
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="d-flex align-items-center text-white-50">
                    <i class="fas fa-users me-2"></i>
                    <span>{{ number_format($statistics['total_users']) }} Total</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center text-white-50">
                    <i class="fas fa-user-plus me-2"></i>
                    <span>{{ number_format($statistics['new_this_month']) }} This Month</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center text-white-50">
                    <i class="fas fa-user-check me-2"></i>
                    <span>{{ number_format($statistics['active_users']) }} Active</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center text-white-50">
                    <i class="fas fa-home me-2"></i>
                    <span>{{ number_format($statistics['users_with_properties']) }} With Properties</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Statistics Cards -->
    <x-admin.user-stats :statistics="$statistics" />

    <!-- Search & Filter -->
    <x-admin.user-search />

    <!-- Users Table -->
    <x-admin.user-table :users="$users" />

    <!-- Bulk Actions & Export Modals -->
    <x-admin.user-actions />
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/users.js') }}"></script>
@endpush
@extends('admin.layouts.app')

@section('title', 'Manage Properties')

@section('page-header')
    <div class="page-header-content">
        <div class="page-title-section">
            <div class="page-title-icon">
                <i class="fas fa-home"></i>
            </div>
            <div>
                <h1 class="page-title">
                    Manage Properties
                    <span class="page-subtitle">Oversee and manage all property listings</span>
                </h1>
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard.index') }}">
                                <i class="fas fa-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Properties</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.properties.create') }}" class="btn btn-primary btn-add-property">
                <i class="fas fa-plus me-2"></i>
                Add New Property
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="properties-page-container">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Quick Stats Section -->
    <div class="property-stats-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-chart-bar me-2 text-primary"></i>
                Property Overview
            </h3>
        </div>
        <div class="property-stats">
            <x-admin.property-stats 
                :statistics="$statistics ?? []" 
                :total-count="method_exists($properties, 'total') ? $properties->total() : $properties->count()" 
            />
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="property-search-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-search me-2 text-primary"></i>
                Search & Filter
            </h3>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#searchFilters" aria-expanded="true" aria-controls="searchFilters">
                <i class="fas fa-filter me-1"></i>
                Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="searchFilters">
            <div class="property-search-form">
                <x-admin.property-search :filters="$filters ?? []" />
            </div>
        </div>
    </div>

    <!-- Properties Table Section -->
    <div class="property-table-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-table me-2 text-primary"></i>
                Properties List
            </h3>
            <div class="table-actions">
                <div class="btn-group" role="group" aria-label="View options">
                    <button type="button" class="btn btn-outline-secondary btn-sm active" data-view="table">
                        <i class="fas fa-table me-1"></i>Table
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-view="grid">
                        <i class="fas fa-th-large me-1"></i>Grid
                    </button>
                </div>
            </div>
        </div>
        <div class="property-table-container">
            <div class="property-table">
                <x-admin.property-table :properties="$properties" />
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title d-flex align-items-center" id="deleteModalLabel">
                    <div class="modal-icon me-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    Confirm Delete Property
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="delete-confirmation-content">
                    <div class="text-center mb-4">
                        <div class="delete-warning-icon">
                            <i class="fas fa-home text-muted"></i>
                        </div>
                    </div>
                    <p class="text-center mb-4">
                        Are you sure you want to delete the property 
                        <span id="propertyTitle" class="fw-bold text-primary"></span>?
                    </p>
                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-warning me-3"></i>
                            <div>
                                <strong>Warning:</strong> This action cannot be undone. All associated data will be permanently deleted.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash me-2"></i>Delete Property
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/admin/properties.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/animations.css') }}" rel="stylesheet">
<style>
/* Additional inline styles for immediate effect */
.properties-page-container {
    animation: fadeInUp 0.6s ease-out;
}

.property-stats .card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.property-stats .card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.section-header {
    position: relative;
    overflow: hidden;
}

.section-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(79, 70, 229, 0.1), transparent);
    transition: left 0.5s;
}

.section-header:hover::before {
    left: 100%;
}

.property-table tbody tr {
    transition: all 0.2s ease;
}

.property-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transform: translateX(4px);
    box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
}

.btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn:active::after {
    width: 300px;
    height: 300px;
}
</style>
@endpush

@push('scripts')
<script>
// Wait for document ready and dependencies
document.addEventListener('DOMContentLoaded', function() {
    // Check if required libraries are loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
        return;
    }
    
    // Initialize basic property management without external JS files
    initBasicPropertyManagement();
    
    // Enhanced interactions when page loads
    initEnhancedInteractions();
});

function initBasicPropertyManagement() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize delete modal
    const deleteModalEl = document.getElementById('deleteModal');
    if (deleteModalEl) {
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        const deleteForm = document.getElementById('deleteForm');
        const propertyTitle = document.getElementById('propertyTitle');

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const id = button.getAttribute('data-id');
                const title = button.getAttribute('data-title');
                
                if (propertyTitle && deleteForm) {
                    propertyTitle.textContent = title || 'this property';
                    deleteForm.action = window.location.pathname + '/' + id;
                    deleteModal.show();
                }
            });
        });
        
        // Add loading state to delete button
        if (deleteForm) {
            deleteForm.addEventListener('submit', (e) => {
                const submitBtn = deleteForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
                    submitBtn.disabled = true;
                }
            });
        }
    }
}

function initEnhancedInteractions() {
    // Add stagger animation to stats cards
    const statsCards = document.querySelectorAll('.property-stats .card');
    statsCards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('reveal');
        
        // Add hover effects
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add stagger animation to table rows
    const tableRows = document.querySelectorAll('.property-table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = (index * 0.05) + 's';
        row.classList.add('stagger-item');
        
        // Add hover effects
        row.addEventListener('mouseenter', () => {
            row.style.background = 'linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)';
            row.style.transform = 'translateX(4px)';
        });
        
        row.addEventListener('mouseleave', () => {
            row.style.background = '';
            row.style.transform = 'translateX(0)';
        });
    });
    
    // Enhanced search form interaction
    const searchForm = document.querySelector('.property-search-form');
    if (searchForm) {
        searchForm.classList.add('scroll-fade-in');
    }
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.setAttribute('title', 'Press Ctrl+K to focus search');
            }
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            });
        }
    });
    
    // View toggle functionality
    const viewButtons = document.querySelectorAll('[data-view]');
    const propertyTableContainer = document.querySelector('.property-table-container');

    viewButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const view = button.getAttribute('data-view');
            
            // Update active state
            viewButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Switch view with animation
            if (propertyTableContainer) {
                propertyTableContainer.style.opacity = '0';
                propertyTableContainer.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    if (view === 'grid') {
                        propertyTableContainer.classList.add('grid-view');
                    } else {
                        propertyTableContainer.classList.remove('grid-view');
                    }
                    
                    propertyTableContainer.style.opacity = '1';
                    propertyTableContainer.style.transform = 'translateY(0)';
                }, 200);
            }
        });
    });
}

// Add smooth transitions for page navigation
window.addEventListener('beforeunload', function() {
    document.body.style.opacity = '0.7';
    document.body.style.transform = 'scale(0.98)';
});

// Toast notification function
function showToast(message, type = 'info') {
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type}" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1080';
    document.body.appendChild(container);
    return container;
}

// Export functions for global use
window.PropertyManager = {
    showToast: showToast
};
</script>
@endpush
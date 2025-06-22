<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard.index') }}">
                <i class="fas fa-building me-2"></i>
                {{ config('app.name', 'Laravel') }} Admin
            </a>
            
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger rounded-pill">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>New property added</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>New user registered</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">View all</a></li>
                    </ul>
                </div>
                
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        @auth('admin')
                            @php
                                $adminName = Auth::guard('admin')->user()->full_name ?? 'Admin';
                                $adminInitials = substr($adminName, 0, 1);
                            @endphp
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($adminInitials) }}&background=4f46e5&color=fff&size=32" class="rounded-circle me-2" width="32" height="32">
                            {{ $adminName }}
                        @else
                            <img src="https://ui-avatars.com/api/?name=Admin&background=4f46e5&color=fff&size=32" class="rounded-circle me-2" width="32" height="32">
                            Admin
                        @endauth
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebar">
        <div class="offcanvas-header d-lg-none">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        
        <div class="offcanvas-body p-0">
            <nav class="nav flex-column">
                <!-- Dashboard -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}" href="{{ route('admin.dashboard.index') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>

                <!-- Properties -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}" href="{{ route('admin.properties.index') }}">
                        <i class="fas fa-home"></i>
                        Properties
                    </a>
                </div>

                <!-- Users -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </div>

                <!-- Analytics -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" 
                       href="#analyticsMenu" 
                       data-bs-toggle="collapse">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.analytics.*') ? 'show' : '' }}" id="analyticsMenu">
                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                                <i class="fas fa-chart-line"></i>
                                Overview
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.analytics.property-report') ? 'active' : '' }}" href="{{ route('admin.analytics.property-report') }}">
                                <i class="fas fa-building"></i>
                                Property Reports
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.analytics.user-report') ? 'active' : '' }}" href="{{ route('admin.analytics.user-report') }}">
                                <i class="fas fa-user-chart"></i>
                                User Analytics
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.analytics.financial-report') ? 'active' : '' }}" href="{{ route('admin.analytics.financial-report') }}">
                                <i class="fas fa-dollar-sign"></i>
                                Financial Reports
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- AI Chat -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.ai-chat.*') ? 'active' : '' }}" 
                       href="#aiChatMenu" 
                       data-bs-toggle="collapse">
                        <i class="fas fa-robot"></i>
                        AI Chat
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.ai-chat.*') ? 'show' : '' }}" id="aiChatMenu">
                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('admin.ai-chat.index') ? 'active' : '' }}" href="{{ route('admin.ai-chat.index') }}">
                                <i class="fas fa-comments"></i>
                                Chat Overview
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.ai-chat.services') ? 'active' : '' }}" href="{{ route('admin.ai-chat.services') }}">
                                <i class="fas fa-cogs"></i>
                                AI Services
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.ai-chat.sessions') ? 'active' : '' }}" href="{{ route('admin.ai-chat.sessions') }}">
                                <i class="fas fa-history"></i>
                                Chat Sessions
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.ai-chat.analytics') ? 'active' : '' }}" href="{{ route('admin.ai-chat.analytics') }}">
                                <i class="fas fa-chart-pie"></i>
                                AI Analytics
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Coming Soon Features -->
                <div class="nav-item">
                    <a class="nav-link" href="#" onclick="showComingSoon('Property Valuation')">
                        <i class="fas fa-calculator"></i>
                        Property Valuation
                        <span class="badge bg-info ms-auto">Soon</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a class="nav-link" href="#" onclick="showComingSoon('Legal Support')">
                        <i class="fas fa-gavel"></i>
                        Legal Support
                        <span class="badge bg-info ms-auto">Soon</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a class="nav-link" href="#" onclick="showComingSoon('Transactions')">
                        <i class="fas fa-handshake"></i>
                        Transactions
                        <span class="badge bg-info ms-auto">Soon</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a class="nav-link" href="#" onclick="showComingSoon('Banking Partners')">
                        <i class="fas fa-university"></i>
                        Banking Partners
                        <span class="badge bg-info ms-auto">Soon</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a class="nav-link" href="#" onclick="showComingSoon('Mobile App')">
                        <i class="fas fa-mobile-alt"></i>
                        Mobile App
                        <span class="badge bg-info ms-auto">Soon</span>
                    </a>
                </div>

                <!-- System Settings -->
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}" 
                       href="#systemMenu" 
                       data-bs-toggle="collapse">
                        <i class="fas fa-cog"></i>
                        System Settings
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.system.*') ? 'show' : '' }}" id="systemMenu">
                        <nav class="nav flex-column">
                            <a class="nav-link {{ request()->routeIs('admin.system.index') ? 'active' : '' }}" href="{{ route('admin.system.index') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                System Overview
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.system.settings') ? 'active' : '' }}" href="{{ route('admin.system.settings') }}">
                                <i class="fas fa-sliders-h"></i>
                                General Settings
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.system.integrations') ? 'active' : '' }}" href="{{ route('admin.system.integrations') }}">
                                <i class="fas fa-plug"></i>
                                Integrations
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.system.maintenance') ? 'active' : '' }}" href="{{ route('admin.system.maintenance') }}">
                                <i class="fas fa-tools"></i>
                                Maintenance
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.system.security') ? 'active' : '' }}" href="{{ route('admin.system.security') }}">
                                <i class="fas fa-shield-alt"></i>
                                Security
                            </a>
                        </nav>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        @hasSection('page-header')
            <div class="d-flex justify-content-between align-items-center mb-4">
                @yield('page-header')
            </div>
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom Admin JS -->
    <script>
        // Disable DataTable auto-init to prevent conflicts
        window.dataTableInitialized = false;
        
        // Safe admin initialization
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize alerts auto-dismiss
            $('.alert').each(function() {
                if ($(this).hasClass('alert-dismissible')) {
                    setTimeout(() => {
                        $(this).alert('close');
                    }, 5000);
                }
            });
        });
    </script>
    
    <script>
        // Coming Soon Function
        function showComingSoon(feature) {
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-info border-0 position-fixed';
            toast.style.cssText = 'top: 90px; right: 20px; z-index: 1055;';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-rocket me-2"></i>
                        <strong>${feature}</strong> - Coming Soon!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.querySelector('.btn-close')) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html> 
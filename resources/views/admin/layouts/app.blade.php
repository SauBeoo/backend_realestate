<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - {{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { padding-top: 5rem; }
        .main-content { margin-left: 220px; padding: 20px; }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            width: 220px;
        }
        .sidebar .nav-link { font-weight: 500; color: #333; }
        .sidebar .nav-link.active { color: #0d6efd; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/admin') }}">
                {{ config('app.name', 'Laravel') }} Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    {{-- Add auth links here --}}
                    <li class="nav-item"><a class="nav-link" href="#">Logout (Placeholder)</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}" href="{{ route('admin.dashboard.index') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>

                        <!-- Property Management -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}" href="{{ route('admin.properties.index') }}">
                                <i class="fas fa-home me-2"></i>
                                Properties
                            </a>
                        </li>

                        <!-- User Management -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users me-2"></i>
                                Users
                            </a>
                        </li>

                        <!-- Analytics & Reports -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#analyticsSubmenu" aria-expanded="false">
                                <i class="fas fa-chart-bar me-2"></i>
                                Analytics & Reports
                                <i class="fas fa-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('admin.analytics.*') ? 'show' : '' }}" id="analyticsSubmenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                                            <i class="fas fa-chart-line me-2"></i>
                                            Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.analytics.property-report') ? 'active' : '' }}" href="{{ route('admin.analytics.property-report') }}">
                                            <i class="fas fa-building me-2"></i>
                                            Property Reports
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.analytics.user-report') ? 'active' : '' }}" href="{{ route('admin.analytics.user-report') }}">
                                            <i class="fas fa-user-chart me-2"></i>
                                            User Analytics
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.analytics.financial-report') ? 'active' : '' }}" href="{{ route('admin.analytics.financial-report') }}">
                                            <i class="fas fa-dollar-sign me-2"></i>
                                            Financial Reports
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- AI Chat Management -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.ai-chat.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#aiChatSubmenu" aria-expanded="false">
                                <i class="fas fa-robot me-2"></i>
                                AI Chat Management
                                <i class="fas fa-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('admin.ai-chat.*') ? 'show' : '' }}" id="aiChatSubmenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.ai-chat.index') ? 'active' : '' }}" href="{{ route('admin.ai-chat.index') }}">
                                            <i class="fas fa-comments me-2"></i>
                                            Chat Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.ai-chat.services') ? 'active' : '' }}" href="{{ route('admin.ai-chat.services') }}">
                                            <i class="fas fa-cogs me-2"></i>
                                            AI Services
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.ai-chat.sessions') ? 'active' : '' }}" href="{{ route('admin.ai-chat.sessions') }}">
                                            <i class="fas fa-history me-2"></i>
                                            Chat Sessions
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.ai-chat.analytics') ? 'active' : '' }}" href="{{ route('admin.ai-chat.analytics') }}">
                                            <i class="fas fa-chart-pie me-2"></i>
                                            AI Analytics
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Divider -->
                        <hr class="sidebar-divider my-3">

                        <!-- Property Valuation -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="alert('Property Valuation System - Coming Soon')">
                                <i class="fas fa-calculator me-2"></i>
                                Property Valuation
                            </a>
                        </li>

                        <!-- Legal Support -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="alert('Legal Support System - Coming Soon')">
                                <i class="fas fa-gavel me-2"></i>
                                Legal Support
                            </a>
                        </li>

                        <!-- Transaction Management -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="alert('Transaction Management - Coming Soon')">
                                <i class="fas fa-handshake me-2"></i>
                                Transactions
                            </a>
                        </li>

                        <!-- Banking Partners -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="alert('Banking Partners Management - Coming Soon')">
                                <i class="fas fa-university me-2"></i>
                                Banking Partners
                            </a>
                        </li>

                        <!-- Mobile App Management -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="alert('Mobile App Management - Coming Soon')">
                                <i class="fas fa-mobile-alt me-2"></i>
                                Mobile App
                            </a>
                        </li>

                        <!-- System Settings -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#systemSubmenu" aria-expanded="false">
                                <i class="fas fa-cog me-2"></i>
                                System Settings
                                <i class="fas fa-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('admin.system.*') ? 'show' : '' }}" id="systemSubmenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.system.index') ? 'active' : '' }}" href="{{ route('admin.system.index') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>
                                            System Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.system.settings') ? 'active' : '' }}" href="{{ route('admin.system.settings') }}">
                                            <i class="fas fa-sliders-h me-2"></i>
                                            General Settings
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.system.integrations') ? 'active' : '' }}" href="{{ route('admin.system.integrations') }}">
                                            <i class="fas fa-plug me-2"></i>
                                            Integrations
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.system.maintenance') ? 'active' : '' }}" href="{{ route('admin.system.maintenance') }}">
                                            <i class="fas fa-tools me-2"></i>
                                            Maintenance
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.system.security') ? 'active' : '' }}" href="{{ route('admin.system.security') }}">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            Security
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
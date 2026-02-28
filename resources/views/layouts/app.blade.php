<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'QuickMark | Dashboard')</title>
    
    <!-- External Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Application CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Page Specific Head Injection -->
    @stack('head')

    <style>
        /* Sidebar Layout Structure */
        body {
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            flex-grow: 1;
        }

        #sidebar {
            width: 280px;
            transition: all 0.3s;
            min-height: calc(100vh - 65px); /* Minus Navbar height */
            z-index: 1000;
        }

        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Mobile Sidebar adjustments */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -280px; /* Hide by default on mobile */
                position: fixed;
                height: 100%;
                box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            }
            #sidebar.active {
                margin-left: 0;
            }
            body.sidebar-open {
                overflow: hidden; /* Prevent background scroll when sidebar is open */
            }
            /* Overlay for mobile */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Header / Top Navbar -->
    @include('components.header')

    <div class="wrapper">
        
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="bg-white border-end shadow-sm">
            @include('components.sidebar')
        </div>

        <!-- Main Content Injection -->
        <div id="content" class="bg-body-tertiary">
            <main class="container py-5">
                @yield('content')
            </main>
            
            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>

    <!-- Modals Injection -->
    @stack('modals')

    <!-- External JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Application Global JavaScript -->
    <script src="{{ asset('js/utils.js') }}"></script>
    <script>
        // Sidebar Toggle Logic
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarBtn = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if(sidebarBtn) {
                sidebarBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                    document.body.classList.toggle('sidebar-open');
                });
            }

            if(overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                });
            }
        });
    </script>
    
    <!-- Page Specific Script Injection -->
    @stack('scripts')
</body>
</html>

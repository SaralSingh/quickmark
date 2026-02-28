<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'QuickMark | Universal Presence')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'QuickMark is an API-first presence marking system designed for simplicity and reliability. Built by Saral Singh.')">
    <meta name="keywords" content="QuickMark, Attendance System, Presence Marking, Saral Singh, Backend Developer, PHP, Laravel">
    <meta name="author" content="Saral Singh">
    <link rel="author" href="https://saralsingh.space">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('title', 'QuickMark | Universal Presence')">
    <meta property="og:description" content="@yield('meta_description', 'QuickMark is an API-first presence marking system designed for simplicity and reliability. Built by Saral Singh.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "WebSite",
      "name": "QuickMark",
      "url": "{{ url('/') }}",
      "description": "API-first presence marking system designed for simplicity and reliability.",
      "author": {
        "@@type": "Person",
        "name": "Saral Singh",
        "jobTitle": "Backend Developer (PHP & Laravel)",
        "url": "https://saralsingh.space"
      }
    }
    </script>

    <!-- External Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Application CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('head')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Guest Navbar -->
    @include('components.navbar')

    <!-- Main Content Injection -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Guest Footer -->
    @include('components.footer')

    <!-- External JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Application Scripts -->
    <script src="{{ asset('js/utils.js') }}"></script>
    
    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>

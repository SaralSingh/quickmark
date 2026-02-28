<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="fa-solid fa-check-double me-2"></i> QuickMark
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-dark btn-sm px-4">Dashboard</a>
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm px-4">Log In</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-dark btn-sm px-4">Get Started</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

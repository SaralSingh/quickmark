<footer class="bg-dark text-white pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row align-items-center mb-4 pb-4 border-bottom border-secondary">
            <div class="col-md-6 mb-3 mb-md-0">
                <h4 class="fw-bold mb-1">
                    <i class="fa-solid fa-check-double text-primary bg-light rounded p-1 me-2 shadow-sm"></i> QuickMark
                </h4>
                <p class="text-white-50 small mb-0 ms-1">Universal Presence Register Engine</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="https://github.com/SaralSingh" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle me-2" title="GitHub">
                    <i class="fa-brands fa-github"></i>
                </a>
                <a href="https://www.linkedin.com/in/saralsingh/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-circle me-2" title="LinkedIn">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
                <a href="mailto:saralsingh2005@gmail.com" class="btn btn-outline-light btn-sm rounded-circle" title="Email Developer">
                    <i class="fa-solid fa-envelope"></i>
                </a>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0 pe-lg-5">
                <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wide">About QuickMark</h6>
                <p class="text-white-50 small pe-md-4" style="line-height: 1.7;">
                    A properly engineered presence management system. Version 2.0 introduces API-first architecture, clean database constraints, and absolute rule-driven tracking replacing complex spreadsheets.
                </p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wide">Platform</h6>
                <ul class="list-unstyled small mt-2">
                    <li class="mb-2"><a href="{{ url('/') }}" class="text-decoration-none text-white-50 hover-white transition-all"><i class="fa-solid fa-angle-right me-2 opacity-50"></i>Home</a></li>
                    @guest
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-decoration-none text-white-50 hover-white transition-all"><i class="fa-solid fa-angle-right me-2 opacity-50"></i>Login</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-decoration-none text-white-50 hover-white transition-all"><i class="fa-solid fa-angle-right me-2 opacity-50"></i>Register</a></li>
                    @endguest
                    @auth
                        <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-decoration-none text-white-50 hover-white transition-all"><i class="fa-solid fa-angle-right me-2 opacity-50"></i>Dashboard</a></li>
                    @endauth
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wide">Resources</h6>
                <ul class="list-unstyled small mt-2">
                    <li class="mb-2"><a href="{{ route('api-docs') }}" class="text-decoration-none text-white-50 hover-white transition-all"><i class="fa-solid fa-angle-right me-2 opacity-50"></i>API Documentation</a></li>
                    <li class="mb-2"><a href="https://blog.saralsingh.space/" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-white-50 hover-white transition-all"><i class="fa-solid fa-angle-right me-2 opacity-50"></i>Developer Blog</a></li>
                    <li class="mb-2"><a href="{{ route('privacy.policy') }}" class="text-decoration-none text-white-50 hover-white transition-all"><i class="fa-solid fa-angle-right me-2 opacity-50"></i>Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="row pt-3 align-items-center">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <p class="small text-white-50 mb-0">
                    &copy; 2026 QuickMark Platform. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="small text-white-50 mb-0">
                    Engineered with <i class="fa-solid fa-rocket text-primary mx-1"></i> by 
                    <a href="https://saralsingh.space" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-white fw-bold border-bottom border-primary pb-1">Saral Singh</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer specific inline overrides */
.hover-white:hover { color: #ffffff !important; }
.transition-all { transition: all 0.2s ease-in-out; }
.tracking-wide { letter-spacing: 0.05em; }
.placeholder-white-50::placeholder { color: rgba(255, 255, 255, 0.5); }
</style>

<div class="d-flex flex-column p-3 h-100 bg-white">
    <div class="mb-4 mt-2 px-2 text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.1em;">
        Main Navigation
    </div>
    
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-primary text-white shadow-sm' : 'text-dark' }} fw-medium px-3 py-2 rounded-3">
                <i class="fa-solid fa-house fa-fw me-2 {{ request()->routeIs('dashboard') ? '' : 'text-secondary' }}"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('workspace') ? 'active bg-primary text-white shadow-sm' : 'text-dark' }} fw-medium px-3 py-2 rounded-3">
                <i class="fa-solid fa-layer-group fa-fw me-2 {{ request()->routeIs('workspace') ? '' : 'text-secondary' }}"></i>
                My Lists
            </a>
        </li>
        <!-- Add more sidebar links here dynamically if needed later -->
    </ul>

    <hr class="mt-auto mb-3">
    
    <div class="px-2 pb-2">
        <div class="d-flex align-items-center bg-light p-2 rounded-3 border">
            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                <i class="fa-solid fa-user"></i>
            </div>
            <div style="font-size: 0.85rem;" class="fw-bold text-truncate" id="sidebarUserName">
                User
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand bg-white sticky-top border-bottom">
    <div class="container-fluid px-3">
        
        <!-- Sidebar Toggle (Mobile Only) -->
        <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary d-md-none me-2">
            <i class="fa-solid fa-bars"></i>
        </button>

        <a class="navbar-brand fw-bold text-dark me-auto" href="{{ route('dashboard') }}">
            <i class="fa-solid fa-check-double me-2 text-primary"></i> <span class="d-none d-sm-inline">QuickMark</span>
        </a>

        <div class="d-flex align-items-center">
            
            <div class="dropdown me-3 d-none d-md-block">
                <button class="btn btn-light btn-sm dropdown-toggle border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-plus text-primary"></i> Quick Action
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createListModal"><i class="fa-solid fa-folder-plus me-2 text-secondary"></i> New List</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-circle p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <li><h6 class="dropdown-header">Logged In</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item text-danger" id="logoutBtn">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                        </button>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

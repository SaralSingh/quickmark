<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Dashboard | Praman v2</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #0f172a; /* Slate 900 */
            --bs-secondary: #64748b; /* Slate 500 */
            --bs-body-bg: #f1f5f9; /* Slate 100 */
            --bs-card-border: #e2e8f0;
        }

        body {
            background-color: var(--bs-body-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Card Styling */
        .card {
            border: 1px solid var(--bs-card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        /* Hover effect only for interactive list cards */
        .list-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
            cursor: pointer;
        }

        .stat-card {
            border-left: 4px solid var(--bs-primary);
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: #1e293b;
        }

        /* Empty State */
        .empty-state-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="#">
                <i class="fa-solid fa-check-double me-2"></i> QuickMark <span class="badge bg-dark ms-1" style="font-size: 0.5em; vertical-align: top;"></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active fw-semibold" href="#">Dashboard</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-danger btn-sm" id="logoutBtn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
                <p class="text-secondary mb-0">Manage your lists and track presence.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListModal">
                <i class="fa-solid fa-plus me-2"></i> New List
            </button>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase text-secondary small fw-bold mb-1">Total Lists</p>
                            <h3 class="fw-bold mb-0" id="stat-lists">0</h3>
                        </div>
                        <div class="bg-light p-2 rounded text-primary">
                            <i class="fa-solid fa-layer-group fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="card stat-card h-100 p-3" style="border-left-color: #475569;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase text-secondary small fw-bold mb-1">Total People</p>
                            <h3 class="fw-bold mb-0" id="stat-people">0</h3>
                        </div>
                        <div class="bg-light p-2 rounded text-secondary">
                            <i class="fa-solid fa-users fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-4">
                <div class="card stat-card h-100 p-3" style="border-left-color: #94a3b8;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase text-secondary small fw-bold mb-1">Sessions Held</p>
                            <h3 class="fw-bold mb-0" id="stat-sessions">0</h3>
                        </div>
                        <div class="bg-light p-2 rounded text-secondary">
                            <i class="fa-solid fa-calendar-check fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <h4 class="fw-bold text-dark mb-4">Your Lists</h4>
        
        <div id="loader" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-secondary">Loading your data...</p>
        </div>

        <div id="empty-state" class="text-center py-5 d-none bg-white rounded border border-dashed">
            <div class="empty-state-icon">
                <i class="fa-regular fa-folder-open"></i>
            </div>
            <h5 class="fw-bold">No lists found</h5>
            <p class="text-secondary mb-4">You haven't created any presence lists yet.</p>
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#createListModal">
                Create First List
            </button>
        </div>

        <div id="lists-container" class="row g-4 d-none">
            </div>

    </div>

    <div class="modal fade" id="createListModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Create New List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createListForm">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">List Name</label>
                            <input type="text" class="form-control" id="new-list-name" placeholder="e.g. BCA 3rd Sem, Cricket Team" required>
                            <div class="form-text">This will be the main container for your people.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
/* ---------- Helpers ---------- */

function csrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');
}

function jsonHeaders(withCsrf = false) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };

    if (withCsrf) {
        headers['X-CSRF-TOKEN'] = csrfToken();
    }

    return headers;
}


/* ---------- Load Lists ---------- */

async function loadLists() {
    const loader = document.getElementById('loader');
    const emptyState = document.getElementById('empty-state');
    const container = document.getElementById('lists-container');

    // reset UI (important if function is reused)
    loader.classList.remove('d-none');
    emptyState.classList.add('d-none');
    container.classList.add('d-none');
    container.innerHTML = '';

    try {
        const response = await fetch('/api/lists', {
            method: 'GET',
            credentials: 'same-origin',
            headers: jsonHeaders()
        });

        if (response.status === 401) {
            window.location.href = '/login';
            return;
        }

        const result = await response.json();

        loader.classList.add('d-none');

        const lists = result.data || [];
        document.getElementById('stat-lists').innerText = result.total ?? 0;

        if (lists.length === 0) {
            emptyState.classList.remove('d-none');
            return;
        }

        container.classList.remove('d-none');

        lists.forEach(list => {
            const card = `
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">${list.name}</h5>
                            <p class="text-secondary small mb-3">
                                List ID: ${list.id}
                            </p>
                            <button 
                                onclick="openList(${list.id}, '${list.name.replace(/'/g, "\\'")}')" 
                                class="btn btn-sm btn-dark w-100">
                                Open List
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });

    } catch (error) {
        loader.classList.add('d-none');
        emptyState.classList.remove('d-none');
        console.error(error);
    }
}




document.addEventListener('DOMContentLoaded', () => {
    loadLists();
});


/* ---------- Create List ---------- */

document.getElementById('createListForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const input = document.getElementById('new-list-name');
    const name = input.value.trim();
    if (!name) return;

    const response = await fetch('/api/lists', {
        method: 'POST',
        credentials: 'same-origin',
        headers: jsonHeaders(true), // CSRF
        body: JSON.stringify({ name })
    });

    const data = await response.json();

    if (!response.ok) {
        alert(data.message || 'Error creating list');
        return;
    }

    // ✅ Reset input
    input.value = '';

    // ✅ Close modal properly
    const modalEl = document.getElementById('createListModal');
    const modalInstance = bootstrap.Modal.getInstance(modalEl)
        || new bootstrap.Modal(modalEl);

    modalInstance.hide();

    // ✅ Refresh lists
    loadLists();
});



/* ---------- Logout ---------- */

document.getElementById('logoutBtn').addEventListener('click', async () => {
    try {
        const res = await fetch('/logout', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await res.json();

        if (!res.ok) {
            alert(data.message || 'Logout failed');
            return;
        }

        window.location.href = '/login';
    } catch (err) {
        console.error(err);
        alert('Server error during logout');
    }
});


/* ---------- Open List ---------- */

function openList(id, name) {
    localStorage.setItem('current_list_id', id);
    localStorage.setItem('current_list_name', name);
    window.location.href = '/list-workspace';
}
</script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
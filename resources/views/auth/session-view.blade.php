<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Report | Praman v2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #0f172a; /* Slate 900 */
            --bs-secondary: #64748b; /* Slate 500 */
            --bs-body-bg: #f1f5f9; /* Slate 100 */
            --bs-border-color: #e2e8f0;
            --bs-success-rgb: 16, 185, 129; /* Emerald 500 */
            --bs-danger-rgb: 239, 68, 68; /* Red 500 */
        }

        body {
            background-color: var(--bs-body-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--bs-border-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Cards */
        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow: hidden;
        }

        /* Stats Cards */
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .icon-box {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }

        /* List Styling */
        .list-group-item {
            border-left: none; border-right: none;
            border-color: #f1f5f9;
            padding: 1rem 1.25rem;
            transition: background-color 0.15s;
        }
        .list-group-item:first-child { border-top: none; }
        .list-group-item:hover { background-color: #f8fafc; }

        .avatar-initial {
            width: 40px; height: 40px;
            background-color: #e2e8f0;
            color: #475569;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 1rem;
        }

        /* Status Pills */
        .badge-present {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
            border: 1px solid rgba(var(--bs-success-rgb), 0.2);
            padding: 0.5em 1em;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-absent {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
            border: 1px solid rgba(var(--bs-danger-rgb), 0.2);
            padding: 0.5em 1em;
            border-radius: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="/dashboard">
                <i class="fa-solid fa-check-double me-2"></i> QuickMark <span class="badge bg-dark ms-1" style="font-size: 0.5em; vertical-align: top;"></span>
            </a>
            <div class="ms-auto">
                <button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='/list-workspace'">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Workspace
                </button>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase text-secondary small fw-bold ls-1">Session Report</span>
                <h2 class="fw-bold text-dark mt-1" id="page-title">Session Details</h2>
                <div class="text-secondary small">
                    <i class="fa-regular fa-calendar me-1"></i> <span id="session-date-display">Loading date...</span>
                </div>
            </div>
            <div>
                <button class="btn btn-outline-dark btn-sm" onclick="window.print()">
                    <i class="fa-solid fa-print me-2"></i> Print Report
                </button>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card stat-card p-3 h-100 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-light text-primary me-3">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-secondary small fw-bold text-uppercase">Total Enrolled</p>
                            <h3 class="mb-0 fw-bold" id="stat-total">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3 h-100 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success-subtle text-success me-3">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-secondary small fw-bold text-uppercase">Present</p>
                            <h3 class="mb-0 fw-bold text-success" id="stat-present">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3 h-100 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-danger-subtle text-danger me-3">
                            <i class="fa-solid fa-user-xmark"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-secondary small fw-bold text-uppercase">Absent</p>
                            <h3 class="mb-0 fw-bold text-danger" id="stat-absent">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-0 text-dark">Attendance List</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-search text-secondary"></i>
                            </span>
                            <input type="text" id="search-input" class="form-control border-start-0 bg-light" placeholder="Search name..." onkeyup="filterList()">
                        </div>
                    </div>
                </div>
            </div>

            <div id="people-list-container" class="card-body p-0">
                <div class="text-center py-5 text-secondary">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <div>Loading attendance data...</div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
<script>
    const sessionId = {{ $sessionId }};

    let allPeopleData = []; // Store data for searching/filtering

    function loadSessionPeople() {
        fetch(`/api/sessions/${sessionId}/attendance`, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => {

            // --- Session Date (safe timezone handling) ---
            if (data.session && data.session.session_date) {
                const rawDate = data.session.session_date;
                const d = new Date(rawDate + 'T00:00:00');
                const formatted = d.toLocaleDateString(undefined, {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                document.getElementById('session-date-display').innerText = formatted;
            }

            const container = document.getElementById('people-list-container');

            // 1. Handle Empty Data
            if (!data.people || data.people.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fa-regular fa-folder-open fa-3x text-secondary mb-3"></i>
                        <p class="text-secondary">No records found for this session.</p>
                    </div>`;
                return;
            }

            // 2. Store data globally for search features
            allPeopleData = data.people;

            // 3. Update Header Info
            if (data.session) {
                document.getElementById('page-title').innerText =
                    data.session.title || 'Session Details';
            }

            // 4. Calculate Stats
            const total = allPeopleData.length;
            const present = allPeopleData.filter(p => p.is_present).length;
            const absent = total - present;

            document.getElementById('stat-total').innerText = total;
            document.getElementById('stat-present').innerText = present;
            document.getElementById('stat-absent').innerText = absent;

            // 5. Render List
            renderList(allPeopleData);
        })
        .catch(err => {
            console.error(err);
            document.getElementById('people-list-container').innerHTML = `
                <div class="text-center py-5 text-danger">
                    <i class="fa-solid fa-circle-exclamation mb-2 fa-2x"></i>
                    <p>Failed to load session data.</p>
                </div>`;
        });
    }

    // Render Function
    function renderList(people) {
        const container = document.getElementById('people-list-container');

        if (people.length === 0) {
            container.innerHTML =
                '<div class="p-4 text-center text-secondary">No matching results.</div>';
            return;
        }

        let html = `<ul class="list-group list-group-flush">`;

        people.forEach(p => {
            const initials = p.name.slice(0, 2).toUpperCase();
            const badgeClass = p.is_present ? 'badge-present' : 'badge-absent';
            const badgeIcon = p.is_present
                ? '<i class="fa-solid fa-check me-1"></i>'
                : '<i class="fa-solid fa-xmark me-1"></i>';
            const badgeText = p.is_present ? 'Present' : 'Absent';
            const rowBg = p.is_present ? '' : 'bg-light';

            html += `
                <li class="list-group-item d-flex justify-content-between align-items-center ${rowBg}">
                    <div class="d-flex align-items-center">
                        <div class="avatar-initial">${initials}</div>
                        <div>
                            <div class="fw-semibold text-dark">${p.name}</div>
                        </div>
                    </div>
                    <span class="${badgeClass} small">
                        ${badgeIcon}${badgeText}
                    </span>
                </li>
            `;
        });

        html += `</ul>`;
        container.innerHTML = html;
    }

    // Client-Side Search
    function filterList() {
        const query = document.getElementById('search-input').value.toLowerCase();
        const filtered = allPeopleData.filter(p =>
            p.name.toLowerCase().includes(query)
        );
        renderList(filtered);
    }

    // Init
    loadSessionPeople();
</script>


</body>
</html>
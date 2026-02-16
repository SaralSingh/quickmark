<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Active Session | Praman v2</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #0f172a; /* Slate 900 */
            --bs-success: #10b981; /* Emerald 500 */
            --bs-body-bg: #f1f5f9; /* Slate 100 */
            --bs-border-color: #e2e8f0;
        }

        body {
            background-color: var(--bs-body-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
            padding-bottom: 80px; /* Space for fixed footer on mobile */
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--bs-border-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Session Header Card */
        .session-card {
            background: #fff;
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        /* List Items */
        .list-group-item {
            border: 1px solid var(--bs-border-color);
            margin-bottom: 8px;
            border-radius: 8px !important; /* Spaced out items look better on mobile */
            padding: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .list-group-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Active State (Present) */
        .list-group-item.active-presence {
            background-color: #ecfdf5; /* Light Green */
            border-color: #10b981;
        }

        .avatar-initial {
            width: 40px;
            height: 40px;
            background-color: #e2e8f0;
            color: #475569;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 1rem;
        }

        .active-presence .avatar-initial {
            background-color: #10b981;
            color: white;
        }

        /* Floating Action Bar for Mobile/Desktop */
        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            padding: 1rem;
            border-top: 1px solid var(--bs-border-color);
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Saving Overlay */
        #saving-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 2000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="/dashboard">
                <i class="fa-solid fa-check-double me-2"></i> QuickMark
            </a>
            <div class="d-flex align-items-center">
                <span class="badge bg-danger animate-pulse">Live Session</span>
            </div>
        </div>
    </nav>

    <div class="container py-4">

        <div class="session-card d-flex justify-content-between align-items-center">
            <div>
                <span class="text-secondary small text-uppercase fw-bold ls-1">Active Roll Call</span>
                <h4 id="session-title" class="fw-bold mb-1 mt-1 text-dark">Loading Session...</h4>
                <div class="text-secondary small">
                    <i class="fa-regular fa-calendar me-1"></i> <span id="session-date">...</span>
                </div>
            </div>
            <div class="text-end">
                <h2 class="fw-bold mb-0 text-primary" id="present-count">0</h2>
                <small class="text-secondary">Marked Present</small>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-secondary fw-bold text-uppercase small">People List</h6>
            <span class="badge bg-light text-dark border" id="total-count">Total: 0</span>
        </div>

        <ul id="people-list" class="list-group list-group-flush bg-transparent">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </ul>

    </div>

    <div class="action-bar">
        <div>
            <span class="text-secondary small">Review carefully before saving.</span>
        </div>
        <button class="btn btn-dark px-5 py-2 fw-bold rounded-pill shadow" onclick="saveSession()">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Save Record
        </button>
    </div>

    <div id="saving-overlay" class="d-none">
        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
        <h4 class="fw-bold text-dark">Saving Attendance...</h4>
        <p class="text-secondary">Please do not close this window.</p>
        <div class="progress w-50 mt-2" style="height: 6px;">
            <div id="save-progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
        </div>
        <p class="small text-muted mt-2" id="save-status-text">Preparing...</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Blade variable (unchanged)
    const sessionId = {{ $sessionId }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let markedSet = new Set();
    let allPeopleIds = [];

    // 1. Load Session
    async function loadSession() {
        try {
            const res = await fetch(`/api/sessions/${sessionId}/people`, {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!res.ok) throw new Error("Failed to load session");

            const data = await res.json();

            // UI Updates
            document.getElementById('session-title').innerText = data.session.title;

            const dateObj = new Date(data.session.session_date + 'T00:00:00');
            document.getElementById('session-date').innerText =
                dateObj.toLocaleDateString(undefined, {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

            document.getElementById('total-count').innerText =
                `Total: ${data.people.length}`;

            const ul = document.getElementById('people-list');
            ul.innerHTML = '';
            allPeopleIds = [];

            data.people.forEach(p => {
                allPeopleIds.push(p.id);
                const initials = p.name.slice(0, 2).toUpperCase();

                ul.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center"
                        id="row-${p.id}"
                        onclick="toggleBtn(${p.id})">

                        <div class="d-flex align-items-center">
                            <div class="avatar-initial" id="avatar-${p.id}">
                                ${initials}
                            </div>
                            <div class="fw-semibold ms-2" style="font-size:1.1rem;">
                                ${p.name}
                            </div>
                        </div>

                        <button
                            class="btn btn-outline-secondary rounded-pill px-4"
                            id="btn-${p.id}"
                            onclick="event.stopPropagation(); toggleBtn(${p.id});">
                            Absent
                        </button>
                    </li>
                `;
            });

        } catch (error) {
            console.error(error);
            alert("Error loading session data.");
        }
    }

    // 2. Toggle Presence
    function toggleBtn(personId) {
        const btn = document.getElementById(`btn-${personId}`);
        const row = document.getElementById(`row-${personId}`);
        const countDisplay = document.getElementById('present-count');

        if (markedSet.has(personId)) {
            markedSet.delete(personId);

            btn.classList.remove('btn-success', 'fw-bold', 'border-0');
            btn.classList.add('btn-outline-secondary');
            btn.innerHTML = 'Absent';

            row.classList.remove('active-presence');
        } else {
            markedSet.add(personId);

            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success', 'fw-bold', 'border-0');
            btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Present';

            row.classList.add('active-presence');
        }

        countDisplay.innerText = markedSet.size;
    }

    // 3. Save Session (CSRF FIXED)
    async function saveSession() {
        if (!confirm("Are you sure you want to save and close this session?")) return;

        const overlay = document.getElementById('saving-overlay');
        const progressBar = document.getElementById('save-progress-bar');
        const statusText = document.getElementById('save-status-text');

        overlay.classList.remove('d-none');

        try {
            const total = allPeopleIds.length;

            for (let i = 0; i < total; i++) {
                const personId = allPeopleIds[i];
                const isPresent = markedSet.has(personId) ? 1 : 0;
                const isLast = (i === total - 1);

                const pct = Math.round(((i + 1) / total) * 100);
                progressBar.style.width = `${pct}%`;
                statusText.innerText = `Saving record ${i + 1} of ${total}...`;

                const res = await fetch(`/api/sessions/${sessionId}/presence`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        person_id: personId,
                        is_present: isPresent,
                        final_save: isLast ? 1 : 0
                    })
                });

                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.message || "Server Error");
                }
            }

            statusText.innerText = "Done! Redirecting...";
            setTimeout(() => {
                window.location.href = '/list-workspace';
            }, 500);

        } catch (e) {
            alert('Something went wrong: ' + e.message);
            overlay.classList.add('d-none');
            console.error(e);
        }
    }
    // Init
    loadSession();
</script>

</body>
</html>
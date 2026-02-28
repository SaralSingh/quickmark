/**
 * Active Session Roll Call Logic
 */
document.addEventListener('DOMContentLoaded', () => {

    if (typeof sessionId === 'undefined') return;

    let markedSet = new Set();
    let allPeopleIds = [];

    // --- 1. Load Session Content ---
    async function loadSession() {
        try {
            const res = await fetch(`/api/sessions/${sessionId}/people`, {
                headers: jsonHeaders(),
                credentials: 'same-origin'
            });

            if (!res.ok) throw new Error("Failed to load session");

            const data = await res.json();

            // UI
            const titleEl = document.getElementById('session-title');
            if (titleEl) titleEl.innerText = data.session.title;

            const dateObj = new Date(data.session.session_date + 'T00:00:00');
            const dateEl = document.getElementById('session-date');
            if (dateEl) {
                dateEl.innerText = dateObj.toLocaleDateString(undefined, {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            const totalEl = document.getElementById('total-count');
            if (totalEl) totalEl.innerText = `Total: ${data.people.length}`;

            const ul = document.getElementById('people-list');
            if (!ul) return;

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
                            type="button"
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

    // --- 2. Toggle Presence Logic ---
    window.toggleBtn = function (personId) {
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

        if (countDisplay) countDisplay.innerText = markedSet.size;
    };

    // --- 3. Save Session (CSRF FIXED) ---
    window.saveSession = async function () {
        if (!confirm("Are you sure you want to save and close this session?")) return;

        const overlay = document.getElementById('saving-overlay');
        const progressBar = document.getElementById('save-progress-bar');
        const statusText = document.getElementById('save-status-text');

        if (overlay) overlay.classList.remove('d-none');

        try {
            const total = allPeopleIds.length;

            if (total === 0) {
                // Nothing to save. Exit early.
                if (overlay) overlay.classList.add('d-none');
                window.location.href = '/list-workspace';
                return;
            }

            if (statusText) statusText.innerText = `Saving attendance for ${total} people...`;
            if (progressBar) progressBar.style.width = `50%`;

            // Collect all presences
            const presences = allPeopleIds.map(personId => ({
                person_id: personId,
                is_present: markedSet.has(personId) ? 1 : 0
            }));

            // Send a single batch request
            const res = await fetch(`/api/sessions/${sessionId}/presence`, {
                method: 'POST',
                headers: jsonHeaders(true),
                credentials: 'same-origin',
                body: JSON.stringify({
                    presences: presences,
                    final_save: 1
                })
            });

            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.message || "Server Error");
            }

            if (progressBar) progressBar.style.width = `100%`;
            if (statusText) statusText.innerText = "Done! Redirecting...";

            setTimeout(() => {
                window.location.href = '/list-workspace';
            }, 500);

        } catch (e) {
            alert('Something went wrong: ' + e.message);
            if (overlay) overlay.classList.add('d-none');
            console.error(e);
        }
    };

    // Init
    loadSession();
});

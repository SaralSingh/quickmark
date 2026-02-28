/**
 * Session Report / View
 */
document.addEventListener('DOMContentLoaded', () => {

    if (typeof sessionId === 'undefined') return;

    let allPeopleData = [];

    function loadSessionPeople() {
        const container = document.getElementById('people-list-container');
        if (!container) return;

        fetch(`/api/sessions/${sessionId}/attendance`, {
            headers: jsonHeaders(),
            credentials: 'same-origin'
        })
            .then(res => res.json())
            .then(data => {
                if (data.session && data.session.session_date) {
                    const d = new Date(data.session.session_date + 'T00:00:00');
                    const dateEl = document.getElementById('session-date-display');
                    if (dateEl) {
                        dateEl.innerText = d.toLocaleDateString(undefined, {
                            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                        });
                    }
                    const titleEl = document.getElementById('page-title');
                    if (titleEl) {
                        titleEl.innerText = data.session.title || 'Session Details';
                    }
                }

                if (!data.people || data.people.length === 0) {
                    container.innerHTML = `<div class="text-center py-5"><p class="text-secondary">No records found.</p></div>`;
                    return;
                }

                allPeopleData = data.people;

                // Update Stats
                const total = allPeopleData.length;
                const present = allPeopleData.filter(p => p.is_present == true || p.is_present == 1).length;
                const absent = total - present;

                const st = document.getElementById('stat-total');
                const sp = document.getElementById('stat-present');
                const sa = document.getElementById('stat-absent');

                if (st) st.innerText = total;
                if (sp) sp.innerText = present;
                if (sa) sa.innerText = absent;

                renderList(allPeopleData);
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `<div class="text-center py-5 text-danger"><p>Failed to load data.</p></div>`;
            });
    }

    function renderList(people) {
        const container = document.getElementById('people-list-container');
        if (!container) return;

        if (people.length === 0) {
            container.innerHTML = '<div class="p-4 text-center text-secondary">No matching results found.</div>';
            return;
        }

        let html = `<ul class="list-group list-group-flush">`;
        people.forEach(p => {
            const isPresent = p.is_present == true || p.is_present == 1;
            const initials = p.name.slice(0, 2).toUpperCase();

            // Custom CSS classes matched from blade
            const badgeClass = isPresent ? 'badge-present' : 'badge-absent';
            const badgeText = isPresent ? 'Present' : 'Absent';
            const badgeIcon = isPresent ? 'check' : 'xmark';
            const rowBg = isPresent ? '' : 'bg-light';

            html += `
                <li class="list-group-item d-flex justify-content-between align-items-center ${rowBg}">
                    <div class="d-flex align-items-center">
                        <div class="avatar-initial">${initials}</div>
                        <div class="fw-semibold text-dark">${p.name}</div>
                    </div>
                    <span class="${badgeClass} small">
                        <i class="fa-solid fa-${badgeIcon} me-1"></i>${badgeText}
                    </span>
                </li>`;
        });
        html += `</ul>`;
        container.innerHTML = html;
    }

    window.filterList = function () {
        const searchInput = document.getElementById('search-input');
        const filterSelect = document.getElementById('status-filter');

        const query = searchInput ? searchInput.value.toLowerCase() : '';
        const statusFilter = filterSelect ? filterSelect.value : 'all';

        const filtered = allPeopleData.filter(p => {
            const matchesSearch = p.name.toLowerCase().includes(query);
            const isPresent = p.is_present == true || p.is_present == 1;

            let matchesStatus = true;
            if (statusFilter === 'present') matchesStatus = isPresent;
            if (statusFilter === 'absent') matchesStatus = !isPresent;

            return matchesSearch && matchesStatus;
        });

        renderList(filtered);
    };

    // Init
    loadSessionPeople();
});

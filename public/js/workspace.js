/**
 * Workspace Scripts (People Management, Session Starting)
 */
document.addEventListener('DOMContentLoaded', () => {

    const listId = localStorage.getItem('current_list_id');
    const listName = localStorage.getItem('current_list_name');

    // --- BASIC INIT CHECK ---
    if (!listId) {
        window.location.href = '/dashboard';
        return;
    }

    if (listName) {
        const titleEl = document.getElementById('list-title');
        if (titleEl) titleEl.innerText = listName;
    }

    loadPeople();
    loadSessions();

    // --- MODAL: Load Previous Session Titles ---
    const startSessionModal = document.getElementById('startSessionModal');
    if (startSessionModal) {
        startSessionModal.addEventListener('show.bs.modal', function () {
            fetch(`/api/lists/${listId}/session-titles`, {
                headers: jsonHeaders(),
                credentials: 'same-origin'
            })
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('suggestion-chips-container');
                    const input = document.getElementById('session-title-input');

                    container.innerHTML = '';

                    const uniqueTitles = [...new Set((data.sessions || []).map(s => s.title))].slice(0, 5);

                    if (uniqueTitles.length === 0) {
                        container.innerHTML = '<small class="text-secondary">No recent sessions.</small>';
                        return;
                    }

                    uniqueTitles.forEach(title => {
                        const chip = document.createElement('div');
                        chip.className = 'suggestion-chip';
                        chip.innerHTML = `<i class="fa-solid fa-clock-rotate-left me-2"></i>${title}`;
                        chip.addEventListener('click', () => {
                            input.value = title;
                            input.focus();
                        });
                        container.appendChild(chip);
                    });
                })
                .catch(err => console.error('Error loading session titles:', err));
        });
    }

    // --- LOAD PEOPLE ---
    function loadPeople() {
        if (!document.getElementById('people-list')) return;

        fetch(`/api/lists/${listId}/people`, {
            headers: jsonHeaders(),
            credentials: 'same-origin'
        })
            .then(res => res.json())
            .then(data => {
                const ul = document.getElementById('people-list');
                const emptyState = document.getElementById('empty-state');
                const countDisplay = document.getElementById('count-display');

                ul.innerHTML = '';
                const people = data.data || [];

                if (countDisplay) countDisplay.innerText = people.length;

                if (people.length === 0) {
                    emptyState.classList.remove('d-none');
                    return;
                }

                emptyState.classList.add('d-none');

                people.forEach(person => {
                    const initials = person.name.slice(0, 2).toUpperCase();
                    ul.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-initial">${initials}</div>
                            <div class="fw-medium text-dark">${person.name}</div>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePerson(${person.id}, '${person.name.replace(/'/g, "\\'")}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </li>
                `;
                });
            })
            .catch(err => console.error('Error loading people:', err));
    }

    // --- LOAD SESSIONS ---
    function loadSessions() {
        if (!document.getElementById('sessions-list')) return;

        fetch(`/api/lists/${listId}/sessions`, {
            headers: jsonHeaders(),
            credentials: 'same-origin'
        })
            .then(res => res.json())
            .then(data => {
                const div = document.getElementById('sessions-list');
                div.innerHTML = '';

                const sessions = data.data || [];

                if (sessions.length === 0) {
                    div.innerHTML = '<div class="text-center py-4 text-secondary small">No past sessions found.</div>';
                    return;
                }

                sessions.forEach(s => {
                    const d = new Date(s.session_date + 'T00:00:00');
                    const dateStr = d.toLocaleDateString();

                    div.innerHTML += `
                    <div class="session-item p-3 d-flex justify-content-between align-items-center border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 text-secondary me-3">
                                <i class="fa-regular fa-calendar-check"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark small">${s.title}</div>
                                <div class="text-secondary" style="font-size: 0.75rem;">${dateStr}</div>
                            </div>
                        </div>
                        <a href="/session-view/${s.id}" class="btn btn-sm btn-outline-dark">
                            View <i class="fa-solid fa-angle-right ms-1"></i>
                        </a>
                    </div>
                `;
                });
            });
    }

    // --- GLOBAL FUNCTIONS ATTACHED TO WINDOW (For inline onclicks) ---

    // START SESSION
    window.startSession = async function () {
        const titleInput = document.getElementById('session-title-input');
        const title = titleInput.value.trim();

        if (!title) {
            alert('Enter session title');
            return;
        }

        try {
            const res = await fetch(`/api/lists/${listId}/sessions`, {
                method: 'POST',
                headers: jsonHeaders(true),
                credentials: 'same-origin',
                body: JSON.stringify({ title })
            });

            const data = await res.json();

            if (!res.ok) {
                alert(data.message || 'Failed to start session');
                return;
            }

            const sessionId = data.data.id;

            bootstrap.Modal.getInstance(document.getElementById('startSessionModal')).hide();
            window.location.href = `/session/${sessionId}`;
        } catch (err) {
            console.error(err);
            alert('Connection error starting session');
        }
    };

    // BULK ADD PEOPLE
    window.submitPeople = function () {
        const textarea = document.getElementById('people-textarea');
        const text = textarea.value.trim();
        if (!text) return;

        const names = text.split('\n').map(n => n.trim()).filter(Boolean);

        fetch(`/api/lists/${listId}/people/bulk`, {
            method: 'POST',
            headers: jsonHeaders(true),
            credentials: 'same-origin',
            body: JSON.stringify({ names })
        })
            .then((res) => {
                if (!res.ok) throw new Error("API returned failure");
                textarea.value = '';
                loadPeople();
                bootstrap.Modal.getInstance(document.getElementById('addPeopleModal')).hide();
            })
            .catch(err => {
                alert('Failed to add people');
                console.error(err);
            });
    };

    // DELETE PERSON
    window.deletePerson = async function (personId, personName) {
        const confirmDelete = confirm(`Delete "${personName}" ?`);
        if (!confirmDelete) return;

        try {
            const response = await fetch(`/api/lists/${listId}/people/${personId}`, {
                method: 'DELETE',
                headers: jsonHeaders(true),
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || 'Failed to delete person');
                return;
            }

            loadPeople();
        } catch (err) {
            console.error(err);
            alert('Server error while deleting person');
        }
    };

    // DELETE LIST
    window.deleteCurrentList = async function () {
        if (!listId) return;

        const confirmDelete = confirm(`Delete list "${listName}" ?`);
        if (!confirmDelete) return;

        try {
            const response = await fetch(`/api/lists/${listId}`, {
                method: 'DELETE',
                headers: jsonHeaders(true),
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || 'Unable to delete list.');
                return;
            }

            localStorage.removeItem('current_list_id');
            localStorage.removeItem('current_list_name');

            window.location.href = '/dashboard';

        } catch (err) {
            console.error(err);
            alert('Server error while deleting list.');
        }
    };

});

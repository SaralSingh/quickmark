/**
 * Dashboard Scripts (List Management)
 */
document.addEventListener('DOMContentLoaded', () => {

    const createListForm = document.getElementById('createListForm');

    // Initial Load
    loadLists();

    /* ---------- Load Lists ---------- */
    async function loadLists() {
        const loader = document.getElementById('loader');
        const emptyState = document.getElementById('empty-state');
        const container = document.getElementById('lists-container');

        if (!loader || !emptyState || !container) return; // Not on dashboard

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

            const statLists = document.getElementById('stat-lists');
            if (statLists) statLists.innerText = result.total ?? 0;

            if (lists.length === 0) {
                emptyState.classList.remove('d-none');
                return;
            }

            container.classList.remove('d-none');

            lists.forEach(list => {
                const safeName = list.name.replace(/'/g, "\\'");
                const card = `
                    <div class="col-md-4">
                        <div class="card hover-card border-0 h-100">
                            <div class="card-body">
                                <h5 class="fw-bold">${list.name}</h5>
                                <p class="text-secondary small mb-3">
                                    List ID: ${list.id}
                                </p>
                                <button 
                                    onclick="openList(${list.id}, '${safeName}')" 
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
            console.error('Failed to load lists:', error);
        }
    }

    /* ---------- Create List ---------- */
    if (createListForm) {
        createListForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const input = document.getElementById('new-list-name');
            const name = input.value.trim();
            if (!name) return;

            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/lists', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: jsonHeaders(true),
                    body: JSON.stringify({ name })
                });

                const data = await response.json();

                if (!response.ok) {
                    alert(data.message || 'Error creating list');
                    return;
                }

                input.value = '';

                // Close modal 
                const modalEl = document.getElementById('createListModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl)
                    || new bootstrap.Modal(modalEl);
                modalInstance.hide();

                // Refresh Lists
                loadLists();
            } catch (err) {
                alert('Connection error while creating list.');
                console.error(err);
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }
});

/* ---------- Global Open List ---------- */
window.openList = function (id, name) {
    localStorage.setItem('current_list_id', id);
    localStorage.setItem('current_list_name', name);
    window.location.href = '/list-workspace';
}

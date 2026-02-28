/**
 * Centralized Utility Functions
 */

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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

/**
 * Validates inputs visually on fetch rejection 
 */
function displayValidationErrors(errors) {
    // Clear old ones
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    for (const [key, messages] of Object.entries(errors)) {
        const input = document.getElementById(key);
        const errorDiv = document.getElementById(`error-${key}`);
        if (input && errorDiv) {
            input.classList.add('is-invalid');
            errorDiv.textContent = messages[0];
        }
    }
}

/**
 * Handle Logout
 */
document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            try {
                const res = await fetch('/logout', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: jsonHeaders(true)
                });

                if (res.ok) {
                    window.location.href = '/login';
                } else {
                    alert('Logout Failed');
                }
            } catch (err) {
                console.error(err);
                alert('Server error during logout');
            }
        });
    }
});

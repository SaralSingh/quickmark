/**
 * Authentication Scripts (Login & Register)
 */
document.addEventListener('DOMContentLoaded', () => {

    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const generalError = document.getElementById('general-error');

    function resetFormState(btn, spinner) {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        if (generalError) generalError.classList.add('d-none');
        btn.disabled = true;
        spinner.classList.remove('d-none');
    }

    function restoreFormState(btn, spinner) {
        btn.disabled = false;
        spinner.classList.add('d-none');
    }

    /* ---------- Login Handler ---------- */
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const spinner = btn.querySelector('.spinner-border');
            resetFormState(btn, spinner);

            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                remember: document.getElementById('remember').checked
            };

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: jsonHeaders(true),
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.status === 422) {
                    displayValidationErrors(data.errors);
                    throw new Error("Please check your input.");
                }

                if (response.status === 401 || !response.ok) {
                    throw new Error(data.message || 'Invalid email or password.');
                }

                window.location.href = '/dashboard';

            } catch (error) {
                if (error.message !== "Please check your input." && generalError) {
                    generalError.textContent = error.message;
                    generalError.classList.remove('d-none');
                }
            } finally {
                restoreFormState(btn, spinner);
            }
        });
    }

    /* ---------- Register Handler ---------- */
    if (registerForm) {
        registerForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const spinner = btn.querySelector('.spinner-border');
            resetFormState(btn, spinner);

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value
            };

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: jsonHeaders(true),
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.status === 422) {
                    displayValidationErrors(data.errors);
                    throw new Error("Please fix the errors above.");
                }

                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong. Please try again.');
                }

                window.location.href = '/login';

            } catch (error) {
                if (error.message !== "Please fix the errors above." && generalError) {
                    generalError.textContent = error.message;
                    generalError.classList.remove('d-none');
                }
            } finally {
                restoreFormState(btn, spinner);
            }
        });
    }
});

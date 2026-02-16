<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Login | Praman v2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #0f172a; /* Slate 900 */
            --bs-body-bg: #f1f5f9; /* Slate 100 */
        }
        
        body {
            background-color: var(--bs-body-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 0.25rem rgba(15, 23, 42, 0.25);
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #1e293b;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="auth-card p-4 p-md-5">
                
                <div class="text-center mb-4">
                    <a href="/" class="text-dark text-decoration-none fs-4 fw-bold">
                        <i class="fa-solid fa-check-double me-2"></i> QuickMark <span class="badge bg-dark ms-1" style="font-size: 0.5em; vertical-align: top;"></span>
                    </a>
                    <p class="text-secondary mt-2 mb-0">Welcome back. Please sign in.</p>
                </div>

                <div id="general-error" class="alert alert-danger d-none text-center small" role="alert"></div>

                <form id="loginForm">
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" placeholder="name@example.com">
                        <label for="email">Email Address</label>
                        <div class="invalid-feedback" id="error-email"></div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                        <label for="password">Password</label>
                        <div class="invalid-feedback" id="error-password"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label small text-secondary" for="remember">
                                Remember me
                            </label>
                        </div>
                        <a href="/forgot-password" class="small text-decoration-none text-secondary fw-bold">Forgot password?</a>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-secondary mb-0">
                        Don't have an account? 
                        <a href="{{route('register')}}" class="text-decoration-none fw-bold text-dark">Create one</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('submitBtn');
    const spinner = btn.querySelector('.spinner-border');
    const generalError = document.getElementById('general-error');

    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    generalError.classList.add('d-none');
    btn.disabled = true;
    spinner.classList.remove('d-none');

    const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        remember: document.getElementById('remember').checked
    };

    try {
        const response = await fetch('/login', {
            method: 'POST',
            credentials: 'same-origin',   // ⭐ IMPORTANT
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (response.status === 422) {
            for (const [key, messages] of Object.entries(data.errors)) {
                const input = document.getElementById(key);
                const errorDiv = document.getElementById(`error-${key}`);
                if (input && errorDiv) {
                    input.classList.add('is-invalid');
                    errorDiv.textContent = messages[0];
                }
            }
            throw new Error("Please check your input.");
        }

        if (response.status === 401 || !response.ok) {
            throw new Error(data.message || 'Invalid email or password.');
        }

        // ✅ NO TOKEN STORAGE
        // Laravel ne already session cookie de di hai

        window.location.href = '/dashboard';

    } catch (error) {
        if(error.message !== "Please check your input.") {
            generalError.textContent = error.message;
            generalError.classList.remove('d-none');
        }
    } finally {
        btn.disabled = false;
        spinner.classList.add('d-none');
    }
});
</script>

</body>
</html>
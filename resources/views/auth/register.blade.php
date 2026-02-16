<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Register | QuickMark</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #0f172a; 
            --bs-body-bg: #f1f5f9;
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
        /* Error text style */
        .invalid-feedback {
            font-size: 0.85em;
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
                    <p class="text-secondary mt-2 mb-0">Create your account to start tracking.</p>
                </div>

                <div id="general-error" class="alert alert-danger d-none" role="alert"></div>

                <form id="registerForm">
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" placeholder="John Doe">
                        <label for="name">Full Name</label>
                        <div class="invalid-feedback" id="error-name"></div>
                    </div>

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

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password">
                        <label for="password_confirmation">Confirm Password</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                            Create Account
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-secondary mb-0">
                        Already have an account? 
                        <a href="{{route('login')}}" class="text-decoration-none fw-bold text-dark">Log in</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // 1. Setup UI (Clear errors, show loading)
            const btn = document.getElementById('submitBtn');
            const spinner = btn.querySelector('.spinner-border');
            const generalError = document.getElementById('general-error');
            
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            generalError.classList.add('d-none');

            // Disable button & show spinner
            btn.disabled = true;
            spinner.classList.remove('d-none');

            // 2. Prepare Data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value
            };

            try {
                // 3. Send Request
                const response = await fetch('/register', { 
                    method: 'POST',
                    credentials: 'same-origin',  
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Laravel CSRF Token (Assuming this is inside a Blade file)
                                'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                // 4. Handle Validation Errors (Laravel sends 422)
                if (response.status === 422) {
                    // Loop through errors object (e.g., { email: ['Email taken'], password: ['Too short'] })
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const input = document.getElementById(key);
                        const errorDiv = document.getElementById(`error-${key}`);
                        
                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.textContent = messages[0]; // Show first error message
                        }
                    }
                    throw new Error("Please fix the errors above.");
                }

                // 5. Handle General Errors (Server Error, DB connection, etc)
                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong. Please try again.');
                }

                // 6. Success!
                // Optional: Show success message or redirect
                // window.location.href = '/dashboard'; 
                alert('Account created successfully! Redirecting...');
                window.location.href = '/login'; 

            } catch (error) {
                // Show general error message at top
                generalError.textContent = error.message;
                generalError.classList.remove('d-none');
            } finally {
                // Reset Loading State
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
</body>
</html>
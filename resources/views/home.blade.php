<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickMark | Universal Presence Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom tweaks to standard Bootstrap for a premium feel */
        :root {
            --bs-primary: #0f172a; /* Slate 900 - Serious Dark */
            --bs-secondary: #475569; /* Slate 600 */
            --bs-light: #f8fafc;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
        }

        .hero-section {
            padding: 100px 0;
            background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%);
        }

        .feature-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            background-color: rgba(15, 23, 42, 0.05);
            border-radius: 12px;
            color: var(--bs-primary);
        }

        .code-block {
            background-color: #1e293b;
            color: #e2e8f0;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9rem;
            border-radius: 8px;
            padding: 20px;
        }

        .step-number {
            font-weight: 800;
            font-size: 3rem;
            opacity: 0.1;
            position: absolute;
            top: -10px;
            right: 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fa-solid fa-check-double me-2"></i> QuickMark <span class="badge bg-dark ms-1" style="font-size: 0.6em; vertical-align: top;"></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    {{-- <li class="nav-item"><a class="nav-link" href="#how-it-works">How it Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#architecture">Architecture</a></li>
                    <li class="nav-item"><a class="nav-link" href="#api">API Docs</a></li> --}}
                    <li class="nav-item ms-lg-3">
                        <a href="{{route('login')}}" class="btn btn-outline-dark btn-sm px-4">Log In</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{route('register')}}" class="btn btn-dark btn-sm px-4">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-secondary mb-3 text-uppercase tracking-wide">Presence Register Engine</span>
                    <h1 class="display-4 fw-bold mb-4 text-dark">Universal Presence Tracking.<br>Simplified.</h1>
                    <p class="lead text-secondary mb-5">
                        QuickMark is not just an attendance app for students. It is a rule-driven engine for classes, teams, workshops, and events. 
                        <br><strong>Create list → Start session → Mark presence.</strong>
                    </p>
                    {{-- <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-dark btn-lg px-5 shadow-sm">Create Account</button>
                        <button class="btn btn-outline-secondary btn-lg px-5">View API Spec</button>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Core Concepts</h2>
                    <p class="text-secondary">A flexible structure designed for any group dynamic.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fa-solid fa-users fa-xl"></i>
                            </div>
                            <h4 class="card-title fw-bold">1. The List</h4>
                            <p class="card-text text-secondary">A fixed group of people. Define it once, use it forever.</p>
                            <ul class="list-unstyled text-secondary small bg-light p-2 rounded text-start">
                                <li><i class="fa-solid fa-angle-right me-2"></i>BCA 3rd Year - Section A</li>
                                <li><i class="fa-solid fa-angle-right me-2"></i>Morning Cricket Team</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fa-solid fa-calendar-day fa-xl"></i>
                            </div>
                            <h4 class="card-title fw-bold">2. The Session</h4>
                            <p class="card-text text-secondary">A single occurrence where tracking happens.</p>
                            <ul class="list-unstyled text-secondary small bg-light p-2 rounded text-start">
                                <li><i class="fa-solid fa-angle-right me-2"></i>DBMS Lecture - 5 Feb</li>
                                <li><i class="fa-solid fa-angle-right me-2"></i>Monthly Workshop</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3 text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fa-solid fa-check fa-xl"></i>
                            </div>
                            <h4 class="card-title fw-bold">3. The Presence</h4>
                            <p class="card-text text-secondary">The binary record of attendance per person.</p>
                            <ul class="list-unstyled text-secondary small bg-light p-2 rounded text-start">
                                <li><i class="fa-solid fa-angle-right me-2"></i>Present / Absent</li>
                                <li><i class="fa-solid fa-angle-right me-2"></i>Rule: 1 record per session</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold">Universal Application</h2>
                    <p class="text-secondary">Built to handle academic rigor and casual team management alike.</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow h-100 position-relative overflow-hidden">
                        <div class="card-body p-4">
                            <span class="step-number">01</span>
                            <h5 class="text-uppercase text-primary fw-bold small mb-2">Scenario: Academic</h5>
                            <h3 class="fw-bold mb-3">The Teacher</h3>
                            <p class="text-secondary mb-4">Perfect for recurring classes like "BCA 3rd Year".</p>
                            <div class="d-flex align-items-start mb-2">
                                <i class="fa-solid fa-circle-check text-success mt-1 me-3"></i>
                                <div><strong>Create List:</strong> Import student roster.</div>
                            </div>
                            <div class="d-flex align-items-start mb-2">
                                <i class="fa-solid fa-circle-check text-success mt-1 me-3"></i>
                                <div><strong>Lecture Day:</strong> Start "DBMS Lecture" session.</div>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="fa-solid fa-circle-check text-success mt-1 me-3"></i>
                                <div><strong>Record:</strong> Tick checkboxes and save.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow h-100 position-relative overflow-hidden">
                        <div class="card-body p-4">
                            <span class="step-number">02</span>
                            <h5 class="text-uppercase text-primary fw-bold small mb-2">Scenario: General</h5>
                            <h3 class="fw-bold mb-3">The Team Captain</h3>
                            <p class="text-secondary mb-4">Ideal for clubs, workshops, or sports teams.</p>
                            <div class="d-flex align-items-start mb-2">
                                <i class="fa-solid fa-circle-check text-primary mt-1 me-3"></i>
                                <div><strong>Create List:</strong> "Cricket Team".</div>
                            </div>
                            <div class="d-flex align-items-start mb-2">
                                <i class="fa-solid fa-circle-check text-primary mt-1 me-3"></i>
                                <div><strong>Practice:</strong> Start "Morning Drill" session.</div>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="fa-solid fa-circle-check text-primary mt-1 me-3"></i>
                                <div><strong>Record:</strong> Mark who showed up.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">QuickMark</h5>
                    <p class="text-muted small">A properly engineered presence register engine. Version 2.0 focuses on clean database design and rule-driven tracking.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold small text-muted">Platform</h6>
                    <ul class="list-unstyled small mt-2">
                        <li><a href="#" class="text-decoration-none text-white-50">Login</a></li>
                        <li><a href="#" class="text-decoration-none text-white-50">Register</a></li>
                        <li><a href="#" class="text-decoration-none text-white-50">Dashboard</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold small text-muted">Developers</h6>
                    <ul class="list-unstyled small mt-2">
                        <li><a href="#" class="text-decoration-none text-white-50">API Docs</a></li>
                        <li><a href="#" class="text-decoration-none text-white-50">Schema</a></li>
                        <li><a href="#" class="text-decoration-none text-white-50">Github</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4 text-md-end">
                    <p class="small text-muted">&copy; 2026 QuickMark Project.<br>All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
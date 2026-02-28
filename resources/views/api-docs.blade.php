@extends('layouts.guest')

@section('title', 'API Documentation | QuickMark')

@push('head')
<style>
    .api-header {
        background: linear-gradient(135deg, var(--bs-primary) 0%, #1e293b 100%);
        color: white;
        padding: 4rem 0 3rem;
    }

    .sidebar-nav {
        position: sticky;
        top: 80px;
    }

    .sidebar-nav .nav-link {
        color: var(--bs-secondary);
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
        background-color: #f1f5f9;
        color: var(--bs-primary);
        font-weight: 600;
    }

    .endpoint-card {
        border: 1px solid var(--bs-card-border);
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        background: #fff;
    }

    .endpoint-header {
        background: #f8fafc;
        border-bottom: 1px solid var(--bs-card-border);
        padding: 1rem 1.5rem;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .method-badge {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .method-get { background: #e0f2fe; color: #0284c7; }
    .method-post { background: #dcfce7; color: #16a34a; }
    .method-delete { background: #fee2e2; color: #dc2626; }

    .endpoint-url {
        font-family: monospace;
        font-size: 1.05rem;
        color: var(--bs-primary);
        font-weight: 600;
        word-break: break-word;
    }

    .endpoint-body {
        padding: 1.5rem;
    }

    .code-block {
        background: #0f172a;
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.9rem;
        overflow-x: auto;
    }

    .param-table th {
        background: #f8fafc;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--bs-secondary);
    }
</style>
@endpush

@section('content')

<section class="api-header text-center">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">API Documentation</h1>
        <p class="lead mb-0 text-white-50">Integrate QuickMark's rule-driven presence engine into your own applications.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 d-none d-lg-block">
            <nav class="sidebar-nav pe-4">
                <h6 class="text-uppercase fw-bold text-secondary mb-3 small ls-1">Core Modules</h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#auth">1. Authentication</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#lists">2. List Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#people">3. People & Roster</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sessions">4. Session Tracking</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            
            <div class="alert alert-light border border-primary border-opacity-25 shadow-sm mb-5">
                <div class="d-flex">
                    <i class="fa-solid fa-circle-info text-primary mt-1 me-3 fa-lg"></i>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Base URL</h5>
                        <code class="fs-6 bg-light text-dark px-2 py-1 rounded border text-break">https://yourdomain.com/api</code>
                        <p class="mb-0 mt-2 text-secondary small">All API requests must include the `Accept: application/json` header. Stateful requests like `POST` and `DELETE` from the browser must also include the `X-CSRF-TOKEN` header if relying on session cookies.</p>
                    </div>
                </div>
            </div>

            <!-- 1. Authentication -->
            <section id="auth" class="mb-5 pt-4">
                <h3 class="fw-bold mb-4 border-bottom pb-2">1. Authentication</h3>
                
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-url">/login</span>
                    </div>
                    <div class="endpoint-body">
                        <p class="text-secondary mb-3">Authenticate a user and initialize a session. This is an API-first endpoint that uses web middleware for secure cookie-based session persistence.</p>
                        <h6 class="fw-bold small text-uppercase text-secondary">Request Body</h6>
                        <pre class="code-block mb-0">
{
  "email": "user@example.com",
  "password": "yourpassword"
}
                        </pre>
                    </div>
                </div>

                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-url">/register</span>
                    </div>
                    <div class="endpoint-body">
                         <p class="text-secondary mb-3">Create a new user account.</p>
                         <pre class="code-block mb-0">
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "securepassword",
  "password_confirmation": "securepassword"
}
                        </pre>
                    </div>
                </div>
            </section>

            <!-- 2. Lists -->
            <section id="lists" class="mb-5 pt-4">
                <h3 class="fw-bold mb-4 border-bottom pb-2">2. List Management</h3>
                <p class="text-secondary mb-4">Lists are isolated containers for people (students, team members, etc.). Users can only access and modify their own lists.</p>
                
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-url">/api/lists</span>
                    </div>
                    <div class="endpoint-body">
                         <p class="text-secondary mb-3">Retrieve all lists belonging to the authenticated user.</p>
                         <h6 class="fw-bold small text-uppercase text-secondary">Response</h6>
                         <pre class="code-block mb-0">
{
  "data": [
    { "id": 1, "name": "BCA 3rd Semester", "created_at": "..." },
    { "id": 2, "name": "Cricket Team", "created_at": "..." }
  ],
  "total": 2
}
                        </pre>
                    </div>
                </div>

                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-url">/api/lists</span>
                    </div>
                    <div class="endpoint-body">
                        <p class="text-secondary mb-3">Create a new list.</p>
                        <pre class="code-block mb-0">
{
  "name": "Weekend Workshop"
}
                        </pre>
                    </div>
                </div>
            </section>

            <!-- 3. People -->
            <section id="people" class="mb-5 pt-4">
                <h3 class="fw-bold mb-4 border-bottom pb-2">3. People & Roster</h3>
                <p class="text-secondary mb-4">Manage the roster of individuals inside a specific list.</p>

                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-url">/api/lists/{list_id}/people/bulk</span>
                    </div>
                    <div class="endpoint-body">
                        <p class="text-secondary mb-3">Add multiple people to a list at once. Limited to 100 people per request.</p>
                        <pre class="code-block mb-0">
{
  "names": [
    "Alice Smith",
    "Bob Johnson",
    "Charlie Davis"
  ]
}
                        </pre>
                    </div>
                </div>

                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-delete">DELETE</span>
                        <span class="endpoint-url">/api/lists/{list_id}/people/{person_id}</span>
                    </div>
                    <div class="endpoint-body">
                        <p class="text-secondary mb-0">Remove a person from the list. This operation cascades and nullifies their presence history in past sessions to maintain aggregate data integrity.</p>
                    </div>
                </div>
            </section>

            <!-- 4. Sessions & Tracking -->
            <section id="sessions" class="mb-5 pt-4">
                <h3 class="fw-bold mb-4 border-bottom pb-2">4. Session Tracking</h3>
                <p class="text-secondary mb-4">The core tracking engine. You initialize a session, mark presences, and then finalize and save the record.</p>

                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-url">/api/lists/{list_id}/sessions</span>
                    </div>
                    <div class="endpoint-body">
                        <p class="text-secondary mb-3">Start a new attendance tracking session for today.</p>
                        <pre class="code-block mb-0">
{
  "title": "DBMS Lecture 5"
}
                        </pre>
                    </div>
                </div>

                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-url">/api/sessions/{session_id}/presence</span>
                    </div>
                    <div class="endpoint-body">
                        <p class="text-secondary mb-3">Record presence for an individual within an active session. When `final_save` is passed as `1`, the session state is locked and marked as completed.</p>
                        
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered param-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>person_id</code></td>
                                        <td>Integer</td>
                                        <td>The ID of the person being marked.</td>
                                    </tr>
                                    <tr>
                                        <td><code>is_present</code></td>
                                        <td>Boolean/Int</td>
                                        <td><code>1</code> for Present, <code>0</code> for Absent.</td>
                                    </tr>
                                    <tr>
                                        <td><code>final_save</code></td>
                                        <td>Boolean/Int</td>
                                        <td>Send <code>1</code> on the last sync to close the session.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <pre class="code-block mb-0">
{
  "person_id": 142,
  "is_present": 1,
  "final_save": 0
}
                        </pre>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Smooth scrolling for sidebar navigation
    document.querySelectorAll('.sidebar-nav a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
@endpush

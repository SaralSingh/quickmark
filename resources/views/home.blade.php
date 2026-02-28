@extends('layouts.guest')

@section('title', 'QuickMark | Universal Presence Register')

@push('head')
<style>
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
        background-color: rgba(15, 23, 42, 0.05); /* Very light primary */
        border-radius: 12px;
        color: var(--bs-primary);
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
@endpush

@section('content')

<section class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <span class="badge text-bg-secondary mb-3 text-uppercase tracking-wide">Presence Register Engine</span>
                <h1 class="display-4 fw-bold mb-4 text-dark">Universal Presence Tracking.<br>Simplified.</h1>
                <p class="lead text-secondary mb-5">
                    QuickMark is not just an attendance app for students. It is a rule-driven engine for classes, teams, workshops, and events. 
                    <br><strong>Create list → Start session → Mark presence.</strong>
                </p>
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
            <!-- Concept 1 -->
            <div class="col-md-4">
                <div class="card hover-card h-100 p-3 text-center">
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
            <!-- Concept 2 -->
            <div class="col-md-4">
                <div class="card hover-card h-100 p-3 text-center">
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
            <!-- Concept 3 -->
            <div class="col-md-4">
                <div class="card hover-card h-100 p-3 text-center">
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
                    <div class="card-body p-4 border-start border-4 border-success">
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
                    <div class="card-body p-4 border-start border-4 border-primary">
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

@endsection
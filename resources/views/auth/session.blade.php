@extends('layouts.app')

@section('title', 'Active Session | QuickMark')

@push('head')
<style>
    /* Active Session List Items */
    .list-group-item {
        border: 1px solid var(--bs-border-color);
        margin-bottom: 8px;
        border-radius: 8px !important; 
        padding: 1rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .list-group-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Active State (Present) */
    .list-group-item.active-presence {
        background-color: #ecfdf5; /* Light Green */
        border-color: #10b981;
    }

    .avatar-initial {
        width: 40px;
        height: 40px;
        background-color: #e2e8f0;
        color: #475569;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 1rem;
    }

    .active-presence .avatar-initial {
        background-color: #10b981;
        color: white;
    }

    /* Floating Action Bar */
    .action-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        padding: 1rem;
        border-top: 1px solid var(--bs-border-color);
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Important: clear space at the bottom for action bar */
    body {
        padding-bottom: 80px; 
    }

    /* Saving Overlay */
    #saving-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 2000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')

<div class="card p-4 mb-4 border-0 shadow-sm bg-white d-flex flex-row justify-content-between align-items-center">
    <div>
        <span class="text-secondary small text-uppercase fw-bold ls-1">Active Roll Call</span>
        <h4 id="session-title" class="fw-bold mb-1 mt-1 text-dark">Loading Session...</h4>
        <div class="text-secondary small">
            <i class="fa-regular fa-calendar me-1"></i> <span id="session-date">...</span>
        </div>
    </div>
    <div class="text-end">
        <h2 class="fw-bold mb-0 text-primary" id="present-count">0</h2>
        <small class="text-secondary fw-semibold">Marked Present</small>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="text-secondary fw-bold text-uppercase small">People List</h6>
    <span class="badge bg-light text-dark border" id="total-count">Total: 0</span>
</div>

<ul id="people-list" class="list-group list-group-flush bg-transparent">
    <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
</ul>

@endsection

@push('modals')
<!-- Action Bar specific to Session Tracking -->
<div class="action-bar">
    <div class="d-none d-md-block">
        <span class="text-secondary small fw-medium">Review carefully before saving.</span>
    </div>
    <button class="btn btn-dark px-5 py-2 fw-bold rounded-pill shadow w-100 w-md-auto" onclick="saveSession()">
        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Save Record
    </button>
</div>

<!-- Saving States -->
<div id="saving-overlay" class="d-none">
    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
    <h4 class="fw-bold text-dark">Saving Attendance...</h4>
    <p class="text-secondary">Please do not close this window.</p>
    <div class="progress w-50 mt-2" style="height: 6px;">
        <div id="save-progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
    </div>
    <p class="small text-muted mt-2 fw-medium" id="save-status-text">Preparing...</p>
</div>
@endpush

@push('scripts')
<script>
    // Output Blade variable to JS Context securely
    const sessionId = {{ $sessionId }};
</script>
<script src="{{ asset('js/session.js') }}"></script>
@endpush
@extends('layouts.app')

@section('title', 'Session Report | QuickMark')

@push('head')
<style>
    .stat-card { transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-2px); }
    
    .icon-box {
        width: 48px; height: 48px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }

    .list-group-item {
        border-left: none; border-right: none;
        border-color: #f1f5f9;
        padding: 1rem 1.25rem;
        transition: background-color 0.15s;
    }
    .list-group-item:hover { background-color: #f8fafc; }

    .avatar-initial {
        width: 40px; height: 40px;
        background-color: #e2e8f0;
        color: #475569;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 1rem;
    }

    .badge-present {
        background-color: rgba(16, 185, 129, 0.1); /* bg-success-subtle equivalent */
        color: var(--bs-success);
        border: 1px solid rgba(16, 185, 129, 0.2);
        padding: 0.5em 1em;
        border-radius: 20px;
        font-weight: 600;
    }
    .badge-absent {
        background-color: rgba(239, 68, 68, 0.1); /* bg-danger-subtle equivalent */
        color: var(--bs-danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.5em 1em;
        border-radius: 20px;
        font-weight: 600;
    }
</style>
@endpush

@section('header-actions')
<!-- Adding a specific Back Button override for the Header action center -->
<button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='/list-workspace'">
    <i class="fa-solid fa-arrow-left me-1"></i> Back to Workspace
</button>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
    <div>
        <span class="text-uppercase text-secondary small fw-bold">Session Report</span>
        <h2 class="fw-bold text-dark mt-1 mb-2" id="page-title">Session Details</h2>
        <div class="text-secondary small fw-medium">
            <i class="fa-regular fa-calendar me-1"></i> <span id="session-date-display">Loading date...</span>
        </div>
    </div>
    <div>
        <button class="btn btn-outline-dark btn-sm shadow-sm" onclick="window.print()">
            <i class="fa-solid fa-print me-2"></i> Print Report
        </button>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Total Metric -->
    <div class="col-md-4">
        <div class="card stat-card p-3 h-100 border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-light text-primary me-3">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="mb-0 text-secondary small fw-bold text-uppercase">Total Enrolled</p>
                    <h3 class="mb-0 fw-bold" id="stat-total">0</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Present Metric -->
    <div class="col-md-4">
        <div class="card stat-card p-3 h-100 border-0 shadow-sm border-bottom border-success border-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-success-subtle text-success me-3">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <p class="mb-0 text-secondary small fw-bold text-uppercase">Present</p>
                    <h3 class="mb-0 fw-bold text-success" id="stat-present">0</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Absent Metric -->
    <div class="col-md-4">
        <div class="card stat-card p-3 h-100 border-0 shadow-sm border-bottom border-danger border-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-danger-subtle text-danger me-3">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
                <div>
                    <p class="mb-0 text-secondary small fw-bold text-uppercase">Absent</p>
                    <h3 class="mb-0 fw-bold text-danger" id="stat-absent">0</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <h5 class="fw-bold mb-0 text-dark">Attendance List</h5>
            </div>
            <div class="col-md-3">
                <select id="status-filter" class="form-select form-select-sm bg-light shadow-sm border-0" onchange="filterList()">
                    <option value="all">All Records</option>
                    <option value="present">Present Only</option>
                    <option value="absent">Absent Only</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group input-group-sm shadow-sm border rounded">
                    <span class="input-group-text bg-white border-0">
                        <i class="fa-solid fa-search text-secondary"></i>
                    </span>
                    <input type="text" id="search-input" class="form-control border-0 bg-white" placeholder="Search name..." onkeyup="filterList()">
                </div>
            </div>
        </div>
    </div>

    <div id="people-list-container" class="card-body p-0">
        <div class="text-center py-5 text-secondary">
            <div class="spinner-border text-primary mb-2" role="status"></div>
            <div>Loading attendance data...</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const sessionId = {{ $sessionId }};
</script>
<script src="{{ asset('js/session-view.js') }}"></script>
@endpush
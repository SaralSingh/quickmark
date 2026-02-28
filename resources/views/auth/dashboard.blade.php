@extends('layouts.app')

@section('title', 'Dashboard | QuickMark')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
        <p class="text-secondary mb-0">Manage your lists and track presence.</p>
    </div>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createListModal">
        <i class="fa-solid fa-plus me-2"></i> New List
    </button>
</div>

<!-- Key Metrics Row -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card stat-card border-0 h-100 p-3 shadow-sm bg-white" style="border-left: 4px solid var(--bs-primary);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-uppercase text-secondary small fw-bold mb-1">Total Lists</p>
                    <h3 class="fw-bold mb-0 text-dark" id="stat-lists">0</h3>
                </div>
                <div class="bg-light p-2 rounded text-primary">
                    <i class="fa-solid fa-layer-group fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Future Metrics can go here -->
</div>

<h4 class="fw-bold text-dark mb-4"><i class="fa-solid fa-folder-open text-secondary me-2"></i> Your Lists</h4>

<!-- Loader State -->
<div id="loader" class="text-center py-5">
    <div class="spinner-border text-primary" role="status"></div>
    <p class="mt-2 text-secondary fw-medium">Loading your data...</p>
</div>

<!-- Empty State -->
<div id="empty-state" class="text-center py-5 d-none bg-white rounded-4 border border-1 shadow-sm">
    <div class="empty-state-icon text-muted mb-3">
        <i class="fa-regular fa-folder-open fa-3x"></i>
    </div>
    <h5 class="fw-bold text-dark">No lists found</h5>
    <p class="text-secondary mb-4">You haven't created any presence lists yet.</p>
    <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#createListModal">
        Create First List
    </button>
</div>

<!-- Lists Grid -->
<div id="lists-container" class="row g-4 d-none">
    <!-- List cards injected here via JS -->
</div>

@endsection

@push('modals')
<!-- Create List Modal -->
<div class="modal fade" id="createListModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Create New List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createListForm">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">List Name</label>
                        <input type="text" class="form-control form-control-lg" id="new-list-name" placeholder="e.g. BCA 3rd Sem, Cricket Team" required>
                        <div class="form-text mt-2">This will be the main container for your people.</div>
                    </div>
                    <div class="d-grid shadow-sm">
                        <button type="submit" class="btn btn-primary btn-lg">Create List</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
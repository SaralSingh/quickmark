@extends('layouts.app')

@section('title', 'Workspace | QuickMark')

@push('head')
<style>
    /* People List Specifics */
    .list-group-item {
        border-left: none;
        border-right: none;
        border-color: #f1f5f9;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
    }
    .list-group-item:first-child { border-top: none; }
    
    .avatar-initial {
        width: 36px; height: 36px;
        background-color: #e2e8f0;
        color: #475569;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600; font-size: 0.9rem;
        margin-right: 1rem;
    }

    /* Suggestion Chips */
    .suggestion-chip {
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #64748b;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
        user-select: none;
    }
    .suggestion-chip:hover {
        background-color: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')

<!-- Header Map Area -->
<div class="row align-items-center mb-5">
    <div class="col-md-8">
        <span class="text-uppercase text-secondary small fw-bold ls-1">Workspace</span>
        <h2 id="list-title" class="fw-bold text-dark mt-1 display-6">Loading...</h2>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#startSessionModal">
            <i class="fa-solid fa-play me-2"></i> Start Session
        </button>
    </div>
</div>

<div class="row g-4">
    
    <!-- Sidebar Area -->
    <div class="col-lg-4 col-md-5">
        
        <!-- Mobile Toggle Button -->
        <div class="d-md-none mb-3">
            <button class="btn btn-white bg-white w-100 py-3 shadow-sm border text-start d-flex justify-content-between align-items-center rounded-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#workspaceSidebar" aria-controls="workspaceSidebar">
                <span class="text-dark"><i class="fa-solid fa-bars me-2"></i> <span class="fw-bold">Workspace Features</span></span>
                <i class="fa-solid fa-chevron-right text-secondary"></i>
            </button>
        </div>

        <!-- Sidebar offcanvas -->
        <div class="offcanvas-md offcanvas-start" tabindex="-1" id="workspaceSidebar" aria-labelledby="workspaceSidebarLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="workspaceSidebarLabel">Features</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#workspaceSidebar" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body d-block p-3 p-md-0 shadow-sm border-0 d-md-block h-100">
                <!-- Total Enrolled Metric -->
                <div class="card p-4 mb-4 border-0 shadow-sm bg-primary text-white rounded-3">
                    <h6 class="fw-bold text-white-50 text-uppercase small mb-2">Total Enrolled</h6>
                    <div class="d-flex align-items-baseline">
                        <h1 class="fw-bold mb-0 me-2" id="count-display">0</h1>
                        <span class="text-white-50">people</span>
                    </div>
                </div>

                <!-- Features Navigation -->
                <div class="card border-0 shadow-sm mb-3 rounded-3">
                    <div class="card-body p-2">
                        <div class="nav nav-pills flex-column" id="workspace-tabs" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active text-start mb-2 py-3 fw-medium" data-bs-toggle="pill" data-bs-target="#people-tabpane" type="button" role="tab" onclick="closeMobileSidebar()">
                                <i class="fa-solid fa-users fa-fw me-2"></i> People List
                            </button>
                            <button class="nav-link text-start mb-2 py-3 fw-medium" data-bs-toggle="pill" data-bs-target="#sessions-tabpane" type="button" role="tab" onclick="closeMobileSidebar()">
                                <i class="fa-solid fa-clock-rotate-left fa-fw me-2"></i> Sessions History
                            </button>
                            <button class="nav-link text-start py-3 fw-medium" data-bs-toggle="pill" data-bs-target="#manage-tabpane" type="button" role="tab" onclick="closeMobileSidebar()">
                                <i class="fa-solid fa-gear fa-fw me-2"></i> Manage Workspace
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area / Tab Panes -->
    <div class="col-lg-8 col-md-7">
        <div class="tab-content" id="workspace-tabs-content">
            
            <!-- People Tab -->
            <div class="tab-pane fade show active" id="people-tabpane" role="tabpanel" tabindex="0">
                <div class="card shadow-sm h-100 border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">People</h5>
                        <div class="small text-secondary"><i class="fa-solid fa-circle-check text-success me-1"></i> Synced</div>
                    </div>

                    <div class="card-body p-0">
                        <div id="empty-state" class="text-center py-5 d-none">
                            <i class="fa-solid fa-users-slash text-secondary mb-3" style="font-size: 2rem;"></i>
                            <p class="text-secondary mb-0">No people in this list yet.</p>
                        </div>

                        <ul id="people-list" class="list-group list-group-flush rounded-bottom-3">
                            <!-- Populated by JS -->
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Sessions Tab -->
            <div class="tab-pane fade" id="sessions-tabpane" role="tabpanel" tabindex="0">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold text-dark mb-0">Previous Sessions</h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                        <div id="sessions-list" class="rounded-bottom-3">
                            <div class="text-center py-4 text-secondary small">Loading history...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Tab -->
            <div class="tab-pane fade" id="manage-tabpane" role="tabpanel" tabindex="0">
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold text-dark mb-0">Manage Workspace</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-secondary mb-4">You can add multiple people at once or completely erase this workspace.</p>
                        
                        <button class="btn btn-dark w-100 mb-3 py-3 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addPeopleModal">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user-plus fa-lg me-3"></i> 
                                <span class="fw-bold fs-6">Add People</span>
                            </div>
                        </button>
                        
                        <button class="btn btn-outline-danger w-100 py-3 rounded-3" onclick="deleteCurrentList()">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-trash fa-lg me-3"></i> 
                                <span class="fw-bold fs-6">Delete Workspace</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@push('modals')
<!-- Start Session Modal -->
<div class="modal fade" id="startSessionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white">
                <div>
                    <h5 class="modal-title fw-bold text-dark">Start New Session</h5>
                    <p class="text-secondary small mb-0 mt-1">Create a new attendance record.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-4 pb-2">
                <div class="form-floating mb-4">
                    <input type="text" id="session-title-input" class="form-control form-control-lg border-secondary-subtle" placeholder="Session Title">
                    <label for="session-title-input" class="text-secondary">Session Title (e.g., DBMS Lec 5)</label>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-uppercase text-secondary ls-1 mb-2" style="font-size: 0.75rem;">
                        Recent / Suggestions
                    </label>
                    <div id="suggestion-chips-container" class="d-flex flex-wrap gap-2">
                        <span class="text-muted small fst-italic">Loading history...</span>
                    </div>
                </div>
                <div class="alert alert-light border small text-secondary"> 
                    <i class="fa-solid fa-info-circle me-1"></i> A new session will be created for today's date. 
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-3">
                <button class="btn btn-light fw-medium text-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary px-4 py-2 shadow-sm" onclick="startSession()">
                    <i class="fa-solid fa-play me-2"></i> Start Tracking
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add People Modal -->
<div class="modal fade" id="addPeopleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Bulk Add People</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary small mb-3">Enter names below, one per line.</p>
                <textarea id="people-textarea" class="form-control bg-light" rows="8" 
                    placeholder="Amit Sharma&#10;Rahul Verma&#10;Sneha Gupta"
                    style="font-family: monospace; border: 1px solid #cbd5e1;"></textarea>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary px-4" onclick="submitPeople()">
                    <i class="fa-solid fa-check me-2"></i> Import Names
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function closeMobileSidebar() {
        if (window.innerWidth < 768) {
            const offcanvasEl = document.getElementById('workspaceSidebar');
            if (offcanvasEl) {
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                if (offcanvas) {
                    offcanvas.hide();
                }
            }
        }
    }
</script>
<script src="{{ asset('js/workspace.js') }}"></script>
@endpush
{{-- resources/views/admin/platform/activities/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'System Activity Logs')

@push('styles')
<style>
/* Ensure proper layout with sidebar */
.main-content {
    margin-left: 0;
    padding: 1rem;
}

@media (min-width: 768px) {
    .main-content {
        margin-left: 16.666667%; /* Adjust based on your sidebar width (col-md-2 = 16.67%) */
        padding: 1.5rem;
    }
}

@media (min-width: 992px) {
    .main-content {
        margin-left: 16.666667%; /* col-lg-2 = 16.67% */
    }
}

/* Fix table responsiveness */
.table-responsive {
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Ensure cards don't overflow */
.card {
    margin-bottom: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

/* Stats cards responsive design */
.stats-cards {
    margin-bottom: 1.5rem;
}

.stats-cards .card {
    transition: transform 0.2s ease-in-out;
}

.stats-cards .card:hover {
    transform: translateY(-2px);
}

/* Filter section improvements */
.filter-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1.5rem;
    border: 1px solid #dee2e6;
}

/* Responsive table improvements */
@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .stats-cards .col-md-2 {
        margin-bottom: 0.5rem;
    }
    
    .filter-section .col-md-3,
    .filter-section .col-md-6 {
        margin-bottom: 0.75rem;
    }
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* User avatar improvements */
.user-avatar {
    width: 32px;
    height: 32px;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
}

/* Modal improvements */
.modal-body pre {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Activity Logs</h1>
            <p class="text-muted mb-0">Monitor system activities and user actions</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.activities.security') }}" class="btn btn-outline-warning">
                <i class="bi bi-shield-exclamation me-1"></i>Security Insights
            </a>
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-download me-1"></i>Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="exportLogs('csv')">Export as CSV</a></li>
                <li><a class="dropdown-item" href="#" onclick="exportLogs('json')">Export as JSON</a></li>
            </ul>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-section">
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Search Activities</label>
                <form method="GET" action="{{ route('admin.activities.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Search by action, user, IP..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Quick Filters</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('admin.activities.index') }}" 
                               class="btn btn-outline-secondary {{ !request()->hasAny(['action', 'model_type', 'admin_only']) ? 'active' : '' }}">
                                All Activities
                            </a>
                            <a href="{{ route('admin.activities.index', ['admin_only' => '1']) }}" 
                               class="btn btn-outline-info {{ request('admin_only') === '1' ? 'active' : '' }}">
                                Admin Actions
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('admin.activities.index') }}" class="d-inline">
                            @foreach(request()->except('action') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="action" class="form-select" onchange="this.form.submit()">
                                <option value="">All Actions</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                        {{ format_activity_action($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('admin.activities.index') }}" class="d-inline">
                            @foreach(request()->except('model_type') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="model_type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Models</option>
                                @foreach($modelTypes as $modelType)
                                    <option value="{{ $modelType }}" {{ request('model_type') === $modelType ? 'selected' : '' }}>
                                        {{ class_basename($modelType) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="row">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.activities.index') }}" class="row g-2">
                    @foreach(request()->except(['date_from', 'date_to']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div class="col">
                        <label class="form-label fw-semibold">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-auto d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(isset($stats))
    <div class="stats-cards">
        <div class="row">
            <div class="col-6 col-md-2 mb-2">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h6 class="card-title mb-1 text-muted">Total</h6>
                        <h4 class="text-primary mb-0">{{ number_format($stats['total_activities']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-2">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h6 class="card-title mb-1 text-muted">Admin</h6>
                        <h4 class="text-info mb-0">{{ number_format($stats['admin_activities']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-2">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h6 class="card-title mb-1 text-muted">Users</h6>
                        <h4 class="text-success mb-0">{{ number_format($stats['unique_users']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-2">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h6 class="card-title mb-1 text-muted">IPs</h6>
                        <h4 class="text-warning mb-0">{{ number_format($stats['unique_ips']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-2">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h6 class="card-title mb-1 text-muted">Today</h6>
                        <h4 class="text-secondary mb-0">{{ number_format($stats['today_activities']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Activity Logs Table -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>Activity Logs
                </h5>
                <span class="badge bg-light text-dark">
                    {{ $activities->total() ?? 0 }} total records
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">User</th>
                                <th class="border-0">Action</th>
                                <th class="border-0">Target</th>
                                <th class="border-0">Changes</th>
                                <th class="border-0">IP Address</th>
                                <th class="border-0">Time</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr>
                                <td class="align-middle">
                                    @if($activity->user)
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($activity->user->profile_photo_path)
                                                    <img src="{{ $activity->user->photo_url }}" 
                                                         alt="{{ $activity->user->name }}" 
                                                         class="rounded-circle user-avatar">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center user-avatar">
                                                        {{ $activity->user->initials }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.users.show', $activity->user) }}" 
                                                   class="text-decoration-none fw-medium">
                                                    {{ $activity->user->name }}
                                                </a>
                                                @if($activity->user->isAdmin())
                                                    <span class="badge bg-info ms-1">Admin</span>
                                                @endif
                                                <div class="text-muted small">{{ $activity->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center user-avatar me-3">
                                                <i class="bi bi-gear"></i>
                                            </div>
                                            <div>
                                                <span class="fw-medium text-muted">System</span>
                                                <div class="text-muted small">Automated action</div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-{{ get_action_badge_color($activity->action) }}">
                                        {{ format_activity_action($activity->action) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if($activity->model_type)
                                        <div class="d-flex align-items-center">
                                            <i class="{{ get_model_icon($activity->model_type) }} me-2 text-muted"></i>
                                            <div>
                                                <div class="fw-medium">{{ class_basename($activity->model_type) }}</div>
                                                @if($activity->model_id)
                                                    <small class="text-muted">#{{ $activity->model_id }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">General</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($activity->changes)
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="modal" data-bs-target="#changesModal{{ $activity->id }}">
                                            <i class="bi bi-eye me-1"></i>View Changes
                                        </button>
                                    @else
                                        <span class="text-muted">No changes</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($activity->ip_address)
                                        <code class="small">{{ $activity->ip_address }}</code>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="text-nowrap">
                                        <div>{{ $activity->created_at->diffForHumans() }}</div>
                                        <small class="text-muted">{{ $activity->created_at->format('M j, Y g:i A') }}</small>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('admin.activities.show', $activity) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Changes Modal -->
                            @if($activity->changes)
                            <div class="modal fade" id="changesModal{{ $activity->id }}" tabindex="-1" 
                                 aria-labelledby="changesModalLabel{{ $activity->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="changesModalLabel{{ $activity->id }}">
                                                Activity Changes - {{ format_activity_action($activity->action) }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <strong>Action:</strong> {{ format_activity_action($activity->action) }}<br>
                                                <strong>Target:</strong> {{ class_basename($activity->model_type) ?? 'General' }}<br>
                                                <strong>Time:</strong> {{ $activity->created_at->format('M j, Y g:i:s A') }}
                                            </div>
                                            <hr>
                                            @if(is_array($activity->changes))
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Field</th>
                                                                <th>Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($activity->changes as $key => $value)
                                                            <tr>
                                                                <td class="fw-medium">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                                                <td>
                                                                    @if(is_array($value))
                                                                        <pre class="small mb-0">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <pre class="small">{{ json_encode($activity->changes, JSON_PRETTY_PRINT) }}</pre>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} 
                        of {{ $activities->total() }} results
                    </div>
                    <div>
                        {{ $activities->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-activity display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No activity logs found</h4>
                    <p class="text-muted">Try adjusting your search criteria or check back later.</p>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportLogs(format) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.activities.export") }}';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add format
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);
    
    // Add current filters
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.forEach((value, key) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Auto-refresh functionality (optional)
let autoRefresh = false;
let refreshInterval;

function toggleAutoRefresh() {
    autoRefresh = !autoRefresh;
    if (autoRefresh) {
        refreshInterval = setInterval(() => {
            window.location.reload();
        }, 30000); // Refresh every 30 seconds
    } else {
        clearInterval(refreshInterval);
    }
}
</script>
@endpush
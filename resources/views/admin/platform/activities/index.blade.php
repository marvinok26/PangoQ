{{-- resources/views/admin/platform/activities/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'System Activity Logs')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <form method="GET" action="{{ route('admin.activities.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search activities..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="col-md-8">
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
                                {{ ucwords(str_replace('_', ' ', $action)) }}
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

<div class="card">
    <div class="card-body">
        @if($activities->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Target</th>
                            <th>Changes</th>
                            <th>IP Address</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr>
                            <td>
                                @if($activity->user)
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @if($activity->user->profile_photo_path)
                                                <img src="{{ $activity->user->photo_url }}" alt="{{ $activity->user->name }}" 
                                                     class="rounded-circle" width="30" height="30">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                    {{ $activity->user->initials }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.users.show', $activity->user) }}" class="text-decoration-none">
                                                {{ $activity->user->name }}
                                            </a>
                                            @if($activity->user->isAdmin())
                                                <small class="badge bg-info ms-1">Admin</small>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $this->getActionBadgeColor($activity->action) }}">
                                    {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                                </span>
                            </td>
                            <td>
                                @if($activity->model_type)
                                    <strong>{{ class_basename($activity->model_type) }}</strong>
                                    @if($activity->model_id)
                                        <small class="text-muted d-block">#{{ $activity->model_id }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">General</span>
                                @endif
                            </td>
                            <td>
                                @if($activity->changes)
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#changesModal{{ $activity->id }}">
                                        <i class="bi bi-eye me-1"></i>View Changes
                                    </button>
                                @else
                                    <span class="text-muted">No changes</span>
                                @endif
                            </td>
                            <td>
                                <small><code>{{ $activity->ip_address ?? 'N/A' }}</code></small>
                            </td>
                            <td>
                                {{ $activity->created_at->diffForHumans() }}
                                <br>
                                <small class="text-muted">{{ format_admin_date($activity->created_at) }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Changes Modal -->
                        @if($activity->changes)
                        <div class="modal fade" id="changesModal{{ $activity->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Activity Changes</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Action: {{ ucwords(str_replace('_', ' ', $activity->action)) }}</h6>
                                        <hr>
                                        @if(is_array($activity->changes))
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Field</th>
                                                            <th>Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($activity->changes as $key => $value)
                                                        <tr>
                                                            <td><strong>{{ ucwords(str_replace('_', ' ', $key)) }}</strong></td>
                                                            <td>
                                                                @if(is_array($value))
                                                                    <pre class="small">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
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

            <div class="d-flex justify-content-center">
                {{ $activities->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-activity display-1 text-muted"></i>
                <h4 class="text-muted">No activity logs found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>

@php
    function getActionBadgeColor($action) {
        $colors = [
            'created' => 'success',
            'updated' => 'info',
            'deleted' => 'danger',
            'login' => 'primary',
            'logout' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'flagged' => 'warning',
            'admin_login' => 'info',
            'admin_logout' => 'secondary',
        ];
        
        foreach ($colors as $keyword => $color) {
            if (str_contains($action, $keyword)) {
                return $color;
            }
        }
        
        return 'secondary';
    }
@endphp
@endsection
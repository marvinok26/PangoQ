{{-- resources/views/admin/platform/activities/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Activity Details')
@section('page-title', 'Activity Log Details')

@php
    $breadcrumbs = [
        ['title' => 'Activity Logs', 'url' => route('admin.activities.index')],
        ['title' => 'Activity #' . $activity->id]
    ];
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Activity Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><strong>Activity ID:</strong></td>
                        <td><code>#{{ $activity->id }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>User:</strong></td>
                        <td>
                            @if($activity->user)
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        @if($activity->user->profile_photo_path)
                                            <img src="{{ $activity->user->photo_url }}" alt="{{ $activity->user->name }}" 
                                                 class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                {{ $activity->user->initials }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $activity->user) }}" class="text-decoration-none">
                                            {{ $activity->user->name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $activity->user->email }}</small>
                                        @if($activity->user->isAdmin())
                                            <small class="badge bg-info ms-1">Admin</small>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Action:</strong></td>
                        <td>
                            <span class="badge bg-{{ $this->getActionBadgeColor($activity->action) }} fs-6">
                                {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Target:</strong></td>
                        <td>
                            @if($activity->model_type)
                                <strong>{{ class_basename($activity->model_type) }}</strong>
                                @if($activity->model_id)
                                    <small class="text-muted">#{{ $activity->model_id }}</small>
                                @endif
                            @else
                                <span class="text-muted">General Action</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Date & Time:</strong></td>
                        <td>
                            {{ format_admin_date($activity->created_at) }}
                            <br>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>IP Address:</strong></td>
                        <td><code>{{ $activity->ip_address ?? 'N/A' }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>User Agent:</strong></td>
                        <td>
                            @if($activity->user_agent)
                                <small class="text-muted">{{ Str::limit($activity->user_agent, 100) }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>URL:</strong></td>
                        <td>
                            @if($activity->url)
                                <small><code>{{ $activity->method ?? 'GET' }} {{ $activity->url }}</code></small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Changes Card -->
        @if($activity->changes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Changes Made</h5>
            </div>
            <div class="card-body">
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
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" data-bs-target="#valueModal{{ $loop->index }}">
                                                View Array
                                            </button>
                                            <!-- Modal for array values -->
                                            <div class="modal fade" id="valueModal{{ $loop->index }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ ucwords(str_replace('_', ' ', $key)) }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <pre class="small">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="small">{{ Str::limit($value, 50) }}</span>
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
        </div>
        @endif

        <!-- Original Data Card -->
        @if($activity->original_data)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Original Data</h5>
            </div>
            <div class="card-body">
                @if(is_array($activity->original_data))
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Original Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activity->original_data as $key => $value)
                                <tr>
                                    <td><strong>{{ ucwords(str_replace('_', ' ', $key)) }}</strong></td>
                                    <td>
                                        @if(is_array($value))
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="modal" data-bs-target="#originalModal{{ $loop->index }}">
                                                View Array
                                            </button>
                                            <!-- Modal for array values -->
                                            <div class="modal fade" id="originalModal{{ $loop->index }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Original {{ ucwords(str_replace('_', ' ', $key)) }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <pre class="small">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="small">{{ Str::limit($value, 50) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <pre class="small">{{ json_encode($activity->original_data, JSON_PRETTY_PRINT) }}</pre>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Activities
            </a>
            
            @if($activity->user)
                <a href="{{ route('admin.users.show', $activity->user) }}" class="btn btn-outline-primary">
                    <i class="bi bi-person me-1"></i>View User Profile
                </a>
            @endif
        </div>
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
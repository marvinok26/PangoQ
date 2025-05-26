{{-- resources/views/admin/dashboard/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['total_users']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-2 h-100">
            <div class="card-body text-white">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Trips</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['total_trips']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-map display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-3 h-100">
            <div class="card-body text-white">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Wallets</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['total_wallets']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-wallet2 display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-4 h-100">
            <div class="card-body text-white">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Transactions</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['total_transactions']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-credit-card display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Admin Activities</h5>
            </div>
            <div class="card-body">
                @if($stats['recent_activities']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Target</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_activities'] as $activity)
                                <tr>
                                    <td>{{ $activity->user->name ?? 'System' }}</td>
                                    <td><span class="badge bg-primary">{{ str_replace('_', ' ', $activity->action) }}</span></td>
                                    <td>{{ $activity->model_type ? class_basename($activity->model_type) : 'N/A' }}</td>
                                    <td><small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No recent activities found.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span>Active Users</span>
                        <span class="badge bg-success">{{ number_format($stats['active_users']) }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span>Admin Users</span>
                        <span class="badge bg-info">{{ number_format($stats['admin_users']) }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span>Active Trips</span>
                        <span class="badge bg-primary">{{ number_format($stats['active_trips']) }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span>Flagged Trips</span>
                        <span class="badge bg-warning">{{ number_format($stats['flagged_trips']) }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-2">
                        <span>Featured Trips</span>
                        <span class="badge bg-success">{{ number_format($stats['featured_trips']) }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <span>Flagged Wallets</span>
                        <span class="badge bg-danger">{{ number_format($stats['flagged_wallets']) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-people me-2"></i>Manage Users
                    </a>
                    <a href="{{ route('admin.trips.index') }}" class="btn btn-outline-info">
                        <i class="bi bi-map me-2"></i>Review Trips
                    </a>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-activity me-2"></i>View Activity Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

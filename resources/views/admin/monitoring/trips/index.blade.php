{{-- resources/views/admin/monitoring/trips/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Trips Management')
@section('page-title', 'Trips Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" action="{{ route('admin.trips.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search trips..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="btn-group float-end" role="group">
            <a href="{{ route('admin.trips.index') }}" 
               class="btn btn-outline-secondary {{ !request()->hasAny(['status', 'admin_status', 'is_featured']) ? 'active' : '' }}">
                All Trips
            </a>
            <a href="{{ route('admin.trips.index', ['status' => 'active']) }}" 
               class="btn btn-outline-success {{ request('status') === 'active' ? 'active' : '' }}">
                Active
            </a>
            <a href="{{ route('admin.trips.index', ['admin_status' => 'flagged']) }}" 
               class="btn btn-outline-danger {{ request('admin_status') === 'flagged' ? 'active' : '' }}">
                Flagged
            </a>
            <a href="{{ route('admin.trips.index', ['is_featured' => '1']) }}" 
               class="btn btn-outline-warning {{ request('is_featured') === '1' ? 'active' : '' }}">
                Featured
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($trips->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Trip</th>
                            <th>Creator</th>
                            <th>Destination</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Admin Status</th>
                            <th>Budget</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trips as $trip)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $trip->title }}</strong>
                                    @if($trip->is_featured)
                                        <small class="badge bg-warning ms-1">Featured</small>
                                    @endif
                                    @if($trip->trip_template_id)
                                        <small class="badge bg-info ms-1">Template</small>
                                    @endif
                                </div>
                                <small class="text-muted">{{ Str::limit($trip->description, 50) }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $trip->creator) }}" class="text-decoration-none">
                                    {{ $trip->creator->name }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $trip->creator->email }}</small>
                            </td>
                            <td>{{ $trip->destination }}</td>
                            <td>
                                <small>
                                    {{ format_admin_date($trip->start_date, 'M j') }} - 
                                    {{ format_admin_date($trip->end_date, 'M j, Y') }}
                                </small>
                                <br>
                                <small class="text-muted">{{ $trip->duration ?? $trip->start_date->diffInDays($trip->end_date) + 1 }} days</small>
                            </td>
                            <td>
                                <span class="{{ admin_status_badge($trip->status) }}">
                                    {{ ucfirst($trip->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ admin_status_badge($trip->admin_status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $trip->admin_status)) }}
                                </span>
                            </td>
                            <td>{{ $trip->budget ? format_currency($trip->budget) : 'N/A' }}</td>
                            <td>{{ format_admin_date($trip->created_at, 'M j, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.trips.show', $trip) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" 
                                            data-bs-toggle="modal" data-bs-target="#statusModal{{ $trip->id }}">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.trips.toggle-featured', $trip) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-{{ $trip->is_featured ? 'warning' : 'success' }}" 
                                                title="{{ $trip->is_featured ? 'Remove Featured' : 'Mark Featured' }}">
                                            <i class="bi bi-star{{ $trip->is_featured ? '-fill' : '' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Status Update Modal -->
                        <div class="modal fade" id="statusModal{{ $trip->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Trip Status - {{ $trip->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.trips.update-admin-status', $trip) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="admin_status{{ $trip->id }}" class="form-label">Admin Status</label>
                                                <select name="admin_status" id="admin_status{{ $trip->id }}" class="form-select">
                                                    <option value="approved" {{ $trip->admin_status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="under_review" {{ $trip->admin_status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                                    <option value="flagged" {{ $trip->admin_status === 'flagged' ? 'selected' : '' }}>Flagged</option>
                                                    <option value="restricted" {{ $trip->admin_status === 'restricted' ? 'selected' : '' }}>Restricted</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="admin_notes{{ $trip->id }}" class="form-label">Admin Notes</label>
                                                <textarea name="admin_notes" id="admin_notes{{ $trip->id }}" class="form-control" rows="3">{{ $trip->admin_notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $trips->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-map display-1 text-muted"></i>
                <h4 class="text-muted">No trips found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection
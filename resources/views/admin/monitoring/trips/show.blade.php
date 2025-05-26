@extends('admin.layouts.app')

@section('title', 'Trip Details - ' . $trip->title)
@section('page-title', 'Trip Details')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $trip->title }}</h5>
                <p class="text-muted">{{ $trip->destination }}</p>
                
                <div class="mb-3">
                    <span class="{{ admin_status_badge($trip->status) }}">
                        {{ ucfirst($trip->status) }}
                    </span>
                    <span class="{{ admin_status_badge($trip->admin_status) }} ms-1">
                        {{ ucfirst(str_replace('_', ' ', $trip->admin_status)) }}
                    </span>
                    @if($trip->is_featured)
                        <span class="badge bg-success ms-1">Featured</span>
                    @endif
                </div>

                <table class="table table-sm">
                    <tr>
                        <td><strong>Creator:</strong></td>
                        <td>
                            <a href="{{ route('admin.users.show', $trip->creator) }}">
                                {{ $trip->creator->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Dates:</strong></td>
                        <td>{{ $trip->date_range }}</td>
                    </tr>
                    <tr>
                        <td><strong>Duration:</strong></td>
                        <td>{{ $trip->duration }} days</td>
                    </tr>
                    <tr>
                        <td><strong>Budget:</strong></td>
                        <td>{{ $trip->budget ? format_currency($trip->budget) : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Members:</strong></td>
                        <td>{{ $trip->members->count() }} people</td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ format_admin_date($trip->created_at) }}</td>
                    </tr>
                </table>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="bi bi-gear me-1"></i>Update Status
                    </button>
                    <form method="POST" action="{{ route('admin.trips.toggle-featured', $trip) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $trip->is_featured ? 'outline-warning' : 'success' }}">
                            <i class="bi bi-star{{ $trip->is_featured ? '-fill' : '' }} me-1"></i>
                            {{ $trip->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Trip Description -->
        @if($trip->description)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Description</h5>
            </div>
            <div class="card-body">
                <p>{{ $trip->description }}</p>
            </div>
        </div>
        @endif

        <!-- Itineraries -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Itinerary ({{ $trip->itineraries->count() }} days)</h5>
            </div>
            <div class="card-body">
                @if($trip->itineraries->count() > 0)
                    @foreach($trip->itineraries as $itinerary)
                    <div class="mb-3">
                        <h6>Day {{ $itinerary->day_number }}: {{ $itinerary->title }}</h6>
                        <small class="text-muted">{{ format_admin_date($itinerary->date, 'M j, Y') }}</small>
                        @if($itinerary->activities->count() > 0)
                            <ul class="list-unstyled mt-2">
                                @foreach($itinerary->activities as $activity)
                                <li class="d-flex justify-content-between">
                                    <span>{{ $activity->title }}</span>
                                    <small class="text-muted">{{ $activity->formatted_cost }}</small>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No itinerary created yet.</p>
                @endif
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Changes</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td><span class="badge bg-primary">{{ str_replace('_', ' ', $activity->action) }}</span></td>
                                    <td>{{ $activity->user->name ?? 'System' }}</td>
                                    <td>
                                        @if($activity->changes)
                                            <small class="text-muted">
                                                @foreach($activity->changes as $key => $value)
                                                    {{ ucfirst($key) }}: {{ is_array($value) ? json_encode($value) : $value }}
                                                    @if(!$loop->last), @endif
                                                @endforeach
                                            </small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No activity found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
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
                        <label for="admin_status" class="form-label">Admin Status</label>
                        <select name="admin_status" id="admin_status" class="form-select">
                            <option value="approved" {{ $trip->admin_status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="under_review" {{ $trip->admin_status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="flagged" {{ $trip->admin_status === 'flagged' ? 'selected' : '' }}>Flagged</option>
                            <option value="restricted" {{ $trip->admin_status === 'restricted' ? 'selected' : '' }}>Restricted</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3">{{ $trip->admin_notes }}</textarea>
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
@endsection
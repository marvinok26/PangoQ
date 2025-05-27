{{-- resources/views/admin/destinations/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', $destination->name . ' - Destination Details')
@section('page-title', 'Destination Details')

@section('content')
<!-- Navigation Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        @if(isset($previousDestination) && $previousDestination)
            <a href="{{ route('admin.destinations.show', $previousDestination) }}" class="btn btn-outline-secondary">
                <i class="bi bi-chevron-left"></i> Previous Destination
            </a>
        @else
            <span class="btn btn-outline-secondary disabled">
                <i class="bi bi-chevron-left"></i> Previous Destination
            </span>
        @endif
    </div>
    
    <div>
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-primary">
            <i class="bi bi-list"></i> Back to Destinations
        </a>
    </div>
    
    <div>
        @if(isset($nextDestination) && $nextDestination)
            <a href="{{ route('admin.destinations.show', $nextDestination) }}" class="btn btn-outline-secondary">
                Next Destination <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span class="btn btn-outline-secondary disabled">
                Next Destination <i class="bi bi-chevron-right"></i>
            </span>
        @endif
    </div>
</div>

<div class="row">
    <!-- Destination Overview -->
    <div class="col-lg-4">
        <div class="card">
            <div class="position-relative">
                @if($destination->getRawOriginal('image_url'))
                    <img src="{{ $destination->image_url }}" 
                         alt="{{ $destination->name }}" 
                         class="card-img-top" 
                         style="height: 250px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.alt='Image not available';">
                    
                    <!-- Image Type Badge -->
                    <div class="position-absolute top-0 start-0 m-2">
                        @if(str_starts_with($destination->getRawOriginal('image_url'), 'image'))
                            <span class="badge bg-info">Seeded Image</span>
                        @else
                            <span class="badge bg-success">Uploaded Image</span>
                        @endif
                    </div>
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 250px;">
                        <div class="text-center">
                            <i class="bi bi-geo-alt display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">No Image Available</p>
                        </div>
                    </div>
                @endif
                
                <!-- Template Count Badge -->
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-primary">{{ $destination->tripTemplates->count() }} Template{{ $destination->tripTemplates->count() !== 1 ? 's' : '' }}</span>
                </div>
            </div>
            
            <div class="card-body">
                <h4 class="card-title">{{ $destination->name }}</h4>
                <p class="text-muted mb-3">
                    <i class="bi bi-geo-alt me-1"></i>
                    {{ $destination->city }}, {{ $destination->country }}
                </p>
                
                @if($destination->description)
                    <p class="card-text">{{ $destination->description }}</p>
                @else
                    <p class="text-muted fst-italic">No description available.</p>
                @endif
                
                <div class="row text-center mt-4">
                    <div class="col">
                        <div class="border rounded p-3 {{ $destination->tripTemplates->count() > 0 ? 'bg-light-success' : 'bg-light-warning' }}">
                            <h5 class="mb-1 {{ $destination->tripTemplates->count() > 0 ? 'text-success' : 'text-warning' }}">
                                {{ $destination->tripTemplates->count() }}
                            </h5>
                            <small class="text-muted">Trip Template{{ $destination->tripTemplates->count() !== 1 ? 's' : '' }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Destination
                    </a>
                    <a href="{{ route('admin.trip-templates.create', ['destination' => $destination->id]) }}" class="btn btn-success">
                        <i class="bi bi-plus-lg"></i> Add Trip Template
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Destination Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Destination Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $destination->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Location:</strong></td>
                        <td>{{ $destination->city }}, {{ $destination->country }}</td>
                    </tr>
                    @if($destination->getRawOriginal('image_url'))
                    <tr>
                        <td><strong>Image:</strong></td>
                        <td>
                            <code>{{ basename($destination->getRawOriginal('image_url')) }}</code>
                            <br>
                            @if(str_starts_with($destination->getRawOriginal('image_url'), 'image'))
                                <small class="badge bg-info">Seeded</small>
                            @else
                                <small class="badge bg-success">Uploaded</small>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ format_admin_date($destination->created_at) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ format_admin_date($destination->updated_at) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($destination->tripTemplates->count() > 0)
                                <span class="badge bg-success">Active</span>
                                <small class="text-muted d-block">Has {{ $destination->tripTemplates->count() }} template(s)</small>
                            @else
                                <span class="badge bg-warning">Inactive</span>
                                <small class="text-muted d-block">No templates created</small>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Trip Templates -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Trip Templates 
                    <span class="badge bg-primary">{{ $destination->tripTemplates->count() }}</span>
                </h5>
                <a href="{{ route('admin.trip-templates.create', ['destination' => $destination->id]) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Add Template
                </a>
            </div>
            <div class="card-body">
                @if($destination->tripTemplates->count() > 0)
                    <div class="row">
                        @foreach($destination->tripTemplates as $template)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title text-truncate me-2">{{ $template->title }}</h6>
                                        @if($template->is_featured)
                                            <span class="badge bg-warning text-dark">Featured</span>
                                        @endif
                                    </div>
                                    
                                    @if($template->description)
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($template->description, 80) }}
                                        </p>
                                    @else
                                        <p class="card-text text-muted small fst-italic">
                                            No description available.
                                        </p>
                                    @endif
                                    
                                    <div class="row text-center small mb-3">
                                        <div class="col-4">
                                            <div class="text-muted">Duration</div>
                                            <strong>{{ $template->duration_days }} day{{ $template->duration_days !== 1 ? 's' : '' }}</strong>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted">Price</div>
                                            <strong>${{ number_format($template->base_price, 0) }}</strong>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted">Activities</div>
                                            <strong>{{ $template->activities_count ?? 0 }}</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="badge bg-info">{{ ucfirst($template->difficulty_level) }}</span>
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $template->trip_style)) }}</span>
                                    </div>
                                    
                                    <div class="d-grid gap-1">
                                        <a href="{{ route('admin.trip-templates.show', $template) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.trip-templates.edit', $template) }}" class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.trip-templates.activities.create', $template) }}" class="btn btn-outline-success btn-sm">
                                                <i class="bi bi-plus"></i> Add Activity
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Created {{ $template->created_at->diffForHumans() }}
                                    @if($template->updated_at->gt($template->created_at))
                                        <br>
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Updated {{ $template->updated_at->diffForHumans() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Summary Stats -->
                    @if($destination->tripTemplates->count() > 1)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Template Summary</h6>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="text-muted">Total Templates</div>
                                            <strong>{{ $destination->tripTemplates->count() }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-muted">Featured</div>
                                            <strong>{{ $destination->tripTemplates->where('is_featured', true)->count() }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-muted">Avg. Price</div>
                                            <strong>${{ number_format($destination->tripTemplates->avg('base_price'), 0) }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-muted">Avg. Duration</div>
                                            <strong>{{ number_format($destination->tripTemplates->avg('duration_days'), 1) }} days</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-map display-4 text-muted"></i>
                        <h5 class="text-muted mt-3">No Trip Templates</h5>
                        <p class="text-muted">This destination doesn't have any trip templates yet. Get started by creating the first one!</p>
                        <a href="{{ route('admin.trip-templates.create', ['destination' => $destination->id]) }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Create First Template
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Destinations
            </a>
            <div>
                <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil"></i> Edit Destination
                </a>
                @if($destination->tripTemplates->count() == 0)
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Delete Destination
                </button>
                @else
                <button type="button" class="btn btn-outline-danger" disabled title="Cannot delete destination with existing templates">
                    <i class="bi bi-trash"></i> Cannot Delete
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($destination->tripTemplates->count() == 0)
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Destination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle display-4 text-warning"></i>
                </div>
                <h6 class="text-center">Are you sure you want to delete this destination?</h6>
                <div class="alert alert-warning mt-3">
                    <strong>{{ $destination->name }}</strong><br>
                    <small>{{ $destination->city }}, {{ $destination->country }}</small>
                </div>
                <p class="text-muted text-center">
                    <strong>This action cannot be undone.</strong><br>
                    The destination and its image will be permanently deleted.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i> Cancel
                </button>
                <form method="POST" action="{{ route('admin.destinations.destroy', $destination) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Destination
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
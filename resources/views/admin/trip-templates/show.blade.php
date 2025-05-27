{{-- resources/views/admin/trip-templates/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', $tripTemplate->title . ' - Template Details')
@section('page-title', 'Trip Template Details')

@section('content')
<div class="row">
    <!-- Template Overview -->
    <div class="col-lg-4">
        <div class="card">
            <div class="position-relative">
                @if($tripTemplate->featured_image)
    <img src="{{ $tripTemplate->featured_image_url }}" 
         alt="{{ $tripTemplate->title }}" 
         class="card-img-top" 
         style="height: 250px; object-fit: cover;">
@elseif($tripTemplate->destination->image_url)
    <img src="{{ $tripTemplate->destination->image_url }}" 
         alt="{{ $tripTemplate->destination->name }}" 
         class="card-img-top" 
         style="height: 250px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 250px;">
                        <i class="bi bi-map display-4 text-muted"></i>
                    </div>
                @endif
                
                @if($tripTemplate->is_featured)
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-warning">
                            <i class="bi bi-star-fill"></i> Featured
                        </span>
                    </div>
                @endif
            </div>
            
            <div class="card-body">
                <h4 class="card-title">{{ $tripTemplate->title }}</h4>
                <p class="text-muted mb-3">
                    <i class="bi bi-geo-alt me-1"></i>
                    {{ $tripTemplate->destination->name }}, {{ $tripTemplate->destination->country }}
                </p>
                
                @if($tripTemplate->description)
                    <p class="card-text">{{ $tripTemplate->description }}</p>
                @endif
                
                <!-- Key Stats -->
                <div class="row text-center mt-4">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h6 class="mb-1">{{ $tripTemplate->duration_days }}</h6>
                            <small class="text-muted">Days</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h6 class="mb-1">${{ number_format($tripTemplate->base_price, 0) }}</h6>
                            <small class="text-muted">Base Price</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h6 class="mb-1">{{ $stats['total_activities'] }}</h6>
                            <small class="text-muted">Activities</small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <span class="badge bg-info me-1">{{ ucfirst($tripTemplate->difficulty_level) }}</span>
                    <span class="badge bg-secondary">{{ ucfirst($tripTemplate->trip_style) }}</span>
                </div>
                
                <!-- Highlights -->
                @if(!empty($tripTemplate->highlights_array))
                <div class="mt-4">
                    <h6>Trip Highlights</h6>
                    <ul class="list-unstyled">
                        @foreach($tripTemplate->highlights_array as $highlight)
                        <li class="mb-1">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            {{ $highlight }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('admin.trip-templates.edit', $tripTemplate) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Template
                    </a>
                    <a href="{{ route('admin.trip-templates.activities.create', $tripTemplate) }}" class="btn btn-success">
                        <i class="bi bi-plus-lg"></i> Add Activity
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            More Actions
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li>
                                <form method="POST" action="{{ route('admin.trip-templates.duplicate', $tripTemplate) }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-files"></i> Duplicate Template
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('admin.trip-templates.toggle-featured', $tripTemplate) }}" class="d-inline w-100">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-star{{ $tripTemplate->is_featured ? '-fill' : '' }}"></i>
                                        {{ $tripTemplate->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Template Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Template Statistics</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Total Activities:</td>
                        <td><strong>{{ $stats['total_activities'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Optional Activities:</td>
                        <td><strong>{{ $stats['optional_activities'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Highlight Activities:</td>
                        <td><strong>{{ $stats['highlight_activities'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Total Activity Cost:</td>
                        <td><strong>${{ number_format($stats['total_activity_cost'], 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Optional Cost:</td>
                        <td><strong>${{ number_format($stats['optional_activity_cost'], 2) }}</strong></td>
                    </tr>
                    <tr class="table-active">
                        <td><strong>Total Package:</strong></td>
                        <td><strong>${{ number_format($tripTemplate->base_price + $stats['total_activity_cost'] - $stats['optional_activity_cost'], 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Template Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Template Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Created:</td>
                        <td>{{ format_admin_date($tripTemplate->created_at) }}</td>
                    </tr>
                    <tr>
                        <td>Updated:</td>
                        <td>{{ format_admin_date($tripTemplate->updated_at) }}</td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td>
                            @if($tripTemplate->is_featured)
                                <span class="badge bg-warning">Featured</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Daily Itinerary -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daily Itinerary ({{ $tripTemplate->duration_days }} Days)</h5>
                <a href="{{ route('admin.trip-templates.activities.create', $tripTemplate) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Add Activity
                </a>
            </div>
            <div class="card-body">
                @if($stats['total_activities'] > 0)
                    @for($day = 1; $day <= $tripTemplate->duration_days; $day++)
                        <div class="border rounded mb-4 p-3" id="day-{{ $day }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <span class="badge bg-primary me-2">{{ $day }}</span>
                                    Day {{ $day }}
                                    @if(isset($activitiesByDay[$day]))
                                        <small class="text-muted">({{ $activitiesByDay[$day]->count() }} activities)</small>
                                    @endif
                                </h6>
                                <a href="{{ route('admin.trip-templates.activities.create', ['tripTemplate' => $tripTemplate, 'day' => $day]) }}" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-plus"></i> Add Activity
                                </a>
                            </div>
                            
                            @if(isset($activitiesByDay[$day]) && $activitiesByDay[$day]->count() > 0)
                                <div class="row">
                                    @foreach($activitiesByDay[$day] as $activity)
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ $activity->is_optional ? 'border-warning' : 'border-light' }}">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">{{ $activity->title }}</h6>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.trip-templates.activities.edit', [$tripTemplate, $activity]) }}" 
                                                           class="btn btn-outline-warning btn-sm">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteActivityModal{{ $activity->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <p class="card-text small text-muted mb-2">{{ $activity->description }}</p>
                                                
                                                <div class="small">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ date('g:i A', strtotime($activity->start_time)) }} - 
                                                            {{ date('g:i A', strtotime($activity->end_time)) }}
                                                        </div>
                                                        <div class="col-6">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            {{ $activity->location }}
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-6">
                                                            <i class="bi bi-tag me-1"></i>
                                                            {{ ucfirst($activity->category) }}
                                                        </div>
                                                        <div class="col-6">
                                                            @if($activity->cost > 0)
                                                                <i class="bi bi-currency-dollar me-1"></i>
                                                                ${{ number_format($activity->cost, 2) }}
                                                            @else
                                                                <span class="text-success">Included</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-2">
                                                    @if($activity->is_optional)
                                                        <span class="badge bg-warning">Optional</span>
                                                    @endif
                                                    @if($activity->is_highlight)
                                                        <span class="badge bg-success">Highlight</span>
                                                    @endif
                                                    <span class="badge bg-info">{{ ucfirst($activity->time_of_day) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Activity Modal -->
                                    <div class="modal fade" id="deleteActivityModal{{ $activity->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Activity</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $activity->title }}</strong>?</p>
                                                    <p class="text-muted small">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="{{ route('admin.trip-templates.activities.destroy', [$tripTemplate, $activity]) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete Activity</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3 text-muted">
                                    <i class="bi bi-calendar-x display-6"></i>
                                    <p class="mb-0">No activities scheduled for this day</p>
                                    <a href="{{ route('admin.trip-templates.activities.create', ['tripTemplate' => $tripTemplate, 'day' => $day]) }}" 
                                       class="btn btn-sm btn-outline-primary mt-2">
                                        Add First Activity
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endfor
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">No Activities Added</h5>
                        <p class="text-muted">Start building your itinerary by adding activities for each day.</p>
                        <a href="{{ route('admin.trip-templates.activities.create', $tripTemplate) }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add First Activity
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
            <a href="{{ route('admin.trip-templates.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Templates
            </a>
            <div>
                <a href="{{ route('admin.trip-templates.edit', $tripTemplate) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil"></i> Edit Template
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTemplateModal">
                    <i class="bi bi-trash"></i> Delete Template
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Template Modal -->
<div class="modal fade" id="deleteTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Trip Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $tripTemplate->title }}</strong>?</p>
                <p class="text-warning small">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    This will also delete all {{ $stats['total_activities'] }} associated activities. This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.trip-templates.destroy', $tripTemplate) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Template</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
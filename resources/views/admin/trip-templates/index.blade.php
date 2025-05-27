{{-- resources/views/admin/trip-templates/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Trip Templates Management')
@section('page-title', 'Trip Templates Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('admin.trip-templates.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search templates..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="destination_id" class="form-select">
                    <option value="">All Destinations</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ request('destination_id') == $destination->id ? 'selected' : '' }}>
                            {{ $destination->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="trip_style" class="form-select">
                    <option value="">All Styles</option>
                    @foreach($tripStyles as $style)
                        <option value="{{ $style }}" {{ request('trip_style') === $style ? 'selected' : '' }}>
                            {{ ucfirst($style) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="difficulty_level" class="form-select">
                    <option value="">All Levels</option>
                    @foreach($difficultyLevels as $level)
                        <option value="{{ $level }}" {{ request('difficulty_level') === $level ? 'selected' : '' }}>
                            {{ ucfirst($level) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <div class="btn-group">
            <a href="{{ route('admin.trip-templates.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Template
            </a>
            <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.destinations.index') }}">
                    <i class="bi bi-geo-alt"></i> Manage Destinations
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.trip-templates.index', ['is_featured' => '1']) }}">
                    <i class="bi bi-star"></i> Featured Templates
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Filter Tags -->
@if(request()->hasAny(['search', 'destination_id', 'trip_style', 'difficulty_level', 'is_featured']))
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted">Active filters:</span>
            
            @if(request('search'))
                <span class="badge bg-primary">
                    Search: "{{ request('search') }}"
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            
            @if(request('destination_id'))
                <span class="badge bg-info">
                    Destination: {{ $destinations->find(request('destination_id'))->name ?? 'Unknown' }}
                    <a href="{{ request()->fullUrlWithQuery(['destination_id' => null]) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            
            @if(request('trip_style'))
                <span class="badge bg-secondary">
                    Style: {{ ucfirst(request('trip_style')) }}
                    <a href="{{ request()->fullUrlWithQuery(['trip_style' => null]) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            
            @if(request('difficulty_level'))
                <span class="badge bg-warning">
                    Level: {{ ucfirst(request('difficulty_level')) }}
                    <a href="{{ request()->fullUrlWithQuery(['difficulty_level' => null]) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            
            @if(request('is_featured'))
                <span class="badge bg-success">
                    Featured Only
                    <a href="{{ request()->fullUrlWithQuery(['is_featured' => null]) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            
            <a href="{{ route('admin.trip-templates.index') }}" class="btn btn-sm btn-outline-secondary">
                Clear All
            </a>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        @if($tripTemplates->count() > 0)
            <div class="row">
                @foreach($tripTemplates as $template)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            @if($template->featured_image)
    <img src="{{ $template->featured_image_url }}" 
         alt="{{ $template->title }}" 
         class="card-img-top" 
         style="height: 200px; object-fit: cover;">
@elseif($template->destination->image_url)
    <img src="{{ $template->destination->image_url }}" 
         alt="{{ $template->destination->name }}" 
         class="card-img-top" 
         style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="bi bi-map display-4 text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 start-0 m-2">
                                @if($template->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="bi bi-star-fill"></i> Featured
                                    </span>
                                @endif
                            </div>
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary">{{ $template->activities_count }} Activities</span>
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $template->title }}</h6>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $template->destination->name }}, {{ $template->destination->country }}
                            </p>
                            
                            @if($template->description)
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($template->description, 80) }}
                                </p>
                            @endif
                            
                            <div class="row text-center small mb-3">
                                <div class="col-4">
                                    <div class="text-muted">Duration</div>
                                    <strong>{{ $template->duration_days }}d</strong>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted">Price</div>
                                    <strong>${{ number_format($template->base_price, 0) }}</strong>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted">Level</div>
                                    <strong>{{ ucfirst($template->difficulty_level) }}</strong>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="badge bg-info">{{ ucfirst($template->trip_style) }}</span>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-grid gap-1">
                                    <a href="{{ route('admin.trip-templates.show', $template) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.trip-templates.edit', $template) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                            <span class="visually-hidden">More actions</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('admin.trip-templates.duplicate', $template) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-files"></i> Duplicate
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.trip-templates.toggle-featured', $template) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-star{{ $template->is_featured ? '-fill' : '' }}"></i>
                                                        {{ $template->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="{{ route('admin.trip-templates.activities.create', $template) }}" class="dropdown-item">
                                                    <i class="bi bi-plus"></i> Add Activity
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            Created {{ $template->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $tripTemplates->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-map display-1 text-muted"></i>
                <h4 class="text-muted mt-3">No trip templates found</h4>
                @if(request()->hasAny(['search', 'destination_id', 'trip_style', 'difficulty_level', 'is_featured']))
                    <p class="text-muted">Try adjusting your search criteria.</p>
                    <a href="{{ route('admin.trip-templates.index') }}" class="btn btn-outline-primary">
                        Clear Filters
                    </a>
                @else
                    <p class="text-muted">Start by creating your first trip template.</p>
                    <a href="{{ route('admin.trip-templates.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Create Trip Template
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
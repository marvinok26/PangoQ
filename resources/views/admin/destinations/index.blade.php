{{-- resources/views/admin/destinations/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Destinations Management')
@section('page-title', 'Destinations Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('admin.destinations.index') }}" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search destinations..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="country" class="form-select">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Destination
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($destinations->count() > 0)
            <div class="row">
                @foreach($destinations as $destination)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            @if($destination->image_url)
                                <img src="{{ $destination->image_url }}"
                                     alt="{{ $destination->name }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="bi bi-geo-alt display-4 text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary">{{ $destination->trip_templates_count }} Templates</span>
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $destination->name }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $destination->city }}, {{ $destination->country }}
                            </p>
                            
                            @if($destination->description)
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($destination->description, 100) }}
                                </p>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('admin.destinations.show', $destination) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" 
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    @if($destination->trip_templates_count == 0)
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $destination->id }}">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled 
                                            title="Cannot delete destination with existing templates">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                @if($destination->trip_templates_count == 0)
                <div class="modal fade" id="deleteModal{{ $destination->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Destination</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>{{ $destination->name }}</strong>?</p>
                                <p class="text-muted small">This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form method="POST" action="{{ route('admin.destinations.destroy', $destination) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Destination</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $destinations->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-geo-alt display-1 text-muted"></i>
                <h4 class="text-muted mt-3">No destinations found</h4>
                @if(request()->hasAny(['search', 'country']))
                    <p class="text-muted">Try adjusting your search criteria.</p>
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-primary">
                        Clear Filters
                    </a>
                @else
                    <p class="text-muted">Start by adding your first destination.</p>
                    <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New Destination
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
{{-- resources/views/admin/user-management/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search users..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="btn-group float-end" role="group">
            <a href="{{ route('admin.users.index') }}" 
               class="btn btn-outline-secondary {{ !request()->hasAny(['status', 'is_admin']) ? 'active' : '' }}">
                All Users
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'active']) }}" 
               class="btn btn-outline-success {{ request('status') === 'active' ? 'active' : '' }}">
                Active
            </a>
            <a href="{{ route('admin.users.index', ['is_admin' => '1']) }}" 
               class="btn btn-outline-info {{ request('is_admin') === '1' ? 'active' : '' }}">
                Admins
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Account Number</th>
                            <th>Status</th>
                            <th>Admin</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($user->profile_photo_path)
                                            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" 
                                                 class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                {{ $user->initials }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->isAdmin())
                                            <small class="badge bg-info ms-1">Admin</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td><code>{{ $user->account_number }}</code></td>
                            <td>
                                <span class="{{ admin_status_badge($user->account_status) }}">
                                    {{ ucfirst($user->account_status) }}
                                </span>
                            </td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge bg-info">{{ ucfirst($user->admin_role) }}</span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </td>
                            <td>{{ format_admin_date($user->created_at, 'M j, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-outline-warning" 
                                            data-bs-toggle="modal" data-bs-target="#statusModal{{ $user->id }}">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Status Update Modal -->
                        @if($user->id !== auth()->id())
                        <div class="modal fade" id="statusModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update User Status - {{ $user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="account_status{{ $user->id }}" class="form-label">Account Status</label>
                                                <select name="account_status" id="account_status{{ $user->id }}" class="form-select">
                                                    <option value="active" {{ $user->account_status === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $user->account_status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    <option value="suspended" {{ $user->account_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                </select>
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
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="text-muted">No users found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection
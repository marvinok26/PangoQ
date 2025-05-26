@extends('admin.layouts.app')

@section('title', 'Wallets Management')
@section('page-title', 'Savings Wallets')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" action="{{ route('admin.wallets.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search wallets..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="btn-group float-end" role="group">
            <a href="{{ route('admin.wallets.index') }}" 
               class="btn btn-outline-secondary {{ !request()->hasAny(['flagged', 'currency']) ? 'active' : '' }}">
                All Wallets
            </a>
            <a href="{{ route('admin.wallets.index', ['flagged' => '1']) }}" 
               class="btn btn-outline-danger {{ request('flagged') === '1' ? 'active' : '' }}">
                Flagged
            </a>
            <a href="{{ route('admin.wallets.index', ['currency' => 'USD']) }}" 
               class="btn btn-outline-info {{ request('currency') === 'USD' ? 'active' : '' }}">
                USD
            </a>
            <a href="{{ route('admin.wallets.index', ['currency' => 'KES']) }}" 
               class="btn btn-outline-success {{ request('currency') === 'KES' ? 'active' : '' }}">
                KES
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($wallets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Wallet</th>
                            <th>Owner</th>
                            <th>Trip</th>
                            <th>Goal</th>
                            <th>Current</th>
                            <th>Progress</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wallets as $wallet)
                        <tr>
                            <td>
                                <strong>{{ is_array($wallet->name) ? ($wallet->name['en'] ?? 'Wallet') : $wallet->name }}</strong>
                                @if($wallet->admin_flagged)
                                    <small class="badge bg-danger ms-1">Flagged</small>
                                @endif
                            </td>
                            <td>
                                @if($wallet->user)
                                    <a href="{{ route('admin.users.show', $wallet->user) }}" class="text-decoration-none">
                                        {{ $wallet->user->name }}
                                    </a>
                                @else
                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>No User
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($wallet->trip)
                                    <a href="{{ route('admin.trips.show', $wallet->trip) }}" class="text-decoration-none">
                                        {{ Str::limit($wallet->trip->title, 20) }}
                                    </a>
                                @else
                                    <span class="text-muted">No trip</span>
                                @endif
                            </td>
                            <td>{{ format_currency($wallet->target_amount, $wallet->currency) }}</td>
                            <td>{{ format_currency($wallet->current_amount, $wallet->currency) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 100px; height: 20px;">
                                        <div class="progress-bar bg-{{ $wallet->progress_percentage >= 100 ? 'success' : ($wallet->progress_percentage >= 50 ? 'info' : 'warning') }}" 
                                             role="progressbar" style="width: {{ min($wallet->progress_percentage, 100) }}%">
                                        </div>
                                    </div>
                                    <small>{{ $wallet->progress_percentage }}%</small>
                                </div>
                            </td>
                            <td>{{ $wallet->currency }}</td>
                            <td>
                                @if($wallet->admin_flagged)
                                    <span class="badge bg-danger">Flagged</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ format_admin_date($wallet->created_at, 'M j, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($wallet->user)
                                        <a href="{{ route('admin.wallets.show', $wallet) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-{{ $wallet->admin_flagged ? 'success' : 'warning' }}" 
                                                data-bs-toggle="modal" data-bs-target="#flagModal{{ $wallet->id }}">
                                            <i class="bi bi-flag{{ $wallet->admin_flagged ? '-fill' : '' }}"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">No actions available</span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Flag Toggle Modal - Only show if wallet has user -->
                        @if($wallet->user)
                        <div class="modal fade" id="flagModal{{ $wallet->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            {{ $wallet->admin_flagged ? 'Clear Flag' : 'Flag Wallet' }} - 
                                            {{ is_array($wallet->name) ? ($wallet->name['en'] ?? 'Wallet') : $wallet->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.wallets.toggle-flag', $wallet) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            @if($wallet->admin_flagged)
                                                <div class="alert alert-warning">
                                                    <h6><i class="bi bi-exclamation-triangle me-2"></i>This wallet is currently flagged</h6>
                                                    <p class="mb-0">Are you sure you want to clear the flag?</p>
                                                </div>
                                                @if($wallet->admin_notes)
                                                    <div class="alert alert-info">
                                                        <strong><i class="bi bi-info-circle me-2"></i>Current Flag Reason:</strong><br>
                                                        {{ $wallet->admin_notes }}
                                                    </div>
                                                @endif
                                            @else
                                                <div class="alert alert-danger">
                                                    <h6><i class="bi bi-flag me-2"></i>Flag this wallet</h6>
                                                    <p class="mb-0">This will mark the wallet for review.</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="reason{{ $wallet->id }}" class="form-label">Reason for Flagging</label>
                                                    <textarea name="reason" id="reason{{ $wallet->id }}" class="form-control" rows="3" required 
                                                              placeholder="Explain why you're flagging this wallet..."></textarea>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-{{ $wallet->admin_flagged ? 'success' : 'danger' }}">
                                                {{ $wallet->admin_flagged ? 'Clear Flag' : 'Flag Wallet' }}
                                            </button>
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
                {{ $wallets->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-wallet2 display-1 text-muted"></i>
                <h4 class="text-muted">No wallets found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection
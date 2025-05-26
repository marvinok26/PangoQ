{{-- resources/views/admin/financial/wallets/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Wallet Details')
@section('page-title', 'Savings Wallet Details')

@php
    $breadcrumbs = [
        ['title' => 'Wallets', 'url' => route('admin.wallets.index')],
        ['title' => is_array($wallet->name) ? ($wallet->name['en'] ?? 'Wallet') : $wallet->name]
    ];
@endphp

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ is_array($wallet->name) ? ($wallet->name['en'] ?? 'Wallet') : $wallet->name }}</h5>
                
                <div class="mb-3">
                    @if($wallet->admin_flagged)
                        <span class="badge bg-danger">Flagged</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </div>

                <table class="table table-sm">
                    <tr>
                        <td><strong>Owner:</strong></td>
                        <td>
                            @if($wallet->user)
                                <a href="{{ route('admin.users.show', $wallet->user) }}">
                                    {{ $wallet->user->name }}
                                </a>
                            @else
                                <span class="text-danger">No User</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Trip:</strong></td>
                        <td>
                            @if($wallet->trip)
                                <a href="{{ route('admin.trips.show', $wallet->trip) }}">
                                    {{ $wallet->trip->title }}
                                </a>
                            @else
                                <span class="text-muted">No trip</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Target Amount:</strong></td>
                        <td>{{ format_currency($wallet->target_amount, $wallet->currency) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Current Amount:</strong></td>
                        <td>{{ format_currency($wallet->current_amount, $wallet->currency) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Progress:</strong></td>
                        <td>
                            <div class="progress mb-1" style="height: 20px;">
                                <div class="progress-bar bg-{{ $wallet->progress_percentage >= 100 ? 'success' : ($wallet->progress_percentage >= 50 ? 'info' : 'warning') }}" 
                                     role="progressbar" style="width: {{ min($wallet->progress_percentage, 100) }}%">
                                    {{ $wallet->progress_percentage }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Currency:</strong></td>
                        <td>{{ $wallet->currency }}</td>
                    </tr>
                    <tr>
                        <td><strong>Target Date:</strong></td>
                        <td>{{ $wallet->target_date ? format_admin_date($wallet->target_date, 'M j, Y') : 'Not set' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Frequency:</strong></td>
                        <td>{{ ucfirst($wallet->contribution_frequency) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ format_admin_date($wallet->created_at) }}</td>
                    </tr>
                </table>

                @if($wallet->admin_notes)
                <div class="alert alert-warning">
                    <strong>Admin Notes:</strong><br>
                    {{ $wallet->admin_notes }}
                </div>
                @endif

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-{{ $wallet->admin_flagged ? 'success' : 'warning' }}" 
                            data-bs-toggle="modal" data-bs-target="#flagModal">
                        <i class="bi bi-flag{{ $wallet->admin_flagged ? '-fill' : '' }} me-1"></i>
                        {{ $wallet->admin_flagged ? 'Clear Flag' : 'Flag Wallet' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Recent Transactions -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Transactions</h5>
                <small class="text-muted">{{ $wallet->transactions->count() }} total transactions</small>
            </div>
            <div class="card-body">
                @if($wallet->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wallet->transactions->sortByDesc('created_at')->take(10) as $transaction)
                                <tr>
                                    <td><code>{{ $transaction->transaction_reference ?? 'TXN-' . $transaction->id }}</code></td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'deposit' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>{{ format_currency($transaction->amount, $wallet->currency) }}</td>
                                    <td>
                                        <span class="{{ admin_status_badge($transaction->status) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->payment_method ? ucfirst(str_replace('_', ' ', $transaction->payment_method)) : 'N/A' }}</td>
                                    <td>{{ format_admin_date($transaction->created_at, 'M j, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($wallet->transactions->count() > 10)
                        <div class="text-center">
                            <a href="{{ route('admin.transactions.index', ['wallet_id' => $wallet->id]) }}" class="btn btn-outline-primary">
                                View All Transactions
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-credit-card text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No transactions yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Activity History</h5>
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                    <div class="timeline">
                        @foreach($activities as $activity)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px; font-size: 0.75rem;">
                                        @if($activity->user)
                                            {{ $activity->user->initials }}
                                        @else
                                            <i class="bi bi-gear"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ ucwords(str_replace('_', ' ', $activity->action)) }}</strong>
                                            @if($activity->user)
                                                by {{ $activity->user->name }}
                                            @else
                                                by System
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($activity->changes)
                                        <small class="text-muted">
                                            @if(is_array($activity->changes))
                                                Changes: {{ implode(', ', array_keys($activity->changes)) }}
                                            @else
                                                Additional details available
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-activity text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No activity history</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Flag/Unflag Modal -->
<div class="modal fade" id="flagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $wallet->admin_flagged ? 'Clear Wallet Flag' : 'Flag Wallet for Review' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.wallets.toggle-flag', $wallet) }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    @if(!$wallet->admin_flagged)
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for flagging</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" 
                                      placeholder="Enter reason for flagging this wallet..."></textarea>
                        </div>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            This will flag the wallet for admin review. The user will be notified.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            This will remove the flag from this wallet and allow normal operations.
                        </div>
                        @if($wallet->admin_notes)
                            <div class="mb-3">
                                <strong>Current flag reason:</strong>
                                <p class="text-muted">{{ $wallet->admin_notes }}</p>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-{{ $wallet->admin_flagged ? 'success' : 'warning' }}">
                        {{ $wallet->admin_flagged ? 'Clear Flag' : 'Flag Wallet' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.wallets.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Wallets
            </a>
            
            <div class="btn-group">
                @if($wallet->user)
                    <a href="{{ route('admin.users.show', $wallet->user) }}" class="btn btn-outline-primary">
                        <i class="bi bi-person me-1"></i>View User
                    </a>
                @endif
                @if($wallet->trip)
                    <a href="{{ route('admin.trips.show', $wallet->trip) }}" class="btn btn-outline-info">
                        <i class="bi bi-map me-1"></i>View Trip
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
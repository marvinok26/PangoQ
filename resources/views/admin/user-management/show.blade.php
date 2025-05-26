{{-- resources/views/admin/user-management/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user->name)
@section('page-title', 'User Details')

@php
    $breadcrumbs = [
        ['title' => 'Users', 'url' => route('admin.users.index')],
        ['title' => $user->name]
    ];
@endphp

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                @if($user->profile_photo_path)
                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" 
                         class="rounded-circle mb-3" width="100" height="100">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 100px; height: 100px; font-size: 2rem;">
                        {{ $user->initials }}
                    </div>
                @endif
                
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="mb-3">
                    <span class="{{ admin_status_badge($user->account_status) }}">
                        {{ ucfirst($user->account_status) }}
                    </span>
                    @if($user->isAdmin())
                        <span class="badge bg-info ms-1">{{ ucfirst($user->admin_role) }}</span>
                    @endif
                </div>

                @if($user->id !== auth()->id())
                <button type="button" class="btn btn-warning" 
                        data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i class="bi bi-gear me-1"></i>Update Status
                </button>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Account Number:</strong></td>
                        <td><code>{{ $user->account_number }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $user->phone_number ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth:</strong></td>
                        <td>{{ $user->date_of_birth ? format_admin_date($user->date_of_birth, 'M j, Y') : 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gender:</strong></td>
                        <td>{{ $user->gender ? ucfirst($user->gender) : 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nationality:</strong></td>
                        <td>{{ $user->nationality ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Currency:</strong></td>
                        <td>{{ $user->currency }}</td>
                    </tr>
                    <tr>
                        <td><strong>Account Type:</strong></td>
                        <td>{{ ucfirst($user->account_type) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method:</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $user->preferred_payment_method)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Daily Limit:</strong></td>
                        <td>{{ format_currency($user->daily_transaction_limit ?? 0, $user->currency) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Joined:</strong></td>
                        <td>{{ format_admin_date($user->created_at) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Last Activity:</strong></td>
                        <td>{{ format_admin_date($user->updated_at) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email Verified:</strong></td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Verified</span>
                                <small class="text-muted d-block">{{ format_admin_date($user->email_verified_at) }}</small>
                            @else
                                <span class="badge bg-warning">Not Verified</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($user->isAdmin())
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Admin Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Admin Role:</strong></td>
                        <td><span class="badge bg-info">{{ ucfirst($user->admin_role) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Admin Since:</strong></td>
                        <td>{{ format_admin_date($user->admin_since) }}</td>
                    </tr>
                    @if($user->admin_notes)
                    <tr>
                        <td><strong>Admin Notes:</strong></td>
                        <td>{{ $user->admin_notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-8">
        <!-- User Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center stat-card">
                    <div class="card-body text-white">
                        <h5 class="card-title">{{ $user->createdTrips->count() }}</h5>
                        <p class="card-text">Trips Created</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center stat-card-2">
                    <div class="card-body text-white">
                        <h5 class="card-title">{{ $user->savingsWallets->count() }}</h5>
                        <p class="card-text">Savings Wallets</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center stat-card-3">
                    <div class="card-body text-white">
                        <h5 class="card-title">{{ format_currency($user->total_savings ?? 0, $user->currency) }}</h5>
                        <p class="card-text">Total Savings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center stat-card-4">
                    <div class="card-body text-white">
                        <h5 class="card-title">{{ $user->walletTransactions->count() }}</h5>
                        <p class="card-text">Transactions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Trips -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Trips</h5>
                @if($user->createdTrips->count() > 5)
                    <small class="text-muted">Showing 5 of {{ $user->createdTrips->count() }} trips</small>
                @endif
            </div>
            <div class="card-body">
                @if($user->createdTrips->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Trip</th>
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
                                @foreach($user->createdTrips->take(5) as $trip)
                                <tr>
                                    <td>
                                        <strong>{{ $trip->title }}</strong>
                                        @if($trip->is_featured)
                                            <small class="badge bg-success ms-1">Featured</small>
                                        @endif
                                    </td>
                                    <td>{{ $trip->destination }}</td>
                                    <td>
                                        <small>
                                            {{ format_admin_date($trip->start_date, 'M j') }} - 
                                            {{ format_admin_date($trip->end_date, 'M j, Y') }}
                                        </small>
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
                                    <td>{{ $trip->budget ? format_currency($trip->budget, $user->currency) : 'N/A' }}</td>
                                    <td>{{ format_admin_date($trip->created_at, 'M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.trips.show', $trip) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-map text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No trips created yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Savings Wallets -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Savings Wallets</h5>
                @if($user->savingsWallets->count() > 3)
                    <small class="text-muted">Showing 3 of {{ $user->savingsWallets->count() }} wallets</small>
                @endif
            </div>
            <div class="card-body">
                @if($user->savingsWallets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Wallet</th>
                                    <th>Goal</th>
                                    <th>Current</th>
                                    <th>Progress</th>
                                    <th>Currency</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->savingsWallets->take(3) as $wallet)
                                <tr>
                                    <td>
                                        <strong>{{ $wallet->name['en'] ?? $wallet->name }}</strong>
                                        @if($wallet->admin_flagged)
                                            <small class="badge bg-danger ms-1">Flagged</small>
                                        @endif
                                    </td>
                                    <td>{{ format_currency($wallet->target_amount, $wallet->currency) }}</td>
                                    <td>{{ format_currency($wallet->current_amount, $wallet->currency) }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $wallet->progress_percentage }}%">
                                                {{ $wallet->progress_percentage }}%
                                            </div>
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
                                    <td>
                                        <a href="{{ route('admin.wallets.show', $wallet) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-wallet2"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-wallet2 text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No savings wallets created yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Transactions</h5>
                @if($user->walletTransactions->count() > 5)
                    <small class="text-muted">Showing 5 of {{ $user->walletTransactions->count() }} transactions</small>
                @endif
            </div>
            <div class="card-body">
                @if($user->walletTransactions->count() > 0)
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
                                @foreach($user->walletTransactions->take(5) as $transaction)
                                <tr>
                                    <td><code>{{ $transaction->transaction_reference ?? 'N/A' }}</code></td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'deposit' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>{{ format_currency($transaction->amount, $transaction->wallet->currency ?? $user->currency) }}</td>
                                    <td>
                                        <span class="{{ admin_status_badge($transaction->status) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->payment_method ?? 'N/A' }}</td>
                                    <td>{{ format_admin_date($transaction->created_at, 'M j, Y g:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-credit-card text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No transactions found.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Activity</h5>
                @if($activities->count() > 10)
                    <small class="text-muted">Showing 10 recent activities</small>
                @endif
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Target</th>
                                    <th>Details</th>
                                    <th>IP Address</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ str_replace('_', ' ', ucwords($activity->action)) }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->model_type ? class_basename($activity->model_type) : 'General' }}</td>
                                    <td>
                                        @if($activity->changes)
                                            <small class="text-muted">
                                                @foreach($activity->changes as $key => $value)
                                                    {{ ucfirst($key) }}: {{ is_array($value) ? json_encode($value) : $value }}
                                                    @if(!$loop->last), @endif
                                                @endforeach
                                            </small>
                                        @else
                                            <small class="text-muted">No changes recorded</small>
                                        @endif
                                    </td>
                                    <td><small><code>{{ $activity->ip_address ?? 'N/A' }}</code></small></td>
                                    <td><small>{{ $activity->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-activity text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No recent activity found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
@if($user->id !== auth()->id())
<div class="modal fade" id="statusModal" tabindex="-1">
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
                        <label for="account_status" class="form-label">Account Status</label>
                        <select name="account_status" id="account_status" class="form-select">
                            <option value="active" {{ $user->account_status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $user->account_status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ $user->account_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        <div class="form-text">
                            <strong>Active:</strong> User can use all features normally<br>
                            <strong>Inactive:</strong> User account is disabled but not suspended<br>
                            <strong>Suspended:</strong> User account is suspended due to violations
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Change (Optional)</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" 
                                  placeholder="Explain why you're changing this user's status..."></textarea>
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
@endsection
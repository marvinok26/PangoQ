{{-- resources/views/admin/financial/transactions/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Transactions Management')
@section('page-title', 'Wallet Transactions')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search transactions..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="btn-group float-end" role="group">
            <a href="{{ route('admin.transactions.index') }}" 
               class="btn btn-outline-secondary {{ !request()->hasAny(['type', 'status']) ? 'active' : '' }}">
                All Transactions
            </a>
            <a href="{{ route('admin.transactions.index', ['type' => 'deposit']) }}" 
               class="btn btn-outline-success {{ request('type') === 'deposit' ? 'active' : '' }}">
                Deposits
            </a>
            <a href="{{ route('admin.transactions.index', ['type' => 'withdrawal']) }}" 
               class="btn btn-outline-warning {{ request('type') === 'withdrawal' ? 'active' : '' }}">
                Withdrawals
            </a>
            <a href="{{ route('admin.transactions.index', ['status' => 'failed']) }}" 
               class="btn btn-outline-danger {{ request('status') === 'failed' ? 'active' : '' }}">
                Failed
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>User</th>
                            <th>Wallet</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                <code>{{ $transaction->transaction_reference ?? 'TXN-' . $transaction->id }}</code>
                            </td>
                            <td>
                                @if($transaction->user)
                                    <a href="{{ route('admin.users.show', $transaction->user) }}" class="text-decoration-none">
                                        {{ $transaction->user->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $transaction->user->email }}</small>
                                @else
                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>No User
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->wallet)
                                    <a href="{{ route('admin.wallets.show', $transaction->wallet) }}" class="text-decoration-none">
                                        {{ is_array($transaction->wallet->name) ? ($transaction->wallet->name['en'] ?? 'Wallet') : $transaction->wallet->name }}
                                    </a>
                                    @if($transaction->wallet->trip)
                                        <br>
                                        <small class="text-muted">
                                            <a href="{{ route('admin.trips.show', $transaction->wallet->trip) }}" class="text-decoration-none">
                                                {{ Str::limit($transaction->wallet->trip->title, 20) }}
                                            </a>
                                        </small>
                                    @endif
                                @else
                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>No Wallet
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $transaction->type === 'deposit' ? 'success' : 'warning' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ format_currency($transaction->amount, $transaction->wallet->currency ?? 'USD') }}</strong>
                            </td>
                            <td>
                                <span class="{{ admin_status_badge($transaction->status) }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $transaction->payment_method ? ucfirst(str_replace('_', ' ', $transaction->payment_method)) : 'N/A' }}
                            </td>
                            <td>
                                {{ format_admin_date($transaction->created_at, 'M j, Y') }}
                                <br>
                                <small class="text-muted">{{ format_admin_date($transaction->created_at, 'g:i A') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" 
                                            data-bs-toggle="modal" data-bs-target="#detailsModal{{ $transaction->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($transaction->status === 'pending')
                                        <button type="button" class="btn btn-outline-success" 
                                                data-bs-toggle="modal" data-bs-target="#processModal{{ $transaction->id }}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Transaction Details Modal -->
                        <div class="modal fade" id="detailsModal{{ $transaction->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Transaction Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Reference:</strong></td>
                                                <td><code>{{ $transaction->transaction_reference ?? 'TXN-' . $transaction->id }}</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>User:</strong></td>
                                                <td>{{ $transaction->user ? $transaction->user->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Amount:</strong></td>
                                                <td>{{ format_currency($transaction->amount, $transaction->wallet->currency ?? 'USD') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type:</strong></td>
                                                <td>{{ ucfirst($transaction->type) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td><span class="{{ admin_status_badge($transaction->status) }}">{{ ucfirst($transaction->status) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Payment Method:</strong></td>
                                                <td>{{ $transaction->payment_method ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Created:</strong></td>
                                                <td>{{ format_admin_date($transaction->created_at) }}</td>
                                            </tr>
                                            @if($transaction->admin_notes)
                                            <tr>
                                                <td><strong>Admin Notes:</strong></td>
                                                <td>{{ $transaction->admin_notes }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Process Transaction Modal (for pending transactions) -->
                        @if($transaction->status === 'pending')
                        <div class="modal fade" id="processModal{{ $transaction->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Process Transaction</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.transactions.process', $transaction) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Transaction:</strong> {{ format_currency($transaction->amount, $transaction->wallet->currency ?? 'USD') }} {{ ucfirst($transaction->type) }}
                                            </div>
                                            <div class="mb-3">
                                                <label for="status{{ $transaction->id }}" class="form-label">Status</label>
                                                <select name="status" id="status{{ $transaction->id }}" class="form-select" required>
                                                    <option value="completed">Approve & Complete</option>
                                                    <option value="failed">Reject & Mark Failed</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="admin_notes{{ $transaction->id }}" class="form-label">Admin Notes</label>
                                                <textarea name="admin_notes" id="admin_notes{{ $transaction->id }}" class="form-control" rows="3" placeholder="Add processing notes..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Process Transaction</button>
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
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-credit-card display-1 text-muted"></i>
                <h4 class="text-muted">No transactions found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection
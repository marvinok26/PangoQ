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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Wallet Overview -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h5 class="text-xl font-semibold text-gray-900 mb-4">
                    {{ is_array($wallet->name) ? ($wallet->name['en'] ?? 'Wallet') : $wallet->name }}
                </h5>
                
                <div class="mb-4">
                    @if($wallet->admin_flagged)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Flagged
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Owner:</span>
                        <div class="text-right">
                            @if($wallet->user)
                                <a href="{{ route('admin.users.show', $wallet->user) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $wallet->user->name }}
                                </a>
                            @else
                                <span class="text-red-600">No User</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Trip:</span>
                        <div class="text-right">
                            @if($wallet->trip)
                                <a href="{{ route('admin.trips.show', $wallet->trip) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $wallet->trip->title }}
                                </a>
                            @else
                                <span class="text-gray-500">No trip</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Target Amount:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ format_currency($wallet->target_amount, $wallet->currency) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Current Amount:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ format_currency($wallet->current_amount, $wallet->currency) }}</span>
                    </div>
                    
                   <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Currency:</span>
                        <span class="text-sm text-gray-900">{{ $wallet->currency }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Target Date:</span>
                        <span class="text-sm text-gray-900">{{ $wallet->target_date ? format_admin_date($wallet->target_date, 'M j, Y') : 'Not set' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Frequency:</span>
                        <span class="text-sm text-gray-900">{{ ucfirst($wallet->contribution_frequency) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Created:</span>
                        <span class="text-sm text-gray-900">{{ format_admin_date($wallet->created_at) }}</span>
                    </div>
                </div>

                @if($wallet->admin_notes)
                <div class="mt-6 rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Admin Notes:</h3>
                            <p class="text-sm text-yellow-700 mt-1">{{ $wallet->admin_notes }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-6">
                    <button type="button" 
                            onclick="openFlagModal()"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-{{ $wallet->admin_flagged ? 'green' : 'yellow' }}-600 hover:bg-{{ $wallet->admin_flagged ? 'green' : 'yellow' }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $wallet->admin_flagged ? 'green' : 'yellow' }}-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="{{ $wallet->admin_flagged ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        {{ $wallet->admin_flagged ? 'Clear Flag' : 'Flag Wallet' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h5 class="text-lg font-medium text-gray-900">Recent Transactions</h5>
                <span class="text-sm text-gray-500">{{ $wallet->transactions->count() }} total transactions</span>
            </div>
            <div class="p-6">
                @if($wallet->transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($wallet->transactions->sortByDesc('created_at')->take(10) as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $transaction->transaction_reference ?? 'TXN-' . $transaction->id }}</code>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $transaction->type === 'deposit' ? 'green' : 'yellow' }}-100 text-{{ $transaction->type === 'deposit' ? 'green' : 'yellow' }}-800">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ format_currency($transaction->amount, $wallet->currency) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="{{ admin_status_badge($transaction->status) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->payment_method ? ucfirst(str_replace('_', ' ', $transaction->payment_method)) : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ format_admin_date($transaction->created_at, 'M j, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($wallet->transactions->count() > 10)
                        <div class="mt-6 text-center">
                            <a href="{{ route('admin.transactions.index', ['wallet_id' => $wallet->id]) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                View All Transactions
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <p class="mt-2 text-gray-500">No transactions yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity History -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-medium text-gray-900">Activity History</h5>
            </div>
            <div class="p-6">
                @if($activities->count() > 0)
                    <div class="space-y-4">
                        @foreach($activities as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    @if($activity->user)
                                        <span class="text-xs font-medium text-blue-800">{{ $activity->user->initials }}</span>
                                    @else
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                                            @if($activity->user)
                                                by {{ $activity->user->name }}
                                            @else
                                                by System
                                            @endif
                                        </p>
                                        @if($activity->changes)
                                            <p class="text-xs text-gray-500">
                                                @if(is_array($activity->changes))
                                                    Changes: {{ implode(', ', array_keys($activity->changes)) }}
                                                @else
                                                    Additional details available
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="mt-2 text-gray-500">No activity history</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Flag/Unflag Modal -->
<div id="flagModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $wallet->admin_flagged ? 'Clear Wallet Flag' : 'Flag Wallet for Review' }}
                </h3>
                <button type="button" onclick="closeFlagModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.wallets.toggle-flag', $wallet) }}">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    @if(!$wallet->admin_flagged)
                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for flagging</label>
                            <textarea name="reason" id="reason" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Enter reason for flagging this wallet..."></textarea>
                        </div>
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-yellow-800">This will flag the wallet for admin review. The user will be notified.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-md bg-blue-50 p-4 mb-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-800">This will remove the flag from this wallet and allow normal operations.</p>
                                </div>
                            </div>
                        </div>
                        @if($wallet->admin_notes)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Current flag reason:</h4>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-md">{{ $wallet->admin_notes }}</p>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeFlagModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-{{ $wallet->admin_flagged ? 'green' : 'red' }}-600 border border-transparent rounded-md hover:bg-{{ $wallet->admin_flagged ? 'green' : 'red' }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $wallet->admin_flagged ? 'green' : 'red' }}-500 transition-colors duration-200">
                        {{ $wallet->admin_flagged ? 'Clear Flag' : 'Flag Wallet' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bottom Action Bar -->
<div class="mt-8 flex flex-col sm:flex-row sm:justify-between space-y-4 sm:space-y-0">
    <a href="{{ route('admin.wallets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Wallets
    </a>
    
    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
        @if($wallet->user)
            <a href="{{ route('admin.users.show', $wallet->user) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                View User
            </a>
        @endif
        @if($wallet->trip)
            <a href="{{ route('admin.trips.show', $wallet->trip) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                View Trip
            </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function openFlagModal() {
    document.getElementById('flagModal').classList.remove('hidden');
}

function closeFlagModal() {
    document.getElementById('flagModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('flagModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFlagModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('flagModal').classList.contains('hidden')) {
        closeFlagModal();
    }
});
</script>
@endpush